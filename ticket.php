<?php require("config.php");
header_remove("X-Frame-Options");
header("Content-Security-Policy: frame-ancestors *");

// Language manager
$translations = [
    'fr' => [
        'title' => 'Support',
        'title_feedback' => 'Votre avis nous intéresse !',
        'note' => 'Comment évaluez-vous la résolution de votre ticket ?',
        'commentary' => 'Commentaire',
        'send' => 'Envoyer',
        'title_thanks' => 'Merci pour votre retour !',
        'title_closed' => "Votre ticket a été cloturé<br> ou il n'existe pas.",
        'contact_support' => "Contacter le support",
        'form_error' => 'Une erreur est survenue.',
        'form_error2' => 'Veuillez remplir tous les champs obligatoires désignés par une astérisque (*).'
    ],
    'en' => [
        'title' => 'Support',
        'title_feedback' => 'We’d love to hear your feedback!',
        'note' => 'How do you rate the resolution of your ticket?',
        'commentary' => 'Commentary',
        'send' => 'Send',
        'title_thanks' => 'Thanks for your feedback!',
        'title_closed' => "Your ticket has been closed<br> or does not exist.",
        'contact_support' => "Contact support",
        'form_error' => 'An error occurred.',
        'form_error2' => 'Please fill in all mandatory fields marked with an asterisk (*).'
    ],
    'es' => [
        'title' => 'Soporte',
        'title_feedback' => '¡Valoramos sus comentarios!',
        'note' => '¿Cómo califica la resolución de su solicitud?',
        'commentary' => 'Commentaire',
        'send' => 'Enviar',
        'title_thanks' => '¡Gracias por sus comentarios!',
        'title_closed' => "Su ticket ha sido cerrado<br> o no existe.",
        'contact_support' => "Contacta con soporte",
        'form_error' => 'Se ha producido un error.',
        'form_error2' => 'Por favor, rellene todos los campos obligatorios marcados con un asterisco (*).'
    ]
];
$t = $translations[$lang];

//Get team
$env_slug = isset($_GET['env']) ? strip_tags($_GET['env']) : '';
$ticket_token = isset($_GET['token']) ? strip_tags($_GET['token']) : '';
$stmt = $dbco->prepare("SELECT `teams_id`, `teams_name`, `teams_logo`, `teams_color`, `teams_privacy_policy_url` FROM `teams` WHERE `teams_form_url` = :env AND `teams_deleted` = 0 LIMIT 1;");
$stmt->execute(['env' => $env_slug]);
$team = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$team) {
    header('HTTP/1.0 404 Not Found');
    exit;
} else {
    $team_id = $team['teams_id'];
}

//Get ticket
$stmtTicket = $dbco->prepare("SELECT `tickets_id`, `tickets_status`, `tickets_feedback_date` FROM `tickets` WHERE `tickets_token` = :token AND `tickets_teams` = :team_id LIMIT 1;");
$stmtTicket->execute(['token' => $ticket_token, 'team_id' => $team['teams_id']]);
$ticket = $stmtTicket->fetch(PDO::FETCH_ASSOC);
$is_eligible_for_feedback = false;
$is_ticket_active = false;
if ($ticket) {
    $is_ticket_active = ($ticket['tickets_status'] < 2);
    $is_eligible_for_feedback = ($ticket['tickets_status'] == 2 && is_null($ticket['tickets_feedback_date']));
}

$form_feedback = "";
$is_feedbackthanks = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_eligible_for_feedback) {
    //Survey form process
    $rate = isset($_POST['rate']) ? (int)$_POST['rate'] : 0;
    $commentary = !empty($_POST['commentary']) ? trim(strip_tags($_POST['commentary'])) : null;
    $ticket_token_post = isset($_POST['ticket_token']) ? $_POST['ticket_token'] : '';
    if ($ticket_token_post === $ticket_token && $rate >= 1 && $rate <= 5) {
        // Update ticket
        $updateStmt = $dbco->prepare("UPDATE `tickets` SET `tickets_rating` = :rating, `tickets_feedback` = :feedback, `tickets_feedback_date` = CURRENT_TIMESTAMP() WHERE `tickets_id` = :ticket_id");
        $success = $updateStmt->execute([
            'rating' => $rate,
            'feedback' => $commentary,
            'ticket_id' => $ticket['tickets_id']
        ]);
        
        if ($success) {
            $is_feedbackthanks = true;
            $is_eligible_for_feedback = false;
        } else {
            $form_feedback = $t['form_error'];
        }
    } else {
        $form_feedback = $t['form_error2'];
    }
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['title'] ?> - <?= htmlspecialchars($team['teams_name']) ?></title>
    <link rel="icon" type="image/x-icon" href="<?= $opensupport_link?>/src/opensupport_assets/opensupport_icon.svg">
    <link rel="stylesheet" href="<?= $opensupport_link?>/src/css/main.css">
    <style>
        :root {
            --tertiary-bg-color: <?php echo htmlspecialchars($team['teams_color'] ?: '#4f46e5'); ?>;
        }
    </style>
</head>
<body class="form">
    
    <div class="action_bar">
        <?php require("src/php/language_selector.php"); ?>
    </div>

    <main>
        <?php if(!$is_eligible_for_feedback && $is_ticket_active):?>
        <?php // Last client visit date update
        if ($_SESSION['connected'] != "true") {
            try {
                $updateVisitStmt = $dbco->prepare("UPDATE `tickets` SET `tickets_client_last_visit_date` = CURRENT_TIMESTAMP() WHERE `tickets_id` = :ticket_id");
                $updateVisitStmt->execute(['ticket_id' => $ticket['tickets_id']]);
            } catch (PDOException $e) {
            }
        }?>
        <div class="box chat">
            <?php $ticket_id = $ticket['tickets_id'];
            require("src/php/chat.php");?>
        </div>
        
        <?php elseif($is_eligible_for_feedback):?>
        <div class="box">
            <header>
                <?php if($team['teams_logo']=="1"): ?>
                    <img src="<?= $opensupport_link?>/up/teams/<?= $team['teams_id'] ?>.webp" alt="Logo <?= htmlspecialchars($team['teams_name']) ?>">
                <?php endif; ?>
                <h1><?= $t['title_feedback'] ?></h1>
            </header>

            <!-- Formulaire -->
            <form action="" method="POST">
                <input type="hidden" name="env_slug" value="<?= htmlspecialchars($env_slug) ?>">
                <input type="hidden" name="ticket_token" value="<?= htmlspecialchars($ticket_token) ?>">
                
                <?php if($form_feedback!=""):?>
                <p class="alert"><?= $form_feedback ?></p>
                <?php endif;?>
                
                <div>
                    <label class="rating-label"><?= $t['note'] ?> *</label>
                    <div class="rate">
                        <input type="radio" id="star5" name="rate" value="5" />
                        <label for="star5" title="text">5 stars</label>
                        <input type="radio" id="star4" name="rate" value="4" />
                        <label for="star4" title="text">4 stars</label>
                        <input type="radio" id="star3" name="rate" value="3" />
                        <label for="star3" title="text">3 stars</label>
                        <input type="radio" id="star2" name="rate" value="2" />
                        <label for="star2" title="text">2 stars</label>
                        <input type="radio" id="star1" name="rate" value="1" />
                        <label for="star1" title="text">1 star</label>
                    </div>
                </div>

                <div>
                    <label><?= $t['commentary']?></label>
                    <textarea name="commentary"></textarea>
                </div>

                <button type="submit"><?= $t['send'] ?></button>
            </form>
        </div>
        
        <?php else: ?>
        <div class="box">
            <header>
                <?php if(!empty($team['teams_logo_path'])): ?>
                    <img src="<?= htmlspecialchars($team['teams_logo_path']) ?>" alt="Logo <?= htmlspecialchars($team['teams_name']) ?>">
                <?php endif; ?>
                <h1><?php if($is_feedbackthanks){echo $t['title_thanks'];} else {echo $t['title_closed'];} ?></h1>
            </header>

            <a class="btn" href="../../<?= $env_slug ?>"><?= $t['contact_support'] ?></a>
        </div>
        
        <?php endif; ?>
    </main>
    
    <footer>&copy; <?= date("Y"); ?> <?= htmlspecialchars($team['teams_name']) ?><br>Powered by OpenSupport</footer>
    <script src="<?= $opensupport_link?>/src/js/main.js"></script>
</body>
</html>