<?php

namespace App\Services;

use App\Models\Kitchen;
use App\Models\KitchenMenuItem;
use App\Models\KotItem;
use App\Models\KotTicket;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrderService
{
    /**
     * Create and submit a customer order, automatically generating kitchen-wise KOT tickets.
     */
    public function createOrder(array $data, User $waiter): Order
    {
        return DB::transaction(function () use ($data, $waiter) {
            $companyId = $data['company_id'] ?? $waiter->company_id;
            $branchId = $data['branch_id'] ?? $waiter->branch_id;

            if (! $companyId || ! $branchId) {
                throw new InvalidArgumentException('Company and Branch are required to create an order.');
            }

            // Verify and lock table
            $table = RestaurantTable::where('id', $data['table_id'])
                ->where('branch_id', $branchId)
                ->firstOrFail();

            $orderNumber = $this->generateOrderNumber((int) $branchId);

            $order = Order::create([
                'order_number' => $orderNumber,
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'table_id' => $table->id,
                'waiter_id' => $waiter->id,
                'customer_name' => $data['customer_name'] ?? null,
                'customer_phone' => $data['customer_phone'] ?? null,
                'guest_count' => $data['guest_count'] ?? 1,
                'status' => 'confirmed',
                'total_amount' => 0.00,
                'special_instructions' => $data['special_instructions'] ?? null,
            ]);

            // Update table status to occupied
            $table->update(['table_status' => 'Occupied']);

            // Create order items
            $createdOrderItems = [];
            $totalAmount = 0.00;

            foreach ($data['items'] as $itemData) {
                $menuItem = MenuItem::where('id', $itemData['menu_item_id'])
                    ->where('company_id', $companyId)
                    ->firstOrFail();

                $quantity = (int) $itemData['quantity'];
                $unitPrice = (float) $menuItem->selling_price;
                $totalPrice = $unitPrice * $quantity;
                $totalAmount += $totalPrice;

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'special_instructions' => $itemData['special_instructions'] ?? null,
                    'status' => 'new',
                ]);

                $createdOrderItems[] = $orderItem;
            }

            $order->update(['total_amount' => $totalAmount]);

            // Automatically generate kitchen-wise KOT tickets
            $this->generateKitchenTickets($order, collect($createdOrderItems));

            return $order->load([
                'table',
                'waiter',
                'items.menuItem',
                'kotTickets.kitchen',
                'kotTickets.items.menuItem',
            ]);
        });
    }

    /**
     * Add additional items to an existing order and generate KOT tickets only for the new items.
     */
    public function addItemsToOrder(Order $order, array $items, User $waiter): Order
    {
        return DB::transaction(function () use ($order, $items) {
            $createdOrderItems = [];
            $addedAmount = 0.00;

            foreach ($items as $itemData) {
                $menuItem = MenuItem::where('id', $itemData['menu_item_id'])
                    ->where('company_id', $order->company_id)
                    ->firstOrFail();

                $quantity = (int) $itemData['quantity'];
                $unitPrice = (float) $menuItem->selling_price;
                $totalPrice = $unitPrice * $quantity;
                $addedAmount += $totalPrice;

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'special_instructions' => $itemData['special_instructions'] ?? null,
                    'status' => 'new',
                ]);

                $createdOrderItems[] = $orderItem;
            }

            $order->increment('total_amount', $addedAmount);

            // Generate tickets only for the new items
            $this->generateKitchenTickets($order, collect($createdOrderItems));

            return $order->fresh([
                'table',
                'waiter',
                'items.menuItem',
                'kotTickets.kitchen',
                'kotTickets.items.menuItem',
            ]);
        });
    }

    /**
     * Split order items into kitchen-wise KOT tickets using kitchen_menu_items mappings.
     */
    public function generateKitchenTickets(Order $order, Collection $orderItems): Collection
    {
        if ($orderItems->isEmpty()) {
            return collect();
        }

        // Group items by assigned kitchen
        $itemsByKitchen = [];

        foreach ($orderItems as $item) {
            // Find assigned kitchen from kitchen_menu_items
            $mapping = KitchenMenuItem::where('menu_item_id', $item->menu_item_id)
                ->where('branch_id', $order->branch_id)
                ->where('is_active', true)
                ->first();

            $kitchenId = $mapping?->kitchen_id;

            // If no mapping, fallback to the default/first kitchen for the branch
            if (! $kitchenId) {
                $defaultKitchen = Kitchen::where('branch_id', $order->branch_id)
                    ->where('is_active', true)
                    ->orderBy('id')
                    ->first();

                $kitchenId = $defaultKitchen?->id;
            }

            if (! $kitchenId) {
                // If branch has no kitchen at all, throw exception
                throw new InvalidArgumentException("No active kitchen configured for branch #{$order->branch_id} to route items.");
            }

            $itemsByKitchen[$kitchenId][] = $item;
        }

        $tickets = collect();

        foreach ($itemsByKitchen as $kitchenId => $items) {
            $kotNumber = $this->generateKotNumber((int) $kitchenId);

            $ticket = KotTicket::create([
                'kot_number' => $kotNumber,
                'order_id' => $order->id,
                'kitchen_id' => $kitchenId,
                'company_id' => $order->company_id,
                'branch_id' => $order->branch_id,
                'ticket_status' => 'new',
            ]);

            foreach ($items as $orderItem) {
                KotItem::create([
                    'kot_ticket_id' => $ticket->id,
                    'order_item_id' => $orderItem->id,
                    'menu_item_id' => $orderItem->menu_item_id,
                    'quantity' => $orderItem->quantity,
                    'special_instructions' => $orderItem->special_instructions,
                    'item_status' => 'new',
                ]);
            }

            $tickets->push($ticket->load(['kitchen', 'items.menuItem']));
        }

        return $tickets;
    }

    /**
     * Update the status of a KOT ticket and its items.
     */
    public function updateKotTicketStatus(KotTicket $ticket, string $status): KotTicket
    {
        return DB::transaction(function () use ($ticket, $status) {
            $ticket->update(['ticket_status' => $status]);

            // Sync item statuses
            $ticket->items()->update(['item_status' => $status]);

            // Sync parent order items
            foreach ($ticket->items as $kotItem) {
                $kotItem->orderItem?->update(['status' => $status]);
            }

            // If all KOT tickets for the order are completed / served, update order status
            $this->syncOrderStatusFromTickets($ticket->order);

            return $ticket->fresh(['kitchen', 'items.menuItem', 'order']);
        });
    }

    /**
     * Update status of an individual KOT item.
     */
    public function updateKotItemStatus(KotItem $item, string $status): KotItem
    {
        return DB::transaction(function () use ($item, $status) {
            $item->update(['item_status' => $status]);
            $item->orderItem?->update(['status' => $status]);

            // Check if all items in ticket have same status
            $ticket = $item->ticket;
            $allStatuses = $ticket->items()->pluck('item_status')->unique();

            if ($allStatuses->count() === 1) {
                $ticket->update(['ticket_status' => $allStatuses->first()]);
            }

            $this->syncOrderStatusFromTickets($ticket->order);

            return $item->fresh(['ticket', 'orderItem', 'menuItem']);
        });
    }

    /**
     * Update order status directly (e.g. completed, cancelled).
     */
    public function updateOrderStatus(Order $order, string $status): Order
    {
        return DB::transaction(function () use ($order, $status) {
            $order->update(['status' => $status]);

            // If completed or cancelled, free the table
            if (in_array($status, ['completed', 'cancelled'], true)) {
                $order->table?->update(['table_status' => 'Available']);
            }

            return $order->fresh(['table', 'items']);
        });
    }

    protected function syncOrderStatusFromTickets(Order $order): void
    {
        $statuses = $order->kotTickets()->pluck('ticket_status')->unique();

        if ($statuses->contains('preparing')) {
            $order->update(['status' => 'preparing']);
        } elseif ($statuses->count() === 1 && $statuses->first() === 'ready') {
            $order->update(['status' => 'ready']);
        } elseif ($statuses->count() === 1 && $statuses->first() === 'served') {
            $order->update(['status' => 'served']);
        }
    }

    protected function generateOrderNumber(int $branchId): string
    {
        $prefix = 'ORD-' . date('Ymd');
        $count = Order::where('branch_id', $branchId)
            ->whereDate('created_at', today())
            ->count();

        return sprintf('%s-%04d', $prefix, $count + 1);
    }

    protected function generateKotNumber(int $kitchenId): string
    {
        $prefix = 'KOT-' . date('Ymd');
        $count = KotTicket::where('kitchen_id', $kitchenId)
            ->whereDate('created_at', today())
            ->count();

        return sprintf('%s-K%d-%04d', $prefix, $kitchenId, $count + 1);
    }
}

