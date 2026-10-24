@extends('layouts.app')

@section('title', 'Tất cả sản phẩm - CT mart')
@section('description', 'Khám phá hàng ngàn sản phẩm công nghệ, gia dụng, thời trang chất lượng cao tại CT mart.')

@section('content')
<div class="container-xl py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size:0.875rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sản phẩm</li>
        </ol>
    </nav>

    {{-- Title & Search Summary --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">
                @if(request('q'))
                    Kết quả tìm kiếm cho: <span class="text-accent">"{{ request('q') }}"</span>
                @else
                    Tất cả sản phẩm
                @endif
            </h1>
            <p class="text-muted small mb-0">Tìm thấy <strong>{{ $products->total() }}</strong> sản phẩm phù hợp</p>
        </div>

        {{-- Sắp xếp --}}
        <div class="d-flex align-items-center gap-2">
            <label for="sort-select" class="small text-muted text-nowrap fw-semibold">Sắp xếp theo:</label>
            <form id="sort-form" method="GET" action="{{ url()->current() }}">
                @foreach(request()->except('sort', 'page') as $key => $val)
                    @if(is_array($val))
                        @foreach($val as $subVal)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $subVal }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                <select id="sort-select" name="sort" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width: 170px;">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến cao</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến thấp</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A - Z</option>
                </select>
            </form>
        </div>
    </div>

    <div class="row g-4">
        {{-- BỘ LỌC (Sidebar) --}}
        <aside class="col-lg-3">
            <div class="card border-0 shadow-sm p-3 mb-4" style="border-radius: var(--ct-radius);">
                <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                    <span class="fw-bold" style="color:var(--ct-primary)"><i class="bi bi-funnel me-1"></i>Bộ lọc tìm kiếm</span>
                    @if(request()->anyFilled(['category', 'min_price', 'max_price', 'in_stock', 'q']))
                        <a href="{{ route('products.index') }}" class="small text-danger text-decoration-none">Xóa bộ lọc</a>
                    @endif
                </div>

                <form method="GET" action="{{ route('products.index') }}">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    {{-- Danh mục --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase mb-2">Danh mục</label>
                        <div class="d-flex flex-column gap-2" style="max-height: 220px; overflow-y: auto;">
                            @foreach($categories as $category)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="category" value="{{ $category->slug }}"
                                           id="cat-{{ $category->id }}"
                                           {{ request('category') == $category->slug ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <label class="form-check-label small" for="cat-{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-3">

                    {{-- Khoảng giá --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase mb-2">Khoảng giá (₫)</label>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <input type="number" name="min_price" class="form-control form-control-sm"
                                       placeholder="Từ" value="{{ request('min_price') }}" min="0" step="10000">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_price" class="form-control form-control-sm"
                                       placeholder="Đến" value="{{ request('max_price') }}" min="0" step="10000">
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    {{-- Tình trạng hàng --}}
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="in_stock" value="1"
                                   id="in-stock" {{ request('in_stock') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="in-stock">
                                Chỉ hiển thị còn hàng
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-sm">
                        <i class="bi bi-filter me-1"></i>Áp dụng bộ lọc
                    </button>
                </form>
            </div>
        </aside>

        {{-- DANH SÁCH SẢN PHẨM --}}
        <main class="col-lg-9">
            @if($products->isEmpty())
                <div class="card border-0 shadow-sm p-5 text-center my-3" style="border-radius:var(--ct-radius)">
                    <div class="mb-3 text-muted" style="font-size:3.5rem;">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h3 class="h5 fw-bold" style="color:var(--ct-primary)">Không tìm thấy sản phẩm nào</h3>
                    <p class="text-muted small">Hãy thử tìm với từ khóa khác hoặc điều chỉnh lại bộ lọc giá và danh mục.</p>
                    <div class="mt-2">
                        <a href="{{ route('products.index') }}" class="btn btn-accent btn-sm">
                            Xem tất cả sản phẩm
                        </a>
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
