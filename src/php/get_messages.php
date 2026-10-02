<?php require("../../config.php");
header('Content-Type: application/json');

$ticket_id = isset($_GET['ticket_id']) ? (int)$_GET['ticket_id'] : 0;
$last_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($ticket_id === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'No ticket ID.']);
    exit;
}

try {
    $isAuthorized = false;

    if (isset($_SESSION['user_id'])) {
        $isAuthorized = true;
    } elseif (!empty($token)) {
        $stmtToken = $dbco->prepare("SELECT tickets_id FROM tickets WHERE tickets_id = :ticket_id AND tickets_token = :token LIMIT 1");
        $stmtToken->execute(['ticket_id' => $ticket_id, 'token' => $token]);
        if ($stmtToken->fetchColumn()) {
            $isAuthorized = true;
        }
    }

    if (!$isAuthorized) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized access.']);
        exit;
    }
    $stmt = $dbco->prepare("SELECT messages_id, messages_sender_type, messages_sender_id, DATE(messages_creation_date) as raw_date, DATE_FORMAT(messages_creation_date, '%H:%i') as time, messages_content, messages_attachements_request, messages_attachements FROM messages WHERE messages_ticket_id = :ticket_id AND messages_id > :last_id ORDER BY messages_id ASC;");  
    $stmt->execute(['ticket_id' => $ticket_id, 'last_id'   => $last_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $messages = [];

    foreach ($results as $row) {
        $isSupport = ($row['messages_sender_type'] == 0);
        $attachments = [];
        if (!empty($row['messages_attachements'])) {
            $attachments = json_decode($row['messages_attachements'], true);
        }
        $messages[] = [
            'id' => $row['messages_id'],
            'sender_type' => $isSupport ? 'admin' : 'user',
            'sender_name' => $isSupport ? 'Support' : 'Vous',
            'raw_date' => $row['raw_date'],
            'time' => $row['time'],
            'content' => $row['messages_content'],
            'is_file_request' => $row['messages_attachements_request'],
            'attachments' => $attachments
        ];
    }
    echo json_encode($messages);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error.'.$e]);
}
?>