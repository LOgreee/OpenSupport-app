<?php require("../config.php");
connectionCheck();

// Language manager
$translations = [
    'fr' => [
        'title' => 'Compte',
        'subtitle' => 'Personnalisez les différents paramètres de votre compte.',
        'profile' => 'Paramètres du profil',
        'profile_first_name' => 'Prénom',
        'profile_last_name' => 'Nom',
        'profile_email' => 'Email professionnel',
        'profile_password' => 'Modifier le mot de passe',
        'profile_password_requirement1' => 'Contient plus de 8 caractères.',
        'profile_password_requirement2' => 'Contient au moins un chiffre.',
        'profile_password_requirement3' => 'Contient au moins un caractère spécial.',
        'profile_password_confirm' => 'Confirmez le nouveau mot de passe',
        'profile_modify' => 'Modifier',
        'absence' => 'Absence',
        'absence_subtitle' => 'Planifiez une période d\'absence permet de ne pas avoir de nouveaux tickets attribués automatiquement pendant votre absence.',
        'absence_enable' => 'Activer le mode absent pour la période indiqué ci-dessous.',
        'absence_start' => 'Début',
        'absence_end' => 'Fin',
        'absence_modify' => 'Modifier',
        'teams' => 'Équipes',
        'teams_name' => 'Nom',
        'teams_administrator' => 'Administrateur',
        'teams_join' => 'Rejoint le',
        'teams_actions' => 'Actions',
        'teams_quit' => 'Quitter',
        'teams_empty' => 'Aucune équipe.',
        'data' => 'Data',
        'data_opensupport_privacy' => 'Politique de confidentialité de OpenSupport',
        'data_export_account' => 'Exporter les données de mon compte',
        'advanced_settings' => 'Paramètres avancés',
        'advanced_settings_delete_account' => 'Supprimer le compte',
        'error' => 'Une erreur est survenue, veuillez réessayer.',
        'error_email' => 'L\'adresse email n\'est pas valide.',
        'error_password' => 'Les mots de passe ne correspondent pas.',
        'error_absence_date' => 'Veuillez renseigner les dates de début et de fin pour activer le mode absent.',
        'error_absence_end' => 'La date de fin doit être ultérieure à la date de début.',
        'success_account_informations' => 'Vos informations ont été mises à jour avec succès.',
        'success_absence_informations' => 'Vos paramètres d\'absence ont été mis à jour.'
    ],
    'en' => [
        'title' => 'Account',
        'subtitle' => 'Customize your account settings.',
        'profile' => 'Profile Settings',
        'profile_first_name' => 'First Name',
        'profile_last_name' => 'Last Name',
        'profile_email' => 'Work Email',
        'profile_password' => 'Change Password',
        'profile_password_requirement1' => 'Contains more than 8 characters.',
        'profile_password_requirement2' => 'Contains at least one number.',
        'profile_password_requirement3' => 'Contains at least one special character.',
        'profile_password_confirm' => 'Confirm New Password',
        'profile_modify' => 'Update',
        'absence' => 'Out of Office',
        'absence_subtitle' => 'Scheduling an absence prevents new tickets from being automatically assigned to you while you are away.',
        'absence_enable' => 'Enable out of office mode for the period specified below.',
        'absence_start' => 'Start',
        'absence_end' => 'End',
        'absence_modify' => 'Update',
        'teams' => 'Teams',
        'teams_name' => 'Name',
        'teams_administrator' => 'Administrator',
        'teams_join' => 'Joined on',
        'teams_actions' => 'Actions',
        'teams_quit' => 'Leave',
        'teams_empty' => 'No teams.',
        'data' => 'Data',
        'data_opensupport_privacy' => 'OpenSupport Privacy Policy',
        'data_export_account' => 'Export my account data',
        'advanced_settings' => 'Advanced Settings',
        'advanced_settings_delete_account' => 'Delete Account',
        'error' => 'An error occurred, please try again.',
        'error_email' => 'The email address is invalid.',
        'error_password' => 'Passwords do not match.',
        'error_absence_date' => 'Please provide start and end dates to enable out of office mode.',
        'error_absence_end' => 'The end date must be later than the start date.',
        'success_account_informations' => 'Your information has been successfully updated.',
        'success_absence_informations' => 'Your absence settings have been updated.'
    ],
    'es' => [
        'title' => 'Cuenta',
        'subtitle' => 'Personalice los diferentes ajustes de su cuenta.',
        'profile' => 'Ajustes del perfil',
        'profile_first_name' => 'Nombre',
        'profile_last_name' => 'Apellido',
        'profile_email' => 'Correo electrónico profesional',
        'profile_password' => 'Cambiar la contraseña',
        'profile_password_requirement1' => 'Contiene más de 8 caracteres.',
        'profile_password_requirement2' => 'Contiene al menos un número.',
        'profile_password_requirement3' => 'Contiene al menos un carácter especial.',
        'profile_password_confirm' => 'Confirme la nueva contraseña',
        'profile_modify' => 'Modificar',
        'absence' => 'Ausencia',
        'absence_subtitle' => 'Programar un periodo de ausencia evita que se le asignen nuevos tickets automáticamente durante su ausencia.',
        'absence_enable' => 'Activar el modo ausente para el periodo indicado a continuación.',
        'absence_start' => 'Inicio',
        'absence_end' => 'Fin',
        'absence_modify' => 'Modificar',
        'teams' => 'Equipos',
        'teams_name' => 'Nombre',
        'teams_administrator' => 'Administrador',
        'teams_join' => 'Unido el',
        'teams_actions' => 'Acciones',
        'teams_quit' => 'Salir',
        'teams_empty' => 'Ningún equipo.',
        'data' => 'Datos',
        'data_opensupport_privacy' => 'Política de privacidad de OpenSupport',
        'data_export_account' => 'Exportar los datos de mi cuenta',
        'advanced_settings' => 'Ajustes avanzados',
        'advanced_settings_delete_account' => 'Eliminar la cuenta',
        'error' => 'Ha ocurrido un error, por favor inténtelo de nuevo.',
        'error_email' => 'La dirección de correo electrónico no es válida.',
        'error_password' => 'Las contraseñas no coinciden.',
        'error_absence_date' => 'Por favor, introduzca las fechas de inicio y fin para activar el modo ausente.',
        'error_absence_end' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        'success_account_informations' => 'Su información se ha actualizado con éxito.',
        'success_absence_informations' => 'Sus ajustes de ausencia han sido actualizados.'
    ]
];
$t = $translations[$lang];

// Reset values
$_SESSION['team_id']="";
$_SESSION['team_role']="";

$form_feedback = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['form_section']) && $_POST['form_section'] === 'global'){
        // Profile settings update
        $first_name = strip_tags($_POST['first_name']);
        $last_name = strip_tags($_POST['last_name']);
        $email = strip_tags($_POST['email']);
        $new_password = $_POST['new_password'] ?? '';
        $new_password_confirm = $_POST['new_password_confirm'] ?? '';
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = $t['error_email'];
        }
        // New password process
        $update_password = false;
        $password_hash = null;
        if (!empty($new_password)) {
            if ($new_password !== $new_password_confirm) {
                $errors[] = $t['error_password'];
            } else {
                $update_password = true;
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            }
        }
        if (empty($errors)) {
            try {
                if ($update_password) {
                    $updateUser = $dbco->prepare("UPDATE users SET users_first_name = :first_name, users_last_name = :last_name, users_email = :email, users_password = :password WHERE users_id = :user_id");
                    $updateUser->execute(['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'password' => $password_hash, 'user_id' => $_SESSION['user_id']]);
                } else {
                    $updateUser = $dbco->prepare("UPDATE users SET users_first_name = :first_name, users_last_name = :last_name, users_email = :email WHERE users_id = :user_id");
                    $updateUser->execute(['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'user_id' => $_SESSION['user_id']]);
                }
                $_SESSION['user_name'] = "{$first_name} {$last_name}";
                $_SESSION['user_email'] = $email;
                $form_feedback['global'] = $t['success_account_informations'];
            } catch (Exception $e) {
                $form_feedback['global'] = $t['error']; 
            }
        } else {
            $form_feedback['global'] = implode("<br>", $errors);
        }
        
    } elseif (isset($_POST['form_section']) && $_POST['form_section'] === 'absence'){
        // Absence update
        $post_enabled = isset($_POST['enabled']) ? true : false;
        $post_start = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
        $post_end = !empty($_POST['end_date']) ? $_POST['end_date'] : null;

        if ($post_enabled && (!$post_start || !$post_end)) {
            $errors[] = $t['error_absence_date'];
        }
        if ($post_start && $post_end && strtotime($post_start) >= strtotime($post_end)) {
            $errors[] = $t['error_absence_end'];
        }
        if (empty($errors)) {
            $date_start = $post_start ? date('Y-m-d H:i:s', strtotime($post_start)) : null;
            $date_end = $post_end ? date('Y-m-d H:i:s', strtotime($post_end)) : null;
            $absence_json = json_encode(['enabled' => $post_enabled, 'start' => $date_start, 'end' => $date_end]);
            try {
                $update_stmt = $dbco->prepare("UPDATE users SET users_absence = :absence WHERE users_id = :user_id");
                $update_stmt->execute(['absence' => $absence_json, 'user_id' => $_SESSION['user_id']]);
                $form_feedback['absence'] = $t['success_absence_informations'];
            } catch (Exception $e) {
                $form_feedback['absence'] = $t['error'];
            }
        } else {
            $form_feedback['absence'] = implode("<br>", $errors);
        }
        
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenSupport | <?= $t['title']; ?> </title>
    <link rel="icon" type="image/x-icon" href="<?= $opensupport_link?>/src/opensupport_assets/opensupport_icon.svg">
    <link rel="stylesheet" href="<?= $opensupport_link?>/src/css/main.css">
    <link rel="manifest" href="<?= $opensupport_link ?>/manifest.json">
</head>
<body class="dashboard">

    <?php include("../src/php/dashboard_nav.php");?>
    
    <main>
        <header>
            <div>
                <h1><?= $t['title']; ?></h1>
                <p><?= $t['subtitle']; ?></p>
            </div>
        </header>

        <div class="grid-cols-4">
            <div class="summary">
                <a href="#profile_settings"><?= $t['profile']; ?></a>
                <a href="#absence"><?= $t['absence']; ?></a>
                <a href="#teams"><?= $t['teams']; ?></a>
                <a href="#data"><?= $t['data']; ?></a>
                <a href="#advenced_settings"><?= $t['advanced_settings']; ?></a>
            </div>
            
            <div class="content">
                <!-- Profile settings -->
                <section class="card" id="profile_settings">
                    <header>
                        <h2><?= $t['profile']; ?></h2>
                    </header>
                    <?php $sth = $dbco->prepare("SELECT users_first_name, users_last_name, users_email FROM users WHERE users_id= :user_id LIMIT 1;");
                    $sth->execute(['user_id' => $_SESSION['user_id']]);
                    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row):?>
                    <form target="" method="POST">
                        <?php if($form_feedback['global']!=""):?>
                        <p class="alert"><?php echo $form_feedback['global']; ?></p>
                        <?php endif;?>
                        <input type="hidden" name="form_section" value="global" required>
                        <div class="grid-cols-2">
                            <div>
                                <label><?= $t['profile_first_name']; ?></label>
                                <input type="text" name="first_name" value="<?= $row['users_first_name'] ?>" autocomplete="given-name" required>
                            </div>
                            <div>
                                <label><?= $t['profile_last_name']; ?></label>
                                <input type="text" name="last_name" value="<?= $row['users_last_name'] ?>" autocomplete="family-name" required>
                            </div>
                        </div>
                        <div>
                            <label><?= $t['profile_email']; ?></label>
                            <input type="email" name="email" value="<?= $row['users_email'] ?>" autocomplete="email" required>
                        </div>
                        <div>
                            <label><?= $t['profile_password']; ?></label>
                            <input type="password" id="new_password" name="new_password" autocomplete="new-password">
                            <div class="password-requirements">
                                <p id="req-length" class="invalid"><?= $t['profile_password_requirement1']; ?></p>
                                <p id="req-number" class="invalid"><?= $t['profile_password_requirement2']; ?></p>
                                <p id="req-special" class="invalid"><?= $t['profile_password_requirement3']; ?></p>
                            </div>
                        </div>
                        <div>
                            <label><?= $t['profile_password_confirm']; ?></label>
                            <input type="password" id="new_password_confirm" name="new_password_confirm" autocomplete="new-password">
                        </div>
                        <button type="submit"><?= $t['profile_modify']; ?></button>
                    </form>
                    <?php endforeach;?>
                </section>
                
                <!-- Absence -->
                <section class="card" id="profile_settings">
                    <header>
                        <h2><?= $t['absence']; ?></h2>
                        <p><?= $t['absence_subtitle']; ?></p>
                    </header>
                    <?php $sth = $dbco->prepare("SELECT users_absence FROM users WHERE users_id= :user_id LIMIT 1;");
                    $sth->execute(['user_id' => $_SESSION['user_id']]);
                    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row):
                    $absence_data = json_decode($row['users_absence'], true);
                    if (is_array($absence_data)) {
                        $absence_enabled = isset($absence_data['enabled']) ? (bool)$absence_data['enabled'] : false;
                        if (!empty($absence_data['start'])) {
                            $absence_start = date('Y-m-d\TH:i', strtotime($absence_data['start']));
                        }
                        if (!empty($absence_data['end'])) {
                            $absence_end = date('Y-m-d\TH:i', strtotime($absence_data['end']));
                        }
                    }?>
                    <form target="" method="POST">
                        <?php if($form_feedback['absence']!=""):?>
                        <p class="alert"><?php echo $form_feedback['absence']; ?></p>
                        <?php endif;?>
                        <input type="hidden" name="form_section" value="absence" required>
                        <div class="check">
                            <input type="checkbox" name="enabled" <?= $absence_enabled ? 'checked' : ''; ?>>
                            <label for="enabled"><?= $t['absence_enable']; ?></label>
                        </div>
                        <div class="grid-cols-2">
                            <div>
                                <label><?= $t['absence_start']; ?></label>
                                <input type="datetime-local" name="start_date" value="<?php echo htmlspecialchars($absence_start ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div>
                                <label><?= $t['absence_end']; ?></label>
                                <input type="datetime-local" name="end_date" value="<?php echo htmlspecialchars($absence_end ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                        <button type="submit"><?= $t['absence_modify']; ?></button>
                    </form>
                    <?php endforeach;?>
                </section>
                
                <!-- Teams -->
                <section class="card table" id="teams">
                    <header>
                        <h2><?= $t['teams']; ?></h2>
                    </header>
                    <div class="table-wrapper">
                        <table class="no-wrap">
                            <thead>
                                <tr>
                                    <th class="large"><?= $t['teams_name']; ?></th>
                                    <th><?= $t['teams_administrator']; ?></th>
                                    <th><?= $t['teams_join']; ?></th>
                                    <th no-filter><?= $t['teams_actions']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sth = $dbco->prepare("SELECT t.teams_id, t.teams_name, t.teams_owner, tm.teams_members_join_date, u.users_id, u.users_first_name, u.users_last_name FROM teams t INNER JOIN teams_members tm ON t.teams_id = tm.teams_members_team_id LEFT JOIN users u ON t.teams_owner = u.users_id WHERE t.teams_deleted = 0 AND tm.teams_members_deleted = 0 AND tm.teams_members_user_id = :user_id ORDER BY t.teams_name ASC;");
                                $sth->execute(['user_id' => $_SESSION['user_id']]);
                                $result_teams = $sth->fetchAll(PDO::FETCH_ASSOC);
                                if (count($result_teams)!=0):
                                    foreach ($result_teams as $row_teams):?>
                                    <tr team_id="<?= $row_teams['teams_id'] ?>">
                                        <td><?= $row_teams['teams_name'] ?></td>
                                        <td>
                                            <span class="sr-only"><?= htmlspecialchars($row_teams['users_first_name'] . ' ' . $row_tickets['users_last_name'])?></span>
                                            <span class="profile_picture <?php if($row_teams['users_id']==$_SESSION['user_id']){echo "me";}?>" title="<?= htmlspecialchars($row_teams['users_first_name'] . ' ' . $row_teams['users_last_name']) ?>" aria-hidden="true" user_id="<?= $row_teams['users_id'] ?>"><?= substr($row_teams['users_first_name'], 0, 1) ?></span>
                                        </td>
                                        <td><?= $row_teams['teams_members_join_date'] ?></td>
                                        <td>
                                            <a href="<?= $row_teams['teams_id'] ?>/action/quit_team"><?= $t['teams_quit']; ?></a>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                                <?php else:?>
                                </tbody>
                            </table>
                            <p class="table-empty"><?= $t['teams_empty']; ?></p>
                                <?php endif;?>
                    </div>
                </section>
        
                <!-- Data -->
                <section class="card" id="data">
                    <header>
                        <h2><?= $t['data']; ?></h2>
                    </header>
                    <div class="links">
                        <a href="<?= $opensupport_link?>/privacy_policy/" target="_blank"><?= $t['data_opensupport_privacy']; ?></a>
                        <a href="action/export_account"><?= $t['data_export_account']; ?></a>
                    </div>
                </section>
        
                <!-- Advanced settings -->
                <section class="card" id="advenced_settings">
                    <header>
                        <h2><?= $t['advanced_settings']; ?></h2>
                    </header>
                    <div class="links">
                        <a href="action/delete_account" class="red"><?= $t['advanced_settings_delete_account']; ?></a>
                    </div>
                </section>
            </div>
        </div>
        
        <!-- Footer -->
        <?php include("../src/php/dashboard_footer.php");?>
        <script src="<?= $opensupport_link?>/src/js/main.js"></script>
    </main>
</body>
</html>