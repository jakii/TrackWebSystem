<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
requireAdmin();

$user_query = $db->prepare("SELECT id, username, email, full_name, role, status, is_logged_in, created_at FROM users WHERE role != 'superadmin' ORDER BY created_at DESC");
$user_query->execute();
$users = $user_query->fetchAll();

include '../includes/header.php';
?>
<div class="container mt-4 shadow rounded-4 border-0">
    <div class="d-flex justify-content-between align-items-center fade-in delay-1">
        <h2><i class="fas fa-users me-2"></i>User Management</h2>
        <button type="button" class="btn mb-3" style="background-color: #004F80; color: white;" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-user-plus me-1"></i> Add User
        </button>
    </div>
    <hr>
    <table class="table table-striped align-middle fade-in delay-2">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['full_name']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'secondary' ?>">
                        <?= ucfirst(htmlspecialchars($user['role'])) ?>
                    </span>
                </td>
                <td>
                    <?php if ($user['status'] !== 'active'): ?>
                        <span class="badge bg-warning text-dark">
                            <?= ucfirst($user['status']) ?>
                        </span>
                    <?php else: ?>
                        <?php if (!empty($user['is_logged_in']) && $user['is_logged_in']): ?>
                            <span class="badge bg-success">Online</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Offline</span>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle"
                            type="button" 
                            id="actionMenu<?= $user['id'] ?>"
                            data-bs-toggle="dropdown" 
                            aria-expanded="false" 
                            style="border: none; color: #2F4858">
                            <i class="fas fa-ellipsis-v" style="font-size: 1.2rem; color: #2F4858;"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="actionMenu<?= $user['id'] ?>">
                            <?php if ($user['status'] === 'pending'): ?>
                                <li>
                                    <a class="dropdown-item text-success" href="approve.php?id=<?= $user['id'] ?>">
                                        <i class="fas fa-check me-2"></i> Approve
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $user['id'] ?>">
                                    <i class="fas fa-edit me-2"></i> Edit
                                </a>
                            </li>
                            <?php if ($user['role'] !== 'admin'): ?>
                            <li>
                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?= $user['id'] ?>">
                                    <i class="fas fa-trash me-2"></i> Delete
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if ($user['status'] === 'active'): ?>
                            <li>
                                <a class="dropdown-item text-warning" href="change_status.php?id=<?= $user['id'] ?>&action=disable" onclick="return confirm('Are you sure you want to disable this account?');">
                                    <i class="fas fa-user-slash me-2"></i> Disable Account
                                </a>
                            </li>
                            <?php elseif ($user['status'] === 'disabled'): ?>
                                <li>
                                    <a class="dropdown-item text-success" href="change_status.php?id=<?= $user['id'] ?>&action=enable" onclick="return confirm('Enable this account?');">
                                        <i class="fas fa-user-check me-2"></i> Enable Account
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </td>            
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
        <?php foreach ($users as $user): ?>
            <!-- Edit User Modal -->
            <div class="modal fade" id="editUserModal<?= $user['id'] ?>" tabindex="-1" aria-labelledby="editUserLabel<?= $user['id'] ?>" aria-hidden="true">
              <div class="modal-dialog">
                <form action="edit.php" method="POST" class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editUserLabel<?= $user['id'] ?>">
                      <i class="fas fa-edit me-2"></i>Edit User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <div class="mb-3">
                      <label for="full_name_<?= $user['id'] ?>" class="form-label">Full Name</label>
                      <input type="text" class="form-control" id="full_name_<?= $user['id'] ?>" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label for="username_<?= $user['id'] ?>" class="form-label">Username</label>
                      <input type="text" class="form-control" id="username_<?= $user['id'] ?>" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label for="email_<?= $user['id'] ?>" class="form-label">Email</label>
                      <input type="email" class="form-control" id="email_<?= $user['id'] ?>" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label for="role_<?= $user['id'] ?>" class="form-label">Role</label>
                      <select class="form-select" id="role_<?= $user['id'] ?>" name="role">
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="status_<?= $user['id'] ?>" class="form-label">Status</label>
                      <select class="form-select" id="status_<?= $user['id'] ?>" name="status">
                        <option value="pending" <?= $user['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="disabled" <?= $user['status'] === 'disabled' ? 'selected' : '' ?>>Disabled</option>
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Save Changes</button>
                  </div>
                </form>
              </div>
            </div>
        
            <!-- Delete User Modal -->
            <div class="modal fade" id="deleteUserModal<?= $user['id'] ?>" tabindex="-1" aria-labelledby="deleteUserLabel<?= $user['id'] ?>" aria-hidden="true">
              <div class="modal-dialog">
                <form action="delete.php" method="POST" class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserLabel<?= $user['id'] ?>">
                      <i class="fas fa-trash me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p>Are you sure you want to delete user <strong><?= htmlspecialchars($user['full_name']) ?></strong>?</p>
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                  </div>
                </form>
              </div>
            </div>
        <?php endforeach; ?>
        <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form action="add.php" method="POST" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addUserLabel">
              <i class="fas fa-user-plus me-2"></i> Add New User
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="full_name_add" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="full_name_add" name="full_name" required>
            </div>
            <div class="mb-3">
              <label for="username_add" class="form-label">Username</label>
              <input type="text" class="form-control" id="username_add" name="username" required>
            </div>
            <div class="mb-3">
              <label for="email_add" class="form-label">Email</label>
              <input type="email" class="form-control" id="email_add" name="email" required>
            </div>
            <div class="mb-3">
              <label for="password_add" class="form-label">Password</label>
              <input type="password" class="form-control" id="password_add" name="password" required>
            </div>
            <div class="mb-3">
              <label for="role_add" class="form-label">Role</label>
              <select class="form-select" id="role_add" name="role">
                <option value="user" selected>User</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="status_add" class="form-label">Status</label>
              <select class="form-select" id="status_add" name="status">
                <option value="pending" selected>Pending</option>
                <option value="active">Active</option>
                <option value="disabled">Disabled</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add User</button>
          </div>
        </form>
      </div>
    </div>
</div>
