@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h2 class="h4 mb-0 fw-bold">Welcome, {{ auth()->user()->name }}</h2>
                            @foreach(auth()->user()->roles as $r)
                                <span class="badge bg-primary fs-6">{{ $r->name }}</span>
                            @endforeach
                        </div>
                        <p class="text-muted mb-0">
                            <i class="bi bi-geo-alt text-danger me-1"></i>
                            {{ auth()->user()->branch?->name ?? auth()->user()->company?->name ?? 'System-Wide Administration' }}
                        </p>
                    </div>

                    @if(auth()->user()->isWaiter())
                        <div class="d-flex gap-2">
                            <a href="{{ route('orders.create') }}" class="btn btn-success btn-lg fw-semibold shadow-sm">
                                <i class="bi bi-plus-circle me-1"></i> Take New Order
                            </a>
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-receipt me-1"></i> Active Orders
                            </a>
                        </div>
                    @elseif(auth()->user()->isKitchenStaff())
                        @php
                            $firstKitchen = \App\Models\Kitchen::where('branch_id', auth()->user()->branch_id)->first();
                        @endphp
                        @if($firstKitchen)
                            <a href="{{ route('kots.display', $firstKitchen) }}" target="_blank" class="btn btn-warning btn-lg fw-bold shadow-sm">
                                <i class="bi bi-tv me-1"></i> Open TV Kitchen Display
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 1. WAITER WORKFLOW DASHBOARD --}}
    @if(auth()->user()->isWaiter())
        @php
            $branchId = auth()->user()->branch_id;
            $tables = \App\Models\RestaurantTable::where('branch_id', $branchId)->where('is_active', true)->get();
            $myOrdersToday = \App\Models\Order::where('waiter_id', auth()->id())
                ->whereDate('created_at', today())
                ->latest()
                ->take(5)
                ->get();
        @endphp

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 small mb-1">TOTAL TABLES</h6>
                                <h3 class="mb-0 fw-bold">{{ $tables->count() }}</h3>
                            </div>
                            <i class="bi bi-grid-3x3-gap fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 small mb-1">AVAILABLE TABLES</h6>
                                <h3 class="mb-0 fw-bold">{{ $tables->where('table_status', 'Available')->count() }}</h3>
                            </div>
                            <i class="bi bi-check-circle fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted small mb-1">OCCUPIED TABLES</h6>
                                <h3 class="mb-0 fw-bold">{{ $tables->where('table_status', 'Occupied')->count() }}</h3>
                            </div>
                            <i class="bi bi-people fs-1 text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tables Quick Overview for Waiter --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center py-3">
                <span><i class="bi bi-grid-3x3-gap text-primary me-1"></i> Table Layout & Status</span>
                <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">Take Order on Table</a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @forelse($tables as $t)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card h-100 border text-center p-3 {{ $t->table_status === 'Occupied' ? 'bg-warning-subtle border-warning' : 'bg-light' }}">
                                <h5 class="fw-bold mb-1">{{ $t->name }}</h5>
                                <div class="text-muted small mb-2">Cap: {{ $t->seating_capacity }} guests</div>
                                <span class="badge {{ $t->table_status === 'Occupied' ? 'bg-warning text-dark' : 'bg-success' }} mb-2">
                                    {{ $t->table_status }}
                                </span>
                                @if($t->table_status === 'Available')
                                    <a href="{{ route('orders.create', ['table_id' => $t->id]) }}" class="btn btn-sm btn-outline-success mt-1">
                                        Open Order
                                    </a>
                                @else
                                    @php
                                        $activeOrder = $t->orders()->whereIn('status', ['confirmed', 'preparing', 'ready'])->latest()->first();
                                    @endphp
                                    @if($activeOrder)
                                        <a href="{{ route('orders.show', $activeOrder) }}" class="btn btn-sm btn-outline-dark mt-1">
                                            View / Add Items
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4 text-muted">No tables configured for your branch.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Waiter's Recent Orders --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold py-3">
                <i class="bi bi-clock-history text-primary me-1"></i> Your Orders Today
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Table</th>
                            <th>Items</th>
                            <th>Total (₹)</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myOrdersToday as $o)
                            <tr>
                                <td class="fw-bold">{{ $o->order_number }}</td>
                                <td>{{ $o->table?->name }}</td>
                                <td>{{ $o->items->sum('quantity') }} items</td>
                                <td class="fw-semibold">₹{{ number_format($o->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge {{ $o->status === 'ready' ? 'bg-info text-dark' : ($o->status === 'served' ? 'bg-success' : 'bg-primary') }}">
                                        {{ ucfirst($o->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('orders.show', $o) }}" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">No orders placed today yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    {{-- 2. MANAGER / ADMIN DASHBOARD --}}
    @else
        @php
            $branchId = auth()->user()->branch_id;
            $orderQuery = \App\Models\Order::query();
            $kotQuery = \App\Models\KotTicket::query();
            if ($branchId && !auth()->user()->isAdmin()) {
                $orderQuery->where('branch_id', $branchId);
                $kotQuery->where('branch_id', $branchId);
            }
            $totalOrdersToday = (clone $orderQuery)->whereDate('created_at', today())->count();
            $activeKots = (clone $kotQuery)->whereIn('ticket_status', ['new', 'accepted', 'preparing'])->count();
            $readyKots = (clone $kotQuery)->where('ticket_status', 'ready')->count();
        @endphp

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 small mb-1">TODAY'S ORDERS</h6>
                                <h3 class="mb-0 fw-bold">{{ $totalOrdersToday }}</h3>
                            </div>
                            <i class="bi bi-receipt fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted small mb-1">ACTIVE KITCHEN KOTS</h6>
                                <h3 class="mb-0 fw-bold">{{ $activeKots }}</h3>
                            </div>
                            <i class="bi bi-egg-fried fs-1 text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 small mb-1">READY FOR SERVICE</h6>
                                <h3 class="mb-0 fw-bold">{{ $readyKots }}</h3>
                            </div>
                            <i class="bi bi-bell fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center py-3">
                <span><i class="bi bi-clock-history text-primary me-1"></i> Live Orders</span>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">View All Orders</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Table</th>
                                <th>Waiter</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $liveOrders = (clone $orderQuery)->latest()->take(8)->get();
                            @endphp
                            @forelse($liveOrders as $o)
                                <tr>
                                    <td>
                                        <a href="{{ route('orders.show', $o) }}" class="fw-bold text-decoration-none">
                                            {{ $o->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $o->table?->name }}</td>
                                    <td>{{ $o->waiter?->name }}</td>
                                    <td class="fw-semibold">₹{{ number_format($o->total_amount, 2) }}</td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($o->status) }}</span></td>
                                    <td class="text-end">
                                        <a href="{{ route('orders.show', $o) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No live orders found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
