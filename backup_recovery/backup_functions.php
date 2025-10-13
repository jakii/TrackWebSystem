<?php
require_once '../config/database.php';

/**
 * === DATABASE BACKUP ===
 */
function backupDatabase($db, $backup_dir) {
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $sql_dump = "";

    foreach ($tables as $table) {
        $sql_dump .= "DROP TABLE IF EXISTS `{$table}`;\n";
        $create_table = $db->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_ASSOC);
        $sql_dump .= $create_table['Create Table'] . ";\n\n";

        $rows = $db->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) > 0) {
            $sql_dump .= "INSERT INTO `{$table}` VALUES ";
            $insert_values = [];

            foreach ($rows as $row) {
                $values = array_map(function($value) use ($db) {
                    if ($value === null) return 'NULL';
                    return $db->quote($value);
                }, $row);
                $insert_values[] = "(" . implode(", ", $values) . ")";
            }

            $sql_dump .= implode(",\n", $insert_values) . ";\n\n";
        }
    }

    file_put_contents($backup_dir . 'database_backup.sql', $sql_dump);
}

/**
 * === FILE BACKUP ===
 */
function backupFiles($backup_dir) {
    $uploads_dir = "../documents/uploads/";
    $backup_files_dir = $backup_dir . "uploads/";

    if (!is_dir($backup_files_dir)) {
        mkdir($backup_files_dir, 0755, true);
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($uploads_dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($files as $file) {
        if ($file->isFile()) {
            $relative_path = substr($file->getPathname(), strlen($uploads_dir));
            $dest_path = $backup_files_dir . $relative_path;

            $dest_dir = dirname($dest_path);
            if (!is_dir($dest_dir)) mkdir($dest_dir, 0755, true);

            copy($file->getPathname(), $dest_path);
        }
    }
}

/**
 * === BACKUP INFO ===
 */
function createBackupInfo($backup_dir, $backup_type, $include_files, $user_id, $schedule_type = null) {
    $info = [
        'created_at' => date('Y-m-d H:i:s'),
        'backup_type' => $backup_type,
        'include_files' => $include_files,
        'created_by' => $user_id,
        'schedule_type' => $schedule_type, // daily / weekly / monthly / manual
        'app_version' => '1.0',
        'database' => DB_NAME
    ];
    file_put_contents($backup_dir . 'backup_info.json', json_encode($info, JSON_PRETTY_PRINT));
}

/**
 * === RESTORE ===
 */
function restoreBackup($db, $backup_folder) {
    $backup_path = "../backups/{$backup_folder}/";
    if (!is_dir($backup_path)) throw new Exception("Backup directory not found");
    if (!file_exists($backup_path . 'backup_info.json')) throw new Exception("Invalid backup format");

    $backup_info = json_decode(file_get_contents($backup_path . 'backup_info.json'), true);

    // Restore DB
    if (file_exists($backup_path . 'database_backup.sql')) {
        $sql = file_get_contents($backup_path . 'database_backup.sql');
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($statements as $statement) {
            if (!empty($statement)) $db->exec($statement);
        }
    }

    // Restore files
    $backup_files_dir = $backup_path . "uploads/";
    if (is_dir($backup_files_dir)) {
        $uploads_dir = "../documents/uploads/";

        // Clear old uploads
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($uploads_dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            if ($file->isDir()) rmdir($file->getPathname());
            else unlink($file->getPathname());
        }

        // Copy backup files
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($backup_files_dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            if ($file->isFile()) {
                $relative_path = substr($file->getPathname(), strlen($backup_files_dir));
                $dest_path = $uploads_dir . $relative_path;
                $dest_dir = dirname($dest_path);
                if (!is_dir($dest_dir)) mkdir($dest_dir, 0755, true);
                copy($file->getPathname(), $dest_path);
            }
        }
    }
}

/**
 * === DELETE BACKUP ===
 */
function deleteBackup($backup_folder) {
    $backup_path = "../backups/{$backup_folder}/";
    if (!is_dir($backup_path)) throw new Exception("Backup directory not found");

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($backup_path, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $file) {
        if ($file->isDir()) rmdir($file->getPathname());
        else unlink($file->getPathname());
    }

    rmdir($backup_path);
}

/**
 * === BACKUP LIST ===
 */
function getBackupList() {
    $backups = [];
    $backups_dir = "../backups/";

    if (!is_dir($backups_dir)) mkdir($backups_dir, 0755, true);
    $folders = glob($backups_dir . "backup_*", GLOB_ONLYDIR);

    foreach ($folders as $folder) {
        $folder_name = basename($folder);
        $info_file = $folder . '/backup_info.json';
        if (file_exists($info_file)) {
            $info = json_decode(file_get_contents($info_file), true);
            $backups[] = [
                'folder' => $folder_name,
                'created_at' => $info['created_at'] ?? '',
                'type' => $info['backup_type'] ?? '',
                'include_files' => $info['include_files'] ?? false,
                'created_by' => $info['created_by'] ?? 'system',
                'schedule_type' => $info['schedule_type'] ?? 'manual',
                'size' => formatFolderSize($folder)
            ];
        }
    }

    usort($backups, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));
    return $backups;
}

/**
 * === FOLDER SIZE ===
 */
function formatFolderSize($path) {
    $size = 0;
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    foreach ($files as $file) $size += $file->getSize();
    $units = ['B', 'KB', 'MB', 'GB'];
    $pow = $size > 0 ? floor(log($size, 1024)) : 0;
    $pow = min($pow, count($units) - 1);
    $size /= (1 << (10 * $pow));
    return round($size, 2) . ' ' . $units[$pow];
}

/**
 * === ZIP BACKUP ===
 */
function zipBackup($folderPath) {
    $zipFile = rtrim($folderPath, '/') . '.zip';
    $rootPath = realpath($folderPath);
    $zip = new ZipArchive();

    if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
    }

    return basename($zipFile);
}

/**
 * === AUTOMATIC BACKUP (for cron or scheduled) ===
 */
function autoBackup($db, $type = 'daily', $user_id = 0) {
    $backup_dir = "../backups/backup_" . date('Y-m-d_H-i-s') . "_{$type}/";
    if (!is_dir($backup_dir)) mkdir($backup_dir, 0755, true);

    backupDatabase($db, $backup_dir);
    backupFiles($backup_dir);
    createBackupInfo($backup_dir, 'both', true, $user_id, $type);
    $zipName = zipBackup($backup_dir);

    deleteBackup(basename($backup_dir));

    return "../backups/" . $zipName;
}
?>
