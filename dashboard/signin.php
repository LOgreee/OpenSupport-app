<?php require("../config.php");

// Language manager
$translations = [
    'fr' => [
        'page_title' => 'Création de compte',
        'welcome_title' => 'Bienvenue !',
        'welcome_subtitle' => 'Finalisons votre profil.',
        'label_email' => 'Email professionnel',
        'label_firstname' => 'Prénom',
        'label_lastname' => 'Nom',
        'label_password' => 'Mot de passe',
        'label_confirm_password' => 'Confirmez le mot de passe',
        'req_length' => 'Contient plus de 8 caractères.',
        'req_number' => 'Contient au moins un chiffre.',
        'req_special' => 'Contient au moins un caractère spécial.',
        'terms_prefix' => 'J’accepte la',
        'privacy_policy' => 'Politique de confidentialité',
        'terms_suffix' => 'de OpenSupport.',
        'btn_submit' => 'Finaliser',
        'error_password_mismatch' => 'Les mots de passe ne correspondent pas.',
        'error_invalid_token' => 'Ce lien d\'invitation n\'est pas valide.'
    ],
    'en' => [
        'page_title' => 'Sign in',
        'welcome_title' => 'Welcome!',
        'welcome_subtitle' => 'Let\'s complete your profile.',
        'label_email' => 'Work email',
        'label_firstname' => 'First name',
        'label_lastname' => 'Last name',
        'label_password' => 'Password',
        'label_confirm_password' => 'Confirm password',
        'req_length' => 'Contains more than 8 characters.',
        'req_number' => 'Contains at least one number.',
        'req_special' => 'Contains at least one special character.',
        'terms_prefix' => 'I agree to the',
        'privacy_policy' => 'Privacy Policy',
        'terms_suffix' => 'of OpenSupport.',
        'btn_submit' => 'Complete',
        'error_password_mismatch' => 'Passwords do not match.',
        'error_invalid_token' => 'This invitation link is invalid.'
    ],
    'es' => [
        'page_title' => 'Creación de cuenta',
        'welcome_title' => '¡Bienvenido!',
        'welcome_subtitle' => 'Finalicemos su perfil.',
        'label_email' => 'Correo electrónico profesional',
        'label_firstname' => 'Nombre',
        'label_lastname' => 'Apellido',
        'label_password' => 'Contraseña',
        'label_confirm_password' => 'Confirme la contraseña',
        'req_length' => 'Contiene más de 8 caracteres.',
        'req_number' => 'Contiene al menos un número.',
        'req_special' => 'Contiene al menos un carácter especial.',
        'terms_prefix' => 'Acepto la',
        'privacy_policy' => 'Política de privacidad',
        'terms_suffix' => 'de OpenSupport.',
        'btn_submit' => 'Finalizar',
        'error_password_mismatch' => 'Las contraseñas no coinciden.',
        'error_invalid_token' => 'Este enlace de invitación no es válido.'
    ]
];
$t = $translations[$lang];

$form_feedback = "";

// FORMS PROCESS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_GET["page"] ?? '') == "invite") {
        if (!empty($_GET["token"])) {
            // Sign in invitation script
            $token = strip_tags($_GET["token"]);
            $first_name = strip_tags($_POST["first_name"] ?? '');
            $last_name = strip_tags($_POST["last_name"] ?? '');
            $password =$_POST['new_password'] ?? '';
            $password_confirm =$_POST['new_password_confirm'] ?? '';
            $sth =$dbco->prepare("SELECT * FROM users WHERE users_invitation_token = :token LIMIT 1;");
            $sth->execute(['token' =>$token]);
            $row =$sth->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                if ($password ===$password_confirm) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $sthUpdate =$dbco->prepare("UPDATE `users` SET `users_first_name` = :first_name, `users_last_name` = :last_name, `users_password` = :password, `users_invitation_token` = NULL, `users_last_connexion` = NOW(), `users_creation_date` = NOW() WHERE `users_id` = :user_id;");
                    $sthUpdate->execute([
                        'first_name' => $first_name,
                        'last_name'  => $last_name,
                        'password'   => $hashed_password,
                        'user_id'    => $row['users_id']
                    ]);
                    $updateTeams =$dbco->prepare("UPDATE `teams_members` SET `teams_members_join_date` = NOW() WHERE `teams_members_user_id` = :user_id AND `teams_members_join_date` IS NULL AND `teams_members_deleted` = 0");
                    $updateTeams->execute(['user_id' =>$row['users_id']]);
                    if (isset($_SESSION['connected']) &&$_SESSION['connected'] === "true") {
                        $_SESSION = array();
                        if (ini_get("session.use_cookies")) {
                            $params = session_get_cookie_params();
                            setcookie(session_name(), '', time() - 42000,
                                $params["path"], $params["domain"],
                                $params["secure"], $params["httponly"]
                            );
                        }
                        session_destroy();
                    }
                    session_start();
                    $_SESSION['connected'] = "true";
                    $_SESSION['connection_datetime'] = date('m/d/Y h:i:s a', time());
                    $_SESSION['user_id'] = $row['users_id'];
                    $_SESSION['user_name'] = trim("{$first_name} {$last_name}");
                    $_SESSION['user_email'] =$row['users_email'];
                    header('Location: ../');
                    exit;
                } else {
                    $form_feedback =$t['error_password_mismatch'];
                }
            } else {
                $form_feedback =$t['error_invalid_token'];
            }
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
    </div>
    
    <main>
        <?php if (($_GET["page"] ?? '') == "invite"): ?>
            <?php 
            $invite_token_valid = false;
            $email = "";
            if (!empty($_GET["token"])) {
                $token = strip_tags($_GET["token"]);
                $sth =$dbco->prepare("SELECT users_email FROM users WHERE users_invitation_token = :token LIMIT 1;");
                $sth->execute(['token' =>$token]);
                $row =$sth->fetch(PDO::FETCH_ASSOC);
                if (!$row) {
                    header("Location: ../login");
                    exit();
                } else {
                    $invite_token_valid = true;
                    $email =$row['users_email'];
                }
            } else {
                header("Location: ../login");
                exit();
            }
            if ($invite_token_valid == true): ?>
            <section class="box">
                <header>
                    <img src="<?= $opensupport_link?>/src/opensupport_assets/opensupport_logo.svg" alt="OpenSupport Logo">
                    <h1><?= $t['welcome_title'] ?></h1>
                    <p><?= $t['welcome_subtitle'] ?></p>
                </header>

                <form method="POST">
                    <?php if ($form_feedback != ""): ?>
                        <p class="alert"><?= htmlspecialchars($form_feedback); ?></p>
                    <?php endif; ?>
                    <div>
                        <label><?= $t['label_email'] ?></label>
                        <input type="email" name="email" value="<?= htmlspecialchars($email); ?>" disabled required>
                    </div>
                    <div class="grid-cols-2">
                        <div>
                            <label><?= $t['label_firstname'] ?></label>
                            <input type="text" name="first_name" autocomplete="given-name" required>
                        </div>
                        <div>
                            <label><?= $t['label_lastname'] ?></label>
                            <input type="text" name="last_name" autocomplete="family-name" required>
                        </div>
                    </div>
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
                    <div class="check">
                        <input type="checkbox" name="terms" id="terms" required>
                        <label for="terms"><?= $t['terms_prefix'] ?> <a href="<?= $opensupport_link?>/privacy_policy/" target="_blank" class="underline"><?= $t['privacy_policy'] ?></a> <?=$t['terms_suffix'] ?>
                        </label>
                    </div>
                    <button type="submit" disabled><?= $t['btn_submit'] ?></button>
                </form>
            </section>
            <?php endif; ?>
        <?php else:
            header("Location: login");
            exit();
        endif; ?>
    </main>
    <footer>Powered by OpenSupport</footer>
    <script src="<?= $opensupport_link?>/src/js/main.js"></script>
</body>
</html>