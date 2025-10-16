<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once 'backup_functions.php';

requireAdmin();

$backups = getBackupList();
?>
<?php include '../includes/header.php'; ?>
    <link rel="stylesheet" href="../assets/css/backup.css">
    <div class="container-fluid mt-4">
        <div class="row">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-database me-2"></i>Backup & Recovery
                    </h2>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.history.back();">
                        <i class="fas fa-arrow-left me-2"></i> Go Back
                    </button>
                </div>

                <div id="alert-container"></div>


                <div class="row">
                    <!-- Create Backup Card -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Create New Backup</h5>
                            </div>
                            <div class="card-body">
                                <form id="createBackupForm">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Backup Type</label>
                                        <select class="form-select" name="backup_type" required>
                                            <option value="both">Database & Files</option>
                                            <option value="database">Database Only</option>
                                            <option value="files">Files Only</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" name="include_files" id="include_files" checked>
                                        <label class="form-check-label" for="include_files">
                                            Include uploaded files
                                        </label>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <small>
                                            <i class="fas fa-info-circle me-2"></i>
                                            Full backup includes both database and all uploaded documents.
                                        </small>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-download me-2"></i>Create Backup
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Restore Backup Card -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-undo me-2"></i>Restore Backup</h5>
                            </div>
                            <div class="card-body">
                                <form id="restoreBackupForm">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Select Backup</label>
                                        <select class="form-select" name="backup_folder" required>
                                            <option value="">Choose a backup...</option>
                                            <?php foreach ($backups as $backup): ?>
                                                <option value="<?php echo htmlspecialchars($backup['folder']); ?>">
                                                    <?php echo htmlspecialchars($backup['folder']); ?> 
                                                    (<?php echo $backup['created_at']; ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="alert alert-warning">
                                        <small>
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Warning: This will overwrite current data. Use with caution.
                                        </small>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-warning w-100">
                                        <i class="fas fa-undo me-2"></i>Restore Backup
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Existing Backups -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Existing Backups</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($backups)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No backups found</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Backup Name</th>
                                            <th>Created</th>
                                            <th>Type</th>
                                            <th>Size</th>
                                            <th>Files Included</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($backups as $backup): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($backup['folder']); ?></td>
                                                <td><?php echo $backup['created_at']; ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $backup['type'] === 'both' ? 'primary' : ($backup['type'] === 'database' ? 'info' : 'success'); ?>">
                                                        <?php echo ucfirst($backup['type']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo $backup['size']; ?></td>
                                                <td>
                                                    <?php if ($backup['include_files']): ?>
                                                        <i class="fas fa-check text-success"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-times text-danger"></i>
                                                    <?php endif; ?>
                                                </td>
                                                    <td>
                                                        <?php if (!empty($backup['zip_exists'])): ?>
                                                            <a href="../backups/uploads/<?php echo htmlspecialchars($backup['zip_file']); ?>" 
                                                               class="btn btn-sm btn-outline-success me-1" download>
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <button class="btn btn-sm btn-outline-danger delete-backup"
                                                                data-backup-folder="<?php echo htmlspecialchars($backup['folder']); ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                <td>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show alert message
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alert-container');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            alertContainer.appendChild(alert);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }

        // Handle form submissions with AJAX
        document.getElementById('createBackupForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'create_backup');
            
            fetch('api_backup.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    // Reload page after 2 seconds to show new backup
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert(data.error, 'danger');
                }
            })
            .catch(error => {
                showAlert('Backup creation failed', 'danger');
            });
        });

        document.getElementById('restoreBackupForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const backupFolder = this.backup_folder.value;
            
            if (!confirm('WARNING: This will overwrite all current data. Are you sure?')) {
                return;
            }
            
            const formData = new FormData(this);
            formData.append('action', 'restore_backup');
            
            fetch('api_backup.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    // Reload page after 2 seconds
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert(data.error, 'danger');
                }
            })
            .catch(error => {
                showAlert('Backup restoration failed', 'danger');
            });
        });

        // Handle backup deletion
        document.querySelectorAll('.delete-backup').forEach(button => {
            button.addEventListener('click', function() {
                const backupFolder = this.getAttribute('data-backup-folder');
                
                if (!confirm('Are you sure you want to delete this backup?')) {
                    return;
                }
                
                const formData = new FormData();
                formData.append('action', 'delete_backup');
                formData.append('backup_folder', backupFolder);
                
                fetch('api_backup.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message, 'success');
                        // Reload page after 2 seconds
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        showAlert(data.error, 'danger');
                    }
                })
                .catch(error => {
                    showAlert('Backup deletion failed', 'danger');
                });
            });
        });
    </script>