@php
    $status = $kot['status'] ?? 'new';
    $elapsed = $kot['elapsed_seconds'] ?? 0;
    $mins = floor($elapsed / 60);
    $secs = $elapsed % 60;
    $timeFormatted = sprintf('%02d:%02d', $mins, $secs);
@endphp

<div class="kds-order-card" id="card-{{ $kot['id'] }}" data-id="{{ $kot['id'] }}" data-status="{{ $status }}" data-elapsed="{{ $elapsed }}">
    
    <!-- Left Section: KOT Label, Big KOT Number, Table Number -->
    <div class="kds-card-left">
        <div class="kot-label">KOT</div>
        <div class="kot-number">{{ $kot['kot_number'] }}</div>
        <div class="kot-table">{{ $kot['table_name'] }}</div>
    </div>

    <!-- Middle Section: Dishes List (Dish Name  x  Qty) -->
    <div class="kds-card-middle">
        @foreach($kot['items'] as $item)
            <div class="dish-item-row">
                <span class="dish-title">{{ $item['name'] }}</span>
                <span class="dish-multiplier">×</span>
                <span class="dish-quantity">{{ $item['quantity'] }}</span>
            </div>
        @endforeach
    </div>

    <!-- Right Section: Timer or Ready Badge + Action Trigger -->
    <div class="kds-card-right">
        @if($status === 'new')
            <button class="status-badge-btn badge-timer" onclick="kds.progressOrder({{ $kot['id'] }}, 'preparing')" title="Click to Start Preparing">
                <svg class="badge-icon" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                    <path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm1-11V7h-2v6h5v-2h-3z"/>
                </svg>
                <span class="timer-display">{{ $kot['waiting_display'] ?? $timeFormatted }}</span>
            </button>
        @elseif($status === 'preparing')
            <button class="status-badge-btn badge-timer" onclick="kds.progressOrder({{ $kot['id'] }}, 'ready')" title="Click to Mark Ready">
                <svg class="badge-icon" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                    <path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm1-11V7h-2v6h5v-2h-3z"/>
                </svg>
                <span class="timer-display">{{ $kot['waiting_display'] ?? $timeFormatted }}</span>
            </button>
        @else
            <button class="status-badge-btn badge-ready" onclick="kds.progressOrder({{ $kot['id'] }}, 'served')" title="Click to Dispatch / Clear">
                <svg class="badge-icon" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                <span>Ready</span>
            </button>
        @endif
    </div>

</div>
