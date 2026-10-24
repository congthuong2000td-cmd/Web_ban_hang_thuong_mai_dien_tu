import 'bootstrap';

// CSRF token helper
const csrfToken = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

// ===== GIỎ HÀNG (Cart) AJAX =====
document.addEventListener('DOMContentLoaded', () => {
    // Thêm vào giỏ hàng
    document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const qty = parseInt(document.querySelector('#qty-input')?.value || 1);

            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang thêm...';

            try {
                const res = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken(),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ product_id: productId, quantity: qty }),
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    showToast('success', data.message || 'Đã thêm vào giỏ hàng!');
                    updateCartBadge(data.cart_count);
                } else {
                    showToast('danger', data.message || 'Không thể thêm vào giỏ hàng.');
                }
            } catch (err) {
                showToast('danger', 'Lỗi kết nối. Vui lòng thử lại.');
            } finally {
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ';
            }
        });
    });

    // Cập nhật số lượng giỏ hàng
    document.querySelectorAll('.cart-qty-input').forEach(input => {
        input.addEventListener('change', async function () {
            const productId = this.dataset.productId;
            const qty = parseInt(this.value);
            if (qty < 1 || isNaN(qty)) {
                this.value = 1;
                return;
            }
            await updateCartItem(productId, qty);
        });
    });

    // Xóa khỏi giỏ hàng
    document.querySelectorAll('.btn-cart-remove').forEach(btn => {
        btn.addEventListener('click', async function () {
            const productId = this.dataset.productId;
            const row = this.closest('tr') || this.closest('.cart-item');
            if (!row) return;
            row.style.opacity = '0.5';
            await removeCartItem(productId, row);
        });
    });

    // Số lượng tăng/giảm trang chi tiết
    const qtyInput = document.querySelector('#qty-input');
    document.querySelector('#btn-qty-plus')?.addEventListener('click', () => {
        if (qtyInput) {
            const max = parseInt(qtyInput.max || 999);
            qtyInput.value = Math.min(parseInt(qtyInput.value || 1) + 1, max);
        }
    });
    document.querySelector('#btn-qty-minus')?.addEventListener('click', () => {
        if (qtyInput) {
            qtyInput.value = Math.max(parseInt(qtyInput.value || 1) - 1, 1);
        }
    });
});

async function updateCartItem(productId, qty) {
    try {
        const res = await fetch('/cart/update', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId, quantity: qty }),
        });
        const data = await res.json();
        if (res.ok && data.success) {
            updateCartTotals(data);
        } else {
            showToast('warning', data.message || 'Không thể cập nhật số lượng.');
            location.reload();
        }
    } catch {
        showToast('danger', 'Lỗi kết nối. Vui lòng tải lại trang.');
    }
}

async function removeCartItem(productId, row) {
    try {
        const res = await fetch('/cart/remove', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId }),
        });
        const data = await res.json();
        if (res.ok && data.success) {
            row.remove();
            updateCartBadge(data.cart_count);
            updateCartTotals(data);
            if (data.cart_count === 0) {
                location.reload();
            }
        }
    } catch {
        row.style.opacity = '1';
        showToast('danger', 'Lỗi kết nối. Vui lòng tải lại trang.');
    }
}

function updateCartBadge(count) {
    const badge = document.querySelector('#cart-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? '' : 'none';
    }
}

function updateCartTotals(data) {
    if (data.subtotal_formatted) {
        document.querySelector('#cart-subtotal')?.replaceWith(
            Object.assign(document.querySelector('#cart-subtotal') || document.createElement('span'), { textContent: data.subtotal_formatted })
        );
    }
}

// ===== TÌMKIẾM & LỌC AJAX (cập nhật URL và kết quả) =====
let searchDebounceTimer;

const productSearchInput = document.querySelector('#product-search-input');
if (productSearchInput) {
    productSearchInput.addEventListener('input', function () {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(() => applyFilters(), 400);
    });
}

function applyFilters() {
    const form = document.querySelector('#filter-form');
    if (!form) return;
    const params = new URLSearchParams(new FormData(form));
    params.set('page', 1);
    const url = `${window.location.pathname}?${params.toString()}`;
    window.history.pushState({}, '', url);
    fetchProducts(url);
}

function fetchProducts(url) {
    const container = document.querySelector('#products-container');
    if (!container) return;
    container.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('#products-container');
            if (newContent) container.innerHTML = newContent.innerHTML;
            // Cập nhật phân trang
            const paginationEl = doc.querySelector('#pagination-container');
            const currentPag = document.querySelector('#pagination-container');
            if (paginationEl && currentPag) currentPag.innerHTML = paginationEl.innerHTML;
        })
        .catch(() => {
            container.innerHTML = '<div class="col-12 text-center py-5 text-danger"><i class="bi bi-exclamation-triangle fs-3"></i><p>Đã xảy ra lỗi. Vui lòng thử lại.</p></div>';
        });
}

// ===== TOAST NOTIFICATIONS =====
function showToast(type, message) {
    const container = document.querySelector('#toast-container') || createToastContainer();
    const toastId = `toast-${Date.now()}`;
    const icons = { success: 'bi-check-circle-fill', danger: 'bi-x-circle-fill', warning: 'bi-exclamation-triangle-fill', info: 'bi-info-circle-fill' };
    const colors = { success: '#10B981', danger: '#EF4444', warning: '#F59E0B', info: '#0284C7' };

    const html = `
        <div id="${toastId}" class="toast align-items-center border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi ${icons[type] || 'bi-info-circle-fill'}" style="color: ${colors[type] || '#0284C7'}; font-size: 1.2rem;"></i>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>`;

    container.insertAdjacentHTML('beforeend', html);
    const toastEl = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
    toast.show();
    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}

function createToastContainer() {
    const el = document.createElement('div');
    el.id = 'toast-container';
    el.className = 'position-fixed top-0 end-0 p-3';
    el.style.zIndex = '1090';
    document.body.appendChild(el);
    return el;
}

// Hiển thị flash message từ session dưới dạng toast
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-flash-type]').forEach(el => {
        showToast(el.dataset.flashType, el.dataset.flashMessage);
    });

    // Vô hiệu nút submit sau khi bấm để chống double submit
    document.querySelectorAll('form[data-prevent-double-submit]').forEach(form => {
        form.addEventListener('submit', function () {
            const btn = this.querySelector('[type=submit]');
            if (btn) {
                btn.disabled = true;
                const original = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Đang xử lý...';
                // Tự động re-enable sau 10s nếu có lỗi
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = original;
                }, 10000);
            }
        });
    });
});

// Export helpers
window.showToast = showToast;
window.applyFilters = applyFilters;
