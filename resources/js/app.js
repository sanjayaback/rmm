import './bootstrap';

// Auto-dismiss flash messages after 5s
document.addEventListener('DOMContentLoaded', () => {
    // Auto-hide non-Alpine flash messages
    const flashes = document.querySelectorAll('.flash-auto-dismiss');
    flashes.forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.4s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 5000);
    });

    // Confirm delete buttons
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', e => {
            const msg = btn.dataset.confirm || 'Are you sure?';
            if (!confirm(msg)) e.preventDefault();
        });
    });

    // File input preview (generic)
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        const previewId = input.dataset.preview;
        const preview = document.getElementById(previewId);
        if (!preview) return;
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => { preview.src = e.target.result; };
                reader.readAsDataURL(file);
            }
        });
    });
});
