@extends('layouts.app')

@section('title', $category->name . ' - CT mart')
@section('description', 'Mua sắm các sản phẩm ' . $category->name . ' chính hãng, giá ưu đãi tại CT mart.')

@section('content')
<div class="container-xl py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size:0.875rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Danh mục</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    {{-- Banner Danh Mục --}}
    <div class="card border-0 shadow-sm p-4 mb-4 text-white position-relative overflow-hidden"
         style="background: linear-gradient(135deg, var(--ct-primary) 0%, #1E40AF 100%); border-radius: var(--ct-radius-lg);">
        <div class="row align-items-center position-relative" style="z-index: 1;">
            <div class="col-md-8">
                <span class="badge mb-2" style="background: rgba(255, 107, 53, 0.25); color: #FF8F6B;">Danh mục ngành hàng</span>
                <h1 class="h2 fw-bold text-white mb-2">{{ $category->name }}</h1>
                <p class="mb-0 text-white-50">
                    {{ $category->description ?? 'Tổng hợp các sản phẩm chất lượng cao thuộc ngành hàng ' . $category->name . ' tại CT mart.' }}
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-white text-dark py-2 px-3 fw-semibold">
                    <i class="bi bi-box-seam me-1"></i>{{ $products->total() }} sản phẩm
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Danh mục khác (Sidebar) --}}
        <aside class="col-lg-3">
            <div class="card border-0 shadow-sm p-3 mb-4" style="border-radius: var(--ct-radius);">
                <h3 class="h6 fw-bold pb-2 mb-3 border-bottom" style="color:var(--ct-primary)">
                    <i class="bi bi-grid me-2"></i>Tất cả danh mục
                </h3>
                <div class="list-group list-group-flush">
                    @foreach($categories as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}"
                           class="list-group-item list-group-item-action border-0 px-2 py-2 rounded-2 d-flex justify-content-between align-items-center {{ $cat->id === $category->id ? 'active fw-bold' : '' }}">
                            <span>{{ $cat->name }}</span>
                            @if($cat->id === $category->id)
                                <i class="bi bi-chevron-right"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- Danh sách sản phẩm của danh mục --}}
        <main class="col-lg-9">
            @if($products->isEmpty())
                <div class="card border-0 shadow-sm p-5 text-center my-3" style="border-radius:var(--ct-radius)">
                    <div class="mb-3 text-muted" style="font-size:3.5rem;">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h3 class="h5 fw-bold" style="color:var(--ct-primary)">Danh mục này hiện chưa có sản phẩm</h3>
                    <p class="text-muted small">Vui lòng quay lại sau hoặc khám phá các danh mục sản phẩm khác.</p>
                    <div class="mt-2">
                        <a href="{{ route('products.index') }}" class="btn btn-accent btn-sm">Xem tất cả sản phẩm</a>
                    </div>
                </div>
            @else
                <div class="row g-3">
                    @foreach($products as $product)
                        @include('products.partials.card', ['product' => $product])
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
