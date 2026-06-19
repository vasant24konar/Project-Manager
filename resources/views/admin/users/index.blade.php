@extends('layouts.shop')
@section('title', 'User Management')

@section('content')
<div class="container py-5">

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1" style="font-family:'Raleway',sans-serif">
                <i class="fa fa-users me-2" style="color:#86B817"></i>User Management
            </h2>
            <p class="text-muted small mb-0">Manage roles and access for all registered users</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="btn fw-semibold rounded-pill px-4 py-2"
           style="background:#86B817;color:#fff;">
            <i class="fa fa-user-plus me-2"></i>Create User
        </a>
    </div>

    {{-- Role legend --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #86B817;border-radius:.75rem;">
                <div class="card-body py-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:40px;height:40px;background:#f0f9e8;flex-shrink:0;">
                            <i class="fa fa-crown" style="color:#86B817;"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Admin</div>
                            <small class="text-muted">Full access — users, approvals, orders</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #3b82f6;border-radius:.75rem;">
                <div class="card-body py-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:40px;height:40px;background:#eff6ff;flex-shrink:0;">
                            <i class="fa fa-box" style="color:#3b82f6;"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Product Manager</div>
                            <small class="text-muted">Add products (require admin approval)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #f59e0b;border-radius:.75rem;">
                <div class="card-body py-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:40px;height:40px;background:#fffbeb;flex-shrink:0;">
                            <i class="fa fa-user" style="color:#f59e0b;"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Customer</div>
                            <small class="text-muted">Browse, cart &amp; place orders</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:.85rem;overflow:hidden;">
        @if($users->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fa fa-users fa-3x mb-3"></i>
                <p>No users found.</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fdf0;">
                    <tr>
                        <th class="ps-4 py-3" style="font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">User</th>
                        <th class="py-3" style="font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Role</th>
                        <th class="py-3" style="font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Change Role</th>
                        <th class="py-3" style="font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Joined</th>
                        <th class="py-3 pe-4"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                 style="width:40px;height:40px;background:#86B817;font-size:.9rem;flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-light text-muted ms-1" style="font-size:.7rem;">You</span>
                                    @endif
                                </div>
                                <div class="text-muted" style="font-size:.8rem;">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $roleConfig = match($user->role) {
                                'admin'           => ['bg' => '#f0f9e8', 'color' => '#4a7c15', 'icon' => 'fa-crown'],
                                'product_manager' => ['bg' => '#eff6ff', 'color' => '#1d4ed8', 'icon' => 'fa-box'],
                                default           => ['bg' => '#fffbeb', 'color' => '#92400e', 'icon' => 'fa-user'],
                            };
                        @endphp
                        <span class="badge rounded-pill px-3 py-2"
                              style="background:{{ $roleConfig['bg'] }};color:{{ $roleConfig['color'] }};font-size:.78rem;">
                            <i class="fa {{ $roleConfig['icon'] }} me-1"></i>
                            {{ \App\Models\User::ROLES[$user->role] ?? ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="d-flex align-items-center gap-2">
                            @csrf @method('PATCH')
                            <select name="role" class="form-select form-select-sm" style="max-width:185px;">
                                @foreach(\App\Models\User::ROLES as $value => $label)
                                    <option value="{{ $value }}" {{ $user->role === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm rounded-pill px-3 fw-semibold" style="background:#86B817;color:#fff;white-space:nowrap;">
                                Save
                            </button>
                        </form>
                        @else
                            <span class="text-muted small"><i class="fa fa-lock me-1"></i>Your own account</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="pe-4">
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Delete user {{ addslashes($user->name) }}? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                <i class="fa fa-trash me-1"></i>Delete
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    <div class="mt-4">{{ $users->links() }}</div>

</div>
@endsection
