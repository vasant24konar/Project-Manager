@extends('layouts.shop')
@section('title', 'My Orders')

@section('content')

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">My Orders</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Home</a></li>
        <li class="breadcrumb-item active text-white">My Orders</li>
    </ol>
</div>

<div class="container py-5">

    <!-- Status tabs -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h4 class="fw-bold mb-0" style="font-family:'Raleway',sans-serif">Order History</h4>
        <span class="text-muted small">{{ $orders->total() }} order{{ $orders->total() !== 1 ? 's' : '' }}</span>
    </div>

    <div class="mb-4">
        <ul class="nav nav-pills gap-2 flex-wrap">
            @foreach([
                'all'        => ['label' => 'All Orders',  'icon' => 'fa-list',         'color' => 'secondary'],
                'pending'    => ['label' => 'Pending',     'icon' => 'fa-clock',        'color' => 'warning'],
                'processing' => ['label' => 'In Process',  'icon' => 'fa-spinner',      'color' => 'info'],
                'completed'  => ['label' => 'Completed',   'icon' => 'fa-check-circle', 'color' => 'success'],
                'cancelled'  => ['label' => 'Cancelled',   'icon' => 'fa-times-circle', 'color' => 'danger'],
            ] as $key => $tab)
            <li class="nav-item">
                <a href="{{ route('shop.orders', ['status' => $key]) }}"
                   class="nav-link rounded-pill px-4 py-2 fw-semibold {{ $status === $key ? 'active' : 'border' }}"
                   style="{{ $status === $key ? 'background:#86B817;color:#fff;' : 'color:#555;border-color:#dee2e6;' }}">
                    <i class="fa {{ $tab['icon'] }} me-1"></i>{{ $tab['label'] }}
                    @if($key !== 'all')
                        <span class="badge rounded-pill ms-1"
                              style="{{ $status === $key ? 'background:rgba(255,255,255,.3);' : 'background:#f3f4f6;color:#555;' }}">
                            {{ $counts[$key] ?? 0 }}
                        </span>
                    @endif
                </a>
            </li>
            @endforeach
        </ul>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="fa fa-box-open fa-5x text-secondary mb-4"></i>
            <h3 class="fw-semibold text-muted">
                @if($status === 'all') No orders yet @else No {{ $status }} orders @endif
            </h3>
            <p class="text-muted">
                @if($status === 'all') Start shopping to see your orders here.
                @else You have no orders with this status. @endif
            </p>
            <a href="{{ route('shop.index') }}" class="btn border border-secondary rounded-pill px-4 py-3 text-primary">
                <i class="fa fa-store me-2"></i>Browse Shop
            </a>
        </div>
    @else
    <div class="card border-0 shadow-sm" style="border-radius:.85rem;overflow:hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fdf0;">
                    <tr>
                        <th class="ps-4 py-3" style="font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Order #</th>
                        <th class="py-3" style="font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Date</th>
                        <th class="py-3" style="font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Items</th>
                        <th class="py-3" style="font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Total</th>
                        <th class="py-3" style="font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Status</th>
                        <th class="py-3 pe-4 text-end" style="font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="ps-4 py-3 fw-bold">#{{ $order->id }}</td>
                        <td class="text-muted small">{{ $order->created_at->format('M j, Y') }}</td>
                        <td class="text-muted small">{{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}</td>
                        <td class="fw-bold" style="color:var(--bs-primary)">${{ number_format($order->total, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $order->getStatusBadgeClass() }} rounded-pill px-3 py-2">
                                @php
                                    $statusLabel = match($order->status) {
                                        'processing' => 'In Process',
                                        default      => ucfirst($order->status),
                                    };
                                @endphp
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('shop.order.show', $order) }}"
                               class="btn btn-sm border border-secondary rounded-pill px-3 py-2 text-primary" style="font-size:.82rem">
                                <i class="fa fa-eye me-1"></i>View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
    @endif

</div>

@endsection
