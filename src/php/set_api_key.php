<?php
require("../../config.php");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Unauthorized method.']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized access.']);
    exit;
}

$team_id = (int)($_POST['team_id']);

if ($team_id == "") {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No required parameters.']);
    exit;
}

try {
    $stmtAuth = $dbco->prepare("SELECT tm.teams_members_id FROM teams_members tm WHERE tm.teams_members_team_id = :team_id AND tm.teams_members_user_id = :user_id AND (tm.teams_members_deleted = 0 OR tm.teams_members_deleted IS NULL) AND tm.teams_members_join_date IS NOT NULL LIMIT 1");
    $stmtAuth->execute(['team_id' => $team_id, 'user_id' => $_SESSION['user_id']]);
    if (!$stmtAuth->fetch()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Permissions insuffisantes pour cette équipe.']);
        exit;
    }
    $new_api_key = random_str(64);
    $stmtUpdate = $dbco->prepare("UPDATE teams SET teams_api_token = :api_token WHERE teams_id = :team_id");
    $stmtUpdate->execute(['api_token' => $new_api_key, 'team_id'   => $team_id]);
    echo json_encode(['success' => true, 'api_key' => $new_api_key]);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error.']);
    exit;
}
?>