<?php
if (php_sapi_name() !== 'cli' && (!isset($_GET['token']) || $_GET['token'] !== 'CLE_CRON_SECRETE')) {
    http_response_code(403);
    exit('Access denied');
}

require_once(__DIR__ . '/../config.php');
$now = new DateTime();
$baseStorage = !empty($opensupport_storage_dir) ? $opensupport_storage_dir : (__DIR__ . '/../storage/attachments/');

$stmtFiles = $dbco->prepare("SELECT tickets_id FROM tickets WHERE tickets_status = 2 AND tickets_closing_date <= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$stmtFiles->execute();
$tickets = $stmtTickets->fetchAll(PDO::FETCH_ASSOC);

if (!empty($tickets)) {
    $ticketIds = array_column($tickets, 'tickets_id');
    // Delete attachements from closed tickets > 30 days
    foreach ($ticketIds as $ticketId) {
        $dir = rtrim($baseStorage, '/') . '/' . (int)$ticketId;
        if (is_dir($dir)) {
            array_map('unlink', glob("$dir/*.*"));
            @rmdir($dir);
        }
    }

    // GDPR anonymization of personal data for tickets closed for > 30 days
    $stmtUpdateTicket = $dbco->prepare("UPDATE tickets SET tickets_first_name = 'Anonymous', tickets_last_name = 'User', tickets_email = CONCAT('anonymized_', tickets_id, '@opensupport.local'), tickets_description = '[Data deleted in accordance with the GDPR]', tickets_admin_notes = NULL, tickets_additionnal_fields = :clean_fields, tickets_deleted_at = NOW() WHERE tickets_id = :ticket_id");
    foreach ($tickets as $ticket) {
        $cleanFields = [];
        $rawFields = json_decode($ticket['tickets_additionnal_fields'] ?? '[]', true);
        if (is_array($rawFields)) {
            foreach ($rawFields as $field) {
                $question = $field['question'] ?? '';
                $answer = $field['answer'] ?? '';
                if (filter_var($answer, FILTER_VALIDATE_EMAIL) || preg_match('/^[0-9+ ]{8,15}$/', $answer)) {
                    $answer = '[Deleted]';
                }
                $cleanFields[] = [
                    'question' => $question,
                    'answer'   => $answer
                ];
            }
        }
        $stmtUpdateTicket->execute([
            'clean_fields' => json_encode($cleanFields, JSON_UNESCAPED_UNICODE),
            'ticket_id' => $ticket['tickets_id']
        ]);
    }

    // Anonymization of messages
    $inClause = implode(',', array_map('intval', $ticketIds));
    $dbco->exec("UPDATE messages SET messages_content = '[Deleted message]', messages_attachements = '[]', messages_deleted_at = NOW() WHERE messages_ticket_id IN ($inClause)");
}
?>