<?php
include 'api/api_dashboard.php';
include 'includes/header.php';
requireAuth();
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/dashboard.css">
<div class="container-fluid py-4">

    <!-- Loading Skeleton -->
    <div id="loadingSkeleton">
        <div class="row mb-4">
            <div class="col-12">
                <div class="dashboard-header">
                    <div class="placeholder-glow">
                        <span class="placeholder col-3 rounded" style="height: 30px;"></span>
                        <span class="placeholder col-2 rounded ms-2" style="height: 20px;"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <?php for ($i = 0; $i < 3; $i++): ?>
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                        <div class="card-body p-4">
                            <div class="placeholder-glow">
                                <div class="placeholder col-7 rounded" style="height: 20px;"></div>
                                <div class="placeholder col-4 rounded mt-3" style="height: 40px;"></div>
                                <div class="placeholder col-5 rounded mt-2" style="height: 15px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Main Content -->
    <div id="dashboardContent" class="d-none">

        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="dashboard-header">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <h2 class="mb-2">
                                <i class="fas fa-chart-line me-2"></i>Dashboard
                            </h2>
                            <p class="text-muted mb-0">Welcome back! Here's your overview.</p>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Last updated: <?php echo date('M j, Y g:i A'); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isAdmin()): ?>
            <!-- ===================== ADMIN DASHBOARD ===================== -->
            <div class="row g-4 mb-4">
                <!-- Stat Cards -->
                <?php
                $stats = [
                    [
                        'title' => 'Total Documents',
                        'icon' => 'fa-file-alt',
                        'value' => $total_documents,
                        'desc' => 'Across all users'
                    ],
                    [
                        'title' => 'Storage Used',
                        'icon' => 'fa-database',
                        'value' => formatFileSize($total_size),
                        'desc' => 'Total system storage'
                    ],
                    [
                        'title' => 'Categories',
                        'icon' => 'fa-layer-group',
                        'value' => count($categories),
                        'desc' => 'Organized categories'
                    ]
                ];
                foreach ($stats as $stat): ?>
                    <div class="col-xl-4 col-md-6">
                        <div class="stat-card h-100">
                            <div class="card-body text-white p-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle">
                                        <i class="fas <?= $stat['icon'] ?> fa-2x"></i>
                                    </div>
                                    <div class="ms-4 flex-grow-1">
                                        <h6 class="opacity-75 mb-2 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;"><?= $stat['title'] ?></h6>
                                        <div class="stat-value"><?= $stat['value'] ?></div>
                                        <small class="opacity-75"><?= $stat['desc'] ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Charts -->
            <div class="row g-4 mb-4">
                <div class="col-xl-8">
                    <div class="chart-card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2" style="color: var(--primary-color);"></i>
                                Weekly Uploads
                            </h5>
                            <a href="all_reports.php" class="btn btn-accent-custom btn-sm">
                                <i class="fas fa-file-chart me-2"></i>View Reports
                            </a>
                        </div>
                        <div class="card-body">
                            <canvas id="weeklyUploadsChart"
                                data-labels='<?php echo json_encode(array_keys($weekly_uploads ?? [])); ?>'
                                data-data='<?php echo json_encode(array_values($weekly_uploads ?? [])); ?>'
                                height="250"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header border-0 py-3">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-chart-pie me-2" style="color: #FFD166;"></i> Document Statistics
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="statisticsChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Documents -->
            <div class="documents-table">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-folder-open me-2" style="color: #FFD166;"></i>
                            Recent Documents
                        </h5>
                        <small class="text-muted">Latest uploaded across the system</small>
                    </div>
                    <a href="documents/browse.php" class="btn btn-accent-custom btn-sm">
                        <i class="fas fa-folder me-2"></i>Browse All
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recent_documents)): ?>
                        <div class="empty-state">
                            <i class="fas fa-file-circle-plus"></i>
                            <p>No documents uploaded yet.</p>
                            <a href="documents/upload.php" class="btn btn-accent-custom">
                                <i class="fas fa-upload me-2"></i>Upload Document
                            </a>
                        </div>
                    <?php else: ?>
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Document</th>
                                        <th>Category</th>
                                        <th>Size</th>
                                        <th>Date</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_documents as $i => $doc): ?>
                                        <tr class="doc-row <?= $i >= 5 ? 'd-none' : '' ?>">
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="file-icon">
                                                        <i class="<?= getFileIcon(pathinfo($doc['filename'], PATHINFO_EXTENSION)); ?>" style="color: var(--primary-color);"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold text-dark"><?= htmlspecialchars($doc['title']); ?></div>
                                                        <small class="text-muted">
                                                            <i class="fas fa-user me-1"></i><?= htmlspecialchars($doc['uploader_name'] ?? $_SESSION['username']); ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($doc['category_name']): ?>
                                                    <span class="badge badge-custom" style="background-color: <?= $doc['category_color'] ?>; color: white;">
                                                        <?= htmlspecialchars($doc['category_name']); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-custom" style="background-color: #6B7280; color: white;">Uncategorized</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-muted"><?= formatFileSize($doc['file_size']); ?></td>
                                            <td class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                <?= date('M j, Y', strtotime($doc['created_at'])); ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn-action" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v text-muted"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="documents/preview.php?id=<?= $doc['id'] ?>"><i class="fas fa-eye me-2"></i>Preview</a></li>
                                                        <li><a class="dropdown-item" href="documents/download.php?id=<?= $doc['id'] ?>"><i class="fas fa-download me-2"></i>Download</a></li>
                                                        <li><a class="dropdown-item" href="documents/view.php?id=<?= $doc['id'] ?>"><i class="fas fa-info-circle me-2"></i>Details</a></li>
                                                        <li><a class="dropdown-item" href="documents/share.php?id=<?= $doc['id'] ?>"><i class="fas fa-share me-2"></i>Share</a></li>
                                                        <?php if ($doc['uploaded_by'] == $_SESSION['user_id'] || isAdmin()): ?>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger" href="documents/delete.php?id=<?= $doc['id'] ?>"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php if (count($recent_documents) > 5): ?>
                            <div class="text-center p-4">
                                <button id="showMoreBtn" class="btn btn-show-more">
                                    <i class="fas fa-chevron-down me-2"></i>Show More
                                </button>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <!-- ===================== USER DASHBOARD ===================== -->
            <?php include 'partials/user_dashboard_content.php'; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="assets/js/weekly_uploads.js"></script>
<script src="assets/js/counter.js"></script>
<script src="assets/js/statistics.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const skeleton = document.getElementById('loadingSkeleton');
    const content = document.getElementById('dashboardContent');
    
    setTimeout(() => {
        skeleton.classList.add('d-none');
        content.classList.remove('d-none');
    }, 500);

    const btn = document.getElementById("showMoreBtn");
    if (btn) {
        btn.addEventListener("click", function() {
            const hidden = document.querySelectorAll(".doc-row.d-none");
            if (hidden.length > 0) {
                hidden.forEach(r => r.classList.remove("d-none"));
                btn.innerHTML = '<i class="fas fa-chevron-up me-2"></i>Show Less';
            } else {
                document.querySelectorAll(".doc-row").forEach((r, i) => {
                    if (i >= 5) r.classList.add("d-none");
                });
                btn.innerHTML = '<i class="fas fa-chevron-down me-2"></i>Show More';
            }
        });
    }
});
</script>