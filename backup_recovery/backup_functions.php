<?php
function getBackupList() {
    $backupBase = realpath(__DIR__ . '/../backups/uploads');
    if (!$backupBase || !is_dir($backupBase)) return [];

    $backups = [];
    foreach (scandir($backupBase) as $folder) {
        if ($folder === '.' || $folder === '..') continue;

        $path = $backupBase . '/' . $folder;
        if (is_dir($path)) {
            $zipFile = $path . '.zip';
            $infoFile = $path . '/backup_info.json';
            $info = file_exists($infoFile) ? json_decode(file_get_contents($infoFile), true) : [];

            $backups[] = [
                'folder' => $folder,
                'zip' => file_exists($zipFile),
                'created_at' => $info['created_at'] ?? date('Y-m-d H:i:s', filemtime($path)),
                'backup_type' => $info['backup_type'] ?? 'unknown',
                'include_files' => $info['include_files'] ?? false,
                'user_id' => $info['user_id'] ?? null
            ];
        }
    }

    return array_reverse($backups);
}

function backupDatabase($db, $backupDir) {
    $file = $backupDir . 'database_backup.sql';
    $tables = [];
    $result = $db->query('SHOW TABLES');
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }

    $sqlScript = '';
    foreach ($tables as $table) {
        $result = $db->query("SHOW CREATE TABLE `$table`");
        $row = $result->fetch(PDO::FETCH_NUM);
        $sqlScript .= "\n\n" . $row[1] . ";\n\n";

        $result = $db->query("SELECT * FROM `$table`");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $columns = array_map(fn($v) => $v === null ? 'NULL' : $db->quote($v), array_values($row));
            $sqlScript .= "INSERT INTO `$table` VALUES (" . implode(',', $columns) . ");\n";
        }
    }

    file_put_contents($file, $sqlScript);
}

function backupFiles($backupDir) {
    $sourceDir = realpath(__DIR__ . '/../documents/uploads');
    $targetDir = $backupDir . 'files/';

    if (!is_dir($sourceDir)) return;
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        $targetPath = $targetDir . str_replace($sourceDir . '/', '', $item->getPathname());
        if ($item->isDir()) {
            if (!is_dir($targetPath)) mkdir($targetPath, 0755, true);
        } else {
            copy($item->getPathname(), $targetPath);
        }
    }
}

function createBackupInfo($backupDir, $type, $includeFiles, $userId) {
    $info = [
        'created_at' => date('Y-m-d H:i:s'),
        'backup_type' => $type,
        'include_files' => $includeFiles,
        'user_id' => $userId
    ];
    file_put_contents($backupDir . '/backup_info.json', json_encode($info, JSON_PRETTY_PRINT));
}

function zipBackup($backupDir) {
    $zipPath = rtrim($backupDir, '/') . '.zip';
    if (!is_dir($backupDir) || count(glob($backupDir . '*')) === 0) {
        return false;
    }

    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        throw new Exception("Failed to create ZIP file at $zipPath");
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($backupDir, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($files as $file) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($backupDir) + 1);
        $zip->addFile($filePath, $relativePath);
    }

    $zip->close();
    return true;
}

function restoreBackup($db, $backupFolder) {
    $backupPath = realpath(__DIR__ . '/../backups/uploads/' . $backupFolder);
    if (!$backupPath || !is_dir($backupPath)) {
        throw new Exception("Backup folder not found: {$backupFolder}");
    }

    $sqlFile = $backupPath . '/database_backup.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        $db->exec($sql);
    }

    $filesDir = $backupPath . '/files';
    $targetDir = realpath(__DIR__ . '/../documents/uploads');

    if (is_dir($filesDir) && $targetDir) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($filesDir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $targetPath = $targetDir . '/' . str_replace($filesDir . '/', '', $item->getPathname());
            if ($item->isDir()) {
                if (!is_dir($targetPath)) mkdir($targetPath, 0755, true);
            } else {
                copy($item->getPathname(), $targetPath);
            }
        }
    }
}

function deleteBackup($backupFolder) {
    $backupPath = realpath(__DIR__ . '/../backups/uploads/' . $backupFolder);
    $zipFile = $backupPath . '.zip';

    if ($backupPath && is_dir($backupPath)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($backupPath, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $file) {
            $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
        }
        rmdir($backupPath);
    }

    if (file_exists($zipFile)) unlink($zipFile);
}
?>