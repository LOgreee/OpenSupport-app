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
        'title' => 'Tickets',
        'subtitle' => 'Gérez l\'ensemble des tickets.',
        'new_ticket' => 'Nouveau ticket',
        'queue' => 'File d\'attente',
        'queue_empty' => 'Aucun ticket en attente.',
        'ticket_filter_all' => 'Tous',
        'ticket_filter_my' => 'Mes tickets',
        'ticket_filter_search' => 'Rechercher',
        'ticket_table_id' => 'Ticket',
        'ticket_table_client' => 'Client',
        'ticket_table_subject' => 'Objet',
        'ticket_table_assignee' => 'Assigné à',
        'ticket_table_priority' => 'Priorité',
        'ticket_table_status' => 'Statut',
        'ticket_table_action' => 'Action',
        'ticket_status_new' => 'Nouveau',
        'ticket_status_progress' => 'En cours',
        'ticket_status_closed' => 'Fermé',
        'ticket_open_chat' => 'Ouvrir Chat',
        'ticket_empty' => 'Aucun nouveau ticket.',
        'help' => 'Félicitation, vous venez de créer votre équipe !',
        'help_content' => 'Bienvenue dans l\'espace de votre équipe !<br>Voici une présentation des différents onglets disponible dans le menu latérale à gauche :',
        'help_box1_title' => 'Tickets',
        'help_box1_content' => 'Gérez l\'ensemble des tickets, attribuez les agents, suivez les statuts et échangez en direct avec vos clients via le chat.',
        'help_box2_title' => 'Membres',
        'help_box2_content' => 'Invitez des collaborateurs, gérez les rôles d\'administration, attribuez des postes et organisez votre équipe par groupes.',
        'help_box3_title' => 'Statistiques',
        'help_box3_content' => 'Analysez les performances du support : volume de demandes reçues, temps de résolution et taux de satisfaction client.',
        'help_box4_title' => 'Paramètres de l\'équipe',
        'help_box4_content' => 'Personnalisez votre image de marque, construisez votre formulaire public sur mesure, configurez le routage automatique et gérez vos jetons API.',
        'help_documentation' => 'Besoin d\'aide? Consultez notre documentation!',
    ],
    'en' => [
        'title' => 'Tickets',
        'subtitle' => 'Manage all support tickets.',
        'new_ticket' => 'New ticket',
        'queue' => 'Queue',
        'queue_empty' => 'No tickets waiting in queue.',
        'ticket_filter_all' => 'All',
        'ticket_filter_my' => 'My tickets',
        'ticket_filter_search' => 'Search',
        'ticket_table_id' => 'Ticket',
        'ticket_table_client' => 'Client',
        'ticket_table_subject' => 'Subject',
        'ticket_table_assignee' => 'Assigned to',
        'ticket_table_priority' => 'Priority',
        'ticket_table_status' => 'Status',
        'ticket_table_action' => 'Action',
        'ticket_status_new' => 'New',
        'ticket_status_progress' => 'In progress',
        'ticket_status_closed' => 'Closed',
        'ticket_open_chat' => 'Open Chat',
        'ticket_empty' => 'No new tickets.',
        'help' => 'Congratulations, you have just created your team!',
        'help_content' => 'Welcome to your team workspace!<br>Here is an overview of the different sections available in the left sidebar:',
        'help_box1_title' => 'Tickets',
        'help_box1_content' => 'Manage all tickets, assign agents, track statuses, and communicate in real time with your customers via chat.',
        'help_box2_title' => 'Members',
        'help_box2_content' => 'Invite collaborators, manage administrator roles, set positions, and organize your team into groups.',
        'help_box3_title' => 'Analytics',
        'help_box3_content' => 'Analyze support performance: incoming request volume, resolution times, and customer satisfaction rates.',
        'help_box4_title' => 'Team Settings',
        'help_box4_content' => 'Customize your branding, build tailored public forms, set up automated routing, and manage your API tokens.',
        'help_documentation' => 'Need help? Check out our documentation!',
    ],
    'es' => [
        'title' => 'Tickets',
        'subtitle' => 'Gestione todos los tickets.',
        'new_ticket' => 'Nuevo ticket',
        'queue' => 'Cola de espera',
        'queue_empty' => 'No hay tickets en espera.',
        'ticket_filter_all' => 'Todos',
        'ticket_filter_my' => 'Mis tickets',
        'ticket_filter_search' => 'Buscar',
        'ticket_table_id' => 'Ticket',
        'ticket_table_client' => 'Cliente',
        'ticket_table_subject' => 'Asunto',
        'ticket_table_assignee' => 'Asignado a',
        'ticket_table_priority' => 'Prioridad',
        'ticket_table_status' => 'Estado',
        'ticket_table_action' => 'Acción',
        'ticket_status_new' => 'Nuevo',
        'ticket_status_progress' => 'En curso',
        'ticket_status_closed' => 'Cerrado',
        'ticket_open_chat' => 'Abrir Chat',
        'ticket_empty' => 'Ningún nuevo ticket.',
        'help' => '¡Felicitaciones, acaba de crear su equipo!',
        'help_content' => '¡Bienvenido al espacio de su equipo!<br>A continuación le presentamos las distintas secciones disponibles en el menú lateral izquierdo:',
        'help_box1_title' => 'Tickets',
        'help_box1_content' => 'Gestione todos los tickets, asigne agentes, siga el estado y hable en tiempo real con sus clientes mediante el chat.',
        'help_box2_title' => 'Miembros',
        'help_box2_content' => 'Invite a colaboradores, gestione los roles de administración, asigne puestos y organice su equipo por grupos.',
        'help_box3_title' => 'Estadísticas',
        'help_box3_content' => 'Analice el rendimiento del soporte: volumen de solicitudes recibidas, tiempo de resolución y tasa de satisfacción del cliente.',
        'help_box4_title' => 'Ajustes del equipo',
        'help_box4_content' => 'Personalice su imagen corporativa, cree formularios públicos a medida, configure la asignación automática y gestione sus tokens de API.',
        'help_documentation' => '¿Necesita ayuda? ¡Consulte nuestra documentación!',
    ]
];
$t = $translations[$lang];

// Show help verifications
$showTeamCreationHelp = false;
$current_team_id = (int)($_SESSION['team_id'] ?? 0);
if ($current_team_id > 0) {
    $stmtTeamCheck = $dbco->prepare("SELECT t.teams_owner, t.teams_creation_date, (SELECT COUNT(*) FROM tickets WHERE tickets_teams = t.teams_id) AS total_tickets FROM teams t WHERE t.teams_id = :team_id AND t.teams_deleted = 0 LIMIT 1");
    $stmtTeamCheck->execute(['team_id' => $current_team_id]);
    $teamCheckData = $stmtTeamCheck->fetch(PDO::FETCH_ASSOC);
    if ($teamCheckData) {
        $isTeamAdmin = ($_SESSION['team_role'] === 'admin' || (int)$teamCheckData['teams_owner'] === (int)$_SESSION['user_id']);
        $hasNoTickets = ((int)$teamCheckData['total_tickets'] === 0);
        $isCreatedToday = (date('Y-m-d', strtotime($teamCheckData['teams_creation_date'])) === date('Y-m-d'));
        if ($isTeamAdmin && $hasNoTickets && $isCreatedToday) {
            $showTeamCreationHelp = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenSupport | <?= $t['title']; ?></title>
    <link rel="icon" type="image/x-icon" href="<?= $opensupport_link?>/src/opensupport_assets/opensupport_icon.svg">
    <link rel="stylesheet" href="<?= $opensupport_link?>/src/css/main.css">
    <link rel="manifest" href="<?= $opensupport_link ?>/manifest.json">
</head>
<body class="dashboard">

    <?php include("../src/php/dashboard_nav.php");?>

    <?php if(!empty($_GET['ticket_id'])):?>
    <main class="tickets-chat">
        <div class="tickets-queue">
            <header>
                <a href="../tickets" class="back-btn"></a>
                <h1><?= $t['queue']; ?></h1>
            </header>
            <div class="table-filters">
                <a href="" class="filter-btn active" data-filter="all"><?= $t['ticket_filter_all']; ?></a>
                <a href="" class="filter-btn" data-filter="new"><?= $t['ticket_status_new']; ?></a>
                <a href="" class="filter-btn" data-filter="in-progress"><?= $t['ticket_status_progress']; ?></a>
            </div>
            <div class="tickets-wrapper">
                <?php $team_id = (int)$_SESSION['team_id'];
                $current_ticket_id = (int)$_GET['ticket_id'];
                $today = date('Y-m-d');
                $sth = $dbco->prepare("SELECT t.tickets_id, t.tickets_first_name, t.tickets_last_name, t.tickets_subject, t.tickets_status, t.tickets_priority, t.tickets_creation_date, MAX(m.messages_creation_date) AS last_message_date, COALESCE(MAX(m.messages_creation_date), t.tickets_creation_date) AS latest_activity FROM tickets t LEFT JOIN messages m ON m.messages_ticket_id = t.tickets_id WHERE t.tickets_teams = :team_id AND (t.tickets_status IN (0, 1) OR t.tickets_id = :current_ticket_id) GROUP BY t.tickets_id ORDER BY (t.tickets_id = :current_ticket_id_order AND t.tickets_status NOT IN (0, 1)) DESC, latest_activity DESC");
                $sth->execute(['team_id' => $team_id, 'current_ticket_id' => $current_ticket_id, 'current_ticket_id_order' => $current_ticket_id]);
                $queue_tickets = $sth->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($queue_tickets)):
                    foreach ($queue_tickets as $ticket):
                        $isActive = ($ticket['tickets_id'] == $current_ticket_id) ? 'active' : '';
                        $ticketDate = !empty($ticket['last_message_date']) ? new DateTime($ticket['last_message_date']) : new DateTime($ticket['tickets_creation_date']);
                        $formattedDate = '';
                        if ($ticketDate) {
                            if ($ticketDate->format('Y-m-d') === $today) {
                                $formattedDate = $ticketDate->format('H:i');
                            } else {
                                $formattedDate = $ticketDate->format('d/m/Y');
                            }
                        }
                ?>
                    <a href="<?= $ticket['tickets_id'] ?>" class="ticket-card <?= $isActive ?>" data-status="<?= $ticket['tickets_status'] ?>">
                        <div class="tickets-card-main">
                            <div class="ticket-card-header">
                                <div class="ticket-card-title">#<?= htmlspecialchars($ticket['tickets_id']) ?> - <?= htmlspecialchars($ticket['tickets_subject']) ?></div>
                                <div class="ticket-card-meta">
                                    <span><?= htmlspecialchars($ticket['tickets_first_name'] . ' ' . $ticket['tickets_last_name']) ?></span>
                                </div>
                                <?php if ($ticket['tickets_status'] == '0'): ?>
                                    <span class="tag"><?= $t['ticket_status_new']; ?></span>
                                <?php elseif ($ticket['tickets_status'] == '1'): ?>
                                    <span class="tag in-progress"><?= $t['ticket_status_progress']; ?></span>
                                <?php elseif ($ticket['tickets_status'] == '2'): ?>
                                    <span class="tag closed"><?= $t['ticket_status_closed']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <span class="ticket-time"><?= $formattedDate ?></span>
                    </a>
                <?php endforeach;
                else: ?>
                    <p class="table-empty"><?= $t['queue_empty']; ?></p>
                <?php endif; ?>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                const queueFilterBtns = document.querySelectorAll('.filter-btn');
                const ticketCards = document.querySelectorAll('.tickets-wrapper .ticket-card');
                const statusMap = {
                    'new': '0',
                    'in-progress': '1'
                };
                queueFilterBtns.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        queueFilterBtns.forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');
                        const filterValue = btn.getAttribute('data-filter');
                        ticketCards.forEach(card => {
                            const cardStatus = card.getAttribute('data-status');
                            if (filterValue === 'all' || cardStatus === statusMap[filterValue]) {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        });
                    });
                });
            });
            </script>
        </div>
        
        <?php $ticket_id = (int)$_GET['ticket_id'];
        require("../src/php/chat.php"); ?>
        
    </main>
    
    <?php else:?>
    <main>
        <header>
            <div>
                <h1><?= $t['title']; ?></h1>
                <p><?= $t['subtitle']; ?></p>
            </div>
            <a href="new/ticket" class="btn"><?= $t['new_ticket']; ?></a>
        </header>

        <?php if ($showTeamCreationHelp): ?>
        <section class="card help">
            <header>
                <h2>🎉 <?= $t['help']; ?></h2>
            </header>
            <p><?= $t['help_content']; ?></p>
            <div class="grid-cols-2">
                <div class="step">
                    <strong>1. <?= $t['help_box1_title']; ?></strong>
                    <p><?= $t['help_box1_content']; ?></p>
                </div>
                <div class="step">
                    <strong>2. <?= $t['help_box2_title']; ?></strong>
                    <p><?= $t['help_box2_content']; ?></p>
                </div>
                <div class="step">
                    <strong>3. <?= $t['help_box3_title']; ?></strong>
                    <p><?= $t['help_box3_content']; ?></p>
                </div>
                <div class="step">
                    <strong>4. <?= $t['help_box4_title']; ?></strong>
                    <p><?= $t['help_box4_content']; ?></p>
                </div>
            </div>
            <div>
                <a href="https://logreee.github.io/OpenSupport/docs" target="_blank" rel="noopener noreferrer"><?= $t['help_documentation']; ?></a>
            </div>
        </section>
        <?php endif; ?>
        
        <!-- Filtres rapides -->
        <div class="table-filters">
            <input type="search" id="searchInput" placeholder="<?= $t['ticket_filter_search']; ?>...">
            <span class="separator"></span>
            <a href="" class="filter-btn active" data-filter="all"><?= $t['ticket_filter_all']; ?></a>
            <a href="" class="filter-btn" data-filter="new"><?= $t['ticket_status_new']; ?></a>
            <a href="" class="filter-btn" data-filter="in-progress"><?= $t['ticket_status_progress']; ?></a>
            <a href="" class="filter-btn" data-filter="closed"><?= $t['ticket_status_closed']; ?></a>
            <span class="separator"></span>
            <a href="" class="filter-btn" data-filter="me"><?= $t['ticket_filter_my']; ?></a>
        </div>

        <!-- Liste des tickets -->
        <div class="card table">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th><?= $t['ticket_table_id']; ?></th>
                            <th><?= $t['ticket_table_client']; ?></th>
                            <th class="large"><?= $t['ticket_table_subject']; ?></th>
                            <th><?= $t['ticket_table_assignee']; ?></th>
                            <th><?= $t['ticket_table_priority']; ?></th>
                            <th><?= $t['ticket_table_status']; ?></th>
                            <th no-filter><?= $t['ticket_table_action']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sth = $dbco->prepare("SELECT t.tickets_id, t.tickets_teams, t.tickets_first_name, t.tickets_last_name, t.tickets_subject, t.tickets_additionnal_fields, t.tickets_status, t.tickets_priority, t.tickets_rating, t.tickets_assigned_to, u.users_id, u.users_first_name, u.users_last_name, u.users_email FROM tickets t LEFT JOIN users u ON t.tickets_assigned_to = u.users_id WHERE t.tickets_teams = {$_SESSION['team_id']} ORDER BY t.tickets_id ASC, t.tickets_status ASC;");
                        $sth->execute();
                        $result_tickets = $sth->fetchAll(PDO::FETCH_ASSOC);
                        if (count($result_tickets)!=0):
                            foreach ($result_tickets as $row_tickets):?>
                                <tr ticket_id="<?= $row_tickets['tickets_id'] ?>" team_id="<?= $row_tickets['tickets_teams'] ?>">
                                    <td>#<?= $row_tickets['tickets_id'] ?></td>
                                    <td><?= $row_tickets['tickets_first_name'] ?> <?= $row_tickets['tickets_last_name'] ?></td>
                                    <td class="large"><?= $row_tickets['tickets_subject'] ?></td>
                                    <td>
                                        <?php if($row_tickets['tickets_assigned_to']!=""):?>
                                        <span class="sr-only"><?= isset($row_tickets['users_first_name']) ?htmlspecialchars($row_tickets['users_first_name'] . ' ' . $row_tickets['users_last_name']) : $row_tickets['users_email'] ?></span>
                                        <span class="profile_picture <?php if($row_tickets['users_id']==$_SESSION['user_id']){echo "me";}?>" title="<?= isset($row_tickets['users_first_name']) ?htmlspecialchars($row_tickets['users_first_name'] . ' ' . $row_tickets['users_last_name']) : $row_tickets['users_email'] ?> <?= isUserCurrentlyAbsent($row_tickets['users_id']) ? "(Absent)" : "" ?>" aria-hidden="true" user_id="<?php echo $row_tickets['users_id'] ?>"><?= isset($row_tickets['users_first_name']) ?substr($row_tickets['users_first_name'], 0, 1) : substr($row_tickets['users_email'], 0, 1) ?></span>
                                        <?php endif;?>
                                    </td>
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
                                            <span class="tag"><?= $t['ticket_status_new']; ?></span>
                                        <?php elseif($row_tickets['tickets_status'] == '1'): ?>
                                            <span class="tag in-progress"><?= $t['ticket_status_progress']; ?></span>
                                        <?php else: ?>
                                            <span class="tag closed"><?= $t['ticket_status_closed']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="tickets/<?= $row_tickets['tickets_id'] ?>"><?= $t['ticket_open_chat']; ?> ➔</a>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                        <?php else:?>
                            </tbody>
                        </table>
                        <p class="table-empty"><?= $t['ticket_empty']; ?></p>
                        <?php endif;?>
            </div>
        </div>
        
        <!-- Footer -->
        <?php include("../src/php/dashboard_footer.php");?>
        <script src="<?= $opensupport_link?>/src/js/main.js"></script>
    </main>
    <?php endif;?>
</body>
</html>