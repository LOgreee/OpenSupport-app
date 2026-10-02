<?php
namespace OpenSupport\Api\V1;

use PDO;
use DateTime;
use Exception;
use OpenSupport\Api\Response;

class MembersController {
    private PDO $db;
    private array $team;

    public function __construct(PDO $db, array $team) {
        $this->db = $db;
        $this->team = $team;
    }

    /**
     * GET /api/v1/members
     */
    public function list(): void {
        $stmt = $this->db->prepare("SELECT u.users_id, u.users_first_name, u.users_last_name, u.users_email, tm.teams_members_position, tm.teams_members_groups, u.users_absence FROM teams_members tm JOIN users u ON tm.teams_members_user_id = u.users_id WHERE tm.teams_members_team_id = :team_id AND tm.teams_members_deleted = 0 ORDER BY u.users_first_name ASC");
        $stmt->execute(['team_id' => $this->team['teams_id']]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($rows)) {
            Response::success([]);
            return;
        }
        $members = [];
        $now = new DateTime();
        foreach ($rows as $row) {
            $userId = (int)$row['users_id'];
            $rawGroups = $row['teams_members_groups'] ?? '';
            $groups = json_decode($rawGroups, true);
            if (!is_array($groups)) {
                $groups = array_values(array_filter(array_map('trim', explode(',', $rawGroups))));
            }
            $absenceData = json_decode($row['users_absence'] ?? '', true);
            $isAbsent = false;
            $absencePeriod = null;
            if (is_array($absenceData) && !empty($absenceData['enabled']) && !empty($absenceData['start']) && !empty($absenceData['end'])) {
                try {
                    $start = new DateTime($absenceData['start']);
                    $end = new DateTime($absenceData['end']);
                    if ($now >= $start && $now <= $end) {
                        $isAbsent = true;
                        $absencePeriod = [
                            'start' => $absenceData['start'],
                            'end'   => $absenceData['end']
                        ];
                    }
                } catch (Exception $e) {
                    $isAbsent = false;
                    $absencePeriod = null;
                }
            }
            $members[] = [
                'id'         => $userId,
                'first_name' => $row['users_first_name'],
                'last_name'  => $row['users_last_name'],
                'email'      => $row['users_email'],
                'position'   => $row['teams_members_position'] ?? '',
                'groups'     => $groups,
                'absence'    => [
                    'is_absent' => $isAbsent,
                    'period'    => $absencePeriod
                ]
            ];
        }
        Response::success($members);
    }

    /**
     * POST /api/v1/members/invite
     */
    public function invite(array $payload): void {
        global $opensupport_link, $opensupport_domain;

        $email = filter_var(trim($payload['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        if (!$email) {
            Response::error('INVALID_EMAIL', 'A valid email address is required.', 422);
        }

        $position = strip_tags(trim($payload['position'] ?? 'Support Agent'));

        $stmtUser = $this->db->prepare("SELECT users_id FROM users WHERE users_email = :email LIMIT 1");
        $stmtUser->execute(['email' => $email]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $stmtCheck = $this->db->prepare("SELECT teams_members_id FROM teams_members WHERE teams_members_user_id = :uid AND teams_members_team_id = :tid AND teams_members_deleted = 0 LIMIT 1");
            $stmtCheck->execute(['uid' => $user['users_id'], 'tid' => $this->team['teams_id']]);
            if ($stmtCheck->fetch()) {
                Response::error('ALREADY_MEMBER', 'This user is already a member of this team.', 409);
            }

            $stmtInsert = $this->db->prepare("INSERT INTO teams_members (teams_members_team_id, teams_members_user_id, teams_members_position, teams_members_deleted) VALUES (:tid, :uid, :pos, 0)");
            $stmtInsert->execute([
                'tid' => $this->team['teams_id'],
                'uid' => $user['users_id'],
                'pos' => $position
            ]);
        }

        if (function_exists('renderEmailLayout')) {
            $inviteUrl = rtrim($opensupport_link, '/') . "/dashboard/login";
            $teamName = !empty($this->team['teams_name']) ? htmlspecialchars($this->team['teams_name']) : 'OpenSupport';
            
            $mailSubject = "Invitation to join team " . $teamName;
            $mailBody = '
                <p style="margin: 0 0 16px 0;">Hello,</p>
                <p style="margin: 0 0 16px 0;">You have been invited to join the <strong>' . $teamName . '</strong> workspace on OpenSupport as <em>' . htmlspecialchars($position) . '</em>.</p>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 24px auto;">
                    <tr>
                        <td align="center" style="border-radius: 8px; background-color: ' . $accentColor . ';">
                            <a href="' . htmlspecialchars($inviteUrl) . '" target="_blank" rel="noopener noreferrer" style="display: inline-block; padding: 13px 28px; font-size: 14px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 8px;">Access Workspace</a>
                        </td>
                    </tr>
                </table>
            ';

            $mailHtml = renderEmailLayout([
                'team'            => $this->team,
                'recipient_email' => $email,
                'body_content'    => $mailBody,
                'privacy_token'   => null,
                'subject'         => $mailSubject
            ]);
            sendOpenSupportMail($email, $mail_subject, $mail_html, (!empty($this->team['teams_name']) ? $this->team['teams_name'] : 'OpenSupport'));
        }

        Response::success([
            'email'    => $email,
            'position' => $position,
            'message'  => 'Invitation processed successfully.'
        ], 201);
    }
}