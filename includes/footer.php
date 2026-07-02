<?php
if (!defined('HRMS_APP')) { http_response_code(403); die('Direct access is not allowed.'); }
?>
<footer class="app-footer">
    <p>&copy; <?= date('Y') ?> <?= sanitizeInput(APP_NAME) ?> - Phiên bản <?= sanitizeInput(APP_VERSION) ?></p>
</footer>
