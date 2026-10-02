<?php
require("../../config.php");

$ticket_id = (int)($_GET['ticket_id'] ?? 0);
$file_name = basename($_GET['file'] ?? '');
$token = trim($_GET['token'] ?? '');
$action = $_GET['action'] ?? 'view'; // 'view' ou 'download'

if ($ticket_id <= 0 || empty($file_name)) {
    http_response_code(400);
    exit("Invalid request.");
}

$isAuthorized = false;

if (isset($_SESSION['user_id'])) {
    $stmtAuth = $dbco->prepare("SELECT tm.teams_members_id FROM tickets t INNER JOIN teams_members tm ON tm.teams_members_team_id = t.tickets_teams WHERE t.tickets_id = :ticket_id AND tm.teams_members_user_id = :user_id AND (tm.teams_members_deleted = 0 OR tm.teams_members_deleted IS NULL) AND tm.teams_members_join_date IS NOT NULL LIMIT 1 ");
    $stmtAuth->execute(['ticket_id' => $ticket_id, 'user_id' => $_SESSION['user_id']]);
    if ($stmtAuth->fetch()) {
        $isAuthorized = true;
    }
}

if (!$isAuthorized && !empty($token)) {
    $stmtClient = $dbco->prepare("SELECT tickets_id FROM tickets WHERE tickets_id = :ticket_id AND tickets_token = :token LIMIT 1");
    $stmtClient->execute(['ticket_id' => $ticket_id, 'token' => $token]);
    if ($stmtClient->fetch()) {
        $isAuthorized = true;
    }
}

if (!$isAuthorized) {
    http_response_code(403);
    exit("Unauthorized access.");
}

$stmtMsg = $dbco->prepare("SELECT messages_attachements FROM messages WHERE messages_ticket_id = :tid");
$stmtMsg->execute(['tid' => $ticket_id]);
$allAttachments = $stmtMsg->fetchAll(PDO::FETCH_COLUMN);
$fileExistsInTicket = false;
foreach ($allAttachments as $json) {
    $list = json_decode($json ?? '[]', true);
    if (is_array($list) && in_array($file_name, $list, true)) {
        $fileExistsInTicket = true;
        break;
    }
}

if (!$fileExistsInTicket) {
    http_response_code(404);
    exit("File not associated with this ticket.");
}

$baseStorage = !empty($opensupport_storage_dir) ? $opensupport_storage_dir : (__DIR__ . '/../../storage/attachments/');
$filePath = rtrim($baseStorage, '/') . '/' . $ticket_id . '/' . $file_name;
if (!file_exists($filePath)) {
    http_response_code(404);
    exit("No file.");
}

// MIME
$extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
$mimeTypes = [
    'webp' => 'image/webp',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'pdf'  => 'application/pdf',
    'mp4'  => 'video/mp4'
];

if (isset($mimeTypes[$extension])) {
    $mime = $mimeTypes[$extension];
} elseif (function_exists('mime_content_type')) {
    $mime = mime_content_type($filePath);
} else {
    $mime = 'application/octet-stream';
}
if (ob_get_level()) {
    ob_end_clean();
}

$displayTitle = 'Attachement #' . $ticket_id . ' - ' . $file_name;
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($filePath));
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, max-age=86400');

if ($action === 'download') {
    header('Content-Disposition: attachment; filename="' . addslashes($displayTitle) . '"');
} else {
    header('Content-Disposition: inline; filename="' . addslashes($displayTitle) . '"');
}

readfile($filePath);
exit;