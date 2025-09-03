<?php
require_once '../includes/header.php';
include '../api/api_share.php';
?>
<div class="row">
    <div class="col-md-8">
        <div class="card shadow rounded-4 border-0 fade-in delay-1">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-share-alt me-2" style="color: #004F80;"></i>Share Document
                </h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>Document Details</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="<?php echo getFileIcon(pathinfo($document['filename'], PATHINFO_EXTENSION)); ?> me-2"></i>
                                    <?php echo htmlspecialchars($document['title']); ?>
                                </h6>
                                <p class="card-text">
                                    <small class="text-muted">
                                        File: <?php echo htmlspecialchars($document['original_filename']); ?><br>
                                        Size: <?php echo formatFileSize($document['file_size']); ?><br>
                                        <?php if ($document['category_name']): ?>
                                        Category: <?php echo htmlspecialchars($document['category_name']); ?><br>
                                        <?php endif; ?>
                                        Uploaded: <?php echo date('M j, Y', strtotime($document['created_at'])); ?>
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Share with User</h6>
                        <form method="POST" action="share.php?id=<?php echo $document['id']; ?>" class="shadow-sm p-4 bg-white rounded">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <div class="mb-3">
                                <label for="share_with" class="form-label">Username or Email</label>
                                <input type="text" class="form-control" id="share_with" name="share_with" 
                                       placeholder="Enter username or email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="permission" class="form-label">Permission Level</label>
                                <select class="form-select" id="permission" name="permission">
                                    <option value="view">View Only</option>
                                    <option value="download">View & Download</option>
                                </select>
                            </div>
                            
                            <button type="submit" name="share_document" class="btn" style="background-color: #004F80; color: white;">
                                <i class="fas fa-share me-2"></i>Share Document
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4 shadow rounded-4 border-0 fade-in delay-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2" style="color: #004F80;"></i>Currently Shared With
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($current_shares)): ?>
                <div class="text-center py-3">
                    <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                    <p class="text-muted">This document hasn't been shared with anyone yet.</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Permission</th>
                                <th>Shared On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($current_shares as $share): ?>
                            <tr>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($share['full_name']); ?></strong><br>
                                        <small class="text-muted">@<?php echo htmlspecialchars($share['username']); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $share['permission'] === 'download' ? 'success' : 'info'; ?>">
                                        <?php echo $share['permission'] === 'download' ? 'View & Download' : 'View Only'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y g:i A', strtotime($share['created_at'])); ?></td>
                                <td>
                                    <form method="POST" class="d-inline" onsubmit="return confirm('Remove sharing with this user?')">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <input type="hidden" name="share_id" value="<?php echo $share['id']; ?>">
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
    
    <div class="col-md-4">
        <div class="card shadow rounded-4 border-0 fade-in delay-2">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cog me-2" style="color: #004F80;"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="preview.php?id=<?php echo $document['id']; ?>" class="btn" style ="background-color: #004F80; color: white;">
                        <i class="fas fa-eye me-2"></i>Preview Document
                    </a>
                    <a href="view.php?id=<?php echo $document['id']; ?>" class="btn" style ="background-color: #FFD166";>
                        <i class="fas fa-info-circle me-2"></i>View Details
                    </a>
                    <a href="../dashboard.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>