<?php require("../config.php");
if(isset($_SESSION['connected']) && $_SESSION['connected']==="true"){
    header("Location: ../dashboard/");
    exit();
}

// Language manager
$translations = [
    'fr' => [
        'page_title' => 'Connexion',
        'back' => 'Retour',
        'mail_subject' => 'Réinitialisation de votre mot de passe - OpenSupport',
        'mail_greeting' => 'Bonjour :name,',
        'mail_intro' => 'Vous avez demandé la réinitialisation du mot de passe de votre compte OpenSupport.',
        'mail_instructions' => 'Cliquez sur le bouton ci-dessous pour en définir un nouveau. Ce lien sécurisé est valable pendant <strong>24 heures</strong> :',
        'mail_btn_reset' => 'Réinitialiser mon mot de passe',
        'mail_warning_title' => 'Vous n\'êtes pas à l\'origine de cette demande ?',
        'mail_warning_desc' => 'Ignorez simplement cet e-mail. Votre mot de passe actuel restera inchangé.',
        'mail_fallback_link' => 'Si le bouton ci-dessus ne fonctionne pas, copiez et collez l\'adresse suivante dans votre navigateur :',
        'error_reset_expired' => 'Ce lien de réinitialisation de mot de passe n\'est plus valide. Veuillez recommencer votre demande.',
        'error_password_mismatch' => 'Les mots de passe ne correspondent pas.',
        'error_reset_invalid' => 'Ce lien de réinitialisation de mot de passe n\'est pas valide.',
        'error_reset_rate_limit' => 'Vous avez déjà réinitialisé votre mot de passe il y a moins de 24 heures. Veuillez réessayer plus tard.',
        'error_account_disabled' => 'Ce compte est désactivé, veuillez contacter le support.',
        'error_login_invalid' => 'Email et/ou mot de passe erroné.',
        'success_password_reset' => 'Votre mot de passe a bien été mis à jour. Vous pouvez maintenant vous connecter.',
        'new_password_title' => 'Nouveau mot de passe',
        'label_password' => 'Mot de passe',
        'req_length' => 'Contient plus de 8 caractères.',
        'req_number' => 'Contient au moins un chiffre.',
        'req_special' => 'Contient au moins un caractère spécial.',
        'label_confirm_password' => 'Confirmez le mot de passe',
        'btn_modify' => 'Modifier',
        'forgot_password_title' => 'Mot de passe oublié ?',
        'forgot_password_desc' => 'Saisissez votre adresse email, <br>nous vous enverrons un lien pour modifier votre mot de passe.',
        'label_email' => 'Email professionnel',
        'btn_send' => 'Envoyer',
        'email_sent_title' => 'Email envoyé !',
        'email_sent_desc' => 'Si un compte existe à cette adresse email, vous allez recevoir un message contenant un lien pour modifier votre mot de passe.',
        'login_title' => 'Espace Support',
        'login_desc' => 'Connectez-vous pour gérer vos tickets.',
        'link_forgot_password' => 'Mot de passe oublié ?',
        'btn_login' => 'Se connecter',
        'sso_header' => 'Ou continuer avec :'
    ],
    'en' => [
        'page_title' => 'Log in',
        'back' => 'Back',
        'mail_subject' => 'Reset your password - OpenSupport',
        'mail_greeting' => 'Hello :name,',
        'mail_intro' => 'You requested a password reset for your OpenSupport account.',
        'mail_instructions' => 'Click the button below to choose a new password. This secure link is valid for <strong>24 hours</strong>:',
        'mail_btn_reset' => 'Reset my password',
        'mail_warning_title' => 'Did not request this change?',
        'mail_warning_desc' => 'You can safely ignore this email. Your current password will remain unchanged.',
        'mail_fallback_link' => 'If the button above does not work, copy and paste the following URL into your browser:',
        'error_reset_expired' => 'This password reset link is no longer valid. Please submit a new request.',
        'error_password_mismatch' => 'Passwords do not match.',
        'error_reset_invalid' => 'This password reset link is invalid.',
        'error_reset_rate_limit' => 'You have already reset your password less than 24 hours ago. Please try again later.',
        'error_account_disabled' => 'This account has been disabled, please contact support.',
        'error_login_invalid' => 'Invalid email and/or password.',
        'success_password_reset' => 'Your password has been successfully updated. You can now log in.',
        'new_password_title' => 'New password',
        'label_password' => 'Password',
        'req_length' => 'Contains more than 8 characters.',
        'req_number' => 'Contains at least one number.',
        'req_special' => 'Contains at least one special character.',
        'label_confirm_password' => 'Confirm password',
        'btn_modify' => 'Update',
        'forgot_password_title' => 'Forgot password?',
        'forgot_password_desc' => 'Enter your email address, <br>and we will send you a link to reset your password.',
        'label_email' => 'Work email',
        'btn_send' => 'Send',
        'email_sent_title' => 'Email sent!',
        'email_sent_desc' => 'If an account exists with this email address, you will receive an email with instructions to reset your password.',
        'login_title' => 'Support Portal',
        'login_desc' => 'Log in to manage your tickets.',
        'link_forgot_password' => 'Forgot password?',
        'btn_login' => 'Log in',
        'sso_header' => 'Or continue with:'
    ],
    'es' => [
        'page_title' => 'Iniciar sesión',
        'back' => 'Volver',
        'mail_subject' => 'Restablecimiento de su contraseña - OpenSupport',
        'mail_greeting' => 'Hola :name,',
        'mail_intro' => 'Ha solicitado restablecer la contraseña de su cuenta de OpenSupport.',
        'mail_instructions' => 'Haga clic en el botón de abajo para establecer una nueva contraseña. Este enlace seguro es válido durante <strong>24 horas</strong>:',
        'mail_btn_reset' => 'Restablecer mi contraseña',
        'mail_warning_title' => '¿No solicitó este cambio?',
        'mail_warning_desc' => 'Puede ignorar este correo de forma segura. Su contraseña actual no se modificará.',
        'mail_fallback_link' => 'Si el botón de arriba no funciona, copie y pegue la siguiente dirección en su navegador:',
        'error_reset_expired' => 'Este enlace de restablecimiento ya no es válido. Por favor, solicite uno nuevo.',
        'error_password_mismatch' => 'Las contraseñas no coinciden.',
        'error_reset_invalid' => 'Este enlace de restablecimiento de contraseña no es válido.',
        'error_reset_rate_limit' => 'Ya ha restablecido su contraseña hace menos de 24 horas. Por favor, inténtelo de nuevo más tarde.',
        'error_account_disabled' => 'Esta cuenta está desactivada, por favor contacte con soporte.',
        'error_login_invalid' => 'Correo electrónico y/o contraseña incorrectos.',
        'success_password_reset' => 'Su contraseña se ha actualizado correctamente. Ya puede iniciar sesión.',
        'new_password_title' => 'Nueva contraseña',
        'label_password' => 'Contraseña',
        'req_length' => 'Contiene más de 8 caracteres.',
        'req_number' => 'Contiene al menos un número.',
        'req_special' => 'Contiene al menos un carácter especial.',
        'label_confirm_password' => 'Confirme la contraseña',
        'btn_modify' => 'Modificar',
        'forgot_password_title' => '¿Ha olvidado su contraseña?',
        'forgot_password_desc' => 'Introduzca su dirección de correo electrónico, <br>le enviaremos un enlace para restablecer su contraseña.',
        'label_email' => 'Correo electrónico profesional',
        'btn_send' => 'Enviar',
        'email_sent_title' => '¡Correo enviado!',
        'email_sent_desc' => 'Si existe una cuenta asociada a este correo electrónico, recibirá un enlace para cambiar su contraseña.',
        'login_title' => 'Portal de Soporte',
        'login_desc' => 'Inicie sesión para gestionar sus tickets.',
        'link_forgot_password' => '¿Ha olvidado su contraseña?',
        'btn_login' => 'Iniciar sesión',
        'sso_header' => 'O continuar con:'
    ]
];
$t = $translations[$lang];

$form_feedback = "";
$forgot_password_process = false;

// FORMS PROCESS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_GET["page"] ?? '') == "forgotpassword") {
        if (!empty($_GET["token"])) {
            // Password reset script
            $token = strip_tags($_GET["token"]);
            $password =$_POST['new_password'] ?? '';
            $password_confirm =$_POST['new_password_confirm'] ?? '';

            $sth =$dbco->prepare("SELECT * FROM users WHERE users_password_modify_token = :token LIMIT 1;");
            $sth->execute(['token' =>$token]);
            $row =$sth->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                if ($row['users_deleted'] != "1") {
                    if (!empty($row['users_last_password_date']) && abs(time() - strtotime($row['users_last_password_date'])) < 86400) {
                        $form_feedback =$t['error_reset_expired'];
                    } else {
                        if ($password ===$password_confirm) {
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                            $sth =$dbco->prepare("UPDATE `users` SET `users_password` = :password, `users_password_modify_token` = NULL, `users_last_password_date` = NOW() WHERE `users_id` = :user_id;");
                            $sth->execute([
                                'password' => $hashed_password,
                                'user_id' => $row['users_id']
                            ]);
                            $form_feedback =$t['success_password_reset'];
                        } else {
                            $form_feedback =$t['error_password_mismatch'];
                        }
                    }
                }
            } else {
                $form_feedback =$t['error_reset_invalid'];
            }
            
        } else {
            // Forgot password request script
            $forgot_password_process = true;
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);

            $sth =$dbco->prepare("SELECT * FROM users WHERE users_email = :email LIMIT 1;");
            $sth->execute(['email' =>$email]);
            $row =$sth->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                if ($row['users_deleted'] != "1") {
                    if (!empty($row['users_last_password_date']) && abs(time() - strtotime($row['users_last_password_date'])) < 86400) {$forgot_password_process = false;
                        $form_feedback =$t['error_reset_rate_limit'];
                    } else {
                        $user_password_modify_token = random_str(50);
                        $sth =$dbco->prepare("UPDATE `users` SET `users_password_modify_token` = :token, `users_last_password_date` = NOW() WHERE `users_id` = :user_id;");
                        $sth->execute([
                            'token' => $user_password_modify_token,
                            'user_id' => $row['users_id']
                        ]);

                        // EMAIL
                        $reset_link = rtrim($opensupport_link, '/') . "/dashboard/login/forgotpassword?token=" . urlencode($user_password_modify_token);
                        $mail_subject =$t['mail_subject'];
                        $user_firstname = !empty($row['users_first_name']) ? htmlspecialchars($row['users_first_name']) : '';$greeting_text = str_replace(':name', !empty($user_firstname) ? '<strong>' .$user_firstname . '</strong>' : '', $t['mail_greeting']);$mail_body = '
                            <p style="margin: 0 0 16px 0;">' . $greeting_text . '</p>
                            <p style="margin: 0 0 16px 0;">' . $t['mail_intro'] . '</p>
                            <p style="margin: 0 0 24px 0;">' . $t['mail_instructions'] . '</p>
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 28px auto;">
                                <tr>
                                    <td align="center" style="border-radius: 8px; background-color: #4f46e5;">
                                        <a href="' . htmlspecialchars($reset_link) . '" target="_blank" rel="noopener noreferrer" style="display: inline-block; padding: 13px 28px; font-size: 14px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 8px;">
                                            ' . $t['mail_btn_reset'] . '
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; border-radius: 0 8px 8px 0; padding: 14px 18px; margin: 24px 0; font-size: 13px; line-height: 20px; color: #991b1b;">
                                <strong>' . $t['mail_warning_title'] . '</strong><br>
                                ' . $t['mail_warning_desc'] . '
                            </div>
                            <p style="font-size: 12px; color: #94a3b8; border-top: 1px dashed #e2e8f0; padding-top: 16px; margin-top: 24px;">
                                ' . $t['mail_fallback_link'] . '<br>
                                <a href="' . htmlspecialchars($reset_link) . '" target="_blank" rel="noopener noreferrer" style="color: #4f46e5; word-break: break-all;">' . htmlspecialchars($reset_link) . '</a>
                            </p>
                        ';
                        $mail_html = renderEmailLayout([
                            'team' => null,
                            'recipient_email' => $email,
                            'body_content' => $mail_body,
                            'privacy_token' => null,
                            'subject' => $mail_subject
                        ]);
                        sendOpenSupportMail($email, $mail_subject, $mail_html);
                    }
                }
            }
        }
    } else {
        // Login script
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password =$_POST['password'] ?? '';

        $sth =$dbco->prepare("SELECT * FROM users WHERE users_email = :email LIMIT 1;");
        $sth->execute(['email' =>$email]);
        $row =$sth->fetch(PDO::FETCH_ASSOC);

        if ($row && !empty($password) && !empty($row['users_password']) && password_verify($password,$row['users_password'])) {
            if ($row['users_deleted'] != "1") {
                $_SESSION['connected'] = "true";
                $_SESSION['connection_datetime'] = date('m/d/Y h:i:s a', time());$_SESSION['user_id'] = $row['users_id'];$_SESSION['user_name'] = trim("{$row['users_first_name']} {$row['users_last_name']}");
                $_SESSION['user_email'] =$row['users_email'];
                $_SESSION['user_admin'] =$row['users_admin'];
                
                $sth =$dbco->prepare("UPDATE `users` SET `users_last_connexion` = NOW() WHERE `users_id` = :user_id;");
                $sth->execute(['user_id' =>$row['users_id']]);

                header('Location: ../dashboard/');
                exit;
            } else {
                $form_feedback =$t['error_account_disabled'];
            }
        } else {
            $form_feedback =$t['error_login_invalid'];
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

    <div class="action_bar">
        <?php require("../src/php/language_selector.php"); ?>
        <?php if((($_GET["page"] ?? '') == "forgotpassword" && empty($_GET["token"])) || $forgot_password_process == true): ?>
            <a href="../login"><?= $t['back'] ?></a>
        <?php endif;?>
    </div>

    <section class="box">
        <?php if (($_GET["page"] ?? '') == "forgotpassword"): ?>
            <?php 
            $password_token_valid = false;
            if (!empty($_GET["token"])) {
                $token = strip_tags($_GET["token"]);
                $sth =$dbco->prepare("SELECT users_id FROM users WHERE users_password_modify_token = :token LIMIT 1;");
                $sth->execute(['token' =>$token]);
                if ($sth->fetch()) {$password_token_valid = true;
                } else {
                    header("Location: forgotpassword");
                    exit();
                }
            }
            if ($password_token_valid == true): ?>
            <header>
                <img src="<?= $opensupport_link?>/src/opensupport_assets/opensupport_logo.svg" alt="OpenSupport Logo">
                <h1><?= $t['new_password_title'] ?></h1>
            </header>

            <form method="POST">
                <?php if ($form_feedback != ""): ?>
                    <p class="alert"><?= htmlspecialchars($form_feedback) ?></p>
                <?php endif; ?>
                <div>
                    <label><?= $t['label_password'] ?></label>
                    <input type="password" id="new_password" name="new_password" autocomplete="new-password" required>
                    <div class="password-requirements">
                        <p id="req-length" class="invalid"><?= $t['req_length'] ?></p>
                        <p id="req-number" class="invalid"><?= $t['req_number'] ?></p>
                        <p id="req-special" class="invalid"><?= $t['req_special'] ?></p>
                    </div>
                </div>
                <div>
                    <label><?= $t['label_confirm_password'] ?></label>
                    <input type="password" id="new_password_confirm" name="new_password_confirm" autocomplete="new-password" required>
                </div>
                <button type="submit" disabled><?= $t['btn_modify'] ?></button>
            </form>
        
            <?php else: ?>
            <header>
                <img src="<?= $opensupport_link?>/src/opensupport_assets/opensupport_logo.svg" alt="OpenSupport Logo">
                <h1><?= $t['forgot_password_title'] ?></h1>
                <p><?= $t['forgot_password_desc'] ?></p>
            </header>

            <form method="POST">
                <?php if ($form_feedback != ""): ?>
                    <p class="alert"><?= htmlspecialchars($form_feedback) ?></p>
                <?php endif; ?>
                <div>
                    <label><?= $t['label_email'] ?></label>
                    <input type="email" name="email" autocomplete="email" required>
                </div>
                <button type="submit"><?= $t['btn_send'] ?></button>
            </form>
            <?php endif; ?>
        
        <?php elseif ($forgot_password_process == true): ?>
        <header>
            <img src="<?= $opensupport_link?>/src/opensupport_assets/opensupport_logo.svg" alt="OpenSupport Logo">
            <h1><?= $t['email_sent_title'] ?></h1>
            <p><?= $t['email_sent_desc'] ?></p>
        </header>
        
        <?php else: ?>
        <header>
            <img src="<?= $opensupport_link?>/src/opensupport_assets/opensupport_logo.svg" alt="OpenSupport Logo">
            <h1><?= $t['login_title'] ?></h1>
            <p><?= $t['login_desc'] ?></p>
        </header>

        <form method="POST">
            <?php if ($form_feedback != ""): ?>
                <p class="alert"><?= htmlspecialchars($form_feedback) ?></p>
            <?php endif; ?>
            <div>
                <label><?= $t['label_email'] ?></label>
                <input type="email" name="email" autocomplete="email" required>
            </div>
            <div>
                <label><?= $t['label_password'] ?></label>
                <input type="password" name="password" autocomplete="password" required>
                <a href="login/forgotpassword"><?= $t['link_forgot_password'] ?></a>
            </div>
            <button type="submit"><?= $t['btn_login'] ?></button>
            <!--<section class="sso">
                <header><?= $t['sso_header'] ?></header>
                <a href="sso/google" class="btn tertiary"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ee/Google_2026_logo.svg/3840px-Google_2026_logo.svg.png" alt="Google"></a>
                <a href="sso/microsoft" class="btn tertiary"><img src="https://www.edigitalagency.com.au/wp-content/uploads/new-Microsoft-logo-png-horizontal-large-size.png" alt="Microsoft"></a>
            </section>-->
        </form>
        <?php endif; ?>
    </section>

    <footer>Powered by OpenSupport</footer>
    <script src="<?= $opensupport_link?>/src/js/main.js"></script>
</body>
</html>