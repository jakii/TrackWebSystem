<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
requireAdmin();

// Fetch users (excluding superadmin)
$user_query = $db->prepare("SELECT id, username, email, full_name, role, status, is_logged_in, created_at FROM users WHERE role != 'superadmin' ORDER BY created_at DESC");
$user_query->execute();
$users = $user_query->fetchAll();

include '../includes/header.php';
?>

<div class="container mt-4">
    
    <!-- ===== Header + Controls ===== -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-users me-2"></i>User Management</h2>

        <div class="d-flex gap-2">
            <button type="button" class="btn" style="background-color: #004F80; color: white;" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-user-plus me-1"></i> Add User
            </button>
        </div>
    </div>

    <hr>

    <!-- ===== Filters ===== -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="input-group" style="width: 300px;">
            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
            <input type="text" id="userSearch" class="form-control" placeholder="Search user...">
        </div>

        <select id="roleFilter" class="form-select w-auto">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
    </div>

    <!-- ===== User Table ===== -->
    <div class="card shadow rounded-4 border-0">
        <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
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
                    <tbody id="userTable">
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['full_name']) ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2 <?= $user['role'] === 'admin' ? 'bg-danger' : 'bg-info text-dark' ?>">
                                    <?= ucfirst(htmlspecialchars($user['role'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user['status'] !== 'active'): ?>
                                    <span class="badge bg-warning text-dark"><?= ucfirst($user['status']) ?></span>
                                <?php else: ?>
                                    <span class="badge <?= !empty($user['is_logged_in']) && $user['is_logged_in'] ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= !empty($user['is_logged_in']) && $user['is_logged_in'] ? 'Online' : 'Offline' ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" style="border:none;">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if ($user['status'] === 'pending'): ?>
                                        <li><a class="dropdown-item text-success" href="approve.php?id=<?= $user['id'] ?>"><i class="fas fa-check me-2"></i>Approve</a></li>
                                        <?php endif; ?>
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $user['id'] ?>"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                        <?php if ($user['role'] !== 'admin'): ?>
                                        <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?= $user['id'] ?>"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                        <?php endif; ?>
                                        <?php if ($user['status'] === 'active'): ?>
                                        <li><a class="dropdown-item text-warning" href="change_status.php?id=<?= $user['id'] ?>&action=disable" onclick="return confirm('Disable this account?');"><i class="fas fa-user-slash me-2"></i>Disable</a></li>
                                        <?php elseif ($user['status'] === 'disabled'): ?>
                                        <li><a class="dropdown-item text-success" href="change_status.php?id=<?= $user['id'] ?>&action=enable" onclick="return confirm('Enable this account?');"><i class="fas fa-user-check me-2"></i>Enable</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
    </div>

</div>

<!-- ===== Modals ===== -->
<?php foreach ($users as $user): ?>
<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal<?= $user['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="edit.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Role</label>
          <select name="role" class="form-select">
            <option value="user" <?= $user['role']==='user'?'selected':'' ?>>User</option>
            <option value="admin" <?= $user['role']==='admin'?'selected':'' ?>>Admin</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="pending" <?= $user['status']==='pending'?'selected':'' ?>>Pending</option>
            <option value="active" <?= $user['status']==='active'?'selected':'' ?>>Active</option>
            <option value="disabled" <?= $user['status']==='disabled'?'selected':'' ?>>Disabled</option>
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
<div class="modal fade" id="deleteUserModal<?= $user['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="delete.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete <strong><?= htmlspecialchars($user['full_name']) ?></strong>?</p>
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
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="add_user.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add New User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="full_name" class="form-control" placeholder="Enter full name" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" placeholder="Enter username" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" placeholder="Enter email" required>
        </div>
        <div class="mb-3 position-relative">
          <label class="form-label">Password</label>
          <input type="password" class="form-control pe-5" id="passwordAdd" name="password" placeholder="Enter password" required>
          <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted" style="cursor:pointer;" id="togglePasswordAdd">
            <i class="fas fa-eye"></i>
          </span>
        </div>
        <div class="mb-3">
          <label class="form-label">Role</label>
          <select name="role" class="form-select" required>
            <option value="user" selected>User</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select" required>
            <option value="pending" selected>Pending</option>
            <option value="active">Active</option>
            <option value="disabled">Disabled</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn" style="background-color: #004F80; color:white;">Add User</button>
      </div>
    </form>
  </div>
</div>

<!-- ===== JS ===== -->
<script>
document.getElementById('togglePasswordAdd').addEventListener('click', function () {
    const passwordInput = document.getElementById('passwordAdd');
    const icon = this.querySelector('i');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
});

// Search & Filter
document.getElementById('userSearch').addEventListener('keyup', filterUsers);
document.getElementById('roleFilter').addEventListener('change', filterUsers);

function filterUsers() {
    const search = document.getElementById('userSearch').value.toLowerCase();
    const role = document.getElementById('roleFilter').value.toLowerCase();
    document.querySelectorAll('#userTable tr').forEach(row => {
        const matchesSearch = row.innerText.toLowerCase().includes(search);
        const matchesRole = role ? row.cells[3].innerText.toLowerCase().includes(role) : true;
        row.style.display = matchesSearch && matchesRole ? '' : 'none';
    });
}
</script>

<style>
.container {
    background-color: #fff;
    padding: 2rem;
}

.table-hover tbody tr:hover {
    background-color: #f1f7fd;
    transition: 0.2s;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: 0.2s;
}
</style>
