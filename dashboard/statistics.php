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
        'page_title' => 'Statistiques globales',
        'page_subtitle' => 'Performances de l\'équipe et analyse des incidents.',
        'stat_total_tickets' => 'Tickets totaux',
        'stat_resolution_rate' => 'Taux de résolution',
        'stat_customer_satisfaction' => 'Satisfaction client',
        'stat_avg_resolution_time' => 'Temps de résolution moy.',
        'unit_days' => 'j',
        'unit_hours' => 'h',
        'unit_minutes' => 'm',
        'chart_monthly_title' => 'Répartition des tickets par mois',
        'chart_field_title_prefix' => 'Réponses à',
        'select_no_field' => 'Aucun champ configuré',
        'table_title' => 'Performances par membre du support',
        'th_agent' => 'Agent Support',
        'th_assigned_tickets' => 'Tickets attribués',
        'th_resolved_tickets' => 'Tickets résolus',
        'th_avg_rating' => 'Note moyenne',
        'th_performance' => 'Performance',
        'status_absent' => '(Absent)',
        'js_unassigned' => 'Non attribué',
        'js_tooltip_total_suffix' => 'tickets (total)',
        'js_not_specified' => '(Non renseigné)',
        'js_no_answers' => 'Aucune réponse',
        'js_tooltip_ticket_suffix' => 'ticket(s)'
    ],
    'en' => [
        'page_title' => 'Global Statistics',
        'page_subtitle' => 'Team performance and incident analysis.',
        'stat_total_tickets' => 'Total tickets',
        'stat_resolution_rate' => 'Resolution rate',
        'stat_customer_satisfaction' => 'Customer satisfaction',
        'stat_avg_resolution_time' => 'Avg. resolution time',
        'unit_days' => 'd',
        'unit_hours' => 'h',
        'unit_minutes' => 'm',
        'chart_monthly_title' => 'Monthly ticket breakdown',
        'chart_field_title_prefix' => 'Answers to',
        'select_no_field' => 'No field configured',
        'table_title' => 'Support member performance',
        'th_agent' => 'Support Agent',
        'th_assigned_tickets' => 'Assigned tickets',
        'th_resolved_tickets' => 'Resolved tickets',
        'th_avg_rating' => 'Average rating',
        'th_performance' => 'Performance',
        'status_absent' => '(Out of office)',
        'js_unassigned' => 'Unassigned',
        'js_tooltip_total_suffix' => 'tickets (total)',
        'js_not_specified' => '(Not specified)',
        'js_no_answers' => 'No answers',
        'js_tooltip_ticket_suffix' => 'ticket(s)'
    ],
    'es' => [
        'page_title' => 'Estadísticas globales',
        'page_subtitle' => 'Rendimiento del equipo y análisis de incidentes.',
        'stat_total_tickets' => 'Tickets totales',
        'stat_resolution_rate' => 'Tasa de resolución',
        'stat_customer_satisfaction' => 'Satisfacción del cliente',
        'stat_avg_resolution_time' => 'Tiempo medio de resolución',
        'unit_days' => 'd',
        'unit_hours' => 'h',
        'unit_minutes' => 'm',
        'chart_monthly_title' => 'Distribución mensual de tickets',
        'chart_field_title_prefix' => 'Respuestas a',
        'select_no_field' => 'Ningún campo configurado',
        'table_title' => 'Rendimiento por miembro de soporte',
        'th_agent' => 'Agente de soporte',
        'th_assigned_tickets' => 'Tickets asignados',
        'th_resolved_tickets' => 'Tickets resueltos',
        'th_avg_rating' => 'Puntuación media',
        'th_performance' => 'Rendimiento',
        'status_absent' => '(Ausente)',
        'js_unassigned' => 'Sin asignar',
        'js_tooltip_total_suffix' => 'tickets (total)',
        'js_not_specified' => '(No especificado)',
        'js_no_answers' => 'Sin respuestas',
        'js_tooltip_ticket_suffix' => 'ticket(s)'
    ]
];
$t = $translations[$lang];
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="dashboard">

    <!-- Sidebar Menu -->
    <?php include("../src/php/dashboard_nav.php");?>

    <main>
        <header>
            <div>
                <h1><?= $t['page_title'] ?></h1>
                <p><?= $t['page_subtitle'] ?></p>
            </div>
        </header>

        <!-- Statistics -->
        <div class="grid-cols-4">
            <div class="card statistics_recap">
                <h2><?= $t['stat_total_tickets'] ?></h2>
                <p><?php 
                $sth = $dbco->prepare("SELECT COUNT(*) FROM tickets WHERE tickets_teams = :team_id AND tickets_deleted_at IS NULL;");
                $sth->execute(['team_id' => $_SESSION['team_id']]);
                $tickets_total = (int)$sth->fetchColumn();
                echo $tickets_total;
                ?></p>
            </div>
            <div class="card statistics_recap">
                <h2><?= $t['stat_resolution_rate'] ?></h2>
                <p><?php 
                $sth = $dbco->prepare("SELECT COUNT(*) FROM tickets WHERE tickets_teams = :team_id AND tickets_status = 2 AND tickets_deleted_at IS NULL;");
                $sth->execute(['team_id' => $_SESSION['team_id']]);
                $resolved_count = (int)$sth->fetchColumn();
                if ($tickets_total > 0 && $resolved_count > 0) {
                    echo round(($resolved_count / $tickets_total) * 100) . "%";
                } else {
                    echo "0%";
                }
                ?></p>
            </div>
            <div class="card statistics_recap">
                <h2><?= $t['stat_customer_satisfaction'] ?></h2>
                <p><?php 
                $sth = $dbco->prepare("SELECT AVG(tickets_rating) FROM tickets WHERE tickets_teams = :team_id AND tickets_status = 2 AND tickets_deleted_at IS NULL;");
                $sth->execute(['team_id' => $_SESSION['team_id']]);
                $avgRating = $sth->fetchColumn();
                if ($avgRating !== false && $avgRating !== null) {
                    echo number_format((float)$avgRating, 1, '.', ''); 
                } else {
                    echo "---";
                }
                ?> ★</p>
            </div>
            <div class="card statistics_recap">
                <h2><?= $t['stat_avg_resolution_time'] ?></h2>
                <p><?php 
                $sth = $dbco->prepare("SELECT AVG(TIMESTAMPDIFF(SECOND, tickets_creation_date, tickets_closing_date)) AS avg_resolution_time FROM tickets WHERE tickets_teams = :team_id AND tickets_status = 2 AND tickets_closing_date IS NOT NULL AND tickets_deleted_at IS NULL");
                $sth->execute(['team_id' => $_SESSION['team_id']]);
                $row = $sth->fetch(PDO::FETCH_ASSOC);
                if ($row && $row['avg_resolution_time'] !== null) {
                    $total_seconds = (int)$row['avg_resolution_time'];
                    $days = floor($total_seconds / 86400);
                    $hours = floor(($total_seconds % 86400) / 3600);
                    $minutes = floor(($total_seconds % 3600) / 60);
                    if ($days == 0 && $hours == 0) {
                        echo "{$minutes}" . $t['unit_minutes'];
                    } else if ($days == 0) {
                        echo "{$hours}" . $t['unit_hours'] . " {$minutes}" . $t['unit_minutes'];
                    } else {
                        echo "{$days}" . $t['unit_days'] . " {$hours}" . $t['unit_hours'] . " {$minutes}" . $t['unit_minutes'];
                    }
                } else {
                    echo "---";
                }
                ?></p>
            </div>
        </div>

        <div class="grid-cols-2">
            <!-- Category Charts -->
            <div class="card">
                <header>
                    <h2><?= $t['chart_monthly_title'] ?></h2>
                </header>
                <div class="charts-wrapper">
                    <canvas id="cumulativeTicketsChart"></canvas>
                </div>
            </div>
            
            <?php 
            $stmtTeamConfig = $dbco->prepare("SELECT teams_form_config FROM teams WHERE teams_id = :team_id LIMIT 1");
            $stmtTeamConfig->execute(['team_id' => $_SESSION['team_id']]);
            $teamFormConfigRaw = $stmtTeamConfig->fetchColumn();
            $teamFormConfig = $teamFormConfigRaw ? json_decode($teamFormConfigRaw, true) : [];
            $pieFieldOptions = [];
            if (is_array($teamFormConfig)) {
                foreach ($teamFormConfig as $f) {
                    if (!empty($f['question'])) {
                        $pieFieldOptions[] = $f['question'];
                    }
                }
            }
            $stmtAllTicketsFields = $dbco->prepare("SELECT tickets_additionnal_fields FROM tickets WHERE tickets_teams = :team_id AND tickets_additionnal_fields IS NOT NULL AND tickets_additionnal_fields != '' AND tickets_deleted_at IS NULL");
            $stmtAllTicketsFields->execute(['team_id' => $_SESSION['team_id']]);
            $ticketsFieldsRows = $stmtAllTicketsFields->fetchAll(PDO::FETCH_COLUMN);
            $pieChartDataByField = [];
            foreach ($pieFieldOptions as $qTitle) {
                $pieChartDataByField[$qTitle] = [];
            }
            foreach ($ticketsFieldsRows as $jsonRow) {
                $decodedAnswers = json_decode($jsonRow, true);
                if (is_array($decodedAnswers)) {
                    foreach ($decodedAnswers as $item) {
                        if (isset($item['question'], $item['answer'])) {
                            $q = trim($item['question']);
                            $ans = trim((string)$item['answer']);
                            if ($ans === '') {
                                $ans = $t['js_not_specified'];
                            }
                            if (!isset($pieChartDataByField[$q])) {
                                $pieChartDataByField[$q] = [];
                            }
                            if (!isset($pieChartDataByField[$q][$ans])) {
                                $pieChartDataByField[$q][$ans] = 0;
                            }
                            $pieChartDataByField[$q][$ans]++;
                        }
                    }
                }
            }
            ?>
            <div class="card">
                <header>
                    <h2><?= $t['chart_field_title_prefix'] ?> 
                        <select id="additionalFieldSelect">
                            <?php if (empty($pieFieldOptions)): ?>
                                <option value=""><?= $t['select_no_field'] ?></option>
                            <?php else: ?>
                                <?php foreach ($pieFieldOptions as $idx => $questionTitle): ?>
                                    <option value="<?= htmlspecialchars($questionTitle, ENT_QUOTES, 'UTF-8') ?>" <?= $idx === 0 ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($questionTitle) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </h2>
                </header>
                <div class="charts-wrapper">
                    <canvas id="additionalFieldPieChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Performance by members -->
        <div class="card table">
            <header>
                <h2><?= $t['table_title'] ?></h2>
            </header>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th><?= $t['th_agent'] ?></th>
                            <th><?= $t['th_assigned_tickets'] ?></th>
                            <th><?= $t['th_resolved_tickets'] ?></th>
                            <th><?= $t['th_avg_rating'] ?></th>
                            <th no-filter><?= $t['th_performance'] ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sth = $dbco->prepare("SELECT u.users_id, u.users_first_name, u.users_last_name, u.users_email, COUNT(t.tickets_id) AS total_tickets, SUM(CASE WHEN t.tickets_status = 2 THEN 1 ELSE 0 END) AS tickets_closed, AVG(t.tickets_rating) AS note_moyenne FROM users u INNER JOIN teams_members tm ON u.users_id = tm.teams_members_user_id LEFT JOIN tickets t ON u.users_id = t.tickets_assigned_to AND t.tickets_teams = :team_id_tickets AND t.tickets_deleted_at IS NULL WHERE tm.teams_members_team_id = :team_id AND tm.teams_members_deleted = 0 GROUP BY u.users_id, u.users_first_name, u.users_last_name, u.users_email");
                        $sth->execute([
                            'team_id'         => $_SESSION['team_id'],
                            'team_id_tickets' => $_SESSION['team_id']
                        ]);
                        $staff_stats = $sth->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($staff_stats as $staff): 
                            $note_moyenne = !empty($staff['note_moyenne']) ? (float)$staff['note_moyenne'] : 0;
                            $note_affichee = $note_moyenne > 0 ? number_format($note_moyenne, 1, '.', '') : '---';
                            $percentage = ($note_moyenne / 5) * 100;
                            $isAbsent = isUserCurrentlyAbsent((int)$staff['users_id']);
                            $staffName = trim(($staff['users_first_name'] ?? '') . ' ' . ($staff['users_last_name'] ?? ''));
                            $displayName = !empty($staffName) ? $staffName : $staff['users_email'];
                        ?>
                        <tr>
                            <td class="user">
                                <span class="profile_picture <?= ($staff['users_id'] == $_SESSION['user_id']) ? 'me' : '' ?>" title="<?= htmlspecialchars($displayName) ?><?= $isAbsent ? ' ' . $t['status_absent'] : '' ?>" aria-hidden="true" user_id="<?= (int)$staff['users_id'] ?>">
                                    <?= !empty($staff['users_first_name']) ? substr($staff['users_first_name'], 0, 1) : substr($staff['users_email'], 0, 1) ?>
                                </span>
                                <strong><?= htmlspecialchars($displayName) ?><?= $isAbsent ? ' ' . $t['status_absent'] : '' ?></strong>
                            </td>
                            <td><?= (int)$staff['total_tickets'] ?></td>
                            <td><?= (int)($staff['tickets_closed'] ?? 0) ?></td>
                            <td><?= $note_affichee ?><span class="text-yellow-400 text-sm"> ★</span></td>
                            <td>
                                <div class="progress_bar">
                                    <div style="width: <?= $percentage ?>%"></div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Footer -->
        <?php include("../src/php/dashboard_footer.php");?>

        <!-- Chart.js -->
        <script>
            const i18nStats = {
                unassigned: <?= json_encode($t['js_unassigned']) ?>,
                tooltipTotalSuffix: <?= json_encode($t['js_tooltip_total_suffix']) ?>,
                noAnswers: <?= json_encode($t['js_no_answers']) ?>,
                tooltipTicketSuffix: <?= json_encode($t['js_tooltip_ticket_suffix']) ?>
            };

            // Cumulative Tickets chart
            <?php 
            $months_labels = [];
            for ($i = 5; $i >= 0; $i--) {
                $months_labels[] = date('Y-m', strtotime("-$i months"));
            }
            $stmt_chart = $dbco->prepare("SELECT u.users_first_name, u.users_last_name, u.users_email, DATE_FORMAT(t.tickets_creation_date, '%Y-%m') AS ticket_month, COUNT(t.tickets_id) AS monthly_count FROM tickets t LEFT JOIN users u ON t.tickets_assigned_to = u.users_id WHERE t.tickets_teams = :team_id AND t.tickets_creation_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH) AND t.tickets_deleted_at IS NULL GROUP BY u.users_id, u.users_first_name, u.users_last_name, u.users_email, ticket_month ORDER BY ticket_month ASC");
            $stmt_chart->execute(['team_id' => $_SESSION['team_id']]);
            $raw_data = $stmt_chart->fetchAll(PDO::FETCH_ASSOC);

            // Sort data by agents
            $agents_data = [];
            foreach ($raw_data as $row) {
                if (empty($row['users_email']) && empty($row['users_first_name'])) {
                    $agent_name = $t['js_unassigned'];
                } elseif (!empty($row['users_first_name'])) {
                    $agent_name = $row['users_first_name'] . ' ' . substr($row['users_last_name'] ?? '', 0, 1) . '.';
                } else {
                    $agent_name = $row['users_email'];
                }
                $month = $row['ticket_month'];
                if (!isset($agents_data[$agent_name])) {
                    $agents_data[$agent_name] = array_fill_keys($months_labels, 0);
                }
                if (isset($agents_data[$agent_name][$month])) {
                    $agents_data[$agent_name][$month] = (int)$row['monthly_count'];
                }
            }
            
            // Formatting data for Chart.js
            $datasets = [];
            $colors = ['#4f46e5', '#5b52e8', '#685feb', '#776fee', '#867ff1', '#9690f3', '#a7a2f6', '#b8b4f8', '#cac7fb', '#dedcfd'];
            $color_index = 0;
            foreach ($agents_data as $agent_name => $monthly_counts) {
                $cumulative_data = [];
                $current_sum = 0;
                foreach ($months_labels as $month) {
                    $current_sum += ($monthly_counts[$month] ?? 0);
                    $cumulative_data[] = $current_sum;
                }
                if ($agent_name === $t['js_unassigned']) {
                    $dataset_color = '#9ca3af';
                } else {
                    $dataset_color = $colors[$color_index % count($colors)];
                    $color_index++;
                }
                $datasets[] = [
                    'label' => $agent_name,
                    'data' => $cumulative_data,
                    'borderColor' => $dataset_color,
                    'backgroundColor' => $dataset_color,
                    'tension' => 0.3,
                    'fill' => true
                ];
            }
            ?>
            const chartCumulativeTicketsLabels = <?= json_encode($months_labels) ?>;
            const chartCumulativeTicketsDatasets = <?= json_encode($datasets) ?>;
            const formattedCumulativeTicketsLabels = chartCumulativeTicketsLabels.map(label => {
                const date = new Date(label + '-01');
                return date.toLocaleDateString('<?= $lang ?>', { month: 'long', year: 'numeric' });
            });
            const ctx = document.getElementById('cumulativeTicketsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: formattedCumulativeTicketsLabels,
                    datasets: chartCumulativeTicketsDatasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false},
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' ' + i18nStats.tooltipTotalSuffix;
                                }
                            }
                        },
                        legend: {
                            position: 'none'
                        }
                    },
                    scales: {
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            ticks: { precision: 0}
                        },
                        x: {}
                    }
                }
            });
            
            // Pie chart dynamique pour les champs additionnels
            const fieldStatsMap = <?= json_encode($pieChartDataByField, JSON_UNESCAPED_UNICODE) ?>;
            const chartSelect = document.getElementById('additionalFieldSelect');
            const pieCanvas = document.getElementById('additionalFieldPieChart');
            const pieColors = ['#4f46e5', '#5b52e8', '#685feb', '#776fee', '#867ff1', '#9690f3', '#a7a2f6', '#b8b4f8', '#cac7fb', '#dedcfd'];
            let additionalPieChart = null;

            function updatePieChart(fieldName) {
                if (!pieCanvas) return;
                const pieCtx = pieCanvas.getContext('2d');
                const dataSet = (fieldName && fieldStatsMap[fieldName]) ? fieldStatsMap[fieldName] : {};
                const labels = Object.keys(dataSet);
                const dataValues = Object.values(dataSet);
                const hasData = dataValues.length > 0 && dataValues.some(val => val > 0);
                const chartLabels = hasData ? labels : [i18nStats.noAnswers];
                const chartData = hasData ? dataValues : [1];
                const chartBackgrounds = hasData 
                    ? chartLabels.map((_, i) => pieColors[i % pieColors.length]) 
                    : ['#e2e8f0'];
                if (additionalPieChart) {
                    additionalPieChart.destroy();
                }
                additionalPieChart = new Chart(pieCtx, {
                    type: 'pie',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            data: chartData,
                            backgroundColor: chartBackgrounds,
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 14,
                                    padding: 12,
                                    font: { size: 12 }
                                }
                            },
                            tooltip: {
                                enabled: hasData,
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((acc, curr) => acc + curr, 0);
                                        const val = context.parsed;
                                        const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                        return ` ${context.label}: ${val} ${i18nStats.tooltipTicketSuffix} (${pct}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
            if (chartSelect && chartSelect.value) {
                updatePieChart(chartSelect.value);
                chartSelect.addEventListener('change', (e) => {
                    updatePieChart(e.target.value);
                });
            } else {
                updatePieChart(null);
            }
        </script>
        <script src="<?= $opensupport_link?>/src/js/main.js"></script>
    </main>
</body>
</html>