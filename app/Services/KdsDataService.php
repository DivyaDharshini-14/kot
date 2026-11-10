<?php

namespace App\Services;

use Carbon\Carbon;

class KdsDataService
{
    /**
     * Get structured KOT tickets matching the Nagarjuna Kitchen Display UI specification.
     *
     * @param string|null $kitchenFilter
     * @return array<int, array<string, mixed>>
     */
    public function getDummyTickets(?string $kitchenFilter = null): array
    {
        $now = Carbon::now();

        $tickets = [
            // ==================== SECTION 1: NEW ORDERS ====================
            [
                'id' => 218,
                'kot_number' => '218',
                'order_number' => 'ORD-9101',
                'kitchen_code' => 'biryani-kitchen',
                'kitchen_name' => 'Biryani Kitchen',
                'table_name' => 'Table 8',
                'table_number' => 8,
                'floor_name' => 'Ground Floor',
                'waiter_name' => 'Ramesh (Captain)',
                'order_type' => 'Dine In',
                'status' => 'new',
                'created_at' => $now->copy()->subMinutes(0)->subSeconds(45)->toIso8601String(),
                'elapsed_seconds' => 45,
                'waiting_display' => '00:45',
                'notes' => null,
                'items' => [
                    ['name' => 'Chicken Biryani', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Mutton Biryani', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Andhra Meals', 'quantity' => 1, 'menu_type' => 'veg'],
                    ['name' => 'Chicken 65', 'quantity' => 1, 'menu_type' => 'non-veg'],
                ],
            ],
            [
                'id' => 219,
                'kot_number' => '219',
                'order_number' => 'ORD-9102',
                'kitchen_code' => 'biryani-kitchen',
                'kitchen_name' => 'Biryani Kitchen',
                'table_name' => 'Table 12',
                'table_number' => 12,
                'floor_name' => '1st Floor',
                'waiter_name' => 'Suresh',
                'order_type' => 'Dine In',
                'status' => 'new',
                'created_at' => $now->copy()->subMinutes(0)->subSeconds(10)->toIso8601String(),
                'elapsed_seconds' => 10,
                'waiting_display' => '00:10',
                'notes' => null,
                'items' => [
                    ['name' => 'Chicken Biryani', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Pepper Chicken', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Andhra Meals', 'quantity' => 1, 'menu_type' => 'veg'],
                    ['name' => 'Paneer Butter Masala', 'quantity' => 1, 'menu_type' => 'veg'],
                ],
            ],
            [
                'id' => 220,
                'kot_number' => '220',
                'order_number' => 'ORD-9103',
                'kitchen_code' => 'biryani-kitchen',
                'kitchen_name' => 'Biryani Kitchen',
                'table_name' => 'Table 5',
                'table_number' => 5,
                'floor_name' => 'Ground Floor',
                'waiter_name' => 'Praveen',
                'order_type' => 'Dine In',
                'status' => 'new',
                'created_at' => $now->copy()->subMinutes(0)->subSeconds(25)->toIso8601String(),
                'elapsed_seconds' => 25,
                'waiting_display' => '00:25',
                'notes' => null,
                'items' => [
                    ['name' => 'Mutton Biryani', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Chicken 65', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Andhra Chicken Curry', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Chilli Chicken', 'quantity' => 1, 'menu_type' => 'non-veg'],
                ],
            ],

            // ==================== SECTION 2: PREPARING ORDERS ====================
            [
                'id' => 205,
                'kot_number' => '205',
                'order_number' => 'ORD-9088',
                'kitchen_code' => 'biryani-kitchen',
                'kitchen_name' => 'Biryani Kitchen',
                'table_name' => 'Table 6',
                'table_number' => 6,
                'floor_name' => 'Ground Floor',
                'waiter_name' => 'Ramesh (Captain)',
                'order_type' => 'Dine In',
                'status' => 'preparing',
                'created_at' => $now->copy()->subMinutes(0)->subSeconds(20)->toIso8601String(),
                'elapsed_seconds' => 20,
                'waiting_display' => '00:20',
                'notes' => null,
                'items' => [
                    ['name' => 'Chicken Biryani', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Mutton Biryani', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Chilli Chicken', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Paneer Butter Masala', 'quantity' => 1, 'menu_type' => 'veg'],
                ],
            ],
            [
                'id' => 206,
                'kot_number' => '206',
                'order_number' => 'ORD-9089',
                'kitchen_code' => 'biryani-kitchen',
                'kitchen_name' => 'Biryani Kitchen',
                'table_name' => 'Table 10',
                'table_number' => 10,
                'floor_name' => '1st Floor',
                'waiter_name' => 'Venkatesh',
                'order_type' => 'Dine In',
                'status' => 'preparing',
                'created_at' => $now->copy()->subMinutes(0)->subSeconds(35)->toIso8601String(),
                'elapsed_seconds' => 35,
                'waiting_display' => '00:35',
                'notes' => null,
                'items' => [
                    ['name' => 'Andhra Meals', 'quantity' => 1, 'menu_type' => 'veg'],
                    ['name' => 'Chicken 65', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Pepper Chicken', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Mutton Biryani', 'quantity' => 1, 'menu_type' => 'non-veg'],
                ],
            ],
            [
                'id' => 207,
                'kot_number' => '207',
                'order_number' => 'ORD-9090',
                'kitchen_code' => 'biryani-kitchen',
                'kitchen_name' => 'Biryani Kitchen',
                'table_name' => 'Table 3',
                'table_number' => 3,
                'floor_name' => 'Ground Floor',
                'waiter_name' => 'Suresh',
                'order_type' => 'Dine In',
                'status' => 'preparing',
                'created_at' => $now->copy()->subMinutes(0)->subSeconds(10)->toIso8601String(),
                'elapsed_seconds' => 10,
                'waiting_display' => '00:10',
                'notes' => null,
                'items' => [
                    ['name' => 'Chicken Biryani', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Andhra Chicken Curry', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Paneer Butter Masala', 'quantity' => 1, 'menu_type' => 'veg'],
                    ['name' => 'Chilli Chicken', 'quantity' => 1, 'menu_type' => 'non-veg'],
                ],
            ],

            // ==================== SECTION 3: READY ORDERS ====================
            [
                'id' => 198,
                'kot_number' => '198',
                'order_number' => 'ORD-9070',
                'kitchen_code' => 'biryani-kitchen',
                'kitchen_name' => 'Biryani Kitchen',
                'table_name' => 'Table 7',
                'table_number' => 7,
                'floor_name' => 'Ground Floor',
                'waiter_name' => 'Praveen',
                'order_type' => 'Dine In',
                'status' => 'ready',
                'created_at' => $now->copy()->subMinutes(5)->toIso8601String(),
                'elapsed_seconds' => 300,
                'waiting_display' => 'Ready',
                'notes' => null,
                'items' => [
                    ['name' => 'Paneer Butter Masala', 'quantity' => 1, 'menu_type' => 'veg'],
                    ['name' => 'Andhra Meals', 'quantity' => 1, 'menu_type' => 'veg'],
                    ['name' => 'Chicken 65', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Mutton Biryani', 'quantity' => 1, 'menu_type' => 'non-veg'],
                ],
            ],
            [
                'id' => 199,
                'kot_number' => '199',
                'order_number' => 'ORD-9071',
                'kitchen_code' => 'biryani-kitchen',
                'kitchen_name' => 'Biryani Kitchen',
                'table_name' => 'Table 11',
                'table_number' => 11,
                'floor_name' => '1st Floor',
                'waiter_name' => 'Venkatesh',
                'order_type' => 'Dine In',
                'status' => 'ready',
                'created_at' => $now->copy()->subMinutes(7)->toIso8601String(),
                'elapsed_seconds' => 420,
                'waiting_display' => 'Ready',
                'notes' => null,
                'items' => [
                    ['name' => 'Chicken 65', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Mutton Biryani', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Andhra Chicken Curry', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Chilli Chicken', 'quantity' => 1, 'menu_type' => 'non-veg'],
                ],
            ],
            [
                'id' => 200,
                'kot_number' => '200',
                'order_number' => 'ORD-9072',
                'kitchen_code' => 'biryani-kitchen',
                'kitchen_name' => 'Biryani Kitchen',
                'table_name' => 'Table 4',
                'table_number' => 4,
                'floor_name' => 'Ground Floor',
                'waiter_name' => 'Ramesh (Captain)',
                'order_type' => 'Dine In',
                'status' => 'ready',
                'created_at' => $now->copy()->subMinutes(12)->toIso8601String(),
                'elapsed_seconds' => 720,
                'waiting_display' => 'Ready',
                'notes' => null,
                'items' => [
                    ['name' => 'Andhra Meals', 'quantity' => 1, 'menu_type' => 'veg'],
                    ['name' => 'Pepper Chicken', 'quantity' => 1, 'menu_type' => 'non-veg'],
                    ['name' => 'Paneer Butter Masala', 'quantity' => 1, 'menu_type' => 'veg'],
                    ['name' => 'Chicken Biryani', 'quantity' => 1, 'menu_type' => 'non-veg'],
                ],
            ],
        ];

        if ($kitchenFilter && $kitchenFilter !== 'all') {
            $filtered = array_values(array_filter($tickets, fn ($t) => $t['kitchen_code'] === $kitchenFilter));
            if (!empty($filtered)) {
                return $filtered;
            }
        }

        return $tickets;
    }

    /**
     * Group tickets by their status section: 'new', 'preparing', 'ready'.
     *
     * @param array<int, array<string, mixed>> $tickets
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function groupBySection(array $tickets): array
    {
        $grouped = [
            'new' => [],
            'preparing' => [],
            'ready' => [],
        ];

        foreach ($tickets as $ticket) {
            $status = $ticket['status'] ?? 'new';
            if (isset($grouped[$status])) {
                $grouped[$status][] = $ticket;
            } else {
                $grouped['new'][] = $ticket;
            }
        }

        return $grouped;
    }

    /**
     * Summary stats matching the image header:
     * New: 12, Preparing: 18, Ready: 10
     *
     * @param array<int, array<string, mixed>> $tickets
     * @return array<string, int|float>
     */
    public function getStats(array $tickets): array
    {
        // Provide both total counts matching image specs and live computed counts
        return [
            'total' => 40,
            'new' => 12,
            'preparing' => 18,
            'ready' => 10,
            'active_displayed' => count($tickets),
        ];
    }
}
