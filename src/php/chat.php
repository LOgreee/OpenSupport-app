<?php $user = null;
$is_client = (!empty($ticket));
if ($is_client) {
    $user = "user";
}
$is_admin = false;
if (isset($_SESSION['connected']) && $_SESSION['connected'] === "true") {
    $now = new DateTime();
    $connectionDate = DateTime::createFromFormat('m/d/Y h:i:s a', $_SESSION['connection_datetime']);
    if ($connectionDate) {
        $date_int = $now->diff($connectionDate);
        $hours = ($date_int->days * 24) + ($date_int->h) + ($date_int->i / 60);
        if ($_SESSION['connection_datetime'] < $now && $hours < 6) {
            $current_team_id = $team_id ?? $_SESSION['team_id'] ?? null;
            if ($current_team_id && verifyTeamAccess($current_team_id)) {
                $is_admin = true;
                $user = "admin";
            }
        } else {
            unset($_SESSION['connected']); 
        }
    }
}
if (!$is_client && (!$is_admin)) {
    header("Location: {$GLOBALS['opensupport_link']}/dashboard/");
    exit();
}

// Language manager
$translations_chat = [
    'fr' => [
        'datetime' => "à",
        'ticket_id' => 'ID ticket:',
        'message_user_self' => 'Vous',
        'message_user_support' => 'Support',
        'message_user_client' => 'Client',
        'message_template_file_request' => 'Demander des fichiers',
        'message_input_placeholder' => 'Tapez votre message...',
        'message_input_send' => 'Envoyer',
        'message_input_recent' => 'Aller aux messages récents',
        'ticket_close_btn' => 'Clôturer le ticket',
        'ticket_closed_btn' => 'Ticket clôs',
        'ticket_subject' => 'Sujet',
        'ticket_description' => 'Description',
        'ticket_description_none' => 'Aucune description fournie.',
        'ticket_support_notes' => 'Notes du support',
        'ticket_support_notes_placeholder' => 'Visible uniquement par le support...',
        'ticket_client' => 'Client',
        'ticket_assigned_to' => 'Assigné à',
        'ticket_assigned_to_none' => 'Non assigné',
        'ticket_priority' => 'Priorité',
        'ticket_priority_1' => 'Très basse',
        'ticket_priority_2' => 'Basse',
        'ticket_priority_3' => 'Normale',
        'ticket_priority_4' => 'Haute',
        'ticket_priority_5' => 'Très haute',
        'ticket_status' => 'Statut',
        'ticket_status_new' => 'Nouveau',
        'ticket_status_inprogress' => 'En cours',
        'ticket_status_closed' => 'Fermé',
        'ticket_history' => 'Historique',
        'ticket_history_since' => 'Depuis le ',
        'ticket_history_created_title' => 'Ticket créé depuis',
        'ticket_history_inprogress_title' => 'Prise en charge du ticket',
        'ticket_history_inprogress_content' => 'En cours de résolution...',
        'ticket_history_closed_title' => 'Ticket clôturé',
        'ticket_history_feedback_wait_title' => 'En attente d\'un feedback',
        'ticket_history_feedback_title' => 'Feedback récolté',
        'ticket_history_feedback_rating_title' => 'Note: ',
        'ticket_history_feedback_commentary_title' => 'Commentaire: ',
        'ticket_message_update_error' => 'Erreur lors de la mise à jour.',
        'ticket_message_close_confirm' => 'Êtes-vous sûr de vouloir clôturer ce ticket ?\rUn email d\'évaluation sera envoyé au client.',
        'ticket_message_close_process' => 'Clôture en cours...',
        'ticket_message_close_done' => 'Ticket clôturé',
        'ticket_message_close_error' => 'Erreur de clôture:',
        'message_file_wait' => 'En attente de fichiers...',
        'message_file_drag_drop' => 'Glissez et déposez vos fichier ici ou cliquez pour naviguer dans vos fichiers.',
        'message_file_requirements' => "Limité aux formats .png, .jpg, .mp4, .pdf avec une taille maximum de {$opensupport_max_file_size} Mo.",
        'message_file_requirements_error1' => 'Format non autorisé :',
        'message_file_requirements_error2' => "Fichier trop lourd (> {$opensupport_max_file_size} Mo) :",
        'message_file_send' => 'Envoyer',
        'message_file_send_process' => 'Envoi en cours...',
        'message_file_send_error' => 'Erreur lors du téléversement.',
    ],
    'en' => [
        'datetime' => "at",
        'ticket_id' => 'Ticket ID',
        'message_user_self' => 'You',
        'message_user_support' => 'Support',
        'message_user_client' => 'Client',
        'message_template_file_request' => 'Request files',
        'message_input_placeholder' => 'Type your message...',
        'message_input_send' => 'Send',
        'message_input_recent' => 'Go to recent messages',
        'ticket_close_btn' => 'Close the ticket',
        'ticket_closed_btn' => 'Closed ticket',
        'ticket_subject' => 'Subject',
        'ticket_description' => 'Description',
        'ticket_description_none' => 'No description provided.',
        'ticket_support_notes' => 'Support notes',
        'ticket_support_notes_placeholder' => 'Visible only to Support...',
        'ticket_client' => 'Client',
        'ticket_assigned_to' => 'Assigned to',
        'ticket_assigned_to_none' => 'Unassigned',
        'ticket_priority' => 'Priority',
        'ticket_priority_1' => 'Very low',
        'ticket_priority_2' => 'Low',
        'ticket_priority_3' => 'Normal',
        'ticket_priority_4' => 'High',
        'ticket_priority_5' => 'Very high',
        'ticket_status' => 'Status',
        'ticket_status_new' => 'New',
        'ticket_status_inprogress' => 'In progress',
        'ticket_status_closed' => 'Closed',
        'ticket_history' => 'History',
        'ticket_history_since' => 'Since the ',
        'ticket_history_created_title' => 'Ticket created from',
        'ticket_history_inprogress_title' => 'Ticket handling by support',
        'ticket_history_inprogress_content' => 'Being resolved...',
        'ticket_history_closed_title' => 'Ticket closed',
        'ticket_history_feedback_wait_title' => 'Awaiting feedback',
        'ticket_history_feedback_title' => 'Feedback collected',
        'ticket_history_feedback_rating_title' => 'Rating: ',
        'ticket_history_feedback_commentary_title' => 'Commentary: ',
        'ticket_message_update_error' => 'Error during update.',
        'ticket_message_close_confirm' => 'Are you sure you want to close this ticket?\rAn evaluation email will be sent to the customer.',
        'ticket_message_close_process' => 'Closing in progress...',
        'ticket_message_close_done' => 'Ticket closed',
        'ticket_message_close_error' => 'Error during closing:',
        'message_file_wait' => 'Wait for files...',
        'message_file_drag_drop' => 'Drag & drop files here or click to browse files.',
        'message_file_requirements' => "Limited to .png, .jpg, .mp4, .pdf with a maximum size of {$opensupport_max_file_size} MB.",
        'message_file_requirements_error1' => 'Unauthorized file format:',
        'message_file_requirements_error2' => "File too large (> {$opensupport_max_file_size} MB) :",
        'message_file_send' => 'Send',
        'message_file_send_process' => 'Sending...',
        'message_file_send_error' => 'Error during upload.',
    ],
    'es' => [
        'datetime' => 'a las',
        'ticket_id' => 'ID del ticket',
        'message_user_self' => 'Tú',
        'message_user_support' => 'Soporte',
        'message_user_client' => 'Cliente',
        'message_template_file_request' => 'Solicitar archivos',
        'message_input_placeholder' => 'Escribe tu mensaje...',
        'message_input_send' => 'Enviar',
        'message_input_recent' => 'Ir a los mensajes recientes',
        'ticket_close_btn' => 'Cerrar el ticket',
        'ticket_closed_btn' => 'Ticket cerrado',
        'ticket_subject' => 'Asunto',
        'ticket_description' => 'Descripción',
        'ticket_description_none' => 'Sin descripción proporcionada.',
        'ticket_support_notes' => 'Notas del soporte',
        'ticket_support_notes_placeholder' => 'Visible solo para el Soporte...',
        'ticket_client' => 'Cliente',
        'ticket_assigned_to' => 'Asignado a',
        'ticket_assigned_to_none' => 'Sin asignar',
        'ticket_priority' => 'Prioridad',
        'ticket_priority_1' => 'Muy baja',
        'ticket_priority_2' => 'Baja',
        'ticket_priority_3' => 'Normal',
        'ticket_priority_4' => 'Alta',
        'ticket_priority_5' => 'Muy alta',
        'ticket_status' => 'Estado',
        'ticket_status_new' => 'Nuevo',
        'ticket_status_inprogress' => 'En curso',
        'ticket_status_closed' => 'Cerrado',
        'ticket_history' => 'Historial',
        'ticket_history_since' => 'Desde el ',
        'ticket_history_created_title' => 'Ticket creado el',
        'ticket_history_inprogress_title' => 'Atención del ticket por soporte',
        'ticket_history_inprogress_content' => 'En proceso de resolución...',
        'ticket_history_closed_title' => 'Ticket cerrado',
        'ticket_history_feedback_wait_title' => 'Esperando valoración',
        'ticket_history_feedback_title' => 'Valoración recibida',
        'ticket_history_feedback_rating_title' => 'Calificación: ',
        'ticket_history_feedback_commentary_title' => 'Comentario: ',
        'ticket_message_update_error' => 'Error durante la actualización.',
        'ticket_message_close_confirm' => '¿Está seguro de que desea cerrar este ticket?\rSe enviará un correo electrónico de evaluación al cliente.',
        'ticket_message_close_process' => 'Proceso de cierre en marcha...',
        'ticket_message_close_done' => 'Ticket cerrado',
        'ticket_message_close_error' => 'Error durante la clausura:',
        'message_file_wait' => 'Esperando archivos...',
        'message_file_drag_drop' => 'Arrastra y suelta archivos aquí o haz clic para buscarlos.',
        'message_file_requirements' => "Limitado a .png, .jpg, .mp4 y .pdf, con un tamaño máximo de {$opensupport_max_file_size} MB.",
        'message_file_requirements_error1' => 'Formato de archivo no válido :',
        'message_file_requirements_error2' => "Archivo demasiado grande (> {$opensupport_max_file_size} MB) :",
        'message_file_send' => 'Enviar',
        'message_file_send_process' => 'Envío en curso...',
        'message_file_send_error' => 'Error durante la carga.',
    ],
];
$t_chat = $translations_chat[$lang];

$stmtTicketInfo = $dbco->prepare("SELECT tickets_subject, tickets_description, tickets_first_name, tickets_last_name, tickets_creation_date FROM tickets WHERE tickets_id = :ticket_id LIMIT 1");
$stmtTicketInfo->execute(['ticket_id' => $ticket_id]);
$ticketInfo = $stmtTicketInfo->fetch(PDO::FETCH_ASSOC);
$ticketSubject = $ticketInfo['tickets_subject'];
$ticketDescription = $ticketInfo['tickets_description'] ?? '';
$ticketClientName = $ticketInfo['tickets_first_name'] ?? $t_chat['message_user_client'];
$ticketDateRaw = $ticketInfo['tickets_creation_date'] ?? date('Y-m-d H:i:s');

$ticketTimestamp = strtotime($ticketDateRaw);
$displayTicketDate = (date('Y-m-d', $ticketTimestamp) === date('Y-m-d')) ? date('H:i', $ticketTimestamp) : date('d/m/Y H:i', $ticketTimestamp);
?>
    <div class="chat-container <?= $user == "admin" ? "admin" : "" ?>">
        <header class="chat-header">
            <div class="chat-header-left">
                <a href="../tickets" class="back-btn"></a>
                <div class="chat-title-group">
                    <h1><?= htmlspecialchars($ticketSubject) ?></h1>
                    <span><?= $t_chat['ticket_id'] ?>: #<?= $ticket_id ?></span>
                </div>
            </div>
            <a href="" id="toggleChatInfoBtn" class="chat-mobile-info-btn"></a>
        </header>
        
        <div class="chat">
            <div class="chat-content">
                <div class="chat-messages" id="chat-messages">
                    <?php if (!empty($ticketDescription)): ?>
                    <div class="msg-wrapper user">
                        <div class="msg-meta">
                            <?php if ($user === 'admin'): ?>
                                <strong><?= htmlspecialchars($ticketClientName) ?></strong>
                            <?php elseif($user === 'user'): ?>
                                <strong><?= $t_chat['message_user_self'] ?></strong>
                            <?php endif; ?>
                            <p><?= $displayTicketDate ?></p>
                            <?php $cleanDesc = trim(preg_replace('/\s+/', ' ', strip_tags($ticketDescription))); ?>
                            <button class="tts-btn" onclick="speakText('<?= htmlspecialchars($cleanDesc, ENT_QUOTES, 'UTF-8') ?>')" title="Text-to-speech">🔊</button>
                        </div>
                        <div class="msg-bubble">
                            <?= nl2br(htmlspecialchars($ticketDescription)) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <button class="scroll-recent" id="scroll-recent"><?= $t_chat['message_input_recent'] ?></button>

                <div class="chat-input">
                    <?php if($user == "admin"):?>
                        <div class="messages_template">
                            <div action="file_request"><?= $t_chat['message_template_file_request'] ?></div>
                            <?php $supportFullName = $_SESSION['user_name'] ?? '';
                            $supportNameParts = explode(' ', $supportFullName, 2);
                            $supportFirstName = $supportNameParts[0] ?? '';
                            $supportLastName = $supportNameParts[1] ?? '';

                            $clientFullName = $ticketInfo['tickets_first_name'].' '.$ticketInfo['tickets_last_name'] ?? '';
                            $clientFirstName = $ticketInfo['tickets_first_name'] ?? '';
                            $clientLastName = $ticketInfo['tickets_last_name'] ?? '';

                            $formattedTicketId = isset($ticket_id) ? '#' . $ticket_id : '';
                            $supportTeam = '';
                            if (isset($_SESSION['team_id'])) {
                                $stmtTeamInfo = $dbco->prepare("SELECT teams_name, teams_messages_template FROM teams WHERE teams_id = :team_id LIMIT 1");
                                $stmtTeamInfo->execute(['team_id' => $current_team_id]);
                                $teamData = $stmtTeamInfo->fetch(PDO::FETCH_ASSOC);
                                if ($teamData) {
                                    $supportTeam = $teamData['teams_name'] ?? '';
                                    $jsonString = $teamData['teams_messages_template'] ?? '';
                                    $searchPlaceholders = [
                                        '@supportFirstName',
                                        '@supportLastName',
                                        '@supportName',
                                        '@supportTeam',
                                        '@clientFirstName',
                                        '@clientLastName',
                                        '@client',
                                        '@ticketId',
                                        '@ticketName'
                                    ];
                                    $replaceValues = [
                                        $supportFirstName,
                                        $supportLastName,
                                        $supportFullName,
                                        $supportTeam,
                                        $clientFirstName,
                                        $clientLastName,
                                        $clientFullName,
                                        $formattedTicketId,
                                        $ticketSubject
                                    ];
                                    if (!empty($jsonString)) {
                                        $templates = json_decode($jsonString, true);
                                        if (is_array($templates)) {
                                            foreach ($templates as $template):
                                                if (isset($template['name']) && isset($template['message'])):
                                                    $processedMessage = str_replace($searchPlaceholders, $replaceValues, $template['message']);
                                                    $safeMessage = htmlspecialchars($processedMessage, ENT_QUOTES, 'UTF-8');
                                                    $safeName = htmlspecialchars($template['name'], ENT_QUOTES, 'UTF-8');?>
                                                    <div action="message_template" message="<?= $safeMessage ?>"><?= $safeName ?></div>
                                                <?php endif;
                                            endforeach;
                                        }
                                    }
                                }
                            }?>
                        </div>
                    <?php endif; ?>
                    <div class="messages-input">
                        <button class="emoji-btn" id="emoji-btn">😊</button>
                        <div class="emoji-picker" id="emoji-picker">
                            <span class="emoji-item">😀</span><span class="emoji-item">😃</span><span class="emoji-item">😄</span><span class="emoji-item">😁</span>
                            <span class="emoji-item">😆</span><span class="emoji-item">😅</span><span class="emoji-item">😂</span><span class="emoji-item">🤣</span>
                            <span class="emoji-item">😊</span><span class="emoji-item">😇</span><span class="emoji-item">🙂</span><span class="emoji-item">🙃</span>
                            <span class="emoji-item">😉</span><span class="emoji-item">😌</span><span class="emoji-item">😍</span><span class="emoji-item">🥰</span>
                            <span class="emoji-item">😘</span><span class="emoji-item">😗</span><span class="emoji-item">😚</span><span class="emoji-item">😋</span>
                            <span class="emoji-item">😛</span><span class="emoji-item">😜</span><span class="emoji-item">🤪</span><span class="emoji-item">🤨</span>
                            <span class="emoji-item">🧐</span><span class="emoji-item">🤓</span><span class="emoji-item">😎</span><span class="emoji-item">🥳</span>
                            <span class="emoji-item">😏</span><span class="emoji-item">😒</span><span class="emoji-item">😞</span><span class="emoji-item">😔</span>
                            <span class="emoji-item">😟</span><span class="emoji-item">😕</span><span class="emoji-item">🙁</span><span class="emoji-item">☹️</span>
                            <span class="emoji-item">😣</span><span class="emoji-item">😖</span><span class="emoji-item">😫</span><span class="emoji-item">😩</span>
                            <span class="emoji-item">🥺</span><span class="emoji-item">😢</span><span class="emoji-item">😭</span><span class="emoji-item">😤</span>
                            <span class="emoji-item">😠</span><span class="emoji-item">😡</span><span class="emoji-item">🤬</span><span class="emoji-item">🤯</span>
                            <span class="emoji-item">😳</span><span class="emoji-item">🥵</span><span class="emoji-item">🥶</span><span class="emoji-item">😱</span>
                            <span class="emoji-item">📁</span><span class="emoji-item">📄</span><span class="emoji-item">📌</span><span class="emoji-item">📎</span>
                            <span class="emoji-item">👍</span><span class="emoji-item">👎</span><span class="emoji-item">👌</span><span class="emoji-item">✌️</span>
                            <span class="emoji-item">🤞</span><span class="emoji-item">👏</span><span class="emoji-item">🙌</span><span class="emoji-item">🙏</span>
                            <span class="emoji-item">❤️</span><span class="emoji-item">🧡</span><span class="emoji-item">💛</span><span class="emoji-item">💚</span>
                            <span class="emoji-item">💙</span><span class="emoji-item">💜</span><span class="emoji-item">🖤</span><span class="emoji-item">✨</span>
                        </div>
                        <textarea id="message-input" placeholder="<?= $t_chat['message_input_placeholder'] ?>" rows="1" autocomplete="off"></textarea>
                        <button class="send-btn" onclick="sendMessage()"><?= $t_chat['message_input_send'] ?></button>
                    </div>
                </div>
            </div>
            
            <?php if($user == "admin"):?>
            <?php $stmtAdmin = $dbco->prepare("SELECT * FROM tickets WHERE tickets_id = :ticket_id LIMIT 1");
            $stmtAdmin->execute(['ticket_id' => $ticket_id]);
            $row_tickets = $stmtAdmin->fetch(PDO::FETCH_ASSOC);
            $assignee = null;
            if (!empty($row_tickets['tickets_assigned_to'])) {
                $stmtAssignee = $dbco->prepare("SELECT users_id, users_first_name, users_last_name FROM users WHERE users_id = :user_id LIMIT 1");
                $stmtAssignee->execute(['user_id' => $row_tickets['tickets_assigned_to']]);
                $assignee = $stmtAssignee->fetch(PDO::FETCH_ASSOC);
            }
            $custom_fields = json_decode($row_tickets['tickets_additionnal_fields'] ?? '{}', true);
            $history_logs = [];
            if (!empty($row_tickets['tickets_creation_date'])) {
                $history_logs[] = [
                    'title' => $t_chat['ticket_history_created_title'].': '.$row_tickets['tickets_source'],
                    'time' => date('d/m/Y', strtotime($row_tickets['tickets_creation_date'])) . ' ' . $t_chat['datetime'] . ' ' . date('H:i', strtotime($row_tickets['tickets_creation_date']))
                ];
            }
            if ($row_tickets['tickets_status'] <= 1) {
                $history_logs[] = [
                    'title' => $t_chat['ticket_history_inprogress_title'],
                    'time' => $t_chat['ticket_history_inprogress_content']
                ];
            }
            if (!empty($row_tickets['tickets_closing_date'])) {
                $history_logs[] = [
                    'title' => $t_chat['ticket_history_closed_title'],
                    'time' => date('d/m/Y', strtotime($row_tickets['tickets_closing_date'])) . ' ' . $t_chat['datetime'] . ' ' . date('H:i', strtotime($row_tickets['tickets_closing_date']))
                ];
                if (empty($row_tickets['tickets_feedback_date'])) {
                    $history_logs[] = [
                        'title' => $t_chat['ticket_history_feedback_wait_title'],
                        'time' => $t_chat['ticket_history_since'] . date('d/m/Y', strtotime($row_tickets['tickets_closing_date'])) . ' ' . $t_chat['datetime'] . ' ' . date('H:i', strtotime($row_tickets['tickets_closing_date']))
                    ];
                } else {
                    $rating = $row_tickets['tickets_rating'] ?? '?';
                    $feedbackText = !empty($row_tickets['tickets_feedback']) ? htmlspecialchars($row_tickets['tickets_feedback']) : 'Aucun';

                    $history_logs[] = [
                        'title' => $t_chat['ticket_history_feedback_title'],
                        'time' => date('d/m/Y', strtotime($row_tickets['tickets_feedback_date'])) . ' ' . $t_chat['datetime'] . ' ' . date('H:i', strtotime($row_tickets['tickets_feedback_date'])) . '<br><br><span style="margin-top:2px;">'. $t_chat['ticket_history_feedback_rating_title'] . $rating . '/5</span>' . '<span style="margin-top:2px;">'. $t_chat['ticket_history_feedback_commentary_title'] . $feedbackText . '</span>'
                    ];
                }
            }?>
            <div class="chat-info" data-ticket-id="<?= (int)($ticket_id ?? $row_tickets['tickets_id'] ?? 0) ?>">
                <?php if($row_tickets['tickets_status'] == '0' || $row_tickets['tickets_status'] == '1'): ?>
                    <button class="btn" id="btn-close-ticket"><?= $t_chat['ticket_close_btn'] ?></button>
                <?php else: ?>
                    <button class="btn" id="btn-close-ticket" disabled><?= $t_chat['ticket_closed_btn'] ?></button>
                <?php endif;?>
                
                <div class="info-section">
                    <h3><?= $t_chat['ticket_subject'] ?></h3>
                    <p><?= htmlspecialchars($row_tickets['tickets_subject'] ?? 'None') ?></p>
                </div>

                <div class="info-section">
                    <h3><?= $t_chat['ticket_description'] ?></h3>
                    <p><?= !empty($row_tickets['tickets_description']) ? nl2br(htmlspecialchars($row_tickets['tickets_description'])) : $t_chat['ticket_description_none'] ?></p>
                </div>

                <div class="info-section">
                    <h3><?= $t_chat['ticket_support_notes'] ?></h3>
                    <textarea class="ajax-update custom-select" data-field="tickets_admin_notes" data-ticket-id="<?= $ticket_id ?>" placeholder="<?= $t_chat['ticket_support_notes_placeholder'] ?>"><?= htmlspecialchars($row_tickets['tickets_admin_notes'] ?? '') ?></textarea>
                </div>

                <div class="info-section">
                    <h3><?= $t_chat['ticket_client'] ?></h3>
                    <p><strong><?= htmlspecialchars(($row_tickets['tickets_first_name'] ?? '') . ' ' . ($row_tickets['tickets_last_name'] ?? $t_chat['message_user_client'])) ?></strong> (<?= htmlspecialchars($row_tickets['tickets_email'] ?? '') ?>)</p>
                </div>

                <div class="info-section">
                    <h3><?= $t_chat['ticket_assigned_to'] ?></h3>
                    <div class="select-wrapper">
                        <select class="ajax-update custom-select" data-field="tickets_assigned_to">
                            <option value="0"><?= $t_chat['ticket_assigned_to_none'] ?></option>
                            <?php $stmt_staff = $dbco->prepare("SELECT u.users_id, u.users_first_name, u.users_last_name, u.users_email FROM users u INNER JOIN teams_members tm ON tm.teams_members_user_id = u.users_id WHERE tm.teams_members_team_id = :team_id AND (tm.teams_members_deleted = 0 OR tm.teams_members_deleted IS NULL) AND (u.users_deleted = 0 OR u.users_deleted IS NULL) AND tm.teams_members_join_date IS NOT NULL ORDER BY u.users_first_name ASC, u.users_last_name ASC");
                            $stmt_staff->execute(['team_id' => $_SESSION['team_id']]);
                            $staff_members = $stmt_staff->fetchAll(PDO::FETCH_ASSOC);
                            if (!empty($staff_members)): ?>
                                <?php foreach ($staff_members as $member): ?>
                                    <option value="<?= (int)$member['users_id'] ?>" 
                                        <?= ((int)($row_tickets['tickets_assigned_to'] ?? 0) === (int)$member['users_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars(trim(($member['users_first_name'] ?? '') . ' ' . ($member['users_last_name'] ?? ''))) ?> (<?= htmlspecialchars($member['users_email']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="info-section">
                    <h3><?= $t_chat['ticket_priority'] ?></h3>
                    <div class="select-wrapper">
                        <select class="ajax-update custom-select" data-field="tickets_priority" data-ticket-id="<?= $ticket_id ?>" required>
                            <option value="2" <?= ($row_tickets['tickets_priority'] == 2) ? 'selected' : '' ?>><?= $t_chat['ticket_priority_5'] ?></option>
                            <option value="1" <?= ($row_tickets['tickets_priority'] == 1) ? 'selected' : '' ?>><?= $t_chat['ticket_priority_4'] ?></option>
                            <option value="0" <?= ($row_tickets['tickets_priority'] == 0 || is_null($row_tickets['tickets_priority'])) ? 'selected' : '' ?>><?= $t_chat['ticket_priority_3'] ?></option>
                            <option value="-1" <?= ($row_tickets['tickets_priority'] == -1) ? 'selected' : '' ?>><?= $t_chat['ticket_priority_2'] ?></option>
                            <option value="-2" <?= ($row_tickets['tickets_priority'] == -2) ? 'selected' : '' ?>><?= $t_chat['ticket_priority_1'] ?></option>
                        </select>
                    </div>
                </div>

                <div class="info-section">
                    <h3><?= $t_chat['ticket_status'] ?></h3>
                    <?php if($row_tickets['tickets_status'] == '0'): ?>
                        <span class="tag"><?= $t_chat['ticket_status_new'] ?></span>
                    <?php elseif($row_tickets['tickets_status'] == '1'): ?>
                        <span class="tag in-progress"><?= $t_chat['ticket_status_inprogress'] ?></span>
                    <?php else: ?>
                        <span class="tag closed"><?= $t_chat['ticket_status_closed'] ?></span>
                    <?php endif; ?>
                </div>

                <?php if(is_array($custom_fields) && !empty($custom_fields)): 
                    foreach ($custom_fields as $field):?>
                <div class="info-section">
                    <h3><?= htmlspecialchars($field['question']) ?></h3>
                    <p><?= !empty($field['answer']) ? htmlspecialchars($field['answer']) : '-' ?></p>
                </div>
                <?php endforeach;
                endif; ?>

                <div class="info-section">
                    <h3><?= $t_chat['ticket_history'] ?></h3>
                    <ul class="timeline">
                        <?php foreach($history_logs as $log): ?>
                        <li>
                            <strong><?= $log['title'] ?></strong>
                            <span><?= $log['time'] ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <script> document.addEventListener('DOMContentLoaded', () => {
                function debounce(func, wait) {
                    let timeout;
                    return function(...args) {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(this, args), wait);
                    };
                }
                const chatInfo = document.querySelector('.chat-info');
                if (!chatInfo) return;
                const ticketId = chatInfo.dataset.ticketId;
                const toggleInfoBtn = document.getElementById("toggleChatInfoBtn");
                if (toggleInfoBtn && chatInfo) {
                    toggleInfoBtn.addEventListener("click", (e) => {
                        e.preventDefault();
                        chatInfo.classList.toggle("open");
                    });
                }
                async function updateTicketField(field, value, el = null) {
                    try {
                        const formData = new FormData();
                        formData.append('action', 'update_field');
                        formData.append('ticket_id', ticketId);
                        formData.append('field', field);
                        formData.append('value', value);
                        const response = await fetch('<?= $opensupport_link ?>/src/php/set_ticket.php', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await response.json();
                        if (data.success) {
                            if (el && el.tagName.toLowerCase() === 'textarea') {
                                const indicator = el.parentElement.querySelector('.save-indicator');
                                if (indicator) {
                                    indicator.style.display = 'inline';
                                    setTimeout(() => indicator.style.display = 'none', 2000);
                                }
                            }
                        } else {
                            alert(data.message || '<?= $t_chat['ticket_message_update_error'] ?>');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }

                document.querySelectorAll('select.ajax-update').forEach(select => {
                    select.addEventListener('change', (e) => {
                        const field = e.target.dataset.field;
                        const value = e.target.value;
                        updateTicketField(field, value, e.target);
                    });
                });

                const notesTextarea = document.querySelector('textarea.ajax-update');
                if (notesTextarea) {
                    const debouncedUpdate = debounce((value) => {
                        updateTicketField('tickets_admin_notes', value, notesTextarea);
                    }, 700);
                    notesTextarea.addEventListener('input', (e) => {
                        debouncedUpdate(e.target.value);
                    });
                }

                const closeBtn = document.getElementById('btn-close-ticket');
                if (closeBtn) {
                    closeBtn.addEventListener('click', async () => {
                        const confirmed = confirm('<?= $t_chat['ticket_message_close_confirm'] ?>');
                        if (!confirmed) return;
                        closeBtn.disabled = true;
                        closeBtn.textContent = '<?= $t_chat['ticket_message_close_process'] ?>';
                        try {
                            const formData = new FormData();
                            formData.append('action', 'close_ticket');
                            formData.append('ticket_id', ticketId);
                            const response = await fetch('<?= $opensupport_link ?>/src/php/set_ticket.php', {
                                method: 'POST',
                                body: formData
                            });
                            const data = await response.json();
                            if (data.success) {
                                const statusContainer = document.getElementById('status-container');
                                if (statusContainer) {
                                    statusContainer.innerHTML = '<span class="tag closed"><?= $t_chat['ticket_status_closed'] ?></span>';
                                }
                                closeBtn.textContent = '<?= $t_chat['ticket_message_close_done'] ?>';
                            } else {
                                alert(data.message || 'Error.');
                                closeBtn.disabled = false;
                                closeBtn.textContent = '<?= $t_chat['ticket_close_btn'] ?>';
                            }
                        } catch (error) {
                            console.error('<?= $t_chat['ticket_message_close_error'] ?>', error);
                            closeBtn.disabled = false;
                        }
                    });
                }
            }); </script>
            <?php endif; ?>
        </div>
    </div>

    <script>
        window.addEventListener('dragover', (e) => e.preventDefault(), false);
        window.addEventListener('drop', (e) => e.preventDefault(), false);
        
        let isFetching = false;
        const ticketId = <?= $ticket_id ?>;
        let lastMessageId = 0;
        const token = "<?= $ticket_token ?>";
        const chatBox = document.getElementById('chat-messages');
        const scrollRecentBtn = document.getElementById('scroll-recent');
        const messageInput = document.getElementById('message-input');
        const defaultPollInterval = 3000;
        const backgroundPollInterval = 10000;
        let pollTimer = null;

        // Text-to-Speech
        function speakText(text) {
            window.speechSynthesis.cancel(); 
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'fr';
            utterance.rate = 1;
            window.speechSynthesis.speak(utterance);
        }

        // Fetch New Messages (Live Update Polling)
        async function fetchMessages() {
            if (isFetching) return;
            if (document.hidden) {
                scheduleNextPoll(backgroundPollInterval);
                return;
            }
            isFetching = true;
            try {
                let url = `<?= $opensupport_link?>/src/php/get_messages.php?ticket_id=${ticketId}&last_id=${lastMessageId}`;
                if (token !== "") {
                    url += `&token=${encodeURIComponent(token)}`;
                }
                const response = await fetch(url);
                if (response.ok) {
                    const messages = await response.json();
                    if (Array.isArray(messages) && messages.length > 0) {
                        messages.forEach(msg => appendMessage(msg));
                        lastMessageId = messages[messages.length - 1].id;
                        scrollToBottom();
                    }
                }
            } catch (error) {
                console.error("Polling error:", error);
            } finally {
                isFetching = false;
                scheduleNextPoll(defaultPollInterval);
            }
        }
        function scheduleNextPoll(interval) {
            clearTimeout(pollTimer);
            pollTimer = setTimeout(fetchMessages, interval);
        }
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                clearTimeout(pollTimer);
                fetchMessages();
            }
        });

        // Render Message in DOM
        function appendMessage(msg) {
            const isUser = msg.sender_type === 'user';
            let wrapper = document.getElementById(`msg-item-${msg.id}`);
            const isNew = !wrapper;
            if (isNew) {
                wrapper = document.createElement('div');
                wrapper.id = `msg-item-${msg.id}`;
                wrapper.className = `msg-wrapper ${isUser ? 'user' : 'admin'}`;
            }
            let displayTime = msg.time;
            if (msg.raw_date) {
                const today = new Date().toISOString().slice(0, 10);
                if (msg.raw_date !== today) {
                    const p = msg.raw_date.split('-');
                    displayTime = `${p[2]}/${p[1]}/${p[0]} ${msg.time}`;
                }
            }
            const isAdminView = document.querySelector('.chat-container.admin') !== null;
            let bubbleContent = '';
            console.log(msg);
            
            const isFileReq = (msg.is_file_request == 1 || msg.is_file_request === true || msg.is_file_request === '1');
            const isReady = (msg.ready == 1 || msg.ready === true || msg.ready === '1' || msg.ready === undefined);
            const hasAttachments = (msg.attachments && Array.isArray(msg.attachments) && msg.attachments.length > 0);
            
            // File request
            if (isFileReq && isReady && !hasAttachments) {
                if (!isNew && !isAdminView && wrapper.querySelector('.dropzone-wrapper')) {
                    return; 
                }
                if (isAdminView) {
                    bubbleContent = `
                        <div class="file-request-box file-request-waiting">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242M12 12v9m-4-4 4-4 4 4"/></svg>
                            <span><?= $t_chat['message_file_wait'] ?></span>
                        </div>
                    `;
                } else {
                    bubbleContent = `
                        <div class="file-request-box dropzone-wrapper" id="dropzone-${msg.id}">
                            <div class="dropzone-container" onclick="document.getElementById('file-input-${msg.id}').click()">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242M12 12v9m-4-4 4-4 4 4"/></svg>
                                <h4><?= $t_chat['message_file_drag_drop'] ?></h4>
                                <p><?= $t_chat['message_file_requirements'] ?></p>
                            </div>
                            <input type="file" id="file-input-${msg.id}" multiple accept=".png,.jpg,.jpeg,.mp4,.pdf" style="display:none;">
                            <ul class="selected-files-list" id="file-list-${msg.id}"></ul>
                            <button class="btn-upload-send" id="btn-upload-${msg.id}" style="display:none;"><?= $t_chat['message_file_send'] ?></button>
                        </div>
                    `;
                }
            } 
            // Files
            else if (hasAttachments) {
                let filesHtml = '<div class="file-grid">';
                msg.attachments.forEach(fileName => {
                    const ext = fileName.split('.').pop().toLowerCase();
                    const viewUrl = `<?= $opensupport_link ?>/src/php/view_file.php?ticket_id=${ticketId}&token=${token}&file=${encodeURIComponent(fileName)}&action=view`;
                    const dlUrl = `<?= $opensupport_link ?>/src/php/view_file.php?ticket_id=${ticketId}&token=${token}&file=${encodeURIComponent(fileName)}&action=download`;
                    let previewHtml = '';
                    let filetype = '';
                    if (['webp', 'jpg', 'png'].includes(ext)) {
                        previewHtml = `<img src="${viewUrl}" onclick="window.open('${viewUrl}', '_blank', 'noopener,noreferrer');" alt="Image">`;
                        filetype = 'Image';
                    } else if (ext === 'mp4') {
                        previewHtml = `<video src="${viewUrl}" onclick="window.open('${viewUrl}', '_blank', 'noopener,noreferrer');"></video>`;
                        filetype = 'Video';
                    } else if (ext === 'pdf') {
                        previewHtml = `<div class="file-pdf-thumb" onclick="window.open('${viewUrl}', '_blank', 'noopener,noreferrer');"></div>`;
                        filetype = 'PDF';
                    }
                    filesHtml += `
                        <div class="file-item">
                            ${previewHtml}
                            <div class="file-actions">
                                <p>${filetype}</p>
                                <a href="${dlUrl}">D</a>
                            </div>
                        </div>
                    `;
                });
                filesHtml += '</div>';
                bubbleContent = (msg.content ? `<p>${msg.content.replace(/(?:\r\n|\r|\n)/g, '<br>')}</p>` : '') + filesHtml;
            } else {
                bubbleContent = msg.content.replace(/(?:\r\n|\r|\n)/g, '<br>');
            }
            wrapper.innerHTML = `
                <div class="msg-meta">
                    ${isUser ? '' : `<strong>${msg.sender_name}</strong>`}
                    ${isUser ? `<strong><?= $t_chat['message_user_self'] ?></strong>` : ''}
                    <p>${displayTime}</p>
                </div>
                <div class="msg-bubble">
                    ${bubbleContent}
                </div>
            `;
            if (isNew) {
                chatBox.appendChild(wrapper);
            }
            if (isFileReq && isReady && !hasAttachments && !isAdminView) {
                initDropZone(msg.id);
            }
        }

        // Scroll Logic
        function scrollToBottom() {
            chatBox.scrollTo({top: chatBox.scrollHeight, behavior: 'smooth'});
        }
        chatBox.addEventListener('scroll', () => {
            const isAtBottom = chatBox.scrollHeight - chatBox.scrollTop <= chatBox.clientHeight + 50;
            scrollRecentBtn.style.display = isAtBottom ? 'none' : 'block';
        });
        scrollRecentBtn.addEventListener('click', scrollToBottom);
        
        document.getElementById('message-input').addEventListener('input', () => {
            pollInterval = 3000;
        });

        // Keyboard input
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Send Message
        async function sendMessage() {
            const text = messageInput.value.trim();
            if (!text) return;
            messageInput.value = '';
            scrollToBottom();
            try {
                const response = await fetch(`<?= $opensupport_link ?>/src/php/send_message.php`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        ticket_id: ticketId,
                        token: token,
                        content: text
                    })
                });
                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        clearTimeout(pollTimer);
                        fetchMessages();
                    }
                } else {
                    console.error("The server refused the transmission.");
                }
            } catch (error) {
                console.error("Network error during sending:", error);
            }
        }

        fetchMessages();
        
        // Message templates & File Request
        document.addEventListener('click', async function(e) {
            const templateElement = e.target.closest('div[action="message_template"]');
            const requestBtn = e.target.closest('div[action="file_request"]');
            
            if (templateElement) {
                const messageContent = templateElement.getAttribute('message');
                if (messageContent !== null) {
                    messageInput.value = messageContent;
                    messageInput.focus();
                }
            } else if (requestBtn) {
                try {
                    const res = await fetch(`<?= $opensupport_link ?>/src/php/request_files.php`, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({ ticket_id: ticketId })
                    });
                    const data = await res.json();
                    if (data.success) {
                        clearTimeout(pollTimer);
                        fetchMessages();
                    } else {
                        alert(data.error || 'Error.');
                    }
                } catch (err) {
                    console.error("Error:", err);
                }
            }
        });
        
        function initDropZone(msgId) {
            const dropzone = document.querySelector(`#dropzone-${msgId} .dropzone-container`);
            const fileInput = document.getElementById(`file-input-${msgId}`);
            const fileList = document.getElementById(`file-list-${msgId}`);
            const sendBtn = document.getElementById(`btn-upload-${msgId}`);
            
            if (!dropzone || !fileInput) return;

            let selectedFiles = [];
            const allowedExtensions = ['png', 'jpg', 'jpeg', 'mp4', 'pdf'];
            const maxSizeBytes = (<?= $opensupport_max_file_size ?? 10 ?>) * 1024 * 1024;
            const maxFiles = <?= $opensupport_max_files ?? 5 ?>;

            function renderList() {
                fileList.innerHTML = '';
                selectedFiles.forEach((file, index) => {
                    const li = document.createElement('li');
                    li.innerHTML = `<span>${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</span>`;
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.textContent = '✕';
                    removeBtn.onclick = (e) => {
                        e.stopPropagation();
                        selectedFiles.splice(index, 1);
                        renderList();
                    };
                    li.appendChild(removeBtn);
                    fileList.appendChild(li);
                });
                sendBtn.style.display = selectedFiles.length > 0 ? 'block' : 'none';
            }

            function handleFiles(files) {
                Array.from(files).forEach(file => {
                    if (selectedFiles.length >= maxFiles) return;

                    const ext = file.name.split('.').pop().toLowerCase();
                    if (!allowedExtensions.includes(ext)) {
                        alert(`<?= $t_chat['message_file_requirements_error1'] ?> ${file.name}`);
                        return;
                    }
                    if (file.size > maxSizeBytes) {
                        alert(`<?= $t_chat['message_file_requirements_error2'] ?> ${file.name}`);
                        return;
                    }
                    selectedFiles.push(file);
                });
                renderList();
            }
            dropzone.onclick = (e) => {
                e.stopPropagation();
                fileInput.click();
            };
            ['dragenter', 'dragover'].forEach(name => {
                dropzone.addEventListener(name, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('dragover');
                }, false);
            });
            ['dragleave', 'dragend'].forEach(name => {
                dropzone.addEventListener(name, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('dragover');
                }, false);
            });
            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('dragover');
                if (e.dataTransfer && e.dataTransfer.files.length > 0) {
                    handleFiles(e.dataTransfer.files);
                }
            }, false);
            fileInput.onchange = (e) => {
                if (e.target.files && e.target.files.length > 0) {
                    handleFiles(e.target.files);
                }
            };
            sendBtn.addEventListener('click', async () => {
                if (selectedFiles.length === 0) return;
                sendBtn.disabled = true;
                sendBtn.textContent = '<?= $t_chat['message_file_send_process'] ?>';
                const formData = new FormData();
                formData.append('ticket_id', ticketId);
                formData.append('message_id', msgId);
                formData.append('token', token);
                selectedFiles.forEach(f => formData.append('files[]', f));
                try {
                    const res = await fetch(`<?= $opensupport_link ?>/src/php/upload_files.php`, {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();
                    if (data.success) {
                        lastMessageId = 0;
                        chatBox.innerHTML = '';
                        clearTimeout(pollTimer);
                        fetchMessages();
                    } else {
                        alert(data.error || '<?= $t_chat['message_file_send_error'] ?>');
                        sendBtn.disabled = false;
                        sendBtn.textContent = 'Send';
                    }
                } catch (err) {
                    console.error(err);
                    sendBtn.disabled = false;
                    sendBtn.textContent = 'Send';
                }
            });
        }
        
        // Emoji picker
        document.addEventListener('DOMContentLoaded', () => {
            const emojiBtn = document.getElementById('emoji-btn');
            const emojiPicker = document.getElementById('emoji-picker');
            if (!emojiBtn || !emojiPicker || !messageInput) return;
            emojiBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                emojiPicker.classList.toggle("active");
            });
            emojiPicker.addEventListener('click', (e) => {
                const item = e.target.closest('.emoji-item');
                if (!item) return;
                const emoji = item.textContent;
                const startPos = messageInput.selectionStart;
                const endPos = messageInput.selectionEnd;
                const text = messageInput.value;
                messageInput.value = text.substring(0, startPos) + emoji + text.substring(endPos);
                const nextCursorPos = startPos + emoji.length;
                messageInput.focus();
                messageInput.setSelectionRange(nextCursorPos, nextCursorPos);
                messageInput.dispatchEvent(new Event('input', { bubbles: true }));
            });
            document.addEventListener('click', (e) => {
                if (!emojiPicker.contains(e.target) && e.target !== emojiBtn) {
                    emojiPicker.classList.remove("active");
                }
            });
        });
    </script>