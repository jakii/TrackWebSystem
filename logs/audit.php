<?php
require_once '../config/config.php';
require_once '../includes/auth_check.php';
include '../includes/header.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$recent_logs = [];
$userFilter = $_GET['user'] ?? '';
$dateFilter = $_GET['date'] ?? '';

$sql = "SELECT a.id, u.username, a.message, a.timestamp 
        FROM activity_logs a 
        LEFT JOIN users u ON a.user_id = u.id";

$conditions = [];
$params = [];
$types = '';

if (!empty($userFilter)) {
    $conditions[] = "u.username LIKE ?";
    $params[] = '%' . $userFilter . '%';
    $types .= 's';
}

if (!empty($dateFilter)) {
    $conditions[] = "DATE(a.timestamp) = ?";
    $params[] = $dateFilter;
    $types .= 's';
}

if ($conditions) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY a.timestamp DESC LIMIT 100";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$recent_logs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="container-fluid px-4">
    <h4 class="mt-4">Activity Logs</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Audit Trail</li>
    </ol>
<hr>
    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="user" class="form-control" placeholder="Filter by username..." value="<?= htmlspecialchars($userFilter) ?>">
                </div>
                <div class="col-md-4">
                    <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($dateFilter) ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn w-100" style="background-color: #004F80; color: white;">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-clipboard-list me-1"></i> Recent Activities
        </div>
        <div class="card-body">
            <?php if (empty($recent_logs)): ?>
                <p class="text-muted">No activity logs available.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Message</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_logs as $log): ?>
                                <tr>
                                    <td><?= htmlspecialchars($log['id']) ?></td>
                                    <td><?= htmlspecialchars($log['username'] ?? 'Unknown') ?></td>
                                    <td><?= htmlspecialchars($log['message']) ?></td>
                                    <td><?= date('M d, Y g:i A', strtotime($log['timestamp'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
