@extends('layouts.app')

@section('title', 'Sổ địa chỉ nhận hàng - CT mart')

@section('content')
<div class="container-xl py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size:0.875rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.profile') }}">Tài khoản</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sổ địa chỉ</li>
        </ol>
    </nav>

    <div class="row g-4">
        <aside class="col-lg-3">
            @include('account.partials.sidebar')
        </aside>

        <main class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h5 fw-bold mb-0" style="color:var(--ct-primary)">
                    <i class="bi bi-geo-alt-fill text-danger me-2"></i>Sổ địa chỉ nhận hàng
                </h1>
                <a href="{{ route('account.addresses.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Thêm địa chỉ mới
                </a>
            </div>

            @if($addresses->isEmpty())
                <div class="card border-0 shadow-sm p-5 text-center" style="border-radius: var(--ct-radius);">
                    <div class="mb-3 text-muted" style="font-size: 3.5rem;">
                        <i class="bi bi-geo"></i>
                    </div>
                    <h2 class="h6 fw-bold text-dark">Bạn chưa có địa chỉ nhận hàng nào</h2>
                    <p class="text-muted small mb-3">Thêm địa chỉ giao hàng để đặt hàng nhanh chóng và thuận tiện hơn.</p>
                    <div>
                        <a href="{{ route('account.addresses.create') }}" class="btn btn-primary btn-sm px-3">
                            <i class="bi bi-plus-lg me-1"></i>Thêm địa chỉ ngay
                        </a>
                    </div>
                </div>
            @else
                <div class="row g-3">
                    @foreach($addresses as $addr)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm p-3 h-100 position-relative {{ $addr->is_default ? 'border border-primary' : '' }}" style="border-radius: var(--ct-radius);">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="fw-bold text-dark fs-6">{{ $addr->receiver_name }}</div>
                                    @if($addr->is_default)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Mặc định</span>
                                    @endif
                                </div>

                                <div class="small text-muted mb-3 d-flex flex-column gap-1 flex-grow-1">
                                    <div><i class="bi bi-telephone me-2"></i>{{ $addr->receiver_phone }}</div>
                                    <div><i class="bi bi-geo-alt me-2"></i>{{ $addr->street_address }}, {{ $addr->ward }}, {{ $addr->province }}</div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <div>
                                        @if(!$addr->is_default)
                                            <form action="{{ route('account.addresses.setDefault', $addr) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-link p-0 text-primary small text-decoration-none">
                                                    Đặt làm mặc định
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('account.addresses.edit', $addr) }}" class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size:0.8rem;">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </a>
                                        @if(!$addr->is_default)
                                            <form action="{{ route('account.addresses.destroy', $addr) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size:0.8rem;">
                                                    <i class="bi bi-trash"></i> Xóa
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
