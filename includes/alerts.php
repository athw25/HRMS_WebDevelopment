<?php
if (!defined('HRMS_APP')) { http_response_code(403); die('Direct access is not allowed.'); }

$flashAlert = getAlert();

$iconMap = [
    'success' => 'bi-check-circle-fill',
    'error'   => 'bi-x-circle-fill',
    'warning' => 'bi-exclamation-triangle-fill',
    'info'    => 'bi-info-circle-fill',
];
?>
<div class="toast-container" id="toastContainer">
    <?php if ($flashAlert): ?>
        <div class="toast-item toast-item--<?= sanitizeInput($flashAlert['type']) ?>" data-auto-dismiss="4000">
            <i class="bi <?= sanitizeInput($iconMap[$flashAlert['type']] ?? 'bi-info-circle-fill') ?>"></i>
            <span class="toast-item__message"><?= sanitizeInput($flashAlert['message']) ?></span>
            <button type="button" class="toast-item__close" aria-label="Đóng">&times;</button>
        </div>
    <?php endif; ?>
</div>

<script>
(function () {
    function dismissToast(toast) {
        toast.classList.add('is-hiding');
        setTimeout(() => toast.remove(), 250);
    }

    document.querySelectorAll('.toast-item').forEach(function (toast) {
        const delay = parseInt(toast.dataset.autoDismiss || '4000', 10);
        const timer = setTimeout(() => dismissToast(toast), delay);

        toast.querySelector('.toast-item__close')?.addEventListener('click', function () {
            clearTimeout(timer);
            dismissToast(toast);
        });
    });
})();
</script>
