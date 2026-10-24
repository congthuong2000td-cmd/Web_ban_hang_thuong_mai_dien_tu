@extends('layouts.admin')

@section('title', 'Quản lý khách hàng - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">
                Quản lý khách hàng
            </h1>
            <p class="text-muted small mb-0">Danh sách tài khoản khách hàng đăng ký mua sắm tại CT mart.</p>
        </div>
    </div>

    {{-- Bộ lọc & Tìm kiếm --}}
    <div class="card border-0 shadow-sm p-3 mb-4">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm theo họ tên, email hoặc số điện thoại..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Trạng thái tài khoản --</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Đang bị khóa</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-filter me-1"></i>Lọc
                </button>
            </div>
            @if(request()->anyFilled(['q', 'status']))
                <div class="col-auto">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-link btn-sm text-danger text-decoration-none">
                        Xóa bộ lọc
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- Bảng khách hàng --}}
    <div class="card border-0 shadow-sm p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-muted text-uppercase">
                    <tr>
                        <th class="ps-3">Khách hàng</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th class="text-center">Số đơn hàng</th>
                        <th>Ngày đăng ký</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('admin.customers.show', $c) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $c->name }}
                                </a>
                            </td>
                            <td class="small">{{ $c->email }}</td>
                            <td class="small">{{ $c->phone ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $c->orders_count }}</span>
                            </td>
                            <td class="small text-muted">
                                {{ $c->created_at->format('d/m/Y') }}
                            </td>
                            <td class="text-center">
                                @if($c->is_active)
                                    <span class="badge bg-success-subtle text-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Bị khóa</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.customers.show', $c) }}" class="btn btn-outline-secondary btn-sm py-0 px-2" title="Xem hồ sơ">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.customers.toggleActive', $c) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn {{ $c->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} btn-sm py-0 px-2" title="{{ $c->is_active ? 'Khóa tài khoản' : 'Mở khóa' }}" onclick="return confirm('Bạn có chắc chắn muốn {{ $c->is_active ? 'khóa' : 'mở khóa' }} tài khoản này?');">
                                            <i class="bi {{ $c->is_active ? 'bi-lock' : 'bi-unlock' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Không tìm thấy khách hàng nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
