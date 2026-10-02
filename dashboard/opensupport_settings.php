<?php require("../config.php");
connectionCheck();

// Verify access
$stmtAdminCheck = $dbco->prepare("SELECT users_admin FROM users WHERE users_id = :user_id LIMIT 1");
$stmtAdminCheck->execute(['user_id' => $_SESSION['user_id']]);
$userAdminStatus = $stmtAdminCheck->fetchColumn();
if (!$userAdminStatus || (int)$userAdminStatus !== 1) {
    header("Location: ../dashboard/");
    exit();
}

// Language manager
$translations = [
    'fr' => [
        'page_title' => 'Paramètres OpenSupport',
        'page_subtitle' => 'Supervisez et paramétrez votre instance OpenSupport.',
        'btn_modify' => 'Modifier',
        'nav_global' => 'Paramètres généraux',
        'nav_server' => 'Configuration serveur',
        'nav_db' => 'Base de données',
        'nav_users' => 'Utilisateurs',
        'nav_teams' => 'Équipes',
        'nav_data' => 'Data',
        'feedback_global_success' => 'Paramètres généraux mis à jour avec succès.',
        'feedback_env_error' => 'Erreur lors de l\'écriture dans le fichier config.env.php.',
        'feedback_server_required' => 'Le nom de domaine et l\'URL complète sont obligatoires.',
        'feedback_server_success' => 'Configuration serveur mise à jour avec succès.',
        'feedback_db_required' => 'Le nom de la base et l\'utilisateur sont obligatoires.',
        'feedback_db_success' => 'Identifiants de base de données validés et enregistrés.',
        'feedback_db_error' => 'Impossible de se connecter avec ces paramètres : ',
        'feedback_roles_success' => 'Les droits administrateurs d\'instance ont été mis à jour.',
        'feedback_roles_error' => 'Erreur lors de la mise à jour des droits.',
        'global_max_files' => 'Nombre maximum de fichiers par demande *',
        'global_max_file_size' => 'Taille maximum par fichier (en Mo) *',
        'global_host_name' => 'Hébergeur (Mentions légales)',
        'global_host_details' => 'Adresse / contact de l\'hébergeur',
        'global_host_name_placeholder' => 'Ex : OVH, AWS, Hetzner',
        'global_host_details_placeholder' => 'Ex : 2 rue Kellermann, Roubaix',
        'server_domain' => 'Nom de domaine',
        'server_tz' => 'Fuseau horaire (Timezone)',
        'server_url' => 'URL complète d\'accès (sans slash final)',
        'db_host' => 'Hôte MySQL',
        'db_port' => 'Port',
        'db_name' => 'Nom de la base de données',
        'db_user' => 'Utilisateur MySQL',
        'db_pass' => 'Mot de passe',
        'btn_add_users' => '+ Ajouter des utilisateurs',
        'th_user_name' => 'Nom',
        'th_user_email' => 'Email',
        'th_user_admin' => 'Administrateur',
        'th_user_status' => 'Statut',
        'th_user_created' => 'Créé le',
        'tooltip_self_admin' => 'Vous ne pouvez pas retirer vos propres droits administrateur',
        'status_deleted' => 'Supprimé',
        'status_pending' => 'En attente...',
        'status_active' => 'Actif',
        'users_empty' => 'Aucun utilisateur trouvé.',
        'btn_save_roles' => 'Sauvegarder les rôles',
        'th_team_name' => 'Nom',
        'th_team_admin' => 'Administrateur',
        'th_team_created' => 'Créé le',
        'teams_empty' => 'Aucune équipe.',
        'data_privacy_link' => 'Politique de confidentialité de OpenSupport',
        'data_export_link' => 'Exporter les données de l\'instance'
    ],
    'en' => [
        'page_title' => 'OpenSupport Settings',
        'page_subtitle' => 'Supervise and configure your OpenSupport instance.',
        'btn_modify' => 'Update',
        'nav_global' => 'General Settings',
        'nav_server' => 'Server Configuration',
        'nav_db' => 'Database',
        'nav_users' => 'Users',
        'nav_teams' => 'Teams',
        'nav_data' => 'Data',
        'feedback_global_success' => 'General settings successfully updated.',
        'feedback_env_error' => 'Error writing to the config.env.php file.',
        'feedback_server_required' => 'Domain name and full URL are required.',
        'feedback_server_success' => 'Server configuration successfully updated.',
        'feedback_db_required' => 'Database name and username are required.',
        'feedback_db_success' => 'Database credentials validated and saved.',
        'feedback_db_error' => 'Unable to connect with these parameters: ',
        'feedback_roles_success' => 'Instance administrator rights have been updated.',
        'feedback_roles_error' => 'Error updating administrator rights.',
        'global_max_files' => 'Maximum files per request *',
        'global_max_file_size' => 'Maximum size per file (in MB) *',
        'global_host_name' => 'Hosting Provider (Legal Notice)',
        'global_host_details' => 'Host address / contact',
        'global_host_name_placeholder' => 'E.g., OVH, AWS, Hetzner',
        'global_host_details_placeholder' => 'E.g., 2 rue Kellermann, Roubaix',
        'server_domain' => 'Domain name',
        'server_tz' => 'Timezone',
        'server_url' => 'Full access URL (without trailing slash)',
        'db_host' => 'MySQL Host',
        'db_port' => 'Port',
        'db_name' => 'Database Name',
        'db_user' => 'MySQL User',
        'db_pass' => 'Password',
        'btn_add_users' => '+ Add users',
        'th_user_name' => 'Name',
        'th_user_email' => 'Email',
        'th_user_admin' => 'Administrator',
        'th_user_status' => 'Status',
        'th_user_created' => 'Created on',
        'tooltip_self_admin' => 'You cannot remove your own administrator rights',
        'status_deleted' => 'Deleted',
        'status_pending' => 'Pending...',
        'status_active' => 'Active',
        'users_empty' => 'No users found.',
        'btn_save_roles' => 'Save roles',
        'th_team_name' => 'Name',
        'th_team_admin' => 'Administrator',
        'th_team_created' => 'Created on',
        'teams_empty' => 'No teams.',
        'data_privacy_link' => 'OpenSupport Privacy Policy',
        'data_export_link' => 'Export instance data'
    ],
    'es' => [
        'page_title' => 'Ajustes de OpenSupport',
        'page_subtitle' => 'Supervise y configure su instancia de OpenSupport.',
        'btn_modify' => 'Modificar',
        'nav_global' => 'Ajustes generales',
        'nav_server' => 'Configuración del servidor',
        'nav_db' => 'Base de datos',
        'nav_users' => 'Usuarios',
        'nav_teams' => 'Equipos',
        'nav_data' => 'Datos',
        'feedback_global_success' => 'Ajustes generales actualizados con éxito.',
        'feedback_env_error' => 'Error al escribir en el archivo config.env.php.',
        'feedback_server_required' => 'El nombre de dominio y la URL completa son obligatorios.',
        'feedback_server_success' => 'Configuración del servidor actualizada con éxito.',
        'feedback_db_required' => 'El nombre de la base de datos y el usuario son obligatorios.',
        'feedback_db_success' => 'Credenciales de la base de datos validadas y guardadas.',
        'feedback_db_error' => 'No es posible conectarse con estos parámetros: ',
        'feedback_roles_success' => 'Los permisos de administrador de la instancia se han actualizado.',
        'feedback_roles_error' => 'Error al actualizar los permisos.',
        'global_max_files' => 'Número máximo de archivos por solicitud *',
        'global_max_file_size' => 'Tamaño máximo por archivo (en MB) *',
        'global_host_name' => 'Proveedor de alojamiento (Aviso legal)',
        'global_host_details' => 'Dirección / contacto del alojamiento',
        'global_host_name_placeholder' => 'Ej.: OVH, AWS, Hetzner',
        'global_host_details_placeholder' => 'Ej.: 2 rue Kellermann, Roubaix',
        'server_domain' => 'Nombre de dominio',
        'server_tz' => 'Zona horaria (Timezone)',
        'server_url' => 'URL completa de acceso (sin barra final)',
        'db_host' => 'Host MySQL',
        'db_port' => 'Puerto',
        'db_name' => 'Nombre de la base de datos',
        'db_user' => 'Usuario MySQL',
        'db_pass' => 'Contraseña',
        'btn_add_users' => '+ Añadir usuarios',
        'th_user_name' => 'Nombre',
        'th_user_email' => 'Correo electrónico',
        'th_user_admin' => 'Administrador',
        'th_user_status' => 'Estado',
        'th_user_created' => 'Creado el',
        'tooltip_self_admin' => 'No puede retirar sus propios permisos de administrador',
        'status_deleted' => 'Eliminado',
        'status_pending' => 'Pendiente...',
        'status_active' => 'Activo',
        'users_empty' => 'No se encontraron usuarios.',
        'btn_save_roles' => 'Guardar roles',
        'th_team_name' => 'Nombre',
        'th_team_admin' => 'Administrador',
        'th_team_created' => 'Creado el',
        'teams_empty' => 'Ningún equipo.',
        'data_privacy_link' => 'Política de privacidad de OpenSupport',
        'data_export_link' => 'Exportar datos de la instancia'
    ]
];
$t = $translations[$lang];

$envPath = __DIR__ . '/../config.env.php';
$envConfig = file_exists($envPath) ? require($envPath) : [];
$form_feedback = [
    'global' => '',
    'server' => '',
    'db'     => '',
    'users'  => ''
];

function saveEnvConfig(string $filePath, array $config): bool {
    $content = "<?php\n";
    $content .= "defined('OPEN_SUPPORT_INIT') or exit('Access denied');\n\n";
    $content .= "return " . var_export($config, true) . ";\n";
    return (bool)file_put_contents($filePath, $content, LOCK_EX);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section = $_POST['form_section'] ?? '';
    // Global
    if ($section === 'global') {
        $max_files = max(1, (int)($_POST['max_files'] ?? 5));
        $max_file_size = max(1, (int)($_POST['max_file_size'] ?? 10));
        $host_name = trim($_POST['host_name'] ?? '');
        $host_details = trim($_POST['host_details'] ?? '');
        $envConfig['app']['max_files'] = $max_files;
        $envConfig['app']['max_file_size'] = $max_file_size;
        $envConfig['app']['host_info'] = [
            'provider' => $host_name,
            'details'  => $host_details
        ];
        if (saveEnvConfig($envPath, $envConfig)) {
            $opensupport_max_files = $max_files;
            $opensupport_max_file_size = $max_file_size;
            $opensupport_host_info = $envConfig['app']['host_info'];
            $form_feedback['global'] = $t['feedback_global_success'];
        } else {
            $form_feedback['global'] = $t['feedback_env_error'];
        }

    // Server configuration
    } elseif ($section === 'server') {
        $app_domain = trim($_POST['app_domain'] ?? '');
        $app_tz = trim($_POST['app_tz'] ?? 'Europe/Paris');
        $app_url = rtrim(trim($_POST['app_url'] ?? ''), '/');
        if (empty($app_domain) || empty($app_url)) {
            $form_feedback['server'] = $t['feedback_server_required'];
        } else {
            $envConfig['app']['domain'] = $app_domain;
            $envConfig['app']['timezone'] = $app_tz;
            $envConfig['app']['url'] = $app_url;
            if (saveEnvConfig($envPath, $envConfig)) {
                $opensupport_domain = $app_domain;
                $opensupport_link = $app_url;
                date_default_timezone_set($app_tz);
                $form_feedback['server'] = $t['feedback_server_success'];
            } else {
                $form_feedback['server'] = $t['feedback_env_error'];
            }
        }

    // Data base
    } elseif ($section === 'db') {
        $post_host = trim($_POST['db_host'] ?? '127.0.0.1');
        $post_port = (int)($_POST['db_port'] ?? 3306);
        $post_name = trim($_POST['db_name'] ?? '');
        $post_user = trim($_POST['db_user'] ?? '');
        $post_pass = $_POST['db_pass'] !== '' ? $_POST['db_pass'] : ($envConfig['db']['pass'] ?? '');
        if (empty($post_name) || empty($post_user)) {
            $form_feedback['db'] = $t['feedback_db_required'];
        } else {
            try {
                new PDO("mysql:host={$post_host};port={$post_port};dbname={$post_name};charset=utf8mb4", $post_user, $post_pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
                $envConfig['db']['host'] = $post_host;
                $envConfig['db']['port'] = $post_port;
                $envConfig['db']['name'] = $post_name;
                $envConfig['db']['user'] = $post_user;
                $envConfig['db']['pass'] = $post_pass;
                if (saveEnvConfig($envPath, $envConfig)) {
                    $db_host = $post_host;
                    $db_port = $post_port;
                    $db_name = $post_name;
                    $db_user = $post_user;
                    $db_pass = $post_pass;
                    $form_feedback['db'] = $t['feedback_db_success'];
                } else {
                    $form_feedback['db'] = $t['feedback_env_error'];
                }
            } catch (PDOException $e) {
                $form_feedback['db'] = $t['feedback_db_error'] . $e->getMessage();
            }
        }

    // Users administrator
    } elseif ($section === 'users_roles') {
        $adminUsers = array_map('intval', $_POST['users_admin'] ?? []);
        if (!in_array((int)$_SESSION['user_id'], $adminUsers, true)) {
            $adminUsers[] = (int)$_SESSION['user_id'];
        }
        try {
            $dbco->beginTransaction();
            $dbco->exec("UPDATE users SET users_admin = 0");
            if (!empty($adminUsers)) {
                $inQuery = implode(',', $adminUsers);
                $dbco->exec("UPDATE users SET users_admin = 1 WHERE users_id IN ($inQuery)");
            }
            $dbco->commit();
            $form_feedback['users'] = $t['feedback_roles_success'];
        } catch (Exception $e) {
            $dbco->rollBack();
            $form_feedback['users'] = $t['feedback_roles_error'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenSupport | <?= $t['nav_global'] ?></title>
    <link rel="icon" type="image/x-icon" href="<?= $opensupport_link?>/src/opensupport_assets/opensupport_icon.svg">
    <link rel="stylesheet" href="<?= $opensupport_link?>/src/css/main.css">
    <link rel="manifest" href="<?= $opensupport_link ?>/manifest.json">
</head>
<body class="dashboard">

    <?php include("../src/php/dashboard_nav.php");?>
    
    <main>
        <header>
            <div>
                <h1><?= $t['page_title'] ?></h1>
                <p><?= $t['page_subtitle'] ?></p>
            </div>
        </header>

        <div class="grid-cols-4">
            <div class="summary">
                <a href="#global"><?= $t['nav_global'] ?></a>
                <a href="#server"><?= $t['nav_server'] ?></a>
                <a href="#db"><?= $t['nav_db'] ?></a>
                <a href="#users"><?= $t['nav_users'] ?></a>
                <a href="#teams"><?= $t['nav_teams'] ?></a>
                <a href="#data"><?= $t['nav_data'] ?></a>
            </div>
            
            <div class="content">
                <!-- Global -->
                <section class="card" id="global">
                    <header>
                        <h2><?= $t['nav_global'] ?></h2>
                    </header>
                    <form method="POST">
                        <?php if($form_feedback['global'] != ""):?>
                            <p class="alert"><?= htmlspecialchars($form_feedback['global']); ?></p>
                        <?php endif;?>
                        <input type="hidden" name="form_section" value="global" required>
                        <div class="grid-cols-2">
                            <div>
                                <label><?= $t['global_max_files'] ?></label>
                                <input type="number" name="max_files" value="<?= $opensupport_max_files ?>" min="1" required>
                            </div>
                            <div>
                                <label><?= $t['global_max_file_size'] ?></label>
                                <input type="number" name="max_file_size" value="<?= $opensupport_max_file_size ?>" min="1" required>
                            </div>
                        </div>
                        <div class="grid-cols-2">
                            <div>
                                <label><?= $t['global_host_name'] ?></label>
                                <input type="text" name="host_name" value="<?= htmlspecialchars($opensupport_host_info['provider'] ?? '') ?>" placeholder="<?= $t['global_host_name_placeholder'] ?>">
                            </div>
                            <div>
                                <label><?= $t['global_host_details'] ?></label>
                                <input type="text" name="host_details" value="<?= htmlspecialchars($opensupport_host_info['details'] ?? '') ?>" placeholder="<?= $t['global_host_details_placeholder'] ?>">
                            </div>
                        </div>
                        <button type="submit"><?= $t['btn_modify'] ?></button>
                    </form>
                </section>
                
                <!-- Server -->
                <section class="card" id="server">
                    <header>
                        <h2><?= $t['nav_server'] ?></h2>
                    </header>
                    <form method="POST">
                        <?php if($form_feedback['server'] != ""):?>
                            <p class="alert"><?= htmlspecialchars($form_feedback['server']); ?></p>
                        <?php endif;?>
                        <input type="hidden" name="form_section" value="server" required>
                        <div class="grid-cols-2">
                            <div>
                                <label><?= $t['server_domain'] ?></label>
                                <input type="text" name="app_domain" value="<?= htmlspecialchars($opensupport_domain ?? '') ?>" required>
                            </div>
                            <div>
                                <label><?= $t['server_tz'] ?></label>
                                <select id="app_tz" name="app_tz" required>
                                    <?php $current_tz = $envConfig['app']['timezone'] ?? 'Europe/Paris';
                                    $timezones = DateTimeZone::listIdentifiers();
                                    foreach ($timezones as $tz) {
                                        $selected = ($tz === $current_tz) ? ' selected' : '';
                                        echo "<option value=\"{$tz}\"{$selected}>{$tz}</option>";
                                    }?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label><?= $t['server_url'] ?></label>
                            <input type="url" name="app_url" value="<?= htmlspecialchars($opensupport_link ?? '') ?>" required>
                        </div>
                        <button type="submit"><?= $t['btn_modify'] ?></button>
                    </form>
                </section>
                
                <!-- DB server -->
                <section class="card" id="db">
                    <header>
                        <h2><?= $t['nav_db'] ?></h2>
                    </header>
                    <form method="POST">
                        <?php if($form_feedback['db'] != ""):?>
                            <p class="alert"><?= htmlspecialchars($form_feedback['db']); ?></p>
                        <?php endif;?>
                        <input type="hidden" name="form_section" value="db" required>
                        <div class="grid-cols-2">
                            <div>
                                <label><?= $t['db_host'] ?></label>
                                <input type="text" name="db_host" value="<?= htmlspecialchars($db_host ?? '') ?>" required>
                            </div>
                            <div>
                                <label><?= $t['db_port'] ?></label>
                                <input type="number" name="db_port" value="<?= (int)($db_port ?? 3306) ?>" required>
                            </div>
                        </div>
                        <div>
                            <label><?= $t['db_name'] ?></label>
                            <input type="text" name="db_name" placeholder="opensupport" value="<?= htmlspecialchars($db_name ?? '') ?>" required>
                        </div>
                        <div class="grid-cols-2">
                            <div>
                                <label><?= $t['db_user'] ?></label>
                                <input type="text" name="db_user" value="<?= htmlspecialchars($db_user ?? '') ?>" required>
                            </div>
                            <div>
                                <label><?= $t['db_pass'] ?></label>
                                <input type="password" name="db_pass">
                            </div>
                        </div>
                        <button type="submit"><?= $t['btn_modify'] ?></button>
                    </form>
                </section>
                
                <!-- Users -->
                <section class="card table" id="users">
                    <header class="action">
                        <h2><?= $t['nav_users'] ?></h2>
                        <a href="<?= $opensupport_link ?>/dashboard/new/users" class="btn" style="text-decoration: none;"><?= $t['btn_add_users'] ?></a>
                    </header>
                    <?php if (!empty($form_feedback['users'])): ?>
                        <p class="alert" style="margin: 15px;"><?= htmlspecialchars($form_feedback['users']) ?></p>
                    <?php endif; ?>
                    <form method="POST">
                        <input type="hidden" name="form_section" value="users_roles" required>
                        <div class="table-wrapper">
                            <table class="no-wrap">
                                <thead>
                                    <tr>
                                        <th><?= $t['th_user_name'] ?></th>
                                        <th><?= $t['th_user_email'] ?></th>
                                        <th><?= $t['th_user_admin'] ?></th>
                                        <th><?= $t['th_user_status'] ?></th>
                                        <th><?= $t['th_user_created'] ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $stmtUsers = $dbco->query("SELECT users_id, users_first_name, users_last_name, users_email, users_admin, users_deleted, users_creation_date FROM users ORDER BY users_creation_date DESC");
                                    $allUsers = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
                                    if (!empty($allUsers)):
                                        foreach ($allUsers as $u):
                                            $isCurrentUser = ($u['users_id'] == $_SESSION['user_id']);?>
                                            <tr>
                                                <td class="user">
                                                    <span class="profile_picture <?= $isCurrentUser ? 'me' : '' ?>" title="<?= htmlspecialchars($u['users_first_name'] . ' ' . $u['users_last_name']) ?>">
                                                        <?= htmlspecialchars(substr($u['users_first_name'] ?: $u['users_email'], 0, 1)) ?>
                                                    </span>
                                                    <strong><?= htmlspecialchars($u['users_first_name'] . ' ' . $u['users_last_name']) ?></strong>
                                                </td>
                                                <td><?= htmlspecialchars($u['users_email']) ?></td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" name="users_admin[]" value="<?= (int)$u['users_id'] ?>" <?= ((int)($u['users_admin'] ?? 0) === 1) ? 'checked' : '' ?> <?= $isCurrentUser ? 'onclick="return false;" title="' . htmlspecialchars($t['tooltip_self_admin']) . '"' : '' ?>>
                                                </td>
                                                <td>
                                                    <?php if($u['users_deleted'] == 1): ?>
                                                        <span class="tag closed"><?= $t['status_deleted'] ?></span>
                                                    <?php elseif($u['users_first_name'] == "" && $u['users_last_name'] == ""): ?>
                                                        <span class="tag in-progress"><?= $t['status_pending'] ?></span>
                                                    <?php else: ?>
                                                        <span class="tag"><?= $t['status_active'] ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($u['users_creation_date']) ?></td>
                                            </tr>
                                        <?php endforeach; else: ?>
                                            <tr>
                                                <td colspan="5" class="table-empty"><?= $t['users_empty'] ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        <div style="margin: 0; padding: 15px;">
                            <button type="submit"><?= $t['btn_save_roles'] ?></button>
                        </div>
                    </form>
                </section>
        
                <!-- Teams -->
                <section class="card table" id="teams">
                    <header>
                        <h2><?= $t['nav_teams'] ?></h2>
                    </header>
                    <div class="table-wrapper">
                        <table class="no-wrap">
                            <thead>
                                <tr>
                                    <th class="large"><?= $t['th_team_name'] ?></th>
                                    <th><?= $t['th_team_admin'] ?></th>
                                    <th><?= $t['th_team_created'] ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sth = $dbco->prepare("SELECT t.teams_id, t.teams_name, t.teams_owner, t.teams_creation_date, tm.teams_members_join_date, u.users_id, u.users_first_name, u.users_last_name FROM teams t INNER JOIN teams_members tm ON t.teams_id = tm.teams_members_team_id LEFT JOIN users u ON t.teams_owner = u.users_id WHERE t.teams_deleted = 0 AND tm.teams_members_deleted = 0 ORDER BY t.teams_name ASC;");
                                $sth->execute();
                                $result_teams = $sth->fetchAll(PDO::FETCH_ASSOC);
                                if (count($result_teams) != 0):
                                    foreach ($result_teams as $row_teams):?>
                                    <tr team_id="<?= $row_teams['teams_id'] ?>">
                                        <td><?= htmlspecialchars($row_teams['teams_name']) ?></td>
                                        <td>
                                            <span class="sr-only"><?= htmlspecialchars($row_teams['users_first_name'] . ' ' . $row_teams['users_last_name'])?></span>
                                            <span class="profile_picture <?php if($row_teams['users_id'] == $_SESSION['user_id']){echo "me";}?>" title="<?= htmlspecialchars($row_teams['users_first_name'] . ' ' . $row_teams['users_last_name']) ?>" aria-hidden="true" user_id="<?= $row_teams['users_id'] ?>"><?= substr($row_teams['users_first_name'] ?? '', 0, 1) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($row_teams['teams_creation_date']) ?></td>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                                <?php else:?>
                                </tbody>
                            </table>
                            <p class="table-empty"><?= $t['teams_empty'] ?></p>
                                <?php endif;?>
                    </div>
                </section>
        
                <!-- Data -->
                <section class="card" id="data">
                    <header>
                        <h2><?= $t['nav_data'] ?></h2>
                    </header>
                    <div class="links">
                        <a href="<?= $opensupport_link?>/privacy_policy/" target="_blank"><?= $t['data_privacy_link'] ?></a>
                        <a href="action/export"><?= $t['data_export_link'] ?></a>
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