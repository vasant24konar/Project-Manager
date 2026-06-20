@extends('layouts.app')
@section('title', 'Create User')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="fw-bold mb-1" style="font-family:'Raleway',sans-serif;">
                        <i class="fa fa-user-plus me-2" style="color:#86B817;"></i>Create User
                    </h2>
                    <p class="text-muted small mb-0">Add a new user and assign their role</p>
                </div>
                <a href="{{ route('admin.users.index') }}"
                   class="btn border border-secondary rounded-pill px-4 py-2 text-primary">
                    <i class="fa fa-arrow-left me-2"></i>Back
                </a>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius:.85rem;">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="form-item mb-4">
                            <label class="form-label fw-semibold my-2">
                                Full Name <sup class="text-danger">*</sup>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   placeholder="e.g. John Smith"
                                   class="form-control @error('name') is-invalid @enderror">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-item mb-4">
                            <label class="form-label fw-semibold my-2">
                                Email Address <sup class="text-danger">*</sup>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   placeholder="user@example.com"
                                   class="form-control @error('email') is-invalid @enderror">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-item mb-4">
                            <label class="form-label fw-semibold my-2">
                                Password <sup class="text-danger">*</sup>
                            </label>
                            <input type="password" name="password" required minlength="8"
                                   placeholder="Minimum 8 characters"
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-item mb-4">
                            <label class="form-label fw-semibold my-2">Confirm Password <sup class="text-danger">*</sup></label>
                            <input type="password" name="password_confirmation" required
                                   placeholder="Repeat password"
                                   class="form-control">
                        </div>

                        <div class="form-item mb-4">
                            <label class="form-label fw-semibold my-2">
                                Role <sup class="text-danger">*</sup>
                            </label>
                            <select name="role" required
                                    class="form-select @error('role') is-invalid @enderror">
                                <option value="">— Select a role —</option>
                                @foreach(\App\Models\User::ROLES as $value => $label)
                                    <option value="{{ $value }}" {{ old('role') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="bg-light rounded-3 p-3 mb-4">
                            <p class="fw-semibold mb-2 small"><i class="fa fa-info-circle text-primary me-1"></i>Role Permissions</p>
                            <ul class="mb-0 small text-muted ps-3">
                                <li><strong>Admin</strong> — Full access: manage users, approve/reject products, view all orders</li>
                                <li><strong>Product Manager</strong> — Create products (must be approved by admin before going live)</li>
                                <li><strong>Customer</strong> — Browse shop, add to cart, place and track orders</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit"
                                    class="btn rounded-pill px-5 py-3 fw-semibold flex-grow-1"
                                    style="background:#86B817;color:#fff;">
                                <i class="fa fa-user-plus me-2"></i>Create User
                            </button>
                            <a href="{{ route('admin.users.index') }}"
                               class="btn border border-secondary rounded-pill px-4 py-3 text-muted">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
