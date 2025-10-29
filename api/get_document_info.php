<?php
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;
if (!$id) {
  echo json_encode(['error' => 'Missing document ID']);
  exit;
}

try {
  $stmt = $db->prepare("
    SELECT d.id, d.title, u.email AS uploader_email
    FROM documents d
    JOIN users u ON d.uploaded_by = u.id
    WHERE d.id = ?
  ");
  $stmt->execute([$id]);
  $doc = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($doc) {
    echo json_encode($doc);
  } else {
    echo json_encode(['error' => 'Document not found']);
  }
} catch (PDOException $e) {
  echo json_encode(['error' => $e->getMessage()]);
}
?>