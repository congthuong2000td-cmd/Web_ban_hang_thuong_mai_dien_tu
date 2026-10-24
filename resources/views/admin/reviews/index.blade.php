@extends('layouts.admin')

@section('title', 'Quản lý đánh giá - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">
                Đánh giá từ khách hàng
            </h1>
            <p class="text-muted small mb-0">Kiểm duyệt và phản hồi các nhận xét, xếp hạng sản phẩm.</p>
        </div>
    </div>

    {{-- Bộ lọc --}}
    <div class="card border-0 shadow-sm p-3 mb-4">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="rating" class="form-select form-select-sm">
                    <option value="">-- Số sao đánh giá --</option>
                    @for($i=5;$i>=1;$i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Trạng thái duyệt --</option>
                    <option value="visible" {{ request('status') === 'visible' ? 'selected' : '' }}>Đang hiển thị</option>
                    <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Đang ẩn</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-filter me-1"></i>Lọc
                </button>
            </div>
            @if(request()->anyFilled(['rating', 'status']))
                <div class="col-auto">
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-link btn-sm text-danger text-decoration-none">
                        Xóa bộ lọc
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- Bảng đánh giá --}}
    <div class="card border-0 shadow-sm p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-muted text-uppercase">
                    <tr>
                        <th class="ps-3">Sản phẩm</th>
                        <th>Khách hàng</th>
                        <th>Đánh giá</th>
                        <th style="min-width: 250px;">Nội dung nhận xét</th>
                        <th>Thời gian</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $rev)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 36px; height: 36px; flex-shrink: 0;">
                                        @if($rev->product && $rev->product->thumbnail)
                                            <img src="{{ asset('storage/' . $rev->product->thumbnail) }}" alt="{{ $rev->product->name }}" class="w-100 h-100 object-fit-contain rounded border">
                                        @else
                                            <div class="w-100 h-100 bg-light rounded border d-flex align-items-center justify-content-center text-muted">
                                                <i class="bi bi-box"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="small fw-semibold text-dark text-truncate" style="max-width: 180px;">
                                        {{ $rev->product->name ?? 'Sản phẩm' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong class="small text-dark">{{ $rev->user->name ?? 'Khách hàng' }}</strong>
                                <div class="text-muted" style="font-size:0.72rem;">{{ $rev->user->email ?? '' }}</div>
                            </td>
                            <td>
                                <div class="text-warning small text-nowrap">
                                    @for($i=1;$i<=5;$i++)
                                        <i class="bi {{ $i <= $rev->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                    <span class="text-dark fw-bold ms-1">({{ $rev->rating }})</span>
                                </div>
                            </td>
                            <td>
                                <div class="small text-dark">{{ $rev->comment }}</div>
                            </td>
                            <td class="small text-muted text-nowrap">
                                {{ $rev->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="text-center text-nowrap">
                                @if($rev->is_visible)
                                    <span class="badge bg-success-subtle text-success">Hiển thị</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Đã ẩn</span>
                                @endif
                            </td>
                            <td class="text-end pe-3 text-nowrap">
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="{{ route('admin.reviews.approve', $rev) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn {{ $rev->is_visible ? 'btn-outline-warning' : 'btn-outline-success' }} btn-sm py-0 px-2" title="{{ $rev->is_visible ? 'Ẩn nhận xét này' : 'Duyệt hiển thị' }}">
                                            <i class="bi {{ $rev->is_visible ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.reviews.destroy', $rev) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn đánh giá này?');">
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
                            <td colspan="7" class="text-center py-5 text-muted">
                                Không tìm thấy đánh giá nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
