<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id < 1) {
    echo json_encode(['error' => 'Invalid ID']);
    exit;
}

$details = getListingDetails($id);
if (!$details) {
    echo json_encode(['error' => 'Listing not found']);
    exit;
}

echo json_encode($details, JSON_UNESCAPED_UNICODE);
