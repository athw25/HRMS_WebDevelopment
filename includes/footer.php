<?php
if (!defined('HRMS_APP')) {
    http_response_code(403);
    die('Direct access is not allowed.');
}
?>

    </div>
</div>

<footer class="app-footer">
    <p>
        &copy; <?= date('Y') ?>
        <?= sanitizeInput(APP_NAME) ?>
        - Phiên bản <?= sanitizeInput(APP_VERSION) ?>
    </p>
</footer>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript dùng chung -->
<script src="<?= ASSETS_URL ?>/js/app.js"></script>
<script src="<?= ASSETS_URL ?>/js/form-validation.js"></script>
<script src="<?= ASSETS_URL ?>/js/charts.js"></script>
<script src="<?= ASSETS_URL ?>/js/dark-mode.js"></script>

<!-- JavaScript riêng của module Department -->
<script src="<?= ASSETS_URL ?>/js/department.js"></script>

</body>
</html>