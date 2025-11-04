<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/activity_logger.php';
requireAuth();

$document_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$document_id) {
    header("Location: ../dashboard.php?error=" . urlencode('Invalid document ID.'));
    exit();
}

require_once '../api/api_share.php';

$doc_query = $db->prepare("SELECT d.*, c.name AS category_name FROM documents d LEFT JOIN categories c ON d.category_id = c.id WHERE d.id = ?");
$doc_query->execute([$document_id]);
$document = $doc_query->fetch();

if (!$document || ($document['uploaded_by'] != $_SESSION['user_id'] && !isAdmin() && $document['visibility'] != 'is_public')) {
    header("Location: ../dashboard.php?error=" . urlencode('Document not found or access denied.'));
    exit();
}

$current_shares_query = $db->prepare("
    SELECT ds.*, u.username, u.full_name
    FROM document_shares ds
    JOIN users u ON ds.shared_with = u.id
    WHERE ds.document_id = ? AND ds.shared_by = ?
    ORDER BY ds.created_at DESC
");
$current_shares_query->execute([$document_id, $_SESSION['user_id']]);
$current_shares = $current_shares_query->fetchAll();

require_once '../includes/header.php';
?>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow rounded-4 border-0">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-share-alt me-2 text-primary"></i>Share Document</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Document Details</h6>
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="<?php echo getFileIcon(pathinfo($document['filename'], PATHINFO_EXTENSION)); ?> me-2"></i>
                                    <?php echo htmlspecialchars($document['title']); ?>
                                </h6>
                                <p class="card-text small text-muted">
                                    File: <?php echo htmlspecialchars($document['original_filename']); ?><br>
                                    Size: <?php echo formatFileSize($document['file_size']); ?><br>
                                    <?php if (!empty($document['category_name'])): ?>
                                        Category: <?php echo htmlspecialchars($document['category_name']); ?><br>
                                    <?php endif; ?>
                                    Uploaded: <?php echo date('M j, Y', strtotime($document['created_at'])); ?>
                                </p>
                            <button class="btn" style="background-color:#004F80;color:white;">
                                <a href="multi_share.php?id=<?php echo $document['id']; ?>" class="text-white text-decoration-none">Select Multiple</a>
                            </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Share with User</h6>
                        <form method="POST" action="share.php?id=<?php echo $document['id']; ?>" class="shadow-sm p-4 bg-white rounded">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                            <?php
                            // ✅ Fetch all user emails except the current one
                            $email_query = $db->prepare("SELECT id, full_name, email FROM users WHERE id != ?");
                            $email_query->execute([$_SESSION['user_id']]);
                            $available_users = $email_query->fetchAll(PDO::FETCH_ASSOC);
                            ?>

                            <div class="mb-3">
                                <label for="share_with" class="form-label">Select Email</label>
                                <select class="form-select" id="share_with" name="share_with" required>
                                    <option value="" selected disabled>-- Choose a user to share with --</option>
                                    <?php foreach ($available_users as $u): ?>
                                        <option value="<?php echo htmlspecialchars($u['email']); ?>">
                                            <?php echo htmlspecialchars($u['full_name'] . " (" . $u['email'] . ")"); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                                    
                            <div class="mb-3">
                                <label for="permission" class="form-label">Permission Level</label>
                                <select class="form-select" id="permission" name="permission">
                                    <option value="view">View Only</option>
                                    <option value="download">View & Download</option>
                                </select>
                            </div>
                                    
                            <button type="submit" name="share_document" class="btn" style="background-color:#004F80;color:white;">
                                <i class="fas fa-share me-2"></i>Share Document
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4 shadow rounded-4 border-0">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Currently Shared With</h5>
            </div>
            <div class="card-body">
                <?php if (empty($current_shares)): ?>
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-user-friends fa-3x mb-3"></i>
                        <p>This document hasn't been shared with anyone yet.</p>
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
                                        <strong><?php echo htmlspecialchars($share['full_name']); ?></strong><br>
                                        <small class="text-muted">@<?php echo htmlspecialchars($share['username']); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $share['permission'] === 'download' ? 'success' : 'info'; ?>">
                                            <?php echo $share['permission'] === 'download' ? 'View & Download' : 'View Only'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($share['created_at'])); ?></td>
                                    <td>
                                        <form method="POST" class="d-inline" onsubmit="return confirm('Remove sharing with this user?');">
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
        <div class="card shadow rounded-4 border-0">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cog me-2 text-primary"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="preview.php?id=<?php echo $document['id']; ?>" class="btn" style="background-color:#004F80;color:white;">
                        <i class="fas fa-eye me-2"></i>Preview Document
                    </a>
                    <a href="view.php?id=<?php echo $document['id']; ?>" class="btn btn-warning">
                        <i class="fas fa-info-circle me-2"></i>View Details
                    </a>
                    <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
