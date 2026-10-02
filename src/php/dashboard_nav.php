<!-- Sidebar Menu -->
<?php // Language manager
$translations_navbar = [
    'fr' => [
        'menu' => "Menu",
        'opensupport_settings' => "OpenSupport Paramètres",
        'team_tickets' => "Tickets",
        'team_members' => "Membres",
        'team_statistics' => "Statistiques",
        'team_settings' => "Paramètres",
        'team_select' => "Sélectionnez une équipe...",
        'close_menu' => "Réduire le menu"
    ],
    'en' => [
        'menu' => "Menu",
        'opensupport_settings' => "OpenSupport Settings",
        'team_tickets' => "Tickets",
        'team_members' => "Members",
        'team_statistics' => "Statistics",
        'team_settings' => "Settings",
        'team_select' => "Select a team...",
        'close_menu' => "Collapse the menu"
    ],
    'es' => [
        'menu' => "Menú",
        'opensupport_settings' => "Configuración de OpenSupport",
        'team_tickets' => "Tickets",
        'team_members' => "Miembros",
        'team_statistics' => "Estadística",
        'team_settings' => "Configuración",
        'team_select' => "Selecciona un equipo...",
        'close_menu' => "Minimizar menú"
    ],
];
$t_navbar = $translations_navbar[$lang]; ?>
    <aside class="dashboard_nav">
        <div class="osupport_logo"></div>
        <nav>
            <a href="<?= $opensupport_link?>/dashboard/" class="<?php if(basename($_SERVER['PHP_SELF'], '.php') === 'index'){echo "selected";}?>"><img src="<?= $opensupport_link?>/src/icons/home.svg"><span><?= $t_navbar['menu']; ?></span></a>
            <?php if(isset($_SESSION['user_admin']) && $_SESSION['user_admin']== '1'): ?>
            <a href="<?= $opensupport_link?>/dashboard/opensupport_settings" class="<?php if(basename($_SERVER['PHP_SELF'], '.php') === 'opensupport_settings'){echo "selected";}?>"><img src="<?= $opensupport_link?>/src/icons/settings.svg"><span><?= $t_navbar['opensupport_settings']; ?></span></a>
            <?php endif; ?>
            <?php if(isset($_SESSION['team_id']) && $_SESSION['team_id']!= ''): ?>
            <hr>
            <a href="<?= $opensupport_link?>/dashboard/<?= $_SESSION['team_id']?>/tickets" class="<?php if(basename($_SERVER['PHP_SELF'], '.php') === 'tickets'){echo "selected";}?>"><img src="<?= $opensupport_link?>/src/icons/ticket.svg"><span><?= $t_navbar['team_tickets']; ?></span></a>
            <a href="<?= $opensupport_link?>/dashboard/<?= $_SESSION['team_id']?>/members" class="<?php if(basename($_SERVER['PHP_SELF'], '.php') === 'members'){echo "selected";}?>"><img src="<?= $opensupport_link?>/src/icons/group.svg"><span><?= $t_navbar['team_members']; ?></span></a>
            <?php if($_SESSION['team_role'] === 'admin'): ?>
                <a href="<?= $opensupport_link?>/dashboard/<?= $_SESSION['team_id']?>/statistics" class="<?php if(basename($_SERVER['PHP_SELF'], '.php') === 'statistics'){echo "selected";}?>"><img src="<?= $opensupport_link?>/src/icons/bar_chart.svg"><span><?= $t_navbar['team_statistics']; ?></span></a>
                <a href="<?= $opensupport_link?>/dashboard/<?= $_SESSION['team_id']?>/settings" class="<?php if(basename($_SERVER['PHP_SELF'], '.php') === 'settings'){echo "selected";}?>"><img src="<?= $opensupport_link?>/src/icons/settings.svg"><span><?= $t_navbar['team_settings']; ?></span></a>
            <?php endif; ?>
            <?php endif; ?>
        </nav>
        <a href="" class="closePannel"><img src="<?= $opensupport_link?>/src/icons/arrow_menu_close.svg"><span><?= $t_navbar['close_menu']; ?></span></a>
        <?php $sth = $dbco->prepare("SELECT DISTINCT t.teams_id, t.teams_name FROM teams t LEFT JOIN teams_members tm ON t.teams_id = tm.teams_members_team_id WHERE (t.teams_owner = {$_SESSION['user_id']} OR tm.teams_members_user_id = {$_SESSION['user_id']}) AND t.teams_deleted = 0 AND tm.teams_members_deleted = 0;");
        $sth->execute();
        $resultat = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultat)!=0){
            echo "<select class=\"teams\">
            <option>{$t_navbar['team_select']}</option>";
            foreach ($resultat as $row) {
                if($row['teams_id']==$_SESSION['team_id']){
                    echo "<option value=\"{$row['teams_id']}\" selected>{$row['teams_name']}</option>";
                } else {
                    echo "<option value=\"{$row['teams_id']}\">{$row['teams_name']}</option>";
                }
            }
            echo "</select>";
        }
        ?>
        <div class="user">
            <a href="<?= $opensupport_link?>/dashboard/account" class="account_btn">
                <p class="user_icon" alt="<?= $_SESSION['user_name'] ?>"><?= substr($_SESSION['user_name'], 0, 1)?></p>
                <div>
                    <h2><?= $_SESSION['user_name'] ?></h2>
                    <p><?= $_SESSION['user_email'] ?></p>
                </div>
            </a>
            <a href="<?= $opensupport_link?>/dashboard/logout" class="logout_btn"><img src="<?= $opensupport_link?>/src/icons/logout.svg"></a>
        </div>
        <script>
            var dashboard_nav = document.querySelector(".dashboard_nav");
            document.querySelector(".dashboard_nav .closePannel").addEventListener("click", (btn) => {
                btn.preventDefault();
                dashboard_nav.classList.toggle("full");
            });
            document.querySelector(".dashboard_nav .teams").addEventListener("change", function(e) {
                const newTeamId = e.target.value;
                if (!newTeamId || isNaN(newTeamId)) {
                    return;
                }
                const currentPath = window.location.pathname;
                const match = currentPath.match(/\/dashboard\/\d+\/(members|statistics|settings|tickets)/);
                let targetUrl = '';
                const baseUrl = '<?= rtrim($opensupport_link, "/") ?>';
                if (match) {
                    const subPage = match[1];
                    targetUrl = `${baseUrl}/dashboard/${newTeamId}/${subPage}`;
                } else {
                    targetUrl = `${baseUrl}/dashboard/${newTeamId}/tickets`;
                }
                window.location.href = targetUrl;
            });
        </script>
    </aside>