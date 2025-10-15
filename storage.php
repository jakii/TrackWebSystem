<?php
require_once __DIR__ . '/includes/auth_check.php';
requireAuth();
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-4" id="storage-dashboard">
    <h2 class="mb-3" style="color: var(--primary);">
        <i class="fas fa-database me-2" style="color: var(--accent);"></i>
        Storage Overview
    </h2>

    <div id="storage-content">
        <!-- Content will be loaded via JS -->
        <div class="text-center py-5">
            <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
            <p class="mt-3 text-muted">Loading storage data...</p>
        </div>
    </div>
</div>
<script src="assets/js/storage.js"></script>
