<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Agent</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Vue Agent</h1>
    <h2>Agent : <?= $agent->display_name; ?> (ID: <?= $agent->ID; ?>)</h2>
    <h2>Stats personnelles</h2>
    <ul>
    <!-- Leads supprimés -->
        <li>Propriétés : <?= $stats['properties']; ?></li>
        <li>Ventes : <?= $stats['sales']; ?></li>
    </ul>
    <canvas id="chartAgent" width="400" height="200"></canvas>
    <script>
        const ctx = document.getElementById('chartAgent').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Propriétés', 'Ventes'],
                datasets: [{
                    label: 'Stats agent',
                    data: [<?= $stats['properties']; ?>, <?= $stats['sales']; ?>],
                    backgroundColor: ['#4bc0c0', '#ff6384']
                }]
            }
        });
    </script>
</body>
</html>
