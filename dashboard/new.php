<?php require("../config.php");
connectionCheck();

// Language manager
$translations = [
    'fr' => [
        'page_title' => 'Nouveau',
        'back' => 'Retour',
        'auto' => 'Automatique',
        'need_help' => 'Besoin d\'aide ?',
        'ticket_header' => 'Nouveau ticket',
        'ticket_email' => 'Adresse email',
        'ticket_firstname' => 'Prénom',
        'ticket_lastname' => 'Nom',
        'ticket_select_default_option' => 'Sélectionnez une option',
        'ticket_subject' => 'Objet',
        'ticket_description' => 'Description de votre problème',
        'ticket_assigned_to' => 'Assigner à',
        'ticket_priority' => 'Priorité',
        'ticket_priority_1' => 'Très basse',
        'ticket_priority_2' => 'Basse',
        'ticket_priority_3' => 'Normale',
        'ticket_priority_4' => 'Haute',
        'ticket_priority_5' => 'Très haute',
        'ticket_notes' => 'Ajoutez une note au ticket',
        'ticket_submit' => 'Ajouter',
        'ticket_form_error' => 'Veuillez remplir tous les champs obligatoires désignés par une astérisque (*).',
        'ticket_mail_subject' => 'Support : :subject',
        'ticket_greeting' => 'Bonjour :firstname,',
        'ticket_intro' => 'Votre demande d\'assistance a bien été enregistrée par notre équipe :',
        'ticket_track_info' => 'Vous pouvez suivre son avancement en direct et répondre aux messages de l\'équipe via notre espace sécurisé :',
        'ticket_btn_track' => 'Accéder au suivi de ma demande',
        'ticket_fallback_link' => 'Si le bouton ne s\'affiche pas correctement, vous pouvez utiliser le lien suivant :',
        'member_header' => 'Ajouter un membre',
        'member_label_email' => 'Email professionnel *',
        'member_label_position' => 'Position',
        'member_btn_add' => 'Ajouter',
        'member_mail_subject' => 'Invitation à rejoindre l\'équipe :team - OpenSupport',
        'member_greeting' => 'Bonjour,',
        'member_intro' => 'Vous avez été invité à rejoindre l\'équipe <strong>:team</strong> sur OpenSupport.',
        'member_instruction' => 'Cliquez sur le bouton ci-dessous pour créer votre compte et finaliser votre intégration :',
        'member_btn_accept' => 'Rejoindre l\'équipe',
        'member_fallback_link' => 'Si le bouton ne fonctionne pas, copiez et collez l\'adresse suivante dans votre navigateur :',
        'member_success_created' => 'Le compte a été créé et l\'invitation a été envoyée.',
        'member_success_added' => 'L\'utilisateur a été ajouté à l\'équipe.',
        'member_error_invalid_email' => 'L\'adresse email fournie n\'est pas valide.',
        'member_error_generic' => 'Une erreur est survenue.',
        'users_header' => 'Inviter des utilisateurs sur l\'instance',
        'users_label_list' => 'Adresses email (copiez/collez une liste séparée par des retours à la ligne ou des virgules)',
        'users_btn_send' => 'Envoyer les invitations',
        'user_mail_subject' => 'Invitation à rejoindre OpenSupport',
        'user_intro' => 'Vous avez été invité à créer un compte sur la plateforme OpenSupport.',
        'user_btn_accept' => 'Créer mon compte',
        'users_feedback_done' => 'Traitement terminé : :count invitation(s) envoyée(s).',
        'users_feedback_exists' => ' (:count déjà existante(s))',
        'users_feedback_invalid' => ' (:count invalide(s))',
        'team_header' => 'Créer une équipe',
        'team_label_name' => 'Nom de l\'équipe',
        'team_btn_create' => 'Créer une équipe',
        'team_error_taken' => 'Ce nom d\'équipe est indisponible.',
        'team_join_header' => 'Rejoindre une équipe',
        'team_join_desc' => 'Demandez à l’administrateur de l’équipe de vous ajouter avec l’adresse email de votre compte.<br>Une fois ajouté, l’équipe apparaîtra dans votre menu de sélection d’équipe en bas à gauche de votre écran.'
    ],
    'en' => [
        'page_title' => 'New',
        'back' => 'Back',
        'auto' => 'Automatic',
        'need_help' => 'Need help?',
        'ticket_header' => 'New ticket',
        'ticket_email' => 'Email Address',
        'ticket_firstname' => 'First Name',
        'ticket_lastname' => 'Last Name',
        'ticket_select_default_option' => 'Select an option',
        'ticket_subject' => 'Subject',
        'ticket_description' => 'Describe your issue',
        'ticket_assigned_to' => 'Assigned to',
        'ticket_priority' => 'Priority',
        'ticket_priority_1' => 'Very low',
        'ticket_priority_2' => 'Low',
        'ticket_priority_3' => 'Normal',
        'ticket_priority_4' => 'High',
        'ticket_priority_5' => 'Very high',
        'ticket_notes' => 'Add a note to the ticket',
        'ticket_submit' => 'Add',
        'ticket_form_error' => 'Please fill in all mandatory fields marked with an asterisk (*).',
        'ticket_mail_subject' => 'Support: :subject',
        'ticket_greeting' => 'Hello :firstname,',
        'ticket_intro' => 'Your support request has been successfully received by our team:',
        'ticket_track_info' => 'You can track its progress live and reply to team messages via our secure portal:',
        'ticket_btn_track' => 'Track my support request',
        'ticket_fallback_link' => 'If the button does not display properly, you can use the following link:',
        'member_header' => 'Add a member',
        'member_label_email' => 'Work email *',
        'member_label_position' => 'Position',
        'member_btn_add' => 'Add',
        'member_mail_subject' => 'Invitation to join team :team - OpenSupport',
        'member_greeting' => 'Hello,',
        'member_intro' => 'You have been invited to join the <strong>:team</strong> team on OpenSupport.',
        'member_instruction' => 'Click the button below to create your account and complete your onboarding:',
        'member_btn_accept' => 'Join the team',
        'member_fallback_link' => 'If the button above does not work, copy and paste the following link into your browser:',
        'member_success_created' => 'The account has been created and the invitation was sent.',
        'member_success_added' => 'The user has been added to the team.',
        'member_error_invalid_email' => 'The provided email address is invalid.',
        'member_error_generic' => 'An error occurred.',
        'users_header' => 'Invite users to instance',
        'users_label_list' => 'Email addresses (copy/paste a list separated by line breaks or commas)',
        'users_btn_send' => 'Send invitations',
        'user_mail_subject' => 'Invitation to join OpenSupport',
        'user_intro' => 'You have been invited to create an account on OpenSupport.',
        'user_btn_accept' => 'Create my account',
        'users_feedback_done' => 'Processing completed: :count invitation(s) sent.',
        'users_feedback_exists' => ' (:count already exist)',
        'users_feedback_invalid' => ' (:count invalid)',
        'team_header' => 'Create a team',
        'team_label_name' => 'Team name',
        'team_btn_create' => 'Create a team',
        'team_error_taken' => 'This team name is not available.',
        'team_join_header' => 'Join a team',
        'team_join_desc' => 'Ask the team administrator to add you using your account email address.<br>Once added, the team will appear in your team selector in the bottom-left menu.'
    ],
    'es' => [
        'page_title' => 'Nuevo',
        'back' => 'Volver',
        'auto' => 'Automático',
        'need_help' => '¿Necesita ayuda?',
        'ticket_header' => 'Nuevo ticket',
        'ticket_email' => 'Correo electrónico',
        'ticket_firstname' => 'Nombre',
        'ticket_lastname' => 'Apellido',
        'ticket_select_default_option' => 'Seleccione una opción',
        'ticket_subject' => 'Asunto',
        'ticket_description' => 'Descripción del problema',
        'ticket_assigned_to' => 'Asignar a',
        'ticket_priority' => 'Prioridad',
        'ticket_priority_1' => 'Muy baja',
        'ticket_priority_2' => 'Baja',
        'ticket_priority_3' => 'Normal',
        'ticket_priority_4' => 'Alta',
        'ticket_priority_5' => 'Muy alta',
        'ticket_notes' => 'Añade una nota al ticket.',
        'ticket_submit' => 'Agregar',
        'ticket_form_error' => 'Por favor, rellene todos los campos obligatorios marcados con un asterisco (*).',
        'ticket_mail_subject' => 'Soporte: :subject',
        'ticket_greeting' => 'Hola :firstname,',
        'ticket_intro' => 'Nuestro equipo ha registrado correctamente su solicitud de asistencia:',
        'ticket_track_info' => 'Puede seguir su evolución en directo y responder a los mensajes del equipo a través de nuestro portal seguro:',
        'ticket_btn_track' => 'Acceder al seguimiento de mi solicitud',
        'ticket_fallback_link' => 'Si el botón no se muestra correctamente, puede utilizar el siguiente enlace:',
        'member_header' => 'Añadir un miembro',
        'member_label_email' => 'Correo electrónico profesional *',
        'member_label_position' => 'Puesto',
        'member_btn_add' => 'Añadir',
        'member_mail_subject' => 'Invitación para unirse al equipo :team - OpenSupport',
        'member_greeting' => 'Hola,',
        'member_intro' => 'Ha sido invitado a unirse al equipo <strong>:team</strong> en OpenSupport.',
        'member_instruction' => 'Haga clic en el botón de abajo para crear su cuenta y finalizar su incorporación:',
        'member_btn_accept' => 'Unirse al equipo',
        'member_fallback_link' => 'Si el botón no funciona, copie y pegue la siguiente dirección en su navegador:',
        'member_success_created' => 'Se ha creado la cuenta y se ha enviado la invitación.',
        'member_success_added' => 'El usuario ha sido añadido al equipo.',
        'member_error_invalid_email' => 'La dirección de correo electrónico proporcionada no es válida.',
        'member_error_generic' => 'Ocurrió un error.',
        'users_header' => 'Invitar usuarios a la instancia',
        'users_label_list' => 'Direcciones de correo (copie/pegue una lista separada por saltos de línea o comas)',
        'users_btn_send' => 'Enviar invitaciones',
        'user_mail_subject' => 'Invitación para unirse a OpenSupport',
        'user_intro' => 'Ha sido invitado a crear una cuenta en OpenSupport.',
        'user_btn_accept' => 'Crear mi cuenta',
        'users_feedback_done' => 'Procesamiento finalizado: :count invitación(es) enviada(s).',
        'users_feedback_exists' => ' (:count ya existente(s))',
        'users_feedback_invalid' => ' (:count no válida(s))',
        'team_header' => 'Crear un equipo',
        'team_label_name' => 'Nombre del equipo',
        'team_btn_create' => 'Crear un equipo',
        'team_error_taken' => 'Este nombre de equipo no está disponible.',
        'team_join_header' => 'Unirse a un equipo',
        'team_join_desc' => 'Pida al administrador del equipo que le añada con la dirección de correo de su cuenta.<br>Una vez añadido, el equipo aparecerá en su menú de selección de equipos abajo a la izquierda.'
    ]
];
$t = $translations[$lang];

// Check admin instance access if page == users
if (($_GET["page"] ?? '') === "users") {
    $stmtAdminCheck = $dbco->prepare("SELECT users_admin FROM users WHERE users_id = :user_id LIMIT 1");
    $stmtAdminCheck->execute(['user_id' => $_SESSION['user_id']]);
    $isAdmin = (int)$stmtAdminCheck->fetchColumn();
    if ($isAdmin !== 1) {
        header("Location: ../dashboard/");
        exit();
    }
}

// Get team form
if (($_GET["page"] ?? '') == "ticket" || ($_GET["page"] ?? '') == "member") {
    $stmt = $dbco->prepare("SELECT `teams_id`, `teams_name`, `teams_form_url`, `teams_form_config`, `teams_logo`, `teams_color`, `teams_privacy_policy_url` FROM `teams` WHERE `teams_id` = :id AND `teams_deleted` = 0 LIMIT 1");
    $stmt->execute(['id' => $_SESSION['team_id']]);
    $team = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$team) {
        header('HTTP/1.0 404 Not Found');
        exit;
    }
    $custom_fields = (!empty($team['teams_form_config'])) ? json_decode($team['teams_form_config'], true) : [];
}

$form_feedback = "";

// FORMS PROCESS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_GET["page"] ?? '') === "users") {
        $rawEmails = $_POST['emails_list'] ?? '';
        $parsedEmails = preg_split('/[\r\n,;\s]+/', $rawEmails, -1, PREG_SPLIT_NO_EMPTY);
        $uniqueEmails = array_unique(array_filter(array_map('trim', $parsedEmails)));
        $successCount = 0;
        $alreadyExistsCount = 0;
        $invalidCount = 0;
        $checkUserStmt = $dbco->prepare("SELECT users_id FROM users WHERE users_email = :email LIMIT 1");
        $insertUserStmt = $dbco->prepare("INSERT INTO `users` (`users_email`, `users_first_name`, `users_last_name`, `users_password`, `users_password_modify_token`, `users_last_password_date`, `users_invitation_token`, `users_last_connexion`, `users_creation_date`, `users_deleted`) VALUES (:email, NULL, NULL, NULL, NULL, NULL, :token, NULL, CURRENT_TIMESTAMP, '0')");
        
        foreach ($uniqueEmails as $email) {
            $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
                $invalidCount++;
                continue;
            }
            $checkUserStmt->execute(['email' => $sanitizedEmail]);
            if ($checkUserStmt->fetch()) {
                $alreadyExistsCount++;
                continue;
            }
            $token = random_str(100);
            $insertUserStmt->execute(['email' => $sanitizedEmail, 'token' => $token]);
            $invite_link = rtrim($opensupport_link, '/') . "/dashboard/signin/invite?token=" . urlencode($token);
            $subject = $t['user_mail_subject'];
            $mail_body = '
                <p style="margin: 0 0 16px 0;">' . $t['member_greeting'] . '</p>
                <p style="margin: 0 0 16px 0;">' . $t['user_intro'] . '</p>
                <p style="margin: 0 0 24px 0;">' . $t['member_instruction'] . '</p>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 28px auto;">
                    <tr>
                        <td align="center" style="border-radius: 8px; background-color: #4f46e5;">
                            <a href="' . htmlspecialchars($invite_link) . '" target="_blank" rel="noopener noreferrer" style="display: inline-block; padding: 13px 28px; font-size: 14px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 8px;">
                                ' . $t['user_btn_accept'] . '
                            </a>
                        </td>
                    </tr>
                </table>
                <p style="font-size: 12px; color: #94a3b8; border-top: 1px dashed #e2e8f0; padding-top: 16px; margin-top: 24px;">
                    ' . $t['member_fallback_link'] . '<br>
                    <a href="' . htmlspecialchars($invite_link) . '" target="_blank" rel="noopener noreferrer" style="color: #4f46e5; word-break: break-all;">' . htmlspecialchars($invite_link) . '</a>
                </p>
            ';
            $mail_html = renderEmailLayout([
                'team' => null,
                'recipient_email' => $sanitizedEmail,
                'body_content' => $mail_body,
                'privacy_token' => null,
                'subject' => $subject
            ]);
            sendOpenSupportMail($sanitizedEmail, $subject, $mail_html);
            $successCount++;
        }

        $form_feedback = str_replace(':count', $successCount, $t['users_feedback_done']);
        if ($alreadyExistsCount > 0) {
            $form_feedback .= str_replace(':count', $alreadyExistsCount, $t['users_feedback_exists']);
        }
        if ($invalidCount > 0) {
            $form_feedback .= str_replace(':count', $invalidCount, $t['users_feedback_invalid']);
        }

    } elseif (($_GET["page"] ?? '') == "ticket") {
        // New ticket
        if (!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['email']) && !empty($_POST['subject']) && !empty($_POST['description'])) {
            $first_name = strip_tags($_POST['first_name']);
            $last_name = strip_tags($_POST['last_name']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $subject = strip_tags($_POST['subject']);
            $description = strip_tags($_POST['description']);
            $assigned_to = null;
            $additional_fields_data = [];
            $admin_note = strip_tags($_POST['notes']);
            $priority = strip_tags($_POST['priority']);

            foreach ($custom_fields as $index => $field) {
                $input_name = 'custom_' . $index;
                $user_answer = isset($_POST[$input_name]) ? strip_tags($_POST[$input_name]) : '';
                $additional_fields_data[] = [
                    'question' => $field['question'],
                    'answer'  => $user_answer
                ];
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
            $assigned_to = (!empty($assigned_to) && $assigned_to != "auto") ? (int)$assigned_to : null;
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

            $insert_stmt = $dbco->prepare("INSERT INTO `tickets` (`tickets_id`, `tickets_teams`, `tickets_token`, `tickets_email`, `tickets_first_name`, `tickets_last_name`, `tickets_subject`, `tickets_description`, `tickets_additionnal_fields`, `tickets_assigned_to`, `tickets_status`, `tickets_admin_notes`, `tickets_priority`, `tickets_rating`, `tickets_feedback`, `tickets_client_last_visit_date`, `tickets_creation_date`, `tickets_closing_date`, `tickets_feedback_date`) VALUES (NULL, :team_id, :token, :email, :first_name, :last_name, :subject, :description, :additional_fields, :assigned_to, '0', :admin_notes, :priority, NULL, NULL, NULL, CURRENT_TIMESTAMP, NULL, NULL)");
            $insert_stmt->execute([
                'team_id' => $team['teams_id'],
                'token' => $token,
                'email' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'subject' => $subject,
                'description' => $description,
                'additional_fields' => $json_additional_fields,
                'assigned_to' => $assigned_to,
                'admin_notes' => $admin_note,
                'priority' => $priority
            ]);

            $chat_link = rtrim($opensupport_link, '/') . "/form/" . htmlspecialchars($team['teams_form_url']) . "/ticket/" . htmlspecialchars($token);
            $mail_subject = str_replace(':subject', $subject, $t['ticket_mail_subject']);
            $accent_color = !empty($team['teams_color']) ? htmlspecialchars($team['teams_color']) : '#635bff';
            $mail_body = '
                <p style="margin: 0 0 16px 0;">' . str_replace(':firstname', '<strong>' . htmlspecialchars($first_name) . '</strong>', $t['ticket_greeting']) . '</p>
                <p style="margin: 0 0 16px 0;">' . $t['ticket_intro'] . '</p>
                <div style="background-color: #f8fafc; border-left: 4px solid ' . $accent_color . '; border-radius: 0 8px 8px 0; padding: 16px 20px; margin: 20px 0; font-size: 14px; line-height: 22px; color: #1e293b;">
                    <strong style="display: block; margin-bottom: 6px; color: #0f172a;">' . htmlspecialchars($subject) . '</strong>
                    ' . nl2br(htmlspecialchars($description)) . '
                </div>
                <p style="margin: 0 0 24px 0;">' . $t['ticket_track_info'] . '</p>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 24px auto;">
                    <tr>
                        <td align="center" style="border-radius: 8px; background-color: ' . $accent_color . ';">
                            <a href="' . htmlspecialchars($chat_link) . '" target="_blank" rel="noopener noreferrer" style="display: inline-block; padding: 13px 28px; font-size: 14px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 8px;">
                                ' . $t['ticket_btn_track'] . '
                            </a>
                        </td>
                    </tr>
                </table>
                <p style="font-size: 12px; color: #94a3b8; border-top: 1px dashed #e2e8f0; padding-top: 16px; margin-top: 24px;">
                    ' . $t['ticket_fallback_link'] . '<br>
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

            header("Location: ../../tickets/{$token}");
            exit;
        } else {
            $form_feedback = $t['ticket_form_error'];
        }
        
    } elseif (($_GET["page"] ?? '') == "member") {
        // New member
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $position = htmlspecialchars(trim($_POST['position'] ?? ''));

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                $stmt = $dbco->prepare("SELECT users_id, users_password FROM users WHERE users_email = :email AND users_deleted = '0'");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $is_finalized = false;
                if ($user) {
                    $user_id = $user['users_id'];
                    if (!empty($user['users_password'])) {
                        $is_finalized = true;
                    }
                } else {
                    $token = random_str(100);
                    $insertUserStmt = $dbco->prepare("INSERT INTO `users` (`users_email`, `users_first_name`, `users_last_name`, `users_password`, `users_password_modify_token`, `users_last_password_date`, `users_invitation_token`, `users_last_connexion`, `users_creation_date`, `users_deleted`) VALUES (:email, NULL, NULL, NULL, NULL, NULL, :token, NULL, CURRENT_TIMESTAMP, '0')");
                    $insertUserStmt->execute(['email' =>$email, 'token' => $token]);
                    $user_id = $dbco->lastInsertId();
                    $is_finalized = false;

                    $invite_link = rtrim($opensupport_link, '/') . "/dashboard/signin/invite?token=" . urlencode($token);
                    $team_title = !empty($team['teams_name']) ? htmlspecialchars($team['teams_name']) : 'OpenSupport';$subject = str_replace(':team', $team_title,$t['member_mail_subject']);
                    $intro_text = str_replace(':team',$team_title, $t['member_intro']);$member_mail_body = '
                        <p style="margin: 0 0 16px 0;">' . $t['member_greeting'] . '</p>
                        <p style="margin: 0 0 16px 0;">' . $intro_text . '</p>
                        <p style="margin: 0 0 24px 0;">' . $t['member_instruction'] . '</p>
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 28px auto;">
                            <tr>
                                <td align="center" style="border-radius: 8px; background-color: #4f46e5;">
                                    <a href="' . htmlspecialchars($invite_link) . '" target="_blank" rel="noopener noreferrer" style="display: inline-block; padding: 13px 28px; font-size: 14px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 8px;">
                                        ' . $t['member_btn_accept'] . '
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <p style="font-size: 12px; color: #94a3b8; border-top: 1px dashed #e2e8f0; padding-top: 16px; margin-top: 24px;">
                            ' . $t['member_fallback_link'] . '<br>
                            <a href="' . htmlspecialchars($invite_link) . '" target="_blank" rel="noopener noreferrer" style="color: #4f46e5; word-break: break-all;">' . htmlspecialchars($invite_link) . '</a>
                        </p>
                    ';
                    $mail_html = renderEmailLayout([
                        'team' => $team,
                        'recipient_email' => $email,
                        'body_content' => $member_mail_body,
                        'privacy_token' => null,
                        'subject' => $subject
                    ]);
                    sendOpenSupportMail($email, $subject, $mail_html);

                    $form_feedback =$t['member_success_created'];
                }
                $join_date =$is_finalized ? date('Y-m-d H:i:s') : null;

                $checkRelationStmt =$dbco->prepare("SELECT teams_members_id, teams_members_deleted FROM teams_members WHERE teams_members_user_id = :user_id AND teams_members_team_id = :team_id AND teams_members_deleted = '1' LIMIT 1;");
                $checkRelationStmt->execute(['user_id' => $user_id, 'team_id' =>$_SESSION['team_id']]);
                $existing_relation =$checkRelationStmt->fetch(PDO::FETCH_ASSOC);
                if ($existing_relation) {
                    $updateTeamStmt =$dbco->prepare("UPDATE `teams_members` SET `teams_members_position` = :position, `teams_members_join_date` = :join_date, `teams_members_invite_date` = CURRENT_TIMESTAMP, `teams_members_deleted` = '0' WHERE `teams_members_id` = :tm_id;");
                    $updateTeamStmt->execute(['position' =>$position, 'join_date' => $join_date, 'tm_id' =>$existing_relation['teams_members_id']]);
                } else {
                    $insertTeamStmt =$dbco->prepare("INSERT INTO `teams_members` (`teams_members_user_id`, `teams_members_team_id`, `teams_members_position`, `teams_members_join_date`, `teams_members_invite_date`, `teams_members_deleted`) VALUES (:user_id, :team_id, :position, :join_date, CURRENT_TIMESTAMP, '0')");
                    $insertTeamStmt->execute(['user_id' => $user_id, 'team_id' =>$_SESSION['team_id'], 'position' => $position, 'join_date' =>$join_date]);
                }
                $form_feedback =$t['member_success_added'];

            } catch (PDOException $e) {
                $form_feedback =$t['member_error_generic'];
            }
        } else {
            $form_feedback =$t['member_error_invalid_email'];
        }
        
    } elseif (($_GET["page"] ?? '') == "team") {
        // New team
        $name = strip_tags($_POST['team_name'] ?? '');
        $name_lower = strtolower($name);
        $url = preg_replace('/\s+/', '-',$name_lower);

        $sth =$dbco->prepare("SELECT teams_id FROM teams WHERE LOWER(teams_name) = :name_lower OR teams_form_url = :url LIMIT 1;");
        $sth->execute(['name_lower' => $name_lower, 'url' =>$url]);
        $result =$sth->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) == 0) {$api_token = random_str(64);
            $sth =$dbco->prepare("INSERT INTO `teams` (`teams_id`, `teams_name`, `teams_privacy_policy_url`, `teams_form_url`, `teams_form_config`, `teams_messages_template`, `teams_logo`, `teams_color`, `teams_owner`, `teams_api_token`, `teams_creation_date`, `teams_deleted`) VALUES (NULL, :name, NULL, :url, NULL, NULL, '0', '#4f46e5', :user_id, :api_token, CURRENT_TIMESTAMP, '0');");
            $sth->execute([
                'name' => $name,
                'url' => $url,
                'user_id' => $_SESSION['user_id'],
                'api_token' => $api_token
            ]);
            $team_id =$dbco->lastInsertId();
            
            $sth =$dbco->prepare("INSERT INTO `teams_members` (`teams_members_id`, `teams_members_user_id`, `teams_members_team_id`, `teams_members_position`, `teams_members_join_date`) VALUES (NULL, :user_id, :team_id, NULL, CURRENT_TIMESTAMP);");
            $sth->execute([
                'user_id' => $_SESSION['user_id'],
                'team_id' => $team_id
            ]);

            header("Location: ../{$team_id}/tickets");
            exit;
        } else {
            $form_feedback =$t['team_error_taken'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenSupport | <?= $t['page_title'] ?></title>
    <link rel="icon" type="image/x-icon" href="<?= $opensupport_link?>/src/opensupport_assets/opensupport_icon.svg">
    <link rel="stylesheet" href="<?= $opensupport_link?>/src/css/main.css">
    <link rel="manifest" href="<?= $opensupport_link ?>/manifest.json">
</head>
<body class="form">
    <div class="action_bar reverse">
        <a href="#" onclick="history.back()"><?= $t['back'] ?></a>
    </div>
    
    <main>
        <?php if (!empty($_GET["team_id"])):
            if (verifyTeamAccess($_GET['team_id']) == false) {
                header("Location: ../../dashboard/");
                exit();
            }?>
            <?php if (($_GET["page"] ?? '') == "member"): ?>
                <section class="box">
                    <header>
                        <h1><?= $t['member_header'] ?></h1>
                    </header>

                    <form method="POST">
                        <?php if ($form_feedback != ""): ?>
                            <p class="alert"><?= htmlspecialchars($form_feedback); ?></p>
                        <?php endif; ?>
                        <div>
                            <label><?= $t['member_label_email'] ?></label>
                            <input type="email" name="email" required>
                        </div>
                        <div>
                            <label><?= $t['member_label_position'] ?></label>
                            <input type="text" name="position">
                        </div>
                        <button type="submit"><?= $t['member_btn_add'] ?></button>
                    </form>
                </section>
        
            <?php elseif (($_GET["page"] ?? '') == "ticket"): ?>
                <section class="box large">
                    <header>
                        <h1><?= $t['ticket_header'] ?></h1>
                    </header>

                    <form method="POST">
                        <?php if ($form_feedback != ""): ?>
                            <p class="alert"><?= htmlspecialchars($form_feedback) ?></p>
                        <?php endif; ?>
                        <div class="grid-cols-2">
                            <div>
                                <label><?= $t['ticket_firstname'] ?> *</label>
                                <input type="text" name="first_name" autocomplete="given-name" required>
                            </div>
                            <div>
                                <label><?= $t['ticket_lastname'] ?> *</label>
                                <input type="text" name="last_name" autocomplete="family-name" required>
                            </div>
                        </div>
                        <div>
                            <label><?= $t['ticket_email'] ?> *</label>
                            <input type="email" name="email" autocomplete="email" required>
                        </div>
                        <!-- Additional fields generation -->
                        <?php if (!empty($custom_fields)): ?>
                            <?php foreach ($custom_fields as $index =>$field): ?>
                                <div>
                                    <label><?= htmlspecialchars($field['question']) ?> <?= !empty($field['required']) ? '*' : '' ?></label>

                                    <?php if ($field['type'] === 'select'): ?>
                                        <select name="custom_<?= $index ?>" <?= !empty($field['required']) ? 'required' : '' ?>>
                                            <option value=""><?= $t['ticket_select_default_option'] ?></option>
                                            <?php foreach ($field['options'] as$option): ?>
                                                <option value="<?= htmlspecialchars($option['value']) ?>">
                                                    <?= htmlspecialchars($option['label']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php elseif ($field['type'] === 'textarea'): ?>
                                        <textarea name="custom_<?= $index ?>" <?= !empty($field['required']) ? 'required' : '' ?>></textarea>
                                    <?php else: ?>
                                        <input type="text" name="custom_<?= $index ?>" <?= !empty($field['required']) ? 'required' : '' ?>>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <div>
                            <label><?= $t['ticket_subject'] ?> *</label>
                            <input type="text" name="subject" required>
                        </div>
                        <div>
                            <label><?= $t['ticket_description'] ?> *</label>
                            <textarea name="description" required></textarea>
                        </div>
                        <div>
                            <hr>
                        </div>
                        <div>
                            <label><?= $t['ticket_assigned_to'] ?> *</label>
                            <select name="assigned_to" required>
                                <option value="auto" selected><?= $t['auto'] ?></option>
                                <?php $stmt =$dbco->prepare("SELECT u.users_id, u.users_first_name, u.users_last_name, u.users_email FROM users u INNER JOIN teams_members tm ON u.users_id = tm.teams_members_user_id WHERE tm.teams_members_team_id = :team_id AND u.users_deleted = '0' ORDER BY u.users_first_name ASC, u.users_email ASC;");
                                $stmt->execute(['team_id' =>$team['teams_id']]);
                                $team_members =$stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($team_members as$member): ?>
                                    <?php $display_name = trim($member['users_first_name'] . ' ' .$member['users_last_name']);
                                    if (!empty($display_name)): ?>
                                        <option value="<?= htmlspecialchars($member['users_id']) ?>">
                                            <?= htmlspecialchars($display_name) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label><?= $t['ticket_priority'] ?> *</label>
                            <select name="priority" required>
                                <option value="2"><?= $t['ticket_priority_5'] ?></option>
                                <option value="1"><?= $t['ticket_priority_4'] ?></option>
                                <option value="0" selected><?= $t['ticket_priority_3'] ?></option>
                                <option value="-1"><?= $t['ticket_priority_2'] ?></option>
                                <option value="-2"><?= $t['ticket_priority_1'] ?></option>
                            </select>
                        </div>
                        <div>
                            <label><?= $t['ticket_notes'] ?></label>
                            <textarea name="notes" required></textarea>
                        </div>
                        <button type="submit"><?= $t['ticket_submit'] ?></button>
                    </form>
                </section>
            <?php else:
                header('HTTP/1.0 404 Not Found');
                exit;
            endif; ?>
        
        <?php elseif (($_GET["page"] ?? '') === "users"): ?>
        <section class="box">
            <header>
                <h1><?= $t['users_header'] ?></h1>
            </header>

            <form method="POST">
                <?php if ($form_feedback != ""): ?>
                    <p class="alert"><?= htmlspecialchars($form_feedback); ?></p>
                <?php endif; ?>
                <div>
                    <label><?= $t['users_label_list'] ?></label>
                    <textarea name="emails_list" rows="8" placeholder="user1@example.com&#10;user2@example.com&#10;user3@example.com" required style="resize: vertical;"></textarea>
                </div>
                <button type="submit"><?= $t['users_btn_send'] ?></button>
            </form>
        </section>

        <?php elseif (($_GET["page"] ?? '') == "team"): ?>
        <section class="box">
            <header>
                <h1><?= $t['team_header'] ?></h1>
            </header>

            <form method="POST">
                <?php if ($form_feedback != ""): ?>
                    <p class="alert"><?= htmlspecialchars($form_feedback); ?></p>
                <?php endif; ?>
                <div>
                    <label><?= $t['team_label_name'] ?></label>
                    <input type="text" name="team_name" required>
                </div>
                <button type="submit"><?= $t['team_btn_create'] ?></button>
            </form>
        </section>

        <section class="box center">
            <header>
                <h1><?= $t['team_join_header'] ?></h1>
            </header>
            <p><?= $t['team_join_desc'] ?></p>
            <br>
            <a href="https://logreee.github.io/OpenSupport/docs#section-invite_new_members_to_create_an_account"><?= $t['need_help'] ?></a>
        </section>
        <?php else:
            header('HTTP/1.0 404 Not Found');
            exit;
        endif; ?>
    </main>
    <script src="<?= $opensupport_link?>/src/js/main.js"></script>
</body>
</html>