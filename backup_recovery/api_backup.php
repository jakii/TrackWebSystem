<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/activity_logger.php';
require_once 'backup_functions.php';

requireAdmin();

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // ✅ Fetch backup list
        $backups = getBackupList();
        echo json_encode(['success' => true, 'data' => $backups]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        switch ($action) {
            case 'create_backup':
                handleCreateBackup($db);
                break;
            case 'restore_backup':
                handleRestoreBackup($db);
                break;
            case 'delete_backup':
                handleDeleteBackup($db);
                break;
            default:
                throw new Exception('Invalid action');
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}

// --- Helper handlers ---
function handleCreateBackup($db) {
    $backup_type = $_POST['backup_type'] ?? 'both';
    $include_files = isset($_POST['include_files']);

    $timestamp = date('Y-m-d_H-i-s');
    $backup_dir = "../backups/uploads/backup_{$timestamp}/";

    if (!is_dir($backup_dir)) {
        mkdir($backup_dir, 0755, true);
    }

    // Perform backup
    if ($backup_type === 'database' || $backup_type === 'both') {
        backupDatabase($db, $backup_dir);
    }

    if (($backup_type === 'files' || $backup_type === 'both') && $include_files) {
        backupFiles($backup_dir);
    }

    createBackupInfo($backup_dir, $backup_type, $include_files, $_SESSION['user_id']);

    // ✅ Zip the backup folder
    zipBackup($backup_dir);

    logActivity($db, $_SESSION['user_id'], "Backup created: {$backup_dir}");
    echo json_encode(['success' => true, 'message' => "Backup created and zipped successfully"]);
}

function handleRestoreBackup($db) {
    $backup_folder = $_POST['backup_folder'] ?? '';
    if (empty($backup_folder)) {
        throw new Exception("Please select a backup to restore");
    }

    restoreBackup($db, $backup_folder);
    logActivity($db, $_SESSION['user_id'], "Backup restored: {$backup_folder}");

    echo json_encode(['success' => true, 'message' => "Backup restored successfully"]);
}

function handleDeleteBackup($db) {
    $backup_folder = $_POST['backup_folder'] ?? '';
    if (empty($backup_folder)) {
        throw new Exception("Please select a backup to delete");
    }

    deleteBackup($backup_folder);
    logActivity($db, $_SESSION['user_id'], "Backup deleted: {$backup_folder}");

    echo json_encode(['success' => true, 'message' => "Backup deleted successfully"]);
}
?>
