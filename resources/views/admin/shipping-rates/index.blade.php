@extends('layouts.admin')

@section('title', 'Quản lý phí vận chuyển - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">
                Biểu phí vận chuyển
            </h1>
            <p class="text-muted small mb-0">Cấu hình phí ship và thời gian giao hàng dự kiến cho các tỉnh thành.</p>
        </div>
        <a href="{{ route('admin.shipping-rates.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Thêm tỉnh thành mới
        </a>
    </div>

    {{-- Tìm kiếm --}}
    <div class="card border-0 shadow-sm p-3 mb-4">
        <form action="{{ route('admin.shipping-rates.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm theo tên tỉnh/thành phố..." value="{{ request('q') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search me-1"></i>Tìm kiếm
                </button>
            </div>
            @if(request('q'))
                <div class="col-auto">
                    <a href="{{ route('admin.shipping-rates.index') }}" class="btn btn-link btn-sm text-danger text-decoration-none">
                        Xóa tìm kiếm
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- Bảng biểu phí --}}
    <div class="card border-0 shadow-sm p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-muted text-uppercase">
                    <tr>
                        <th class="ps-3">Tỉnh / Thành phố</th>
                        <th>Cước phí vận chuyển</th>
                        <th>Thời gian giao dự kiến</th>
                        <th class="text-end pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shippingRates as $rate)
                        <tr>
                            <td class="ps-3 fw-bold text-dark">
                                <i class="bi bi-geo-alt text-danger me-1"></i>{{ $rate->province_name }}
                            </td>
                            <td>
                                <strong class="text-accent fs-6" style="color:var(--ct-accent);">{{ number_format($rate->fee, 0, ',', '.') }}₫</strong>
                            </td>
                            <td class="small text-muted">
                                <i class="bi bi-clock me-1"></i>{{ $rate->estimated_days ?? 3 }} ngày làm việc
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.shipping-rates.edit', $rate) }}" class="btn btn-outline-secondary btn-sm py-0 px-2" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.shipping-rates.destroy', $rate) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tỉnh thành này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                Chưa có dữ liệu phí vận chuyển.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($shippingRates->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $shippingRates->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
