<?php
require("../../config.php");
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized access.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$ticket_id = (int)($input['ticket_id'] ?? 0);

if ($ticket_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No tickets ID']);
    exit;
}

$stmt = $dbco->prepare("INSERT INTO messages (messages_ticket_id, messages_sender_type, messages_sender_id, messages_content, messages_attachements_request, messages_attachements, messages_creation_date) VALUES (:ticket_id, 0, :user_id, '', 1, '[]', NOW())");
$stmt->execute(['ticket_id' => $ticket_id, 'user_id' => $_SESSION['user_id']]);

echo json_encode(['success' => true]);
?>