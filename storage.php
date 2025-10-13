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

<script>
async function loadStorageData() {
    try {
        const res = await fetch('api/storage.php');
        const data = await res.json();

        const formatBytes = (bytes) => {
            const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
            if (bytes === 0) return '0 B';
            const i = Math.floor(Math.log(bytes) / Math.log(1024));
            return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + sizes[i];
        }

        const progressBar = (percent) => {
            let color = 'bg-success';
            if (percent > 90) color = 'bg-danger';
            else if (percent > 70) color = 'bg-warning';
            return `<div class="progress" style="height:15px;">
                        <div class="progress-bar ${color}" role="progressbar" style="width:${Math.min(percent,100)}%"></div>
                    </div>`;
        }

        let html = `
            <h5>${data.is_admin ? 'Total Used' : 'Your Usage'}: 
                ${formatBytes(data.is_admin ? data.total_used : data.user_used)} / ${formatBytes(data.limit)}
            </h5>
            ${progressBar(data.is_admin ? data.percent_total : data.percent_user)}
            <small>${(data.is_admin ? data.percent_total : data.percent_user).toFixed(2)}% ${data.is_admin ? 'used system-wide' : 'of total system storage'}</small>
        `;

        if (data.is_admin) {
            // Top Users
            html += `<div class="card shadow-sm mb-4 mt-4"><div class="card-body">
                        <h5 class="mb-3">Top Users by Storage Usage</h5>
                        <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>User</th><th>Storage Used</th></tr></thead>
                        <tbody>`;
            data.users.forEach(u => {
                html += `<tr><td>${u.full_name}</td><td>${formatBytes(u.used)}</td></tr>`;
            });
            html += `</tbody></table></div></div>`;

            // Top Files
            html += `<div class="card shadow-sm mb-4"><div class="card-body">
                        <h5 class="mb-3">Top Documents by Size</h5>
                        <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>File Name</th><th>Size</th><th>Uploaded By</th><th>Date Uploaded</th></tr></thead>
                        <tbody>`;
            data.files.forEach(f => {
                const date = new Date(f.created_at).toLocaleString();
                html += `<tr><td>${f.original_filename}</td><td>${formatBytes(f.file_size)}</td><td>${f.uploader_name}</td><td>${date}</td></tr>`;
            });
            html += `</tbody></table></div></div>`;

            // Top Folders
            if (data.folders.length) {
                html += `<div class="card shadow-sm mb-4"><div class="card-body">
                            <h5 class="mb-3">Top Folders by Storage Usage</h5>
                            <table class="table table-hover align-middle">
                            <thead class="table-light"><tr><th>Folder</th><th>Used</th></tr></thead>
                            <tbody>`;
                data.folders.forEach(f => {
                    html += `<tr><td>${f.name}</td><td>${formatBytes(f.used)}</td></tr>`;
                });
                html += `</tbody></table></div></div>`;
            }
        } else {
            // User files
            html += `<div class="card shadow-sm mt-4"><div class="card-body">
                        <h5 class="mb-3">Your Uploaded Files</h5>
                        <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>File Name</th><th>Size</th><th>Date Uploaded</th></tr></thead>
                        <tbody>`;
            if (data.user_files.length) {
                data.user_files.forEach(f => {
                    const date = new Date(f.created_at).toLocaleString();
                    html += `<tr><td>${f.original_filename}</td><td>${formatBytes(f.file_size)}</td><td>${date}</td></tr>`;
                });
            } else {
                html += `<tr><td colspan="3" class="text-center text-muted">No uploaded files yet</td></tr>`;
            }
            html += `</tbody></table></div></div>`;
        }

        document.getElementById('storage-content').innerHTML = html;

    } catch (err) {
        console.error(err);
        document.getElementById('storage-content').innerHTML = '<p class="text-danger">Failed to load storage data.</p>';
    }
}

loadStorageData();
</script>
