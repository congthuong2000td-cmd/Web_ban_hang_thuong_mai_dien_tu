<div class="row g-3">
    @forelse($products as $product)
        @include('products.partials.card', ['product' => $product])
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm p-5 text-center my-3" style="border-radius:var(--ct-radius)">
                <div class="mb-3 text-muted" style="font-size:3.5rem;">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3 class="h5 fw-bold" style="color:var(--ct-primary)">Không tìm thấy sản phẩm nào</h3>
                <p class="text-muted small">Hãy thử tìm với từ khóa hoặc bộ lọc khác.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $products->links() }}
</div>
