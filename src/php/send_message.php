<?php 
require("../../config.php");
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$ticket_id = isset($data['ticket_id']) ? (int)$data['ticket_id'] : 0;
$token = isset($data['token']) ? trim($data['token']) : '';
$content = isset($data['content']) ? trim($data['content']) : '';

if ($ticket_id === 0 || empty($content)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data or empty message.']);
    exit;
}

try {
    $isAuthorized = false;
    $senderType = null;
    $senderId = null;

    if (isset($_SESSION['user_id']) || (isset($_SESSION['connected']) && $_SESSION['connected'] === "true")) {
        $isAuthorized = true;
        $senderType = 0;
        $senderId = $_SESSION['user_id'];
    } elseif (!empty($token)) {
        $stmtToken = $dbco->prepare("SELECT tickets_id FROM tickets WHERE tickets_id = :ticket_id AND tickets_token = :token LIMIT 1");
        $stmtToken->execute(['ticket_id' => $ticket_id, 'token' => $token]);
        if ($stmtToken->fetchColumn()) {
            $isAuthorized = true;
            $senderType = 1;
            $senderId = null;
        }
    }
    if (!$isAuthorized) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized access.']);
        exit;
    }
    $stmtInsert = $dbco->prepare("INSERT INTO messages (messages_ticket_id, messages_sender_type, messages_sender_id, messages_content) VALUES (:ticket_id, :sender_type, :sender_id, :content)");
    $stmtInsert->execute(['ticket_id' => $ticket_id, 'sender_type' => $senderType, 'sender_id' => $senderId, 'content' => $content]);

    if ($senderType === 1) {
        $updateVisitStmt = $dbco->prepare("UPDATE tickets SET tickets_client_last_visit_date = CURRENT_TIMESTAMP() WHERE tickets_id = :ticket_id");
        $updateVisitStmt->execute(['ticket_id' => $ticket_id]);
    } elseif ($senderType === 0) {
        $updateStatusStmt = $dbco->prepare("UPDATE tickets SET tickets_status = 1 WHERE tickets_id = :ticket_id AND tickets_status = 0");
        $updateStatusStmt->execute(['ticket_id' => $ticket_id]);
    }
    
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error.']);
}
?>