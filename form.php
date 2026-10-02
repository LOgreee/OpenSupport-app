<?php require("config.php");
header_remove("X-Frame-Options");
header("Content-Security-Policy: frame-ancestors *");

// Language manager
$translations = [
    'fr' => [
        'title' => 'Support',
        'subtitle' => 'Une équipe à votre écoute.',
        'email' => 'Adresse email',
        'firstname' => 'Prénom',
        'lastname' => 'Nom',
        'select_default_option' => 'Sélectionnez une option',
        'subject' => 'Objet',
        'description' => 'Description de votre problème',
        'gdpr' => 'J\'accepte que mes données soient traitées dans le cadre de cette demande de support.',
        'warning' => '⚠️ Attention :<br>Ne transmettez jamais d\'informations sensibles (mots de passe, numéros de carte) via ce formulaire.',
        'submit' => 'Lancer le chat',
        'privacy_policy' => 'Politique de confidentialité',
        'form_error' => 'Veuillez remplir tous les champs obligatoires désignés par une astérisque (*).',
        'greeting' => 'Bonjour :firstname,',
        'intro' => 'Votre demande d\'assistance a bien été enregistrée par notre équipe :',
        'track_info' => 'Vous pouvez suivre son avancement en direct et répondre aux messages de l\'équipe via notre espace sécurisé :',
        'btn_track' => 'Accéder au suivi de ma demande',
        'fallback_link' => 'Si le bouton ne s\'affiche pas correctement, vous pouvez utiliser le lien suivant :'
    ],
    'en' => [
        'title' => 'Support',
        'subtitle' => 'Our team is here to help.',
        'email' => 'Email Address',
        'firstname' => 'First Name',
        'lastname' => 'Last Name',
        'select_default_option' => 'Select an option',
        'subject' => 'Subject',
        'description' => 'Describe your issue',
        'gdpr' => 'I agree to have my data processed for this support request.',
        'warning' => '⚠️ Warning:<br>Never share sensitive information (passwords, credit card numbers) in this form.',
        'submit' => 'Start Chat',
        'privacy_policy' => 'Privacy policy',
        'form_error' => 'Please fill in all mandatory fields marked with an asterisk (*).',
        'greeting' => 'Hello :firstname,',
        'intro' => 'Your support request has been successfully received by our team:',
        'track_info' => 'You can track its progress live and reply to team messages via our secure portal:',
        'btn_track' => 'Track my support request',
        'fallback_link' => 'If the button does not display properly, you can use the following link:'
    ],
    'es' => [
        'title' => 'Soporte',
        'subtitle' => 'Nuestro equipo está aquí para ayudar.',
        'email' => 'Correo electrónico',
        'firstname' => 'Nombre',
        'lastname' => 'Apellido',
        'select_default_option' => 'Seleccione una opción',
        'subject' => 'Asunto',
        'description' => 'Descripción del problema',
        'gdpr' => 'Acepto que mis datos sean procesados para esta solicitud de soporte.',
        'warning' => '⚠️ Atención:<br>Nunca comparta información sensible (contraseñas, tarjetas) a través de este formulario.',
        'submit' => 'Iniciar chat',
        'privacy_policy' => 'Política de privacidad',
        'form_error' => 'Por favor, rellene todos los campos obligatorios marcados con un asterisco (*).',
        'greeting' => 'Hola :firstname,',
        'intro' => 'Nuestro equipo ha registrado correctamente su solicitud de asistencia:',
        'track_info' => 'Puede seguir su evolución en directo y responder a los mensajes del equipo a través de nuestro portal seguro:',
        'btn_track' => 'Acceder al seguimiento de mi solicitud',
        'fallback_link' => 'Si el botón no se muestra correctamente, puede utilizar el siguiente enlace:'
    ]
];
$t = $translations[$lang];

//Get team form
$env_slug = isset($_GET['env']) ? strip_tags($_GET['env']) : '';
$stmt = $dbco->prepare("SELECT `teams_id`, `teams_name`, `teams_form_config`, `teams_logo`, `teams_color`, `teams_privacy_policy_url` FROM `teams` WHERE `teams_form_url` = :env AND `teams_deleted` = 0 LIMIT 1");
$stmt->execute(['env' => $env_slug]);
$team = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$team) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
$custom_fields = $team['teams_form_config'] ? json_decode($team['teams_form_config'], true) : [];

$form_feedback = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Form process
    if (!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['email']) && !empty($_POST['subject']) && !empty($_POST['description'])) {
        $first_name = strip_tags($_POST['first_name']);
        $last_name = strip_tags($_POST['last_name']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $subject = strip_tags($_POST['subject']);
        $description = strip_tags($_POST['description']);
        $assigned_to = null;
        $additional_fields_data = [];

        // Additionnal fields data
        foreach ($custom_fields as $index => $field) {
            $input_name = 'custom_' . $index;
            $user_answer = isset($_POST[$input_name]) ? strip_tags($_POST[$input_name]) : '';
            $additional_fields_data[] = [
                'question' => $field['question'],
                'answer'  => $user_answer
            ];
            //Auto-dispatch
                if (isset($field['options']) && is_array($field['options'])) {
                    foreach ($field['options'] as $option) {
                        if ($option['value'] == $user_answer && !empty($option['assign_to'])) {
                            $assignData = is_string($option['assign_to']) ? json_decode($option['assign_to'], true) : $option['assign_to'];
                            if (isset($assignData['type']) && $assignData['type'] === 'group') {
                                $groupName = $assignData['value'];
                                $stmt = $dbco->prepare("SELECT user_id FROM teams_members WHERE FIND_IN_SET(:group_name, REPLACE(teams_members_groups, ', ', ',')) > 0;");
                                $stmt->execute(['group_name' => $groupName]);
                                $members = $stmt->fetchAll(PDO::FETCH_COLUMN);
                                $availableMembers = [];
                                foreach ($members as $memberId) {
                                    if (!isUserCurrentlyAbsent($memberId)) {
                                        $availableMembers[] = $memberId;
                                    }
                                }
                                if (!empty($availableMembers)) {
                                    $randomKey = array_rand($availableMembers);
                                    $assigned_to = $availableMembers[$randomKey];
                                } else {
                                    $assigned_to = null;
                                }
                                
                            } elseif (isset($assignData['type']) && $assignData['type'] === 'user') {
                                $userId = (int) $assignData['value'];
                                if (!isUserCurrentlyAbsent($userId)) {
                                    $assigned_to = $userId;
                                } else {
                                    $assigned_to = null;
                                }
                            }
                            break;
                        }
                    }
                }
        }

        $json_additional_fields = json_encode($additional_fields_data, JSON_UNESCAPED_UNICODE);
        $token = random_str(50);
        $assigned_to = !empty($assigned_to) ? (int)$assigned_to : null;
        // Assignee verification
        if ($assigned_to !== null) {
            $check_user = $dbco->prepare("SELECT `teams_members_id` FROM `teams_members` WHERE `teams_members_user_id` = :user_id AND `teams_members_team_id` = :team_id LIMIT 1");
            $check_user->execute([
                'user_id' => $assigned_to,
                'team_id' => $team['teams_id']
            ]);
            if (!$check_user->fetch()) {
                $assigned_to = null;
            }
        }

        // New ticket
        $insert_stmt = $dbco->prepare("INSERT INTO `tickets` (`tickets_id`, `tickets_teams`, `tickets_token`, `tickets_email`, `tickets_first_name`, `tickets_last_name`, `tickets_subject`, `tickets_source`, `tickets_description`, `tickets_additionnal_fields`, `tickets_assigned_to`, `tickets_status`, `tickets_priority`, `tickets_rating`, `tickets_feedback`, `tickets_client_last_visit_date`, `tickets_creation_date`, `tickets_closing_date`, `tickets_feedback_date`) VALUES (NULL, :team_id, :token, :email, :first_name, :last_name, :subject, :source, :description, :additional_fields, :assigned_to, '0', '0', NULL, NULL, NULL, CURRENT_TIMESTAMP, NULL, NULL)");
        $insert_stmt->execute([
            'team_id' => $team['teams_id'],
            'token' => $token,
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'subject' => $subject,
            'source' => "OpenSupport form",
            'description' => $description,
            'additional_fields' => $json_additional_fields,
            'assigned_to' => $assigned_to
        ]);

        // Email notification
        $chat_link = "{$opensupport_link}form/{$env_slug}/ticket/{$token}";
        $mail_subject = "Support : " . $subject;
        $accent_color = !empty($team['teams_color']) ? htmlspecialchars($team['teams_color']) : '#635bff';
        $mail_body = '<p style="margin: 0 0 16px 0;">' . str_replace(':firstname', '<strong>' . htmlspecialchars($first_name) . '</strong>', $tb['greeting']) . '</p>
    <p style="margin: 0 0 16px 0;">' . $tb['intro'] . '</p>
    
    <div style="background-color: #f8fafc; border-left: 4px solid ' . $accent_color . '; border-radius: 0 8px 8px 0; padding: 16px 20px; margin: 20px 0; font-size: 14px; line-height: 22px; color: #1e293b;">
        <strong style="display: block; margin-bottom: 6px; color: #0f172a;">' . htmlspecialchars($subject) . '</strong>
        ' . nl2br(htmlspecialchars($description)) . '
    </div>

    <p style="margin: 0 0 24px 0;">' . $tb['track_info'] . '</p>

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 24px auto;">
        <tr>
            <td align="center" style="border-radius: 8px; background-color: ' . $accent_color . ';">
                <a href="' . htmlspecialchars($chat_link) . '" target="_blank" rel="noopener noreferrer" style="display: inline-block; padding: 13px 28px; font-size: 14px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 8px;">
                    ' . $tb['btn_track'] . '
                </a>
            </td>
        </tr>
    </table>

    <p style="font-size: 12px; color: #94a3b8; border-top: 1px dashed #e2e8f0; padding-top: 16px; margin-top: 24px;">
        ' . $tb['fallback_link'] . '<br>
        <a href="' . htmlspecialchars($chat_link) . '" target="_blank" rel="noopener noreferrer" style="color: #635bff; word-break: break-all;">' . htmlspecialchars($chat_link) . '</a>
    </p>
        ';
        $mail_html = renderEmailLayout([
            'team' => $team,
            'recipient_email' => $email,
            'body_content' => $mail_body,
            'privacy_token' => $token,
            'subject' => $mail_subject
        ]);
        sendOpenSupportMail($email, $mail_subject, $mail_html, (!empty($team['teams_name']) ? $team['teams_name'] : 'OpenSupport'));

        // Chat redirection
        header("Location: ticket/{$token}");
        exit;
    } else {
        $form_feedback = $t['form_error'];
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
        <div class="box large">
            <header>
                <?php if($team['teams_logo']=="1"): ?>
                    <img src="<?= $opensupport_link?>/up/teams/<?= $team['teams_id'] ?>.webp" alt="Logo <?= htmlspecialchars($team['teams_name']) ?>">
                <?php endif; ?>
                <h1><?= $t['title'] ?></h1>
                <p><?= $t['subtitle'] ?></p>
            </header>

            <!-- Form warning -->
            <div class="warning">
                <p><?= $t['warning']?></p>
            </div>

            <!-- Formulaire -->
            <form action="" method="POST">
                <input type="hidden" name="env_slug" value="<?= htmlspecialchars($env_slug) ?>">
                
                <?php if($form_feedback!=""):?>
                <p class="alert"><?= $form_feedback ?></p>
                <?php endif;?>

                <div class="grid-cols-2">
                    <div>
                        <label><?= $t['firstname'] ?> *</label>
                        <input type="text" name="first_name" autocomplete="given-name" required>
                    </div>
                    <div>
                        <label><?= $t['lastname'] ?> *</label>
                        <input type="text" name="last_name" autocomplete="family-name" required>
                    </div>
                </div>

                <div>
                    <label><?= $t['email'] ?> *</label>
                    <input type="email" name="email" autocomplete="email" required>
                </div>

                <!-- Additionnal fields generation -->
                <?php if (!empty($custom_fields)): ?>
                    <?php foreach ($custom_fields as $index => $field): ?>
                        <div>
                            <?php if ($field['type'] === 'checkbox'): ?>
                                <div class="check">
                                    <input type="checkbox" 
                                           name="custom_<?= $index ?>" 
                                           id="custom_<?= $index ?>" 
                                           value="1" 
                                           <?= !empty($field['required']) ? 'required' : '' ?>>
                                    <label for="custom_<?= $index ?>">
                                        <?= htmlspecialchars($field['question']) ?> <?= !empty($field['required']) ? '*' : '' ?>
                                    </label>
                                </div>

                            <?php else: ?>
                                <label for="custom_<?= $index ?>">
                                    <?= htmlspecialchars($field['question']) ?> <?= !empty($field['required']) ? '*' : '' ?>
                                </label>

                                <?php if ($field['type'] === 'select'): ?>
                                    <select name="custom_<?= $index ?>" id="custom_<?= $index ?>" <?= !empty($field['required']) ? 'required' : '' ?>>
                                        <option value=""><?= $t['select_default_option'] ?></option>
                                        <?php if (!empty($field['options']) && is_array($field['options'])): ?>
                                            <?php foreach ($field['options'] as $option): ?>
                                                <option value="<?= htmlspecialchars($option['value']) ?>">
                                                    <?= htmlspecialchars($option['label']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>

                                <?php else: 
                                    $inputType = in_array($field['type'], ['email', 'tel']) ? $field['type'] : 'text';
                                    $hasLimit = !empty($field['maxlength']) && (int)$field['maxlength'] > 0;
                                    $limitVal = $hasLimit ? (int)$field['maxlength'] : 0;
                                ?>
                                    <div class="input-with-limit">
                                        <input type="<?= $inputType ?>" 
                                               name="custom_<?= $index ?>" 
                                               id="custom_<?= $index ?>" 
                                               <?= $hasLimit ? 'maxlength="' . $limitVal . '"' : '' ?>
                                               <?= !empty($field['required']) ? 'required' : '' ?>
                                               <?= $hasLimit ? 'oninput="document.getElementById(\'limit_badge_' . $index . '\').textContent = this.value.length + \' / ' . $limitVal . '\'"' : '' ?>>
                                        
                                        <?php if ($hasLimit): ?>
                                            <span class="input-char-limit-badge" id="limit_badge_<?= $index ?>">0 / <?= $limitVal ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div>
                    <label><?= $t['subject'] ?> *</label>
                    <input type="text" name="subject" required>
                </div>

                <div>
                    <label><?= $t['description']?> *</label>
                    <textarea name="description" required></textarea>
                </div>

                <div class="check">
                    <input type="checkbox" name="rgpd" id="rgpd" required>
                    <label for="rgpd">
                        <?= $t['gdpr'] ?> <br>
                        <a href="<?= $opensupport_link?>/privacy_policy/<?= htmlspecialchars($env_slug) ?>" class="underline" target="_blank"><?= $t['privacy_policy'] ?></a>.
                    </label>
                </div>

                <button type="submit"><?= $t['submit'] ?></button>
            </form>
        </div>
    </main>
    
    <footer>&copy; <?= date("Y"); ?> <?= htmlspecialchars($team['teams_name']) ?><br>Powered by OpenSupport</footer>
    <script src="<?= $opensupport_link?>src/js/main.js"></script>
</body>
</html>