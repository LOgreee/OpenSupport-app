<?php require("../../config.php");
header('Content-Type: application/json');

// Language manager
$translations_mail = [
    'fr' => [
        'mail_subject' => 'Clôture de votre demande : ',
        'mail_hello' => 'Bonjour',
        'mail_resolved' => 'Votre ticket concernant <strong>%s</strong> a été résolu et clôturé.',
        'mail_feedback' => "Afin d'améliorer la qualité de nos services, merci de prendre quelques secondes pour noter la prise en charge de votre demande :",
        'mail_btn' => 'Donner mon avis',
        'mail_regards' => 'Cordialement,',
        'mail_team' => "L'équipe %s"
    ],
    'en' => [
        'mail_subject' => 'Closure of your request: ',
        'mail_hello' => 'Hello',
        'mail_resolved' => 'Your ticket regarding <strong>%s</strong> has been resolved and closed.',
        'mail_feedback' => 'In order to improve our services, please take a few seconds to rate our support:',
        'mail_btn' => 'Give my feedback',
        'mail_regards' => 'Best regards,',
        'mail_team' => 'The %s team'
    ],
    'es' => [
        'mail_subject' => 'Cierre de su solicitud: ',
        'mail_hello' => 'Hola',
        'mail_resolved' => 'Su ticket sobre <strong>%s</strong> ha sido resuelto y cerrado.',
        'mail_feedback' => 'Para mejorar nuestros servicios, por favor tómese unos segundos para calificar la atención recibida:',
        'mail_btn' => 'Dar mi opinión',
        'mail_regards' => 'Atentamente,',
        'mail_team' => 'El equipo de %s'
    ]
];
$t_mail = $translations_mail[$lang];

$action = $_POST['action'] ?? '';
$ticket_id = (int)($_POST['ticket_id'] ?? 0);

if ($ticket_id === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'No ticket ID.']);
    exit;
}

try {
    $isAuthorized = false;
    if (isset($_SESSION['user_id'])) {
        $isAuthorized = true;
    }
    if (!$isAuthorized) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized access.']);
        exit;
    }
    // Update ticket informations
    if ($action === 'update_field') {
        $field = $_POST['field'] ?? '';
        $value = $_POST['value'] ?? null;
        $allowed_fields = [
            'tickets_admin_notes' => 'string',
            'tickets_priority'    => 'int',
            'tickets_assigned_to' => 'int'
        ];
        if (!array_key_exists($field, $allowed_fields)) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized field']);
            exit;
        }
        if ($allowed_fields[$field] === 'int') {
            $value = ($value === '' || $value === null) ? null : (int)$value;
        } else {
            $value = trim((string)$value);
        }
        $stmt = $dbco->prepare("UPDATE tickets SET {$field} = :val WHERE tickets_id = :ticket_id");
        $stmt->execute(['val' => $value, 'ticket_id' => $ticket_id]);
        echo json_encode(['success' => true]);
        exit;
    }

    // Close ticket
    elseif ($action === 'close_ticket') {
        $stmt = $dbco->prepare("SELECT t.tickets_id, t.tickets_token, t.tickets_email, t.tickets_first_name, t.tickets_last_name, t.tickets_subject, tm.teams_name, tm.teams_form_url FROM tickets t LEFT JOIN teams tm ON tm.teams_id = t.tickets_teams WHERE t.tickets_id = :ticket_id LIMIT 1");
        $stmt->execute(['ticket_id' => $ticket_id]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$ticket) {
            echo json_encode(['success' => false, 'message' => 'Ticket not found.']);
            exit;
        }
        $updateStmt = $dbco->prepare("UPDATE tickets SET tickets_status = 2, tickets_closing_date = NOW() WHERE tickets_id = :ticket_id");
        $updateStmt->execute(['ticket_id' => $ticket_id]);
        $rating_link = $opensupport_link."/form/". $ticket['teams_form_url'] ."/ticket/" . urlencode($ticket['tickets_token']);
        $client_name = trim($ticket['tickets_first_name'] . ' ' . $ticket['tickets_last_name']);
        $to = $ticket['tickets_email'];
        $subject = $t_mail['mail_subject'] . ($ticket['tickets_subject'] ?? '');
        $headers = "From: {$ticket['teams_name']} <no-reply@{$opensupport_domain}>\r\n" .
                   "Reply-To: no-reply@{$opensupport_domain}\r\n" .
                   "Content-Type: text/html; charset=UTF-8\r\n";

        $message = "
            <p>" . $t_mail['mail_hello'] . htmlspecialchars($client_name) . ",</p>
            <p>" . sprintf($t_mail['mail_resolved'], htmlspecialchars($ticket['tickets_subject'])) . "</p>
            <p>" . $t_mail['mail_feedback'] . "</p>
            <p><a href='" . htmlspecialchars($rating_link) . "' style='padding: 10px 15px; background: #007bff; color: #fff; text-decoration: none; border-radius: 4px; display: inline-block;'>" . $t_mail['mail_btn'] . "</a></p>
            <p>" . $t_mail['mail_regards'] . "<br>" . sprintf($t_mail['mail_team'], $ticket['teams_name']) ."</p>
        ";
        @mail($to, $subject, $message, $headers);

        echo json_encode(['success' => true]);
        exit;
    }
    
    else {
        echo json_encode(['success' => false, 'message' => 'Unknown action.']);
        exit;   
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error.']);
}
?>