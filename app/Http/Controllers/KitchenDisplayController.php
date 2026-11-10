<?php

namespace App\Http\Controllers;

use App\Services\KdsDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KitchenDisplayController extends Controller
{
    public function __construct(
        protected KdsDataService $kdsDataService
    ) {}

    /**
     * Display the Kitchen Display System (KDS) board with New, Preparing, and Ready sections.
     * Supports both HTML view and JSON responses for future API consumer readiness.
     *
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View|JsonResponse
    {
        $kitchenFilter = $request->query('kitchen', 'biryani-kitchen');
        $tickets = $this->kdsDataService->getDummyTickets($kitchenFilter);
        $sections = $this->kdsDataService->groupBySection($tickets);
        $stats = $this->kdsDataService->getStats($tickets);

        $availableKitchens = [
            ['code' => 'biryani-kitchen', 'name' => 'Biryani Kitchen'],
            ['code' => 'chinese-kitchen', 'name' => 'Chinese Kitchen'],
            ['code' => 'starters-kitchen', 'name' => 'Starters Kitchen'],
        ];

        // API mode: allows testing data payload directly
        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'status' => 'success',
                'kitchen' => $kitchenFilter,
                'stats' => $stats,
                'sections' => $sections,
                'tickets' => $tickets,
            ]);
        }

        return view('kds.index', [
            'tickets' => $tickets,
            'sections' => $sections,
            'stats' => $stats,
            'currentKitchen' => $kitchenFilter,
            'availableKitchens' => $availableKitchens,
        ]);
    }

    /**
     * Dedicated API endpoint returning the raw KOT data structure.
     * Screen uses this for live polling or simulating real-time WebSocket sync.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function apiTickets(Request $request): JsonResponse
    {
        $kitchenFilter = $request->query('kitchen', 'biryani-kitchen');
        $tickets = $this->kdsDataService->getDummyTickets($kitchenFilter);
        $sections = $this->kdsDataService->groupBySection($tickets);
        $stats = $this->kdsDataService->getStats($tickets);

        return response()->json([
            'success' => true,
            'timestamp' => now()->toIso8601String(),
            'kitchen' => $kitchenFilter,
            'stats' => $stats,
            'sections' => $sections,
            'tickets' => $tickets,
        ]);
    }
}

