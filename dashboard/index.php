<?php require("../config.php");
connectionCheck();

// Reset values
$_SESSION['team_id']="";
$_SESSION['team_role']="";

// Language manager
$translations = [
    'fr' => [
        'page_title' => 'Menu',
        'welcome' => 'Bienvenue :user !',
        'btn_create_team' => 'Créer une équipe',
        'help_title' => '👋 Premiers pas sur OpenSupport',
        'help_intro' => 'Bienvenue dans votre espace d\'assistance !<br>Voici comment fonctionne OpenSupport :',
        'help_step1_title' => '1. Équipes & Formulaire',
        'help_step1_desc' => 'Créez ou rejoignez une équipe pour obtenir une URL de formulaire public dédiée à vos clients.',
        'help_step2_title' => '2. Gestion des tickets',
        'help_step2_desc' => 'Suivez l\'état d\'avancement des demandes, attribuez des priorités et dialoguez en direct avec vos demandeurs.',
        'help_step3_title' => '3. Réponses & Fichiers',
        'help_step3_desc' => 'Utilisez les messages prédéfinis dans le chat et demandez des pièces jointes de manière sécurisée.',
        'help_doc_link' => 'Besoin d\'aide ? Consultez notre documentation !',
        'help_btn_start' => 'Créons votre première équipe',
        'stat_new_tickets' => 'Nouveaux tickets',
        'stat_inprogress_tickets' => 'Tickets en cours',
        'stat_closed_tickets' => 'Tickets clos ce mois-ci',
        'stat_satisfaction' => 'Satisfaction client',
        'table_ticket' => 'Ticket',
        'table_client' => 'Client',
        'table_subject' => 'Objet',
        'table_priority' => 'Priorité',
        'table_status' => 'Statut',
        'table_actions' => 'Actions',
        'status_new' => 'Nouveau',
        'status_inprogress' => 'En cours',
        'status_closed' => 'Fermé',
        'btn_open_chat' => 'Ouvrir Chat ➔',
        'btn_see_more' => 'Voir plus ➔',
        'empty_tickets' => 'Aucun nouveau ticket.',
        'join_team' => 'Rejoindre une équipe.',
        'create_team' => 'Créer une équipe.'
    ],
    'en' => [
        'page_title' => 'Dashboard',
        'welcome' => 'Welcome :user!',
        'btn_create_team' => 'Create a team',
        'help_title' => '👋 Getting Started with OpenSupport',
        'help_intro' => 'Welcome to your support workspace!<br>Here is how OpenSupport works:',
        'help_step1_title' => '1. Teams & Forms',
        'help_step1_desc' => 'Create or join a team to get a dedicated public form URL for your clients.',
        'help_step2_title' => '2. Ticket Management',
        'help_step2_desc' => 'Track request progress, assign priorities, and communicate directly with requesters.',
        'help_step3_title' => '3. Replies & Files',
        'help_step3_desc' => 'Use pre-defined templates in chat and request file attachments securely.',
        'help_doc_link' => 'Need help? Check out our documentation!',
        'help_btn_start' => 'Let\'s create your first team',
        'stat_new_tickets' => 'New tickets',
        'stat_inprogress_tickets' => 'Tickets in progress',
        'stat_closed_tickets' => 'Tickets closed this month',
        'stat_satisfaction' => 'Customer satisfaction',
        'table_ticket' => 'Ticket',
        'table_client' => 'Client',
        'table_subject' => 'Subject',
        'table_priority' => 'Priority',
        'table_status' => 'Status',
        'table_actions' => 'Actions',
        'status_new' => 'New',
        'status_inprogress' => 'In progress',
        'status_closed' => 'Closed',
        'btn_open_chat' => 'Open Chat ➔',
        'btn_see_more' => 'See more ➔',
        'empty_tickets' => 'No new tickets.',
        'join_team' => 'Join a team.',
        'create_team' => 'Create a team.'
    ],
    'es' => [
        'page_title' => 'Menú',
        'welcome' => '¡Bienvenido :user!',
        'btn_create_team' => 'Crear un equipo',
        'help_title' => '👋 Primeros pasos en OpenSupport',
        'help_intro' => '¡Bienvenido a su espacio de soporte!<br>Así funciona OpenSupport:',
        'help_step1_title' => '1. Equipos y Formularios',
        'help_step1_desc' => 'Cree o únase a un equipo para obtener una URL de formulario público dedicada a sus clientes.',
        'help_step2_title' => '2. Gestión de tickets',
        'help_step2_desc' => 'Siga el progreso de las solicitudes, asigne prioridades y converse en directo con sus solicitantes.',
        'help_step3_title' => '3. Respuestas y Archivos',
        'help_step3_desc' => 'Utilice respuestas predefinidas en el chat y solicite archivos adjuntos de forma segura.',
        'help_doc_link' => '¿Necesita ayuda? ¡Consulte nuestra documentación!',
        'help_btn_start' => 'Creemos su primer equipo',
        'stat_new_tickets' => 'Nuevos tickets',
        'stat_inprogress_tickets' => 'Tickets en curso',
        'stat_closed_tickets' => 'Tickets cerrados este mes',
        'stat_satisfaction' => 'Satisfacción del cliente',
        'table_ticket' => 'Ticket',
        'table_client' => 'Cliente',
        'table_subject' => 'Asunto',
        'table_priority' => 'Prioridad',
        'table_status' => 'Estado',
        'table_actions' => 'Acciones',
        'status_new' => 'Nuevo',
        'status_inprogress' => 'En curso',
        'status_closed' => 'Cerrado',
        'btn_open_chat' => 'Abrir Chat ➔',
        'btn_see_more' => 'Ver más ➔',
        'empty_tickets' => 'Ningún nuevo ticket.',
        'join_team' => 'Unirse a un equipo.',
        'create_team' => 'Crear un equipo.'
    ]
];
$t = $translations[$lang];

// Check if user has no teams (owner or member)
$stmtTeamCheck = $dbco->prepare("SELECT COUNT(*) FROM teams t LEFT JOIN teams_members tm ON t.teams_id = tm.teams_members_team_id AND tm.teams_members_deleted = 0 WHERE (t.teams_owner = :user_id OR tm.teams_members_user_id = :user_id_member) AND t.teams_deleted = 0");
$stmtTeamCheck->execute([
    'user_id'        => $_SESSION['user_id'],
    'user_id_member' => $_SESSION['user_id']
]);
$teamCount = (int)$stmtTeamCheck->fetchColumn();
$isFirstLogin = ($teamCount === 0);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenSupport | Dashboard</title>
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
                <p><?= str_replace(':user', htmlspecialchars($_SESSION['user_name'] ?? ''), $t['welcome']) ?></p>
            </div>
            <a href="new/team" class="btn"><?= $t['btn_create_team'] ?></a>
        </header>
        
        <?php if ($isFirstLogin): ?>
        <section class="card help">
            <header>
                <h2><?= $t['help_title'] ?></h2>
            </header>
            <p><?= $t['help_intro'] ?></p>
            <div class="grid-cols-3">
                <div class="step">
                    <strong><?= $t['help_step1_title'] ?></strong>
                    <p><?= $t['help_step1_desc'] ?></p>
                </div>
                <div class="step">
                    <strong><?= $t['help_step2_title'] ?></strong>
                    <p><?= $t['help_step2_desc'] ?></p>
                </div>
                <div class="step">
                    <strong><?= $t['help_step3_title'] ?></strong>
                    <p><?= $t['help_step3_desc'] ?></p>
                </div>
            </div>
            <div>
                <a href="https://logreee.github.io/OpenSupport/docs" target="_blank" rel="noopener noreferrer"><?= $t['help_doc_link'] ?></a>
                <a href="new/team" class="btn"><?= $t['help_btn_start'] ?></a>
            </div>
        </section>
        <?php endif; ?>
        
        <!-- Statistics -->
        <div class="grid-cols-4">
            <div class="card statistics_recap">
                <h2><?= $t['stat_new_tickets'] ?></h2>
                <p><?php $sth = $dbco->prepare("SELECT COUNT(*) FROM tickets WHERE tickets_assigned_to = :user_id AND tickets_status = 0;");
                $sth->execute(['user_id' => $_SESSION['user_id']]);
                echo $sth->fetchColumn(); ?></p>
            </div>
            <div class="card statistics_recap">
                <h2><?= $t['stat_inprogress_tickets'] ?></h2>
                <p><?php $sth = $dbco->prepare("SELECT COUNT(*) FROM tickets WHERE tickets_assigned_to = :user_id AND tickets_status = 1;");
                $sth->execute(['user_id' => $_SESSION['user_id']]);
                echo $sth->fetchColumn(); ?></p>
            </div>
            <div class="card statistics_recap">
                <h2><?= $t['stat_closed_tickets'] ?></h2>
                <p><?php $sth = $dbco->prepare("SELECT COUNT(*) FROM tickets WHERE tickets_assigned_to = :user_id AND tickets_status = 2 AND tickets_closing_date >= DATE_SUB(NOW(), INTERVAL 30 DAY);");
                $sth->execute(['user_id' => $_SESSION['user_id']]);
                echo $sth->fetchColumn(); ?></p>
            </div>
            <div class="card statistics_recap">
                <h2><?= $t['stat_satisfaction'] ?></h2>
                <p><?php $sth = $dbco->prepare("SELECT AVG(tickets_rating) FROM tickets WHERE tickets_assigned_to = :user_id AND tickets_status = 2;");
                $sth->execute(['user_id' => $_SESSION['user_id']]);
                $avgRating = $sth->fetchColumn();
                if ($avgRating !== false && $avgRating !== null) {
                    echo number_format((float)$avgRating, 1, '.', '');
                } else {
                    echo "---";
                } ?> ★</p>
            </div>
        </div>

        <!-- Tickets list by team -->
        <?php $sth = $dbco->prepare("SELECT DISTINCT t.teams_id, t.teams_name FROM teams t LEFT JOIN teams_members tm ON t.teams_id = tm.teams_members_team_id WHERE (t.teams_owner = :owner_id OR tm.teams_members_user_id = :member_id) AND t.teams_deleted = 0 AND (tm.teams_members_deleted = 0 OR tm.teams_members_deleted IS NULL);");
        $sth->execute(['owner_id'  => $_SESSION['user_id'], 'member_id' => $_SESSION['user_id']]);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) != 0):
            foreach ($result as $row):
                $team_id = (int)$row["teams_id"];
                $team_name = htmlspecialchars($row["teams_name"]);?>
                <section class="card table">
                    <header>
                        <h2><?= $team_name; ?></h2>
                    </header>
                    <div class="table-wrapper">
                        <table class="no-wrap">
                            <thead>
                                <tr>
                                    <th><?= $t['table_ticket'] ?></th>
                                    <th><?= $t['table_client'] ?></th>
                                    <th class="large"><?= $t['table_subject'] ?></th>
                                    <th><?= $t['table_priority'] ?></th>
                                    <th no-filter><?= $t['table_status'] ?></th>
                                    <th no-filter><?= $t['table_actions'] ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sthTickets = $dbco->prepare("SELECT tickets_id, tickets_teams, tickets_first_name, tickets_last_name, tickets_subject, tickets_additionnal_fields, tickets_status, tickets_priority, tickets_rating FROM tickets WHERE tickets_teams = :team_id AND ((tickets_assigned_to = :user_id AND tickets_status != 2) OR (tickets_assigned_to IS NULL AND tickets_status != 2)) ORDER BY tickets_status ASC LIMIT 10;");
                                $sthTickets->execute(['team_id' => $team_id, 'user_id' => $_SESSION['user_id']]);
                                $result_tickets = $sthTickets->fetchAll(PDO::FETCH_ASSOC);
                                if (count($result_tickets) != 0):
                                    foreach ($result_tickets as $row_tickets):?>
                                    <tr ticket_id="<?= $row_tickets['tickets_id'] ?>" team_id="<?= $row_tickets['tickets_teams'] ?>">
                                        <td>#<?= $row_tickets['tickets_id'] ?></td>
                                        <td><?= htmlspecialchars($row_tickets['tickets_first_name'] . ' ' . $row_tickets['tickets_last_name']) ?></td>
                                        <td class="large"><?= htmlspecialchars($row_tickets['tickets_subject']) ?></td>
                                        <?php if($row_tickets['tickets_priority'] == '-2'): ?>
                                            <td class="priority lower">-2</td>
                                        <?php elseif($row_tickets['tickets_priority'] == '-1'): ?>
                                            <td class="priority low">-1</td>
                                        <?php elseif($row_tickets['tickets_priority'] == '1'): ?>
                                            <td class="priority up">1</td>
                                        <?php elseif($row_tickets['tickets_priority'] == '2'): ?>
                                            <td class="priority upper">2</td>
                                        <?php else: ?>
                                            <td class="priority">0</td>
                                        <?php endif; ?>
                                        <td>
                                            <?php if($row_tickets['tickets_status'] == '0'): ?>
                                                <span class="tag"><?= $t['status_new'] ?></span>
                                            <?php elseif($row_tickets['tickets_status'] == '1'): ?>
                                                <span class="tag in-progress"><?= $t['status_inprogress'] ?></span>
                                            <?php else: ?>
                                                <span class="tag closed"><?= $t['status_closed'] ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= $team_id; ?>/tickets/<?= $row_tickets['tickets_id'] ?>"><?= $t['btn_open_chat'] ?></a>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                                <?php else:?>
                                </tbody>
                            </table>
                            <p class="table-empty"><?= $t['empty_tickets'] ?></p>
                                <?php endif;?>
                    </div>
                    <a href="<?= $team_id; ?>/tickets"><?= $t['btn_see_more'] ?></a>
                </section>
                
            <?php endforeach;
        endif;?>
        
        <!-- Footer -->
        <?php include("../src/php/dashboard_footer.php");?>
        
        <script src="<?= $opensupport_link?>/src/js/main.js"></script>
    </main>
</body>
</html>