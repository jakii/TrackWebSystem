<?php
function getFolderPath($db, $folderId) {
    $path = [];

    while ($folderId) {
        $stmt = $db->prepare("SELECT id, name, parent_id FROM folders WHERE id = ?");
        $stmt->execute([$folderId]);
        $folder = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($folder) {
            $path[] = $folder;
            $folderId = $folder['parent_id'];
        } else {
            break;
        }
    }
    return array_reverse($path);
}
?>