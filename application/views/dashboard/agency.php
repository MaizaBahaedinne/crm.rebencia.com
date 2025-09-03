<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Agence</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Vue Agence</h1>
    <h2>Agence : <?= $agency->display_name; ?> (ID: <?= $agency->ID; ?>)</h2>
    <h2>Agents</h2>
    <ul>
        <?php foreach($agents as $agent): ?>
            <li><?= $agent->display_name; ?> (ID: <?= $agent->ID; ?>)</li>
        <?php endforeach; ?>
    </ul>
    <h2>Stats agence</h2>
    <ul>
    <!-- Leads supprimés -->
        <li>Propriétés : <?= $stats['properties']; ?></li>
        <li>Ventes : <?= $stats['sales']; ?></li>
    </ul>
    <canvas id="chartAgency" width="400" height="200"></canvas>
    <script>
        const ctx = document.getElementById('chartAgency').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Propriétés', 'Ventes'],
                datasets: [{
                    label: 'Stats agence',
                    data: [<?= $stats['properties']; ?>, <?= $stats['sales']; ?>],
                    backgroundColor: ['#4bc0c0', '#ff6384']
                }]
            }
        });
    </script>
</body>
</html>
