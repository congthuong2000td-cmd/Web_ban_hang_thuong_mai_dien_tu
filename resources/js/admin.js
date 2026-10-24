import 'admin-lte';
import Chart from 'chart.js/auto';

window.Chart = Chart;

// AdminLTE đã tự handle sidebar toggles
// Chỉ cần đặt global helpers cho admin
document.addEventListener('DOMContentLoaded', () => {
    // Confirm dialog cho xóa / hành động nguy hiểm
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm || 'Bạn có chắc chắn không?')) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });

    // Preview ảnh trước khi upload
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        input.addEventListener('change', function () {
            const previewId = this.dataset.preview;
            const preview = document.getElementById(previewId);
            if (!preview) return;
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => { preview.src = e.target.result; preview.style.display = ''; };
                reader.readAsDataURL(file);
            }
        });
    });
});
