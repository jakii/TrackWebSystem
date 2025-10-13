<?php
include '../api/api_view.php';
include '../includes/header.php';
?>
<div class="row">
    <div class="col-md-8">
        <div class="card shadow rounded-4 border-0 fade-in delay-1">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="<?php echo getFileIcon(pathinfo($document['filename'], PATHINFO_EXTENSION)); ?> me-2"></i>
                    <?php echo htmlspecialchars($document['title']); ?>
                </h4>
                <div>
                    <a href="download.php?id=<?php echo $document['id']; ?>" class="btn" style ="background-color: #004F80; color: white;">
                        <i class="fas fa-download me-2"></i>Download
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>Document Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Original Filename:</strong></td>
                                <td><?php echo htmlspecialchars($document['original_filename']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>File Size:</strong></td>
                                <td><?php echo formatFileSize($document['file_size']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>File Type:</strong></td>
                                <td><?php echo htmlspecialchars($document['file_type']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Uploaded By:</strong></td>
                                <td><?php echo htmlspecialchars($document['uploader_name']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Upload Date:</strong></td>
                                <td><?php echo date('F j, Y g:i A', strtotime($document['created_at'])); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Downloads:</strong></td>
                                <td><?php echo number_format($document['download_count']); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Classification</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Category:</strong></td>
                                <td>
                                    <?php if ($document['category_name']): ?>
                                    <span class="badge" style="background-color: <?php echo $document['category_color']; ?>">
                                        <?php echo htmlspecialchars($document['category_name']); ?>
                                    </span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary">Uncategorized</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Visibility:</strong></td>
                                <td>
                                    <?php if ($document['is_public']): ?>
                                    <span class="badge bg-success">Public</span>
                                    <?php else: ?>
                                    <span class="badge bg-warning">Private</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tags:</strong></td>
                                <td>
                                    <?php if ($document['tags']): ?>
                                    <?php foreach (explode(',', $document['tags']) as $tag): ?>
                                    <span class="badge bg-info me-1"><?php echo htmlspecialchars(trim($tag)); ?></span>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <span class="text-muted">No tags</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <?php if ($document['description']): ?>
                <div class="mt-3">
                    <h6>Description</h6>
                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($document['description'])); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow rounded-4 border-0 fade-in delay-2">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cog me-2" style="color: #004F80;"></i>Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="download.php?id=<?php echo $document['id']; ?>" class="btn" style="background-color: #004F80; color: white;">
                        <i class="fas fa-download me-2"></i>Download
                    </a>
                    <?php if ($document['uploaded_by'] == $_SESSION['user_id']): ?>
                      <a href="#" class="btn" style="background-color: #FFD166; color: #2F4858;" data-bs-toggle="modal" data-bs-target="#editDetailsModal">
                        <i class="fas fa-edit me-2"></i>Edit Details
                      </a>
                    <?php endif; ?>
<a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left me-2"></i> Back
</a>

                </div>
            </div>
        </div>
    </div>
    <!-- Edit Details Modal -->
    <div class="modal fade" id="editDetailsModal" tabindex="-1" aria-labelledby="editDetailsLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <form action="edit_document.php" method="POST" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editDetailsLabel">Edit Document Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <input type="hidden" name="id" value="<?= htmlspecialchars($document['id']) ?>">
              <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="title" 
                  name="title" 
                  value="<?= htmlspecialchars($document['title']) ?>" 
                  required
                >
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea 
                  class="form-control" 
                  id="description" 
                  name="description" 
                  rows="3"
                ><?= htmlspecialchars($document['description']) ?></textarea>
              </div>
              <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category_id">
                  <option value="">Uncategorized</option>
                  <?php
                    // Fetch categories for select
                    $categories = $db->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
                    foreach ($categories as $cat):
                  ?>
                  <option value="<?= $cat['id'] ?>" <?= ($document['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
                    
              <div class="mb-3">
                <label for="tags" class="form-label">Tags (comma separated)</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="tags" 
                  name="tags" 
                  value="<?= htmlspecialchars($document['tags']) ?>"
                >
              </div>
                    
              <div class="mb-3 form-check">
                <input 
                  type="checkbox" 
                  class="form-check-input" 
                  id="is_public" 
                  name="is_public" 
                  value="1" 
                  <?= $document['is_public'] ? 'checked' : '' ?>
                >
                <label class="form-check-label" for="is_public">Public</label>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn" style="background-color: #004f58; color: white;">Save Changes</button>
          </div>
        </form>
      </div>
    </div>                 
</div>