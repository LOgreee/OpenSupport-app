<?php http_response_code(200);
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/response.php';
require_once __DIR__ . '/v1/ticketscontroller.php';
require_once __DIR__ . '/v1/memberscontroller.php';
require_once __DIR__ . '/v1/privacycontroller.php';

use OpenSupport\Api\Response;
use OpenSupport\Api\V1\TicketsController;
use OpenSupport\Api\V1\MembersController;
use OpenSupport\Api\V1\PrivacyController;

// 1. Headers CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// 2. Bearer Token extraction
$headers = function_exists('getallheaders') ? getallheaders() : [];
$authHeader = $headers['Authorization'] 
    ?? $headers['authorization'] 
    ?? $_SERVER['HTTP_AUTHORIZATION'] 
    ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] 
    ?? '';

$token = null;
if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    $token = $matches[1];
}

if (!$token) {
    Response::error('UNAUTHORIZED', 'Missing or invalid Authorization header.', 401);
}

// 3. Authenticate team
$stmtTeam = $dbco->prepare("SELECT teams_id, teams_name, teams_form_url, teams_logo, teams_color, teams_privacy_policy_url FROM teams WHERE teams_api_token = :token AND teams_deleted = 0 LIMIT 1");
$stmtTeam->execute(['token' => $token]);
$team = $stmtTeam->fetch(PDO::FETCH_ASSOC);

if (!$team) {
    Response::error('FORBIDDEN', 'Invalid API token or team not found.', 403);
}

// 4. Payload decoding
$rawInput = file_get_contents('php://input');
$body = json_decode($rawInput, true);
if (!is_array($body)) {
    $body = [];
}

// 5. URI Normalization
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = preg_replace('#^.*?/api#', '', $requestUri);
$path = '/' . trim($path, '/');

// 6. Dispatching to controller
$ticketsController = new TicketsController($dbco, $team);
$membersController = new MembersController($dbco, $team);
$privacyController = new PrivacyController($dbco, $team);

// --- TICKETS ---

// GET /v1/tickets
if ($method === 'GET' && $path === '/v1/tickets') {
    $ticketsController->list();
}

// GET /v1/tickets/{id}
if ($method === 'GET' && preg_match('#^/v1/tickets/(\d+)$#', $path, $m)) {
    $ticketsController->get((int)$m[1]);
}

// POST /v1/tickets
if ($method === 'POST' && $path === '/v1/tickets') {
    $ticketsController->create($body);
}

// PATCH /v1/tickets/{id}
if ($method === 'PATCH' && preg_match('#^/v1/tickets/(\d+)$#', $path, $m)) {
    $ticketsController->update((int)$m[1], $body);
}

// POST /v1/tickets/{id}/messages
if ($method === 'POST' && preg_match('#^/v1/tickets/(\d+)/messages$#', $path, $m)) {
    $ticketsController->addMessage((int)$m[1], $body);
}

// --- MEMBERS ---

// GET /v1/members
if ($method === 'GET' && $path === '/v1/members') {
    $membersController->list();
}

// POST /v1/members/invite
if ($method === 'POST' && $path === '/v1/members/invite') {
    $membersController->invite($body);
}

// --- PRIVACY (RGPD) ---

// POST /v1/privacy/anonymize
if ($method === 'POST' && $path === '/v1/privacy/anonymize') {
    $privacyController->anonymize($body);
}

// Route non trouvée
Response::error('ROUTE_NOT_FOUND', "The requested route '{$path}' does not exist.", 404);