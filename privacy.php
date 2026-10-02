<?php require("config.php");
header_remove("X-Frame-Options");
header("Content-Security-Policy: frame-ancestors *");

// Language manager
$translations = [
    'fr' => [
        'title' => 'Gestion de vos données personnelles',
        'subtitle' => 'Consultez, exportez ou supprimez vos données associées à votre adresse e-mail.',
        'email_label' => 'Adresse email associée :',
        'export_section_title' => 'Exporter mes données',
        'export_desc' => 'Téléchargez une copie de vos tickets et échanges de support au format JSON.',
        'export_btn' => 'Télécharger mes données (JSON)',
        'delete_section_title' => 'Supprimer mes données personnelles',
        'delete_desc' => 'Cette action anonymise définitivement vos demandes (nom, prénom, e-mail, messages) et supprime l\'ensemble de vos pièces jointes stockées.',
        'delete_confirm_label' => 'Saisissez exactement "DELETE" pour confirmer la suppression définitive :',
        'delete_btn' => 'Supprimer définitivement mes données',
        'delete_success' => 'Vos données personnelles ont été entièrement supprimées et anonymisées.',
        'delete_error_match' => 'Veuillez saisir exactement "DELETE" pour confirmer.',
        'back_to_chat' => 'Retourner au suivi de mon ticket',
        'ticket_not_found' => 'Lien de consultation invalide ou expiré.'
    ],
    'en' => [
        'title' => 'Personal Data Management',
        'subtitle' => 'Access, export, or permanently delete personal records associated with your email.',
        'email_label' => 'Linked email address:',
        'export_section_title' => 'Export My Data',
        'export_desc' => 'Download a complete JSON export of your tickets and chat conversations.',
        'export_btn' => 'Download my data (JSON)',
        'delete_section_title' => 'Delete Personal Data',
        'delete_desc' => 'This permanently anonymizes your personal information (name, email, message history) and purges all stored attachments.',
        'delete_confirm_label' => 'Type "DELETE" exactly to confirm permanent deletion:',
        'delete_btn' => 'Permanently Delete My Data',
        'delete_success' => 'Your personal records have been successfully erased and anonymized.',
        'delete_error_match' => 'Please type "DELETE" exactly to confirm.',
        'back_to_chat' => 'Back to my ticket',
        'ticket_not_found' => 'Invalid or expired access link.'
    ],
    'es' => [
        'title' => 'Gestión de datos personales',
        'subtitle' => 'Consulte, exporte o elimine los datos asociados a su correo electrónico.',
        'email_label' => 'Correo electrónico asociado:',
        'export_section_title' => 'Exportar mis datos',
        'export_desc' => 'Descargue una copia de sus tickets y mensajes en formato JSON.',
        'export_btn' => 'Descargar mis datos (JSON)',
        'delete_section_title' => 'Eliminar mis datos personales',
        'delete_desc' => 'Esta acción anonimiza sus tickets (nombre, correo, mensajes) y elimina todos los archivos adjuntos.',
        'delete_confirm_label' => 'Escriba "DELETE" para confirmar la eliminación definitiva:',
        'delete_btn' => 'Eliminar mis datos definitivamente',
        'delete_success' => 'Sus datos han sido anonimizados y eliminados correctamente.',
        'delete_error_match' => 'Por favor, escriba "DELETE" para confirmar.',
        'back_to_chat' => 'Volver al ticket',
        'ticket_not_found' => 'Enlace no válido o caducado.'
    ]
];
$t = $translations[$lang];

$env_slug = isset($_GET['env']) ? strip_tags($_GET['env']) : '';
$ticket_token = isset($_GET['token']) ? strip_tags($_GET['token']) : '';
$stmt = $dbco->prepare("SELECT `teams_id`, `teams_name`, `teams_logo`, `teams_color` FROM `teams` WHERE `teams_form_url` = :env AND `teams_deleted` = 0 LIMIT 1;");
$stmt->execute(['env' => $env_slug]);
$team = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$team) {
    header('HTTP/1.0 404 Not Found');
    exit;
}

$stmtTicket = $dbco->prepare("SELECT `tickets_id`, `tickets_email`, `tickets_first_name`, `tickets_last_name` FROM `tickets` WHERE `tickets_token` = :token AND `tickets_teams` = :team_id LIMIT 1;");
$stmtTicket->execute(['token' => $ticket_token, 'team_id' => $team['teams_id']]);
$current_ticket = $stmtTicket->fetch(PDO::FETCH_ASSOC);

if (!$current_ticket) {
   header("Location: {$opensupport_link}/error/403");
    exit();
}

$client_email = $current_ticket['tickets_email'];
$form_feedback = "";
$is_deleted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['privacy_action'] ?? '';

    // JSON Data Export
    if ($action === 'export') {
        $stmtAll = $dbco->prepare("SELECT tickets_id, tickets_subject, tickets_description, tickets_additionnal_fields, tickets_status, tickets_creation_date, tickets_closing_date FROM tickets WHERE tickets_email = :email AND tickets_teams = :team_id AND tickets_deleted_at IS NULL ORDER BY tickets_creation_date DESC");
        $stmtAll->execute(['email' => $client_email, 'team_id' => $team['teams_id']]);
        $client_tickets = $stmtAll->fetchAll(PDO::FETCH_ASSOC);
        $export_data = [
            'requester' => ['email' => $client_email, 'first_name' => $current_ticket['tickets_first_name'], 'last_name' => $current_ticket['tickets_last_name'], 'team' => $team['teams_name']],
            'tickets' => []
        ];
        foreach ($client_tickets as $tRow) {
            $tId = (int)$tRow['tickets_id'];
            $stmtMsg = $dbco->prepare("SELECT messages_sender_type, messages_content, messages_creation_date FROM messages WHERE messages_ticket_id = :ticket_id AND messages_deleted_at IS NULL ORDER BY messages_creation_date ASC");
            $stmtMsg->execute(['ticket_id' => $tId]);
            $rawMessages = $stmtMsg->fetchAll(PDO::FETCH_ASSOC);
            $parsedMessages = [];
            foreach ($rawMessages as $msg) {
                $parsedMessages[] = ['author' => ($msg['messages_sender_type'] == 1) ? 'Client' : 'Support', 'message' => $msg['messages_content'], 'date' => $msg['messages_creation_date']];
            }
            $export_data['tickets'][] = [
                'ticket_id' => $tId,
                'subject' => $tRow['tickets_subject'],
                'description' => $tRow['tickets_description'],
                'custom_fields' => json_decode($tRow['tickets_additionnal_fields'] ?? '[]', true) ?: [],
                'status' => $tRow['tickets_status'],
                'created_at' => $tRow['tickets_creation_date'],
                'closed_at' => $tRow['tickets_closing_date'],
                'messages' => $parsedMessages
            ];
        }
        $filename = 'export_privacy_' . date('Y-m-d') . '.json';
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo json_encode($export_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit();

    // Data anonymization and delete
    } elseif ($action === 'delete') {
        $confirmText = trim($_POST['confirm_delete'] ?? '');
        if ($confirmText !== 'DELETE') {
            $form_feedback = $t['delete_error_match'];
        } else {
            try {
                $dbco->beginTransaction();
                $stmtGetTickets = $dbco->prepare("SELECT tickets_id, tickets_additionnal_fields FROM tickets WHERE tickets_email = :email AND tickets_teams = :team_id");
                $stmtGetTickets->execute(['email' => $client_email, 'team_id' => $team['teams_id']]);
                $tickets = $stmtGetTickets->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($tickets)) {
                    $ticketIds = array_column($tickets, 'tickets_id');
                    $baseStorage = !empty($opensupport_storage_dir) ? $opensupport_storage_dir : (__DIR__ . '/storage/attachments/');
                    // Delete attachements
                    foreach ($ticketIds as $id) {
                        $dir = rtrim($baseStorage, '/') . '/' . (int)$id;
                        if (is_dir($dir)) {
                            array_map('unlink', glob("$dir/*.*"));
                            @rmdir($dir);
                        }
                    }
                    // Anonymization of data
                    $inClause = implode(',', array_map('intval', $ticketIds));
                    $dbco->exec("UPDATE messages SET messages_content = '[Message deleted at the user\'s request]', messages_attachements = '[]', messages_deleted_at = NOW() WHERE messages_ticket_id IN ($inClause)");
                    $stmtUpdate = $dbco->prepare("UPDATE tickets SET tickets_first_name = 'Anonymous', tickets_last_name = 'User', tickets_email = CONCAT('deleted_', tickets_id, '@opensupport.local'), tickets_subject = '[Anonymized request]', tickets_description = '[Data deleted at the user\'s request]', tickets_additionnal_fields = :clean_fields, tickets_admin_notes = NULL, tickets_deleted_at = NOW() WHERE tickets_id = :ticket_id");
                    foreach ($tickets as $ti) {
                        $cleanFields = [];
                        $rawFields = json_decode($ti['tickets_additionnal_fields'] ?? '[]', true);
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
                        $stmtUpdate->execute(['clean_fields' => json_encode($cleanFields, JSON_UNESCAPED_UNICODE), 'ticket_id' => $ti['tickets_id']]);
                    }
                }
                $dbco->commit();
                $is_deleted = true;
            } catch (Exception $e) {
                $dbco->rollBack();
                $form_feedback = "Error : " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['title'] ?> - <?= htmlspecialchars($team['teams_name']) ?></title>
    <link rel="icon" type="image/x-icon" href="<?= $opensupport_link ?>/src/opensupport_assets/opensupport_icon.svg">
    <link rel="stylesheet" href="<?= $opensupport_link ?>/src/css/main.css">
    <style>
        :root {
            --tertiary-bg-color: <?= htmlspecialchars($team['teams_color'] ?: '#4f46e5'); ?>;
        }
    </style>
</head>
<body class="form">

    <div class="action_bar">
        <?php require("src/php/language_selector.php"); ?>
    </div>

    <main>
        <div class="box">
            <header>
                <?php if($team['teams_logo']=="1"): ?>
                    <img src="<?= $opensupport_link?>/up/teams/<?= $team['teams_id'] ?>.webp" alt="Logo <?= htmlspecialchars($team['teams_name']) ?>">
                <?php endif; ?>
                <h1><?= $t['title'] ?></h1>
                <p><?= $t['subtitle'] ?></p>
            </header>

            <?php if (!empty($form_feedback)): ?>
                <p class="alert"><?= htmlspecialchars($form_feedback) ?></p>
            <?php endif; ?>

            <?php if ($is_deleted): ?>
                <div>
                    <p><?= $t['delete_success'] ?></p>
                    <a href="../ticket/<?= htmlspecialchars($ticket_token) ?> ?>" class="btn"><?= $t['back_to_chat'] ?></a>
                </div>
            <?php else: ?>
                <div class="alert">
                    <span><?= $t['email_label'] ?></span>
                    <strong><?= htmlspecialchars($client_email) ?></strong>
                </div>

                <!-- Export Form -->
                <div class="section-action">
                    <h2><strong><?= $t['export_section_title'] ?></strong></h2>
                    <p><?= $t['export_desc'] ?></p>
                    <form method="POST">
                        <input type="hidden" name="privacy_action" value="export">
                        <button type="submit"><?= $t['export_btn'] ?></button>
                    </form>
                </div>
                <hr>
                <!-- Delete Form -->
                <div class="section-action">
                    <h2><strong><?= $t['delete_section_title'] ?></strong></h2>
                    <p><?= $t['delete_desc'] ?></p>
                    <br>
                    <form method="POST">
                        <input type="hidden" name="privacy_action" value="delete">
                        <div>
                            <label><?= $t['delete_confirm_label'] ?></label>
                            <input type="text" name="confirm_delete" placeholder="DELETE" required>
                        </div>
                        <button type="submit"><?= $t['delete_btn'] ?></button>
                    </form>
                </div>

                <a href="../ticket/<?= htmlspecialchars($ticket_token) ?>" class="link-privacy-back">← <?= $t['back_to_chat'] ?></a>
            <?php endif; ?>
        </div>
    </main>

    <footer>&copy; <?= date("Y"); ?> <?= htmlspecialchars($team['teams_name']) ?><br>Powered by OpenSupport</footer>
    <script src="<?= $opensupport_link ?>/src/js/main.js"></script>
</body>
</html>