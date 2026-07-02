<?php
if (!defined('HRMS_APP')) { http_response_code(403); die('Direct access is not allowed.'); }

/**
 * Render Bootstrap-style pagination, giữ nguyên query string hiện tại.
 */
function renderPagination(int $currentPage, int $totalPages, string $baseUrl, array $queryParams = []): void
{
    if ($totalPages <= 1) {
        return;
    }

    $currentPage = max(1, min($currentPage, $totalPages));

    $buildUrl = function (int $page) use ($baseUrl, $queryParams): string {
        $queryParams['page'] = $page;
        return $baseUrl . '?' . http_build_query($queryParams);
    };

    $windowSize = 2;
    $start = max(1, $currentPage - $windowSize);
    $end = min($totalPages, $currentPage + $windowSize);
    ?>
    <nav class="pagination-wrap" aria-label="Phân trang">
        <ul class="pagination-list">
            <li class="pagination-item<?= $currentPage <= 1 ? ' is-disabled' : '' ?>">
                <a href="<?= $currentPage > 1 ? sanitizeInput($buildUrl($currentPage - 1)) : '#' ?>" class="pagination-link" aria-label="Trang trước">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>

            <?php if ($start > 1): ?>
                <li class="pagination-item"><a href="<?= sanitizeInput($buildUrl(1)) ?>" class="pagination-link">1</a></li>
                <?php if ($start > 2): ?>
                    <li class="pagination-item is-ellipsis">&hellip;</li>
                <?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $start; $i <= $end; $i++): ?>
                <li class="pagination-item<?= $i === $currentPage ? ' is-active' : '' ?>">
                    <a href="<?= sanitizeInput($buildUrl($i)) ?>" class="pagination-link"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($end < $totalPages): ?>
                <?php if ($end < $totalPages - 1): ?>
                    <li class="pagination-item is-ellipsis">&hellip;</li>
                <?php endif; ?>
                <li class="pagination-item"><a href="<?= sanitizeInput($buildUrl($totalPages)) ?>" class="pagination-link"><?= $totalPages ?></a></li>
            <?php endif; ?>

            <li class="pagination-item<?= $currentPage >= $totalPages ? ' is-disabled' : '' ?>">
                <a href="<?= $currentPage < $totalPages ? sanitizeInput($buildUrl($currentPage + 1)) : '#' ?>" class="pagination-link" aria-label="Trang sau">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>
    <?php
}
