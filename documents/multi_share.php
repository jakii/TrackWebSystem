<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/activity_logger.php';
requireAuth();

require_once '../api/api_multi_share.php';
require_once '../includes/header.php';
?>

<div class="row">
    <div>
        <!-- Share Documents Card -->
        <div class="card shadow rounded-4 border-0">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-share-alt me-2 text-primary"></i>Share Documents</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?><div class="alert alert-danger w-50 fade show mt-3 text-start shadow-sm" id="autoAlert"><?= $error ?></div><?php endif; ?>
                <?php if (!empty($success)): ?><div class="alert alert-success w-50 fade show mt-3 text-start shadow-sm" id="autoAlert"><?= $success ?></div><?php endif; ?>
            <script>
                setTimeout(() => {
                    const alert = document.getElementById('autoAlert');
                    if (alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 2000);
            </script>
                <form method="POST" action="multi_share.php" class="shadow-sm p-4 bg-white rounded">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    <!-- Documents Selection -->
                     <div class="mb-3">
                        <label class="form-label">Select Documents</label>
                        <div class="border rounded overflow-auto" style="max-height:250px;">
                            <!-- Select All -->
                            <div class="form-check mb-2 ms-4 p-2 border-bottom">
                                <input class="form-check-input" type="checkbox" id="select_all_docs">
                                <label class="form-check-label fw-bold" for="select_all_docs">Select All</label>
                            </div>
                                    
                            <?php if (!empty($documents)): ?>
                                <table class="table table-hover mb-0">
                                    <tbody>
                                        <?php foreach ($documents as $doc): ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <div class="form-check d-flex align-items-center">
                                                        <input class="form-check-input doc-checkbox" type="checkbox" name="document_ids[]" value="<?= $doc['id'] ?>" id="doc_<?= $doc['id'] ?>">
                                                        <label class="form-check-label ms-2 w-100" for="doc_<?= $doc['id'] ?>">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <!-- File Icon -->
                                                                <div class="file-icon">
                                                                    <i class="<?= getFileIcon(pathinfo($doc['original_filename'], PATHINFO_EXTENSION)); ?>" style="color: var(--primary-color);"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-semibold"><?= htmlspecialchars($doc['title']) ?></div>
                                                                    <small class="text-muted">
                                                                        <?= htmlspecialchars($doc['original_filename']) ?> — <?= formatFileSize($doc['file_size']) ?>, <?= htmlspecialchars($doc['category_name'] ?? 'Uncategorized') ?>
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="p-2 text-muted">No documents found.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Users Input -->
                    <div class="mb-3">
                        <label for="share_with" class="form-label">Select Users to Share With</label>
                                                
                        <?php
                        $user_query = $db->prepare("SELECT id, full_name, email FROM users WHERE id != ?");
                        $user_query->execute([$_SESSION['user_id']]);
                        $available_users = $user_query->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                    
                        <select class="form-select" id="share_with" name="share_with[]" multiple required>
                            <?php foreach ($available_users as $u): ?>
                                <option value="<?= htmlspecialchars($u['email']) ?>">
                                    <?= htmlspecialchars($u['full_name'] . ' (' . $u['email'] . ')') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                            
                        <div class="form-text text-muted">
                            Hold <strong>Ctrl</strong> to select multiple users.
                        </div>
                    </div>
                            

                    <!-- Permission -->
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="permission" id="perm_view" value="view" checked>
                            <label class="form-check-label" for="perm_view">View Only</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="permission" id="perm_download" value="download">
                            <label class="form-check-label" for="perm_download">View & Download</label>
                        </div>
                    </div>

                    <button type="submit" name="share_document" class="btn" style="background-color:#004F80;color:white;">
                        <i class="fas fa-share me-2"></i>Share Documents
                    </button>
                </form>
            </div>
        </div>

        <!-- Currently Shared With Card -->
        <div class="card mt-4 shadow rounded-4 border-0">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Currently Shared With</h5>
            </div>
            <div class="card-body">
                <?php if (empty($current_shares)): ?>
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-user-friends fa-3x mb-3"></i>
                        <p>No shares yet.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive" style="max-height:350px; overflow-y:auto;">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>User</th>
                                    <th>Permission</th>
                                    <th>Shared On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($current_shares as $share): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($share['document_title']) ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($share['full_name']) ?></strong><br>
                                            <small class="text-muted">@<?= htmlspecialchars($share['username']) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $share['permission']==='download'?'success':'info' ?>">
                                                <?= $share['permission']==='download'?'View & Download':'View Only' ?>
                                            </span>
                                        </td>
                                        <td><?= date('M j, Y g:i A', strtotime($share['created_at'])) ?></td>
                                        <td>
                                            <form method="POST" class="d-inline" onsubmit="return confirm('Remove sharing?');">
                                                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                                <input type="hidden" name="share_id" value="<?= $share['id'] ?>">
                                                <button type="submit" name="unshare_document" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
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

<script>
    // Select/Deselect all documents
    const selectAll = document.getElementById('select_all_docs');
    if(selectAll){
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.doc-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }
</script>
