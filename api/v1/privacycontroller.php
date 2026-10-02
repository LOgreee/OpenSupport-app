<?php
namespace OpenSupport\Api\V1;

use PDO;
use OpenSupport\Api\Response;

class PrivacyController {
    private PDO $db;
    private array $team;

    public function __construct(PDO $db, array $team) {
        $this->db = $db;
        $this->team = $team;
    }

    /**
     * POST /api/v1/privacy/anonymize
     */
    public function anonymize(array $payload): void {
        $email = filter_var(trim($payload['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        if (!$email) {
            Response::error('INVALID_EMAIL', 'A valid client email address is required.', 422);
        }

        $confirm = trim($payload['confirm'] ?? '');
        if ($confirm !== 'DELETE') {
            Response::error('CONFIRMATION_REQUIRED', "You must provide confirm: 'DELETE' to execute anonymization.", 422);
        }

        // 1. Récupérer les tickets du client pour cette équipe
        $stmtTickets = $this->db->prepare("SELECT tickets_id FROM tickets WHERE tickets_email = :email AND tickets_teams = :tid");
        $stmtTickets->execute(['email' => $email, 'tid' => $this->team['teams_id']]);
        $ticketIds = $stmtTickets->fetchAll(PDO::FETCH_COLUMN);

        if (empty($ticketIds)) {
            Response::error('NO_RECORDS_FOUND', 'No tickets found matching this email address for your team.', 404);
        }

        $inClause = implode(',', array_map('intval', $ticketIds));

        // 2. Transaction pour garantir la cohérence
        $this->db->beginTransaction();
        try {
            // Anonymiser les tickets (Nom, Prénom, Email, Champs additionnels)
            $stmtAnonTickets = $this->db->prepare("UPDATE tickets SET 
                tickets_first_name = 'Anonyme',
                tickets_last_name = 'Anonyme',
                tickets_email = 'anonymized@opensupport.local',
                tickets_description = '[Description supprimée - RGPD]',
                tickets_additionnal_fields = '[]'
                WHERE tickets_id IN ($inClause)");
            $stmtAnonTickets->execute();

            // Purger et anonymiser les messages associés
            $stmtAnonMsgs = $this->db->prepare("UPDATE messages SET 
                messages_content = '[Message supprimé - Demande d\'oubli RGPD]',
                messages_attachements = '[]'
                WHERE messages_ticket_id IN ($inClause)");
            $stmtAnonMsgs->execute();

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            Response::error('TRANSACTION_FAILED', 'Failed to anonymize client records: ' . $e->getMessage(), 500);
        }

        Response::success([
            'message'          => 'All personal data associated with this email has been anonymized.',
            'affected_tickets' => count($ticketIds)
        ]);
    }
}