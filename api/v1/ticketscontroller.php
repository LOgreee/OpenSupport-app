<?php
namespace OpenSupport\Api\V1;

use PDO;
use OpenSupport\Api\Response;

class TicketsController {
    private PDO $db;
    private array $team;

    public function __construct(PDO $db, array $team) {
        $this->db = $db;
        $this->team = $team;
    }

    /**
     * GET /api/v1/tickets
     */
    public function list(): void {
        $status = isset($_GET['status']) ? (int)$_GET['status'] : null;
        $assignedTo = isset($_GET['assigned_to']) ? (int)$_GET['assigned_to'] : null;
        $priority = isset($_GET['priority']) ? (int)$_GET['priority'] : null;

        $limit = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $query = "SELECT tickets_id, tickets_token, tickets_first_name, tickets_last_name, tickets_email, tickets_subject, tickets_source, tickets_description, tickets_additionnal_fields, tickets_status, tickets_priority, tickets_assigned_to, tickets_creation_date FROM tickets WHERE tickets_teams = :team_id";

        $params = ['team_id' => $this->team['teams_id']];

        if ($status !== null) {
            $query .= " AND tickets_status = :status";
            $params['status'] = $status;
        }
        if ($assignedTo !== null) {
            $query .= " AND tickets_assigned_to = :assigned_to";
            $params['assigned_to'] = $assignedTo;
        }
        if ($priority !== null) {
            $query .= " AND tickets_priority = :priority";
            $params['priority'] = $priority;
        }

        $query .= " ORDER BY tickets_creation_date DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $val) {
            $stmt->bindValue(":$key", $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tickets = [];

        foreach ($rows as $row) {
            $tickets[] = [
                'id' => (int)$row['tickets_id'],
                'token' => $row['tickets_token'],
                'client' => [
                    'first_name' => $row['tickets_first_name'],
                    'last_name'  => $row['tickets_last_name'],
                    'email'      => $row['tickets_email']
                ],
                'subject'       => $row['tickets_subject'],
                'description'   => $row['tickets_description'],
                'status'        => (int)$row['tickets_status'],
                'priority'      => (int)$row['tickets_priority'],
                'assigned_to'   => $row['tickets_assigned_to'] !== null ? (int)$row['tickets_assigned_to'] : null,
                'source'        => $row['tickets_source'],
                'created_at'    => $row['tickets_creation_date'],
                'custom_fields' => json_decode($row['tickets_additionnal_fields'] ?? '[]', true) ?: []
            ];
        }

        Response::success($tickets);
    }

    /**
     * GET /api/v1/tickets/{id}
     */
    public function get(int $ticketId): void {
        $stmt = $this->db->prepare("SELECT tickets_id, tickets_token, tickets_first_name, tickets_last_name, tickets_email, tickets_subject, tickets_source, tickets_description, tickets_additionnal_fields, tickets_status, tickets_priority, tickets_assigned_to, tickets_admin_notes, tickets_creation_date FROM tickets WHERE tickets_id = :ticket_id AND tickets_teams = :team_id LIMIT 1");
        $stmt->execute(['ticket_id' => $ticketId, 'team_id' => $this->team['teams_id']]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ticket) {
            Response::error('TICKET_NOT_FOUND', 'Ticket not found or deleted.', 404);
        }

        // Retrieve messages
        $stmtMsg = $this->db->prepare("SELECT messages_id, messages_sender_type, messages_content, messages_attachements, messages_creation_date FROM messages WHERE messages_ticket_id = :ticket_id AND messages_deleted_at IS NULL ORDER BY messages_creation_date ASC");
        $stmtMsg->execute(['ticket_id' => $ticketId]);
        $rawMessages = $stmtMsg->fetchAll(PDO::FETCH_ASSOC);

        $messages = [];
        foreach ($rawMessages as $msg) {
            $messages[] = [
                'id'          => (int)$msg['messages_id'],
                'sender_type' => (int)$msg['messages_sender_type'],
                'content'     => $msg['messages_content'],
                'attachments' => json_decode($msg['messages_attachements'] ?? '[]', true) ?: [],
                'created_at'  => $msg['messages_creation_date']
            ];
        }

        Response::success([
            'id'    => (int)$ticket['tickets_id'],
            'token' => $ticket['tickets_token'],
            'client' => [
                'first_name' => $ticket['tickets_first_name'],
                'last_name'  => $ticket['tickets_last_name'],
                'email'      => $ticket['tickets_email']
            ],
            'subject'       => $ticket['tickets_subject'],
            'description'   => $ticket['tickets_description'],
            'status'        => (int)$ticket['tickets_status'],
            'priority'      => (int)$ticket['tickets_priority'],
            'assigned_to'   => $ticket['tickets_assigned_to'] !== null ? (int)$ticket['tickets_assigned_to'] : null,
            'admin_notes'   => $ticket['tickets_admin_notes'],
            'source'        => $row['tickets_source'],
            'created_at'    => $ticket['tickets_creation_date'],
            'custom_fields' => json_decode($ticket['tickets_additionnal_fields'] ?? '[]', true) ?: [],
            'messages'      => $messages
        ]);
    }

    /**
     * POST /api/v1/tickets
     */
    public function create(array $payload): void {
        global $opensupport_link, $opensupport_domain;

        $required = ['first_name', 'last_name', 'email', 'subject', 'description'];
        foreach ($required as $field) {
            if (empty(trim((string)($payload[$field] ?? '')))) {
                Response::error('MISSING_PARAMETER', "Field '{$field}' is required.", 422);
            }
        }

        $email = filter_var($payload['email'], FILTER_VALIDATE_EMAIL);
        if (!$email) {
            Response::error('INVALID_EMAIL', 'The provided email address is invalid.', 422);
        }

        $firstName = strip_tags(trim($payload['first_name']));
        $lastName = strip_tags(trim($payload['last_name']));
        $subject = strip_tags(trim($payload['subject']));
        $description = strip_tags(trim($payload['description']));
        $priority = isset($payload['priority']) ? max(-2, min(2, (int)$payload['priority'])) : 0;
        $adminNotes = !empty($payload['admin_notes']) ? strip_tags(trim($payload['admin_notes'])) : null;
        $assignedTo = !empty($payload['assigned_to']) ? (int)$payload['assigned_to'] : null;
        $notifyClient = $payload['notify_client'] ?? true;
        $source = $payload['source'] ?? "api";

        if ($assignedTo !== null) {
            $stmtUser = $this->db->prepare("SELECT teams_members_id FROM teams_members WHERE teams_members_user_id = :uid AND teams_members_team_id = :tid AND teams_members_deleted = 0 LIMIT 1");
            $stmtUser->execute(['uid' => $assignedTo, 'tid' => $this->team['teams_id']]);
            if (!$stmtUser->fetch()) {
                $assignedTo = null;
            }
        }

        $customFields = [];
        if (isset($payload['custom_fields']) && is_array($payload['custom_fields'])) {
            foreach ($payload['custom_fields'] as $cf) {
                if (isset($cf['question'], $cf['answer'])) {
                    $customFields[] = [
                        'question' => strip_tags((string)$cf['question']),
                        'answer'   => strip_tags((string)$cf['answer'])
                    ];
                }
            }
        }

        $token = random_str(50);
        $jsonFields = json_encode($customFields, JSON_UNESCAPED_UNICODE);

        $stmt = $this->db->prepare("INSERT INTO tickets (tickets_teams, tickets_token, tickets_email, tickets_first_name, tickets_last_name, tickets_subject, tickets_subject, tickets_description, tickets_additionnal_fields, tickets_assigned_to, tickets_status, tickets_admin_notes, tickets_priority, tickets_creation_date) VALUES (:team_id, :token, :email, :first_name, :last_name, :subject, :source, :description, :additional_fields, :assigned_to, '0', :admin_notes, :priority, CURRENT_TIMESTAMP)");

        $stmt->execute([
            'team_id'           => $this->team['teams_id'],
            'token'             => $token,
            'email'             => $email,
            'first_name'        => $firstName,
            'last_name'         => $lastName,
            'subject'           => $subject,
            'source'            => $source,
            'description'       => $description,
            'additional_fields' => $jsonFields,
            'assigned_to'       => $assignedTo,
            'admin_notes'       => $adminNotes,
            'priority'          => $priority
        ]);

        $newId = (int)$this->db->lastInsertId();
        $chatLink = rtrim($opensupport_link, '/') . "/form/" . htmlspecialchars($this->team['teams_form_url']) . "/ticket/" . htmlspecialchars($token);

        // Client notification using renderEmailLayout
        if ($notifyClient && function_exists('renderEmailLayout')) {
            $accentColor = !empty($this->team['teams_color']) ? htmlspecialchars($this->team['teams_color']) : '#635bff';
            $mailSubject = "Support: " . $subject;
            $mailBody = '
                <p style="margin: 0 0 16px 0;">Hello <strong>' . htmlspecialchars($firstName) . '</strong>,</p>
                <p style="margin: 0 0 16px 0;">Your support request has been successfully registered:</p>
                <div style="background-color: #f8fafc; border-left: 4px solid ' . $accentColor . '; border-radius: 0 8px 8px 0; padding: 16px 20px; margin: 20px 0; font-size: 14px; line-height: 22px; color: #1e293b;">
                    <strong style="display: block; margin-bottom: 6px; color: #0f172a;">' . htmlspecialchars($subject) . '</strong>
                    ' . nl2br(htmlspecialchars($description)) . '
                </div>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 24px auto;">
                    <tr>
                        <td align="center" style="border-radius: 8px; background-color: ' . $accentColor . ';">
                            <a href="' . htmlspecialchars($chatLink) . '" target="_blank" rel="noopener noreferrer" style="display: inline-block; padding: 13px 28px; font-size: 14px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 8px;">View Request</a>
                        </td>
                    </tr>
                </table>
            ';

            $mailHtml = renderEmailLayout([
                'team'            => $this->team,
                'recipient_email' => $email,
                'body_content'    => $mailBody,
                'privacy_token'   => $token,
                'subject'         => $mailSubject
            ]);

            $headers = "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\n";
            $headers .= "From: " . (!empty($this->team['teams_name']) ? '=?UTF-8?B?' . base64_encode($this->team['teams_name']) . '?=' : 'OpenSupport') . " <noreply@" . ($opensupport_domain ?? 'opensupport.local') . ">\r\n";
            @mail($email, '=?UTF-8?B?' . base64_encode($mailSubject) . '?=', $mailHtml, $headers);
        }

        Response::success([
            'id'           => $newId,
            'token'        => $token,
            'tracking_url' => $chatLink,
            'created_at'   => date('Y-m-d H:i:s')
        ], 201);
    }

    /**
     * PATCH /api/v1/tickets/{id}
     */
    public function update(int $ticketId, array $payload): void {
        $stmtCheck = $this->db->prepare("SELECT tickets_id FROM tickets WHERE tickets_id = :id AND tickets_teams = :tid LIMIT 1");
        $stmtCheck->execute(['id' => $ticketId, 'tid' => $this->team['teams_id']]);
        if (!$stmtCheck->fetch()) {
            Response::error('TICKET_NOT_FOUND', 'Ticket not found or deleted.', 404);
        }

        $fields = [];
        $params = ['ticket_id' => $ticketId, 'team_id' => $this->team['teams_id']];

        if (array_key_exists('status', $payload)) {
            $status = (int)$payload['status'];
            if (!in_array($status, [0, 1, 2], true)) {
                Response::error('INVALID_STATUS', 'Status must be 0 (new), 1 (in progress), or 2 (closed).', 422);
            }
            $fields[] = "tickets_status = :status";
            $params['status'] = $status;
            if ($status === 2) {
                $fields[] = "tickets_closing_date = CURRENT_TIMESTAMP";
            }
        }

        if (array_key_exists('priority', $payload)) {
            $priority = max(-2, min(2, (int)$payload['priority']));
            $fields[] = "tickets_priority = :priority";
            $params['priority'] = $priority;
        }

        if (array_key_exists('assigned_to', $payload)) {
            $assignedTo = $payload['assigned_to'] !== null ? (int)$payload['assigned_to'] : null;
            if ($assignedTo !== null) {
                $stmtUser = $this->db->prepare("SELECT teams_members_id FROM teams_members WHERE teams_members_user_id = :uid AND teams_members_team_id = :tid AND teams_members_deleted = 0 LIMIT 1");
                $stmtUser->execute(['uid' => $assignedTo, 'tid' => $this->team['teams_id']]);
                if (!$stmtUser->fetch()) {
                    Response::error('INVALID_ASSIGNEE', 'The specified user does not belong to this team.', 422);
                }
            }
            $fields[] = "tickets_assigned_to = :assigned_to";
            $params['assigned_to'] = $assignedTo;
        }

        if (array_key_exists('admin_notes', $payload)) {
            $fields[] = "tickets_admin_notes = :admin_notes";
            $params['admin_notes'] = strip_tags((string)$payload['admin_notes']);
        }

        if (empty($fields)) {
            Response::error('NO_CHANGES', 'No editable fields were provided.', 422);
        }

        $query = "UPDATE tickets SET " . implode(', ', $fields) . " WHERE tickets_id = :ticket_id AND tickets_teams = :team_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        Response::success([
            'id'         => $ticketId,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * POST /api/v1/tickets/{id}/messages
     */
    public function addMessage(int $ticketId, array $payload): void {
        global $opensupport_link, $opensupport_domain;
        $stmtTicket = $this->db->prepare("SELECT tickets_id, tickets_token, tickets_email, tickets_first_name, tickets_subject FROM tickets WHERE tickets_id = :id AND tickets_teams = :tid LIMIT 1");
        $stmtTicket->execute(['id' => $ticketId, 'tid' => $this->team['teams_id']]);
        $ticket = $stmtTicket->fetch(PDO::FETCH_ASSOC);
        if (!$ticket) {
            Response::error('TICKET_NOT_FOUND', 'Ticket not found or deleted.', 404);
        }

        if (!isset($payload['sender_type']) || !in_array((int)$payload['sender_type'], [0, 1], true)) {
            Response::error('INVALID_SENDER_TYPE', 'sender_type must be 0 (Support) or 1 (Client).', 422);
        }
        $content = trim((string)($payload['content'] ?? ''));
        if ($content === '') {
            Response::error('MISSING_CONTENT', 'The message content cannot be empty.', 422);
        }
        $senderType = (int)$payload['sender_type'];
        $notify = $payload['notify'] ?? true;

        $stmtMsg = $this->db->prepare("INSERT INTO messages (
            messages_ticket_id, messages_sender_type, messages_content, messages_attachements, messages_creation_date
        ) VALUES (
            :ticket_id, :sender_type, :content, '[]', CURRENT_TIMESTAMP
        )");
        $stmtMsg->execute([
            'ticket_id'   => $ticketId,
            'sender_type' => $senderType,
            'content'     => strip_tags($content)
        ]);
        $messageId = (int)$this->db->lastInsertId();
        if ($senderType === 0) {
            $this->db->prepare("UPDATE tickets SET tickets_status = 1 WHERE tickets_id = :id AND tickets_status = 0")
                     ->execute(['id' => $ticketId]);
        }
        if ($notify && function_exists('renderEmailLayout')) {
            $accentColor = !empty($this->team['teams_color']) ? htmlspecialchars($this->team['teams_color']) : '#635bff';
            $chatLink = rtrim($opensupport_link, '/') . "/form/" . htmlspecialchars($this->team['teams_form_url']) . "/ticket/" . htmlspecialchars($ticket['tickets_token']);
            $mailSubject = ($senderType === 0 ? "Re: " : "New message: ") . $ticket['tickets_subject'];
            $senderLabel = ($senderType === 0) ? (!empty($this->team['teams_name']) ? htmlspecialchars($this->team['teams_name']) : 'Support') : htmlspecialchars($ticket['tickets_first_name']);
            $mailBody = '
                <p style="margin: 0 0 16px 0;">Hello,</p>
                <p style="margin: 0 0 16px 0;">A new message was posted by <strong>' . $senderLabel . '</strong>:</p>
                <div style="background-color: #f8fafc; border-left: 4px solid ' . $accentColor . '; border-radius: 0 8px 8px 0; padding: 16px 20px; margin: 20px 0; font-size: 14px; line-height: 22px; color: #1e293b;">
                    ' . nl2br(htmlspecialchars($content)) . '
                </div>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 24px auto;">
                    <tr>
                        <td align="center" style="border-radius: 8px; background-color: ' . $accentColor . ';">
                            <a href="' . htmlspecialchars($chatLink) . '" target="_blank" rel="noopener noreferrer" style="display: inline-block; padding: 13px 28px; font-size: 14px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 8px;">View Discussion</a>
                        </td>
                    </tr>
                </table>
            ';
            $recipient = ($senderType === 0) ? $ticket['tickets_email'] : ($this->team['teams_notification_email'] ?? null);
            if ($recipient) {
                $mailHtml = renderEmailLayout([
                    'team'            => $this->team,
                    'recipient_email' => $recipient,
                    'body_content'    => $mailBody,
                    'privacy_token'   => $ticket['tickets_token'],
                    'subject'         => $mailSubject
                ]);
                $headers = "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\n";
                $headers .= "From: " . (!empty($this->team['teams_name']) ? '=?UTF-8?B?' . base64_encode($this->team['teams_name']) . '?=' : 'OpenSupport') . " <noreply@" . ($opensupport_domain ?? 'opensupport.local') . ">\r\n";
                @mail($recipient, '=?UTF-8?B?' . base64_encode($mailSubject) . '?=', $mailHtml, $headers);
            }
        }
        Response::success([
            'message_id' => $messageId,
            'ticket_id'  => $ticketId,
            'created_at' => date('Y-m-d H:i:s')
        ], 201);
    }
}