<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Floor;
use App\Models\Order;
use App\Models\RestaurantTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WaiterApiController extends Controller
{
    /**
     * Flutter Waiter: Floor & Table Selection
     * GET /api/v1/waiter/tables
     */
    public function tables(Request $request): JsonResponse
    {
        $user = $request->user();
        $branchId = $request->query('branch_id', $user?->branch_id);

        if (! $branchId) {
            return response()->json(['message' => 'Branch ID is required.'], Response::HTTP_BAD_REQUEST);
        }

        $floors = Floor::where('branch_id', $branchId)
            ->where('is_active', true)
            ->with(['restaurantTables' => function ($q) {
                $q->where('is_active', true)
                    ->orderBy('name')
                    ->with(['orders' => function ($oq) {
                        $oq->whereIn('status', ['confirmed', 'preparing', 'ready'])
                            ->latest()
                            ->take(1);
                    }]);
            }])
            ->get();

        $formatted = $floors->map(function ($floor) {
            return [
                'floor_id' => $floor->id,
                'floor_name' => $floor->name,
                'floor_code' => $floor->code,
                'tables' => $floor->restaurantTables->map(function ($table) {
                    $activeOrder = $table->orders->first();
                    return [
                        'id' => $table->id,
                        'name' => $table->name,
                        'seating_capacity' => $table->seating_capacity,
                        'table_status' => $table->table_status,
                        'is_occupied' => $table->table_status === 'Occupied',
                        'active_order' => $activeOrder ? [
                            'id' => $activeOrder->id,
                            'order_number' => $activeOrder->order_number,
                            'status' => $activeOrder->status,
                            'guest_count' => $activeOrder->guest_count,
                            'total_amount' => (float) $activeOrder->total_amount,
                            'created_at' => $activeOrder->created_at->format('H:i:s'),
                        ] : null,
                    ];
                }),
            ];
        });

        return response()->json([
            'branch_id' => (int) $branchId,
            'floors' => $formatted,
        ]);
    }

    /**
     * Flutter Waiter: Menu Display (Category-wise with Veg/Non-Veg & Availability)
     * GET /api/v1/waiter/menu
     */
    public function menu(Request $request): JsonResponse
    {
        $user = $request->user();
        $companyId = $request->query('company_id', $user?->company_id);
        $branchId = $request->query('branch_id', $user?->branch_id);

        if (! $companyId) {
            return response()->json(['message' => 'Company ID is required.'], Response::HTTP_BAD_REQUEST);
        }

        $categories = Category::where('company_id', $companyId)
            ->where(fn ($q) => $q->whereNull('branch_id')->orWhere('branch_id', $branchId))
            ->where('is_active', true)
            ->orderBy('display_order')
            ->with(['menuItems' => function ($q) use ($branchId) {
                $q->where('is_active', true)
                    ->where(fn ($sub) => $sub->whereNull('branch_id')->orWhere('branch_id', $branchId))
                    ->with('menuType')
                    ->orderBy('display_order');
            }])
            ->get();

        $formatted = $categories->map(function ($cat) {
            return [
                'category_id' => $cat->id,
                'category_name' => $cat->name,
                'category_code' => $cat->code,
                'dishes' => $cat->menuItems->map(function ($dish) {
                    return [
                        'id' => $dish->id,
                        'name' => $dish->name,
                        'code' => $dish->code,
                        'selling_price' => (float) $dish->selling_price,
                        'menu_type' => $dish->menuType?->name ?? 'Regular',
                        'is_veg' => strtolower($dish->menuType?->name ?? '') === 'veg',
                        'image_url' => $dish->image ? asset('storage/' . $dish->image) : null,
                        'availability' => $dish->availability,
                        'is_available' => $dish->availability === 'available',
                        'description' => $dish->description,
                    ];
                }),
            ];
        });

        return response()->json([
            'categories' => $formatted,
        ]);
    }

    /**
     * Flutter Waiter: Get Active Table Order (to view status or add additional items)
     * GET /api/v1/waiter/tables/{table}/active-order
     */
    public function activeOrder(RestaurantTable $table): JsonResponse
    {
        $order = Order::where('table_id', $table->id)
            ->whereIn('status', ['confirmed', 'preparing', 'ready'])
            ->with([
                'waiter',
                'items.menuItem.category',
                'kotTickets.kitchen',
                'kotTickets.items.menuItem',
            ])
            ->latest()
            ->first();

        if (! $order) {
            return response()->json([
                'message' => 'No active order on this table.',
                'table' => [
                    'id' => $table->id,
                    'name' => $table->name,
                    'status' => $table->table_status,
                ],
                'order' => null,
            ]);
        }

        return response()->json([
            'table' => [
                'id' => $table->id,
                'name' => $table->name,
                'status' => $table->table_status,
            ],
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'waiter' => $order->waiter?->name,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'guest_count' => $order->guest_count,
                'total_amount' => (float) $order->total_amount,
                'special_instructions' => $order->special_instructions,
                'created_at' => $order->created_at->format('M d, Y H:i:s'),
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'dish_name' => $item->menuItem?->name,
                        'dish_code' => $item->menuItem?->code,
                        'category' => $item->menuItem?->category?->name,
                        'quantity' => $item->quantity,
                        'unit_price' => (float) $item->unit_price,
                        'total_price' => (float) $item->total_price,
                        'special_instructions' => $item->special_instructions,
                        'status' => $item->status,
                    ];
                }),
                'kot_tickets' => $order->kotTickets->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'kot_number' => $ticket->kot_number,
                        'kitchen' => $ticket->kitchen?->name,
                        'status' => $ticket->ticket_status,
                        'items' => $ticket->items->map(function ($kItem) {
                            return [
                                'dish_name' => $kItem->menuItem?->name,
                                'quantity' => $kItem->quantity,
                                'special_instructions' => $kItem->special_instructions,
                                'status' => $kItem->item_status,
                            ];
                        }),
                    ];
                }),
            ],
        ]);
    }
}

