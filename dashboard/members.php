<?php require("../config.php");
connectionCheck();

//Check current team
if(isset($_GET['team_id']) && $_GET['team_id'] != ""){
    if(verifyTeamAccess($_GET['team_id'])==false){
        header("Location: ../../dashboard/");
        exit();
    }
} else {
    header("Location: ../dashboard/");
    exit();
}

// Language manager
$translations = [
    'fr' => [
        'page_title' => 'Équipe Support',
        'back' => 'Retour',
        'admin_desc' => 'Gérez les accès et les membres de l\'équipe.',
        'collab_desc' => 'Consultez les membres de l\'équipe.',
        'btn_add_member' => 'Ajouter un membre',
        'help_title' => '👋 Hey, on ajoute des collègues ?',
        'help_desc' => 'Vous pouvez inviter de nouveaux membres dans votre équipe pour collaborer sur vos tickets !<br>Vous pouvez également affecter les membres à des groupes afin d\'attribuer automatiquement les demandes.',
        'role_admin' => 'Admin',
        'status_pending' => 'En attente...',
        'status_absent' => 'Absent',
        'unnamed_user' => 'Utilisateur sans nom',
        'none' => 'Aucun',
        'position' => 'Position',
        'groups' => 'Groupes',
        'invited_on' => 'Invitation envoyée le : ',
        'joined_on' => 'Ajouté le : ',
        'edit' => 'Éditer',
        'delete' => 'Supprimer',
        'transfer' => 'Transférer',
        'btn_transfer_admin' => 'Transférer le rôle d\'administrateur',
        'edit_title' => 'Éditer un membre',
        'transfer_title' => 'Transfert du rôle d\'administrateur',
        'transfer_desc' => 'Vous vous apprêtez à transférer le rôle d\'administrateur de l\'équipe :team à <strong>:name</strong> (:email).',
        'delete_title' => 'Supprimer un membre',
        'delete_desc' => 'Vous vous apprêtez à supprimer <strong>:name</strong> (:email) de l\'équipe :team.',
        'label_confirm_password' => 'Saisissez votre mot de passe pour confirmer *',
        'label_confirm_delete' => 'Saisissez "delete" pour confirmer *',
        'error_generic' => 'Une erreur est survenue, veuillez réessayer.',
        'error_password_incorrect' => 'Mot de passe incorrect.',
        'error_fill_all_fields' => 'Veuillez remplir tous les champs.',
        'error_delete_confirm' => 'Vous devez saisir exactement "delete" pour confirmer la suppression.'
    ],
    'en' => [
        'page_title' => 'Support Team',
        'back' => 'Back',
        'admin_desc' => 'Manage team access and members.',
        'collab_desc' => 'View team members.',
        'btn_add_member' => 'Add member',
        'help_title' => '👋 Hey, shall we add some colleagues?',
        'help_desc' => 'You can invite new members to your team to collaborate on support tickets!<br>You can also assign members to groups for automated routing.',
        'role_admin' => 'Admin',
        'status_pending' => 'Pending...',
        'status_absent' => 'Out of office',
        'unnamed_user' => 'Unnamed user',
        'none' => 'None',
        'position' => 'Position',
        'groups' => 'Groups',
        'invited_on' => 'Invitation sent on: ',
        'joined_on' => 'Joined on: ',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'transfer' => 'Transfer',
        'btn_transfer_admin' => 'Transfer administrator role',
        'edit_title' => 'Edit member',
        'transfer_title' => 'Transfer administrator role',
        'transfer_desc' => 'You are about to transfer the administrator role of team :team to <strong>:name</strong> (:email).',
        'delete_title' => 'Delete member',
        'delete_desc' => 'You are about to remove <strong>:name</strong> (:email) from team :team.',
        'label_confirm_password' => 'Enter your password to confirm *',
        'label_confirm_delete' => 'Type "delete" to confirm *',
        'error_generic' => 'An error occurred, please try again.',
        'error_password_incorrect' => 'Incorrect password.',
        'error_fill_all_fields' => 'Please fill in all fields.',
        'error_delete_confirm' => 'You must type exactly "delete" to confirm removal.'
    ],
    'es' => [
        'page_title' => 'Equipo de Soporte',
        'back' => 'Volver',
        'admin_desc' => 'Gestione el acceso y los miembros del equipo.',
        'collab_desc' => 'Consulte los miembros del equipo.',
        'btn_add_member' => 'Añadir miembro',
        'help_title' => '👋 ¡Hola! ¿Añadimos compañeros?',
        'help_desc' => '¡Puede invitar a nuevos miembros a su equipo para colaborar en los tickets!<br>También puede agregar miembros a grupos para asignaciones automáticas.',
        'role_admin' => 'Admin',
        'status_pending' => 'Pendiente...',
        'status_absent' => 'Ausente',
        'unnamed_user' => 'Usuario sin nombre',
        'none' => 'Ninguno',
        'position' => 'Puesto',
        'groups' => 'Grupos',
        'invited_on' => 'Invitación enviada el: ',
        'joined_on' => 'Unido el: ',
        'edit' => 'Editar',
        'delete' => 'Eliminar',
        'transfer' => 'Transferir',
        'btn_transfer_admin' => 'Transferir rol de administrador',
        'edit_title' => 'Editar miembro',
        'transfer_title' => 'Transferencia del rol de administrador',
        'transfer_desc' => 'Está a punto de transferir el rol de administrador del equipo :team a <strong>:name</strong> (:email).',
        'delete_title' => 'Eliminar miembro',
        'delete_desc' => 'Está a punto de eliminar a <strong>:name</strong> (:email) del equipo :team.',
        'label_confirm_password' => 'Introduzca su contraseña para confirmar *',
        'label_confirm_delete' => 'Escriba "delete" para confirmar *',
        'error_generic' => 'Ocurrió un error, por favor inténtelo de nuevo.',
        'error_password_incorrect' => 'Contraseña incorrecta.',
        'error_fill_all_fields' => 'Por favor, rellene todos los campos.',
        'error_delete_confirm' => 'Debe escribir exactamente "delete" para confirmar la eliminación.'
    ]
];
$t = $translations[$lang];

$form_feedback = "";

// FORMS PROCESS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_GET["page"] ?? '') == "edit") {
        // Edit member
        $teams_members_id = $_POST['teams_members_id'] ?? 0;
        $position = htmlspecialchars(trim($_POST['position'] ?? ''));
        $groups_string = htmlspecialchars(trim($_POST['groups'] ?? ''));
        $stmt_update = $dbco->prepare("UPDATE teams_members SET teams_members_position = :position, teams_members_groups = :groups WHERE teams_members_id = :tm_id");
        $stmt_update->execute(['position' => $position, 'groups' => $groups_string, 'tm_id' => $teams_members_id]);
        header("Location: ../../members");
        exit();
        
    } elseif (($_GET["page"] ?? '') == "transfer") {
        // Transfer admin role
        $teams_members_id = $_POST['teams_members_id'] ?? 0;
        $password_attempt = $_POST['password'] ?? '';
        if (!empty($teams_members_id) && !empty($password_attempt)) {
            $stmt_auth = $dbco->prepare("SELECT users_password FROM users WHERE users_id = :user_id");
            $stmt_auth->execute(['user_id' => $_SESSION['user_id']]);
            $current_user = $stmt_auth->fetch(PDO::FETCH_ASSOC);
            if ($current_user && password_verify($password_attempt, $current_user['users_password'])) {
                $stmt_member = $dbco->prepare("SELECT teams_members_user_id FROM teams_members WHERE teams_members_id = :teams_members_id AND teams_members_team_id = :teams_id AND teams_members_deleted = '0';");
                $stmt_member->execute(['teams_members_id' => $teams_members_id, 'teams_id' => $_SESSION['team_id']]);
                $new_admin = $stmt_member->fetch(PDO::FETCH_ASSOC);
                if ($new_admin) {
                    $new_owner_id = $new_admin['teams_members_user_id'];
                    $stmt_update = $dbco->prepare("UPDATE teams SET teams_owner = :new_owner_id WHERE teams_id = :team_id AND teams_owner = :current_user_id;");
                    $stmt_update->execute(['new_owner_id' => $new_owner_id, 'team_id' => $_SESSION['team_id'], 'current_user_id' => $_SESSION['user_id']]);
                    header("Location: ../../members");
                    exit();
                } else {
                    $form_feedback = $t['error_generic'];
                }
            } else {
                $form_feedback = $t['error_password_incorrect'];
            }
        } else {
            $form_feedback = $t['error_fill_all_fields'];
        }
        
    } elseif (($_GET["page"] ?? '') == "delete") {
        // Delete member
        $confirmation = trim($_POST['confirmation'] ?? '');
        $teams_members_id = $_POST['teams_members_id'] ?? 0;
        
        if (strtolower($confirmation) == 'delete' && !empty($teams_members_id)) {
            $stmt_info = $dbco->prepare("SELECT teams_members_user_id, teams_members_team_id, teams_members_join_date FROM teams_members WHERE teams_members_id = :teams_members_id;");
            $stmt_info->execute(['teams_members_id' => $teams_members_id]);
            $tm_info = $stmt_info->fetch(PDO::FETCH_ASSOC);
            
            if (!empty($tm_info) && empty($tm_info['teams_members_join_date'])) {
                $stmt_delete = $dbco->prepare("DELETE FROM teams_members WHERE teams_members_id = :team_id");
                $stmt_delete->execute(['team_id' => $teams_members_id]);
                header("Location: ../../members");
                exit();
            } elseif ($tm_info) {
                $user_id = $tm_info['teams_members_user_id'];
                $team_id = $tm_info['teams_members_team_id'];
                $stmt_delete = $dbco->prepare("UPDATE teams_members SET teams_members_deleted = '1' WHERE teams_members_id = :team_id");
                $stmt_delete->execute(['team_id' => $teams_members_id]);
                $stmt_tickets = $dbco->prepare("UPDATE tickets SET tickets_assigned_to = NULL WHERE tickets_assigned_to = :user_id AND tickets_teams = :team_id AND tickets_status != 2");
                $stmt_tickets->execute(['user_id' => $user_id, 'team_id' => $team_id]);
                header("Location: ../../members");
                exit();           
            } else {
                $form_feedback = $t['error_generic'];
            }
        } else {
            $form_feedback = $t['error_delete_confirm'];
        }
        
    } else {
        header("Location: ../../members");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <title>OpenSupport | <?= $t['page_title'] ?></title>
    <link rel="icon" type="image/x-icon" href="<?= $opensupport_link?>/src/opensupport_assets/opensupport_icon.svg">
    <link rel="stylesheet" href="<?= $opensupport_link?>/src/css/main.css">
    <link rel="manifest" href="<?= $opensupport_link ?>/manifest.json">
</head>
<?php if(isset($_GET["user_id"]) && $_GET["user_id"] != ""):?>
    <?php $target_user_id = (int)$_GET["user_id"];
    $stmt = $dbco->prepare("SELECT tm.*, u.users_first_name, u.users_last_name, u.users_email, t.teams_name, IF(t.teams_owner = u.users_id, 'admin', 'collab') AS role FROM users u INNER JOIN teams_members tm ON u.users_id = tm.teams_members_user_id INNER JOIN teams t ON tm.teams_members_team_id = t.teams_id WHERE u.users_id = :user_id AND tm.teams_members_team_id = :team_id AND u.users_deleted = '0' AND tm.teams_members_deleted = '0';");
    $stmt->execute([
        'user_id' => $target_user_id,
        'team_id' => $_SESSION['team_id']
    ]);
    $member_info = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$member_info) {
        header("Location: ../../members");
        exit();
    }
    
    $display_name = trim($member_info['users_first_name'] . ' ' . $member_info['users_last_name']);
    if (empty($display_name)) {
        $display_name = $t['unnamed_user'];
    }?>
    <body class="form">
        <div class="action_bar reverse">
            <a href="#" onclick="history.back()"><?= $t['back'] ?></a>
        </div>

        <main>
        <?php if(($_GET["page"] ?? '') == "edit"):?>
            <section class="box">
                <header>
                    <h1><?= $t['edit_title'] ?></h1>
                </header>
                
                <div class="member_card">
                    <div>
                        <p class="user_icon"><?= !empty($member_info['users_first_name']) ? substr($member_info['users_first_name'], 0, 1) : substr($member_info['users_email'], 0, 1) ?></p>
                        <div>
                            <h3><?= htmlspecialchars($display_name) ?></h3>
                            <p><?= htmlspecialchars($member_info['users_email']) ?></p>
                        </div>
                    </div>
                    <div class="tags">
                        <?php if($member_info['role'] == 'admin'): ?>
                            <span class="tag"><?= $t['role_admin'] ?></span>
                        <?php elseif($member_info['teams_members_join_date'] == ""): ?>
                            <span class="tag in-progress"><?= $t['status_pending'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <form method="POST">
                    <?php if($form_feedback != ""):?>
                    <p class="alert"><?= htmlspecialchars($form_feedback); ?></p>
                    <?php endif;?>
                    <input type="hidden" name="teams_members_id" value="<?= htmlspecialchars($member_info['teams_members_id']) ?>" required>
                    <div>
                        <label><?= $t['position'] ?></label>
                        <input type="text" name="position" value="<?= htmlspecialchars($member_info['teams_members_position'] ?? '') ?>">
                    </div>
                    <div>
                        <label><?= $t['groups'] ?></label>
                        <?php $existing_tags = [];
                            $stmt_groups = $dbco->prepare("SELECT teams_members_groups FROM teams_members WHERE teams_members_team_id = :team_id AND teams_members_deleted = '0' AND teams_members_groups IS NOT NULL AND teams_members_groups != '';");
                            $stmt_groups->execute(['team_id' => $_SESSION['team_id']]);
                            $all_groups_raw = $stmt_groups->fetchAll(PDO::FETCH_COLUMN);
                            foreach ($all_groups_raw as $group_row) {
                                $tags = explode(',', $group_row);
                                foreach ($tags as $tag) {
                                    $tag = trim($tag);
                                    if (!empty($tag) && !in_array($tag, $existing_tags)) {
                                        $existing_tags[] = $tag;
                                    }
                                }
                            }?>
                        <div class="tags-input-wrapper" id="tags-wrapper" data-whitelist='<?= htmlspecialchars(json_encode($existing_tags), ENT_QUOTES, 'UTF-8') ?>'>
                            <div id="tags-container" style="display: contents;">
                            <?php if(!empty($member_info['teams_members_groups'])):
                                $tags = explode(',', $member_info['teams_members_groups']);
                                foreach ($tags as $tag):
                                    $tag = trim($tag);
                                    if (!empty($tag)):?>
                                        <span class="tag"><?= htmlspecialchars($tag) ?><span class="remove-btn">×</span></span>
                                    <?php endif;
                                endforeach;
                            endif;?>
                            </div>
                            <input type="text" id="tag-input" autocomplete="off">
                            <ul id="autocomplete-list" class="autocomplete-list"></ul>
                        </div>
                        <input type="hidden" name="groups" id="hidden-groups-input" value="<?= htmlspecialchars($member_info['teams_members_groups'] ?? '') ?>">
                    </div>
                    <button type="submit"><?= $t['edit'] ?></button>
                    <?php if($member_info['role'] != 'admin' && !empty($member_info['teams_members_join_date'])): ?>
                    <div>
                        <hr>
                    </div>
                    <div>
                        <a href="../transfer/<?= (int)$_GET["user_id"] ?>" class="btn secondary"><?= $t['btn_transfer_admin'] ?></a>
                    </div>
                    <?php endif; ?>
                </form>
            </section>
            
        <?php elseif(($_GET["page"] ?? '') == "transfer"):?>
            <?php if($member_info['role'] == 'admin' || $member_info['users_id'] == $_SESSION['user_id']){
                header("Location: ../../members");
                exit();
            }?>
            <section class="box">
                <header>
                    <h1><?= $t['transfer_title'] ?></h1>
                </header>

                <form method="POST">
                    <input type="hidden" name="teams_members_id" value="<?= htmlspecialchars($member_info['teams_members_id']) ?>" required>
                    <div class="transfer">
                        <p class="user_icon"><?= substr($_SESSION['user_name'] ?? '', 0, 1)?></p>
                        <p>--></p>
                        <p class="user_icon"><?= !empty($member_info['users_first_name']) ? substr($member_info['users_first_name'], 0, 1) : substr($member_info['users_email'], 0, 1) ?></p>
                    </div>
                    <div>
                        <p><?= str_replace(
                            [':team', ':name', ':email'],
                            [htmlspecialchars($member_info['teams_name']), htmlspecialchars($display_name), htmlspecialchars($member_info['users_email'])],
                            $t['transfer_desc']
                        ) ?></p>
                    </div>
                    <?php if($form_feedback != ""):?>
                    <p class="alert"><?= htmlspecialchars($form_feedback); ?></p>
                    <?php endif;?>
                    <div>
                        <label><?= $t['label_confirm_password'] ?></label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit"><?= $t['transfer'] ?></button>
                </form>
            </section>

        <?php elseif(($_GET["page"] ?? '') == "delete"):?>
            <section class="box">
                <header>
                    <h1><?= $t['delete_title'] ?></h1>
                </header>
                
                <div class="member_card">
                    <div>
                        <p class="user_icon"><?= !empty($member_info['users_first_name']) ? substr($member_info['users_first_name'], 0, 1) : substr($member_info['users_email'], 0, 1) ?></p>
                        <div>
                            <h3><?= htmlspecialchars($display_name) ?></h3>
                            <p><?= htmlspecialchars($member_info['users_email']) ?></p>
                        </div>
                    </div>
                    <div class="tags">
                        <?php if($member_info['role'] == 'admin'): ?>
                            <span class="tag"><?= $t['role_admin'] ?></span>
                        <?php elseif($member_info['teams_members_join_date'] == ""): ?>
                            <span class="tag in-progress"><?= $t['status_pending'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <form method="POST">
                    <?php if($form_feedback != ""):?>
                    <p class="alert"><?= htmlspecialchars($form_feedback); ?></p>
                    <?php endif;?>
                    <input type="hidden" name="teams_members_id" value="<?= htmlspecialchars($member_info['teams_members_id']) ?>" required>
                    <div>
                        <p><?= str_replace(
                            [':team', ':name', ':email'],
                            [htmlspecialchars($member_info['teams_name']), htmlspecialchars($display_name), htmlspecialchars($member_info['users_email'])],
                            $t['delete_desc']
                        ) ?></p>
                    </div>
                    <div>
                        <label><?= $t['label_confirm_delete'] ?></label>
                        <input type="text" name="confirmation" autocomplete="off" required>
                    </div>
                    <button type="submit"><?= $t['delete'] ?></button>
                </form>
            </section>
        
        <?php else:?>
            <?php header("Location: ../../members");
            exit();?>
        <?php endif;?>
        </main>
        <script src="<?= $opensupport_link?>/src/js/main.js"></script>
    </body>
    
<?php else:?>
<body class="dashboard">
    
    <?php include("../src/php/dashboard_nav.php");?>
    
    <main>
        <header>
            <div>
                <h1><?= $t['page_title'] ?></h1>
                <?php if($_SESSION['team_role'] == "admin"):?>
                    <p><?= $t['admin_desc'] ?></p>
                <?php else :?>
                    <p><?= $t['collab_desc'] ?></p>
                <?php endif; ?>
            </div>
            <a href="new/member" class="btn"><?= $t['btn_add_member'] ?></a>
        </header>
        
        <?php $sth = $dbco->prepare("SELECT u.users_id, u.users_first_name, u.users_last_name, u.users_email, tm.teams_members_position, tm.teams_members_groups, tm.teams_members_join_date, tm.teams_members_invite_date, IF(t.teams_owner = u.users_id, 'admin', 'collab') AS role FROM teams_members tm INNER JOIN users u ON tm.teams_members_user_id = u.users_id INNER JOIN teams t ON tm.teams_members_team_id = t.teams_id WHERE tm.teams_members_team_id = :team_id AND u.users_deleted = FALSE AND tm.teams_members_deleted = '0' ORDER BY role DESC, u.users_last_name ASC, u.users_first_name ASC;");
        $sth->execute(['team_id' => $_SESSION['team_id']]);
        $resultat = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($_SESSION['team_role'] == "admin" && count($resultat) <= 1): 
        ?>
        <section class="card help">
            <header>
                <h2><?= $t['help_title'] ?></h2>
            </header>
            <p><?= $t['help_desc'] ?></p>
            <div>
                <a href="new/member" class="btn"><?= $t['btn_add_member'] ?></a>
            </div>
        </section>
        <?php endif; ?>

        <div class="grid-cols-3">
            <?php 
            if (count($resultat) != 0):
                foreach ($resultat as $row): ?>
                <section class="card member">
                    <header>
                        <div>
                            <p class="user_icon"><?= !empty($row['users_first_name']) ? substr($row['users_first_name'], 0, 1) : substr($row['users_email'], 0, 1) ?></p>
                            <div>
                                <h3><?= htmlspecialchars($row['users_first_name'] . ' ' . $row['users_last_name']) ?></h3>
                                <p><?= htmlspecialchars($row['users_email']) ?></p>
                            </div>
                        </div>
                        <div class="tags">
                            <?php if($row['role'] == 'admin'): ?>
                                <span class="tag"><?= $t['role_admin'] ?></span>
                            <?php elseif(empty($row['teams_members_join_date'])): ?>
                                <span class="tag in-progress"><?= $t['status_pending'] ?></span>
                            <?php endif; ?>
                            <?php if(isUserCurrentlyAbsent($row['users_id'])): ?>
                                <span class="tag"><?= $t['status_absent'] ?></span>
                            <?php endif; ?>
                        </div>
                    </header>
                    <div class="details">
                        <span><?= $t['position'] ?>: <strong><?= htmlspecialchars($row['teams_members_position'] ?? '') ?></strong></span>
                        <span><?= $t['groups'] ?>: <?php if(!empty($row['teams_members_groups'])):
                            $tags = explode(',', $row['teams_members_groups']);
                            foreach ($tags as $tag):
                                $tag = trim($tag);
                                if (!empty($tag)):?>
                                    <span class="tag"><?= htmlspecialchars($tag) ?></span>
                                <?php endif;
                            endforeach;
                        else:?>
                           <?= $t['none'] ?>
                        <?php endif;?></span>
                    </div>
                    <hr>
                    <footer>
                        <?php if(empty($row['teams_members_join_date'])):?>
                            <p><?= $t['invited_on'] . htmlspecialchars($row['teams_members_invite_date']) ?></p>
                        <?php else: ?>
                            <p><?= $t['joined_on'] . htmlspecialchars($row['teams_members_join_date']) ?></p>
                        <?php endif; ?>
                        <?php if($_SESSION['team_role'] == "admin"):?>
                        <div class="actions">
                            <a href="members/edit/<?= (int)$row['users_id'] ?>"><?= $t['edit'] ?></a>
                            <?php if($row['users_id'] != $_SESSION['user_id']):?>
                                <a href="members/delete/<?= (int)$row['users_id'] ?>"><?= $t['delete'] ?></a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </footer>
                </section>
            <?php endforeach; 
            endif; ?>
        </div>
        
        <!-- Footer -->
        <?php include("../src/php/dashboard_footer.php");?>
        <script src="<?= $opensupport_link?>/src/js/main.js"></script>
    </main>
</body>
<?php endif;?>
</html>