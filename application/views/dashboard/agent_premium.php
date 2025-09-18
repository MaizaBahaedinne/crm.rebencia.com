<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
// Debug temporaire - à supprimer après correction
if(isset($_GET['debug'])) {
    echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px; border: 1px solid #ccc;'>";
    echo "<h3>Debug Info:</h3>";
    echo "<p><strong>Type de \$agent:</strong> " . (isset($agent) ? gettype($agent) : 'non défini') . "</p>";
    if(isset($agent)) {
        echo "<p><strong>Contenu de \$agent:</strong></p>";
        echo "<pre>" . print_r($agent, true) . "</pre>";
    }
    echo "<p><strong>user_post_id:</strong> " . (isset($user_post_id) ? $user_post_id : 'non défini') . "</p>";
    echo "</div>";
}
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Header Premium avec Accueil Agent -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-2 text-dark fw-bold">
                                <i class="ri-dashboard-3-line me-2 text-primary"></i>
                                Bienvenue, <?php 
                                    if(isset($agent) && is_object($agent) && isset($agent->agent_name)) {
                                        echo $agent->agent_name;
                                    } elseif(isset($agent) && is_array($agent) && isset($agent['agent_name'])) {
                                        echo $agent['agent_name'];
                                    } elseif(isset($agent) && is_object($agent) && isset($agent->display_name)) {
                                        echo $agent->display_name;
                                    } elseif(isset($agent) && is_array($agent) && isset($agent['display_name'])) {
                                        echo $agent['display_name'];
                                    } else {
                                        echo 'Agent';
                                    }
                                ?>
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="ri-calendar-line me-1"></i>
                                <?= date('l d F Y'); ?> - Voici votre tableau de bord personnalisé
                            </p>
                        </div>
                        <div class="page-title-right">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="ri-add-line me-2"></i>Actions rapides
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?= base_url('estimation'); ?>">
                                        <i class="ri-file-add-line me-2"></i>Nouvelle estimation
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('clients/add'); ?>">
                                        <i class="ri-user-add-line me-2"></i>Nouveau contact
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('tasks/add'); ?>">
                                        <i class="ri-task-line me-2"></i>Nouvelle tâche
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cartes de Statistiques Principales -->
            <div class="row mb-4">
                <!-- Propriétés -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stats-card" data-color="primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                        <i class="ri-home-4-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Propriétés</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-primary counter" data-target="<?= $properties_total; ?>">0</h4>
                                        <small class="text-success ms-2">
                                            <i class="ri-arrow-up-line me-1"></i>+<?= $properties_recent; ?> cette semaine
                                        </small>
                                    </div>
                                    <div class="mt-2">
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-primary" style="width: <?= $properties_total > 0 ? ($properties_active / $properties_total * 100) : 0; ?>%"></div>
                                        </div>
                                        <small class="text-muted"><?= $properties_active; ?> actives • <?= $properties_sold; ?> vendues</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contacts -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stats-card" data-color="success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                                        <i class="ri-contacts-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Contacts</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-success counter" data-target="<?= $contacts_total; ?>">0</h4>
                                        <small class="text-info ms-2">
                                            <i class="ri-user-add-line me-1"></i>+<?= $contacts_recent; ?> ce mois
                                        </small>
                                    </div>
                                    <div class="mt-2">
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-success" style="width: <?= $contacts_total > 0 ? ($contacts_active / $contacts_total * 100) : 0; ?>%"></div>
                                        </div>
                                        <small class="text-muted"><?= $contacts_active; ?> actifs • <?= $contacts_total - $contacts_active; ?> à relancer</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stats-card" data-color="warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                        <i class="ri-exchange-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Transactions</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-warning counter" data-target="<?= $transactions_total; ?>">0</h4>
                                        <small class="text-primary ms-2">
                                            <i class="ri-calendar-line me-1"></i><?= $transactions_month; ?> ce mois
                                        </small>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted fw-medium">
                                            <i class="ri-money-dollar-circle-line me-1"></i>
                                            <?= number_format($transactions_value, 0, '', ' '); ?> TND de volume
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commissions -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stats-card" data-color="info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-info-subtle text-info rounded-circle fs-20">
                                        <i class="ri-percent-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Commissions</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-20 fw-semibold ff-secondary mb-0 text-info">
                                            <?= number_format($commissions_total, 0, '', ' '); ?> TND
                                        </h4>
                                    </div>
                                    <div class="mt-2">
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">En attente: <?= number_format($commissions_pending, 0, '', ' '); ?> TND</small>
                                            <small class="text-success">Ce mois: <?= number_format($commissions_month, 0, '', ' '); ?> TND</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deuxième ligne de statistiques -->
            <div class="row mb-4">
                <!-- Tâches -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted fw-normal mb-2">Tâches</h6>
                                    <h4 class="mb-0 text-dark counter" data-target="<?= $tasks_total; ?>">0</h4>
                                    <small class="text-danger">
                                        <i class="ri-alert-line me-1"></i><?= $tasks_overdue; ?> en retard
                                    </small>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-dark rounded-circle">
                                        <i class="ri-task-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RDV Aujourd'hui -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted fw-normal mb-2">RDV Aujourd'hui</h6>
                                    <h4 class="mb-0 text-dark counter" data-target="<?= $meetings_today; ?>">0</h4>
                                    <small class="text-info">
                                        <i class="ri-calendar-event-line me-1"></i><?= $meetings_week; ?> cette semaine
                                    </small>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-dark rounded-circle">
                                        <i class="ri-calendar-check-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tâches Aujourd'hui -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted fw-normal mb-2">Tâches Aujourd'hui</h6>
                                    <h4 class="mb-0 text-dark counter" data-target="<?= $tasks_today; ?>">0</h4>
                                    <small class="text-warning">
                                        <i class="ri-time-line me-1"></i><?= $tasks_pending; ?> en attente
                                    </small>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-dark rounded-circle">
                                        <i class="ri-todo-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Objectifs -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted fw-normal mb-2">Objectifs</h6>
                                    <h4 class="mb-0 text-dark">
                                        <?php 
                                        $progress = isset($objectives['progress']) ? $objectives['progress'] : 0;
                                        echo number_format($progress, 0);
                                        ?>%
                                    </h4>
                                    <small class="text-success">
                                        <i class="ri-trophy-line me-1"></i>En progression
                                    </small>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-dark rounded-circle">
                                        <i class="ri-target-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Principale avec Graphiques et Calendrier -->
            <div class="row mb-4">
                <!-- Graphique des Propriétés -->
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">
                                    <i class="ri-bar-chart-line me-2 text-primary"></i>
                                    Performance Mensuelle
                                </h5>
                                <div class="dropdown">
                                    <button class="btn btn-ghost-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="ri-more-2-line"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Exporter</a></li>
                                        <li><a class="dropdown-item" href="#">Rapport détaillé</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Onglets pour différents graphiques -->
                            <ul class="nav nav-tabs nav-justified mb-4" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#properties-chart">
                                        <i class="ri-home-4-line me-1"></i>Propriétés
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#commissions-chart">
                                        <i class="ri-money-dollar-circle-line me-1"></i>Commissions
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#activities-chart">
                                        <i class="ri-pulse-line me-1"></i>Activités
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <!-- Graphique Propriétés -->
                                <div class="tab-pane show active" id="properties-chart">
                                    <canvas id="propertiesChart" height="300"></canvas>
                                </div>
                                
                                <!-- Graphique Commissions -->
                                <div class="tab-pane" id="commissions-chart">
                                    <canvas id="commissionsChart" height="300"></canvas>
                                </div>
                                
                                <!-- Graphique Activités -->
                                <div class="tab-pane" id="activities-chart">
                                    <canvas id="activitiesChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendrier et Tâches -->
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-0">
                                <i class="ri-calendar-line me-2 text-success"></i>
                                Agenda
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Mini Calendrier -->
                            <div class="calendar-mini mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="mb-0"><?= date('F Y'); ?></h6>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary btn-sm">
                                            <i class="ri-arrow-left-s-line"></i>
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm">
                                            <i class="ri-arrow-right-s-line"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Grille calendrier simple -->
                                <div class="calendar-grid">
                                    <div class="calendar-days">
                                        <span>Lun</span><span>Mar</span><span>Mer</span><span>Jeu</span><span>Ven</span><span>Sam</span><span>Dim</span>
                                    </div>
                                    <div class="calendar-dates">
                                        <?php 
                                        $today = date('j');
                                        for($i = 1; $i <= 30; $i++): 
                                        ?>
                                            <span class="calendar-date <?= $i == $today ? 'today' : ''; ?> <?= in_array($i, [5, 12, 18, 25]) ? 'has-event' : ''; ?>">
                                                <?= $i; ?>
                                            </span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Événements du jour -->
                            <div class="events-today">
                                <h6 class="mb-3">
                                    <i class="ri-time-line me-2"></i>Aujourd'hui
                                </h6>
                                
                                <?php if(!empty($calendar_events)): ?>
                                    <?php foreach(array_slice($calendar_events, 0, 3) as $event): ?>
                                        <div class="event-item d-flex align-items-center mb-3 p-2 bg-light rounded">
                                            <div class="avatar-xs me-3">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-12">
                                                    <i class="ri-calendar-event-line"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fs-14"><?= $event['title']; ?></h6>
                                                <small class="text-muted">
                                                    <?= date('H:i', strtotime($event['date'])); ?>
                                                </small>
                                            </div>
                                            <span class="badge bg-primary-subtle text-primary">
                                                <?= $event['type']; ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-3">
                                        <i class="ri-calendar-check-line fs-24 text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Aucun événement aujourd'hui</p>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="text-center mt-3">
                                    <a href="<?= base_url('calendar'); ?>" class="btn btn-primary btn-sm w-100">
                                        <i class="ri-calendar-line me-2"></i>Voir le calendrier complet
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Activités Récentes et Tâches -->
            <div class="row mb-4">
                <!-- Activités Récentes -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">
                                    <i class="ri-history-line me-2 text-info"></i>
                                    Activités Récentes
                                </h5>
                                <a href="<?= base_url('activities'); ?>" class="btn btn-ghost-info btn-sm">
                                    Voir tout
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="activity-timeline">
                                <?php if(!empty($recent_activities)): ?>
                                    <?php foreach(array_slice($recent_activities, 0, 5) as $index => $activity): ?>
                                        <div class="activity-item d-flex align-items-start mb-3">
                                            <div class="activity-icon me-3">
                                                <span class="avatar-xs">
                                                    <span class="avatar-title bg-light text-dark rounded-circle fs-12">
                                                        <i class="ri-record-circle-line"></i>
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fs-14">
                                                    <?= $activity['title'] ?? 'Activité'; ?>
                                                </h6>
                                                <p class="text-muted mb-1 fs-13">
                                                    <?= $activity['description'] ?? 'Description de l\'activité'; ?>
                                                </p>
                                                <small class="text-muted">
                                                    <i class="ri-time-line me-1"></i>
                                                    <?= date('d/m/Y H:i', strtotime($activity['created_at'] ?? 'now')); ?>
                                                </small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="ri-history-line fs-24 text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Aucune activité récente</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tâches à Faire -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">
                                    <i class="ri-task-line me-2 text-warning"></i>
                                    Tâches à Faire
                                </h5>
                                <a href="<?= base_url('tasks/add'); ?>" class="btn btn-ghost-warning btn-sm">
                                    <i class="ri-add-line me-1"></i>Ajouter
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tasks-list">
                                <?php 
                                // Simuler des tâches pour la démo
                                $demo_tasks = [
                                    ['title' => 'Rappeler client Dupont', 'priority' => 'high', 'due' => 'today'],
                                    ['title' => 'Préparer dossier estimation', 'priority' => 'medium', 'due' => 'tomorrow'],
                                    ['title' => 'Visite propriété rue Bourguiba', 'priority' => 'high', 'due' => 'today'],
                                    ['title' => 'Envoyer rapport mensuel', 'priority' => 'low', 'due' => 'week'],
                                    ['title' => 'Réunion équipe commerciale', 'priority' => 'medium', 'due' => 'tomorrow']
                                ];
                                
                                foreach($demo_tasks as $task):
                                    $priority_class = [
                                        'high' => 'danger',
                                        'medium' => 'warning', 
                                        'low' => 'success'
                                    ][$task['priority']];
                                    
                                    $due_text = [
                                        'today' => 'Aujourd\'hui',
                                        'tomorrow' => 'Demain',
                                        'week' => 'Cette semaine'
                                    ][$task['due']];
                                ?>
                                    <div class="task-item d-flex align-items-center mb-3 p-2 bg-light rounded">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="checkbox" id="task-<?= $task['title']; ?>">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fs-14"><?= $task['title']; ?></h6>
                                            <small class="text-muted">
                                                <i class="ri-calendar-line me-1"></i><?= $due_text; ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-<?= $priority_class; ?>-subtle text-<?= $priority_class; ?>">
                                            <?= ucfirst($task['priority']); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                                
                                <div class="text-center mt-3">
                                    <a href="<?= base_url('tasks'); ?>" class="btn btn-warning btn-sm w-100">
                                        <i class="ri-task-line me-2"></i>Voir toutes les tâches
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* Styles Premium pour le Dashboard Agent */
.stats-card {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.15) !important;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 3px;
    width: 100%;
    background: linear-gradient(90deg, var(--bs-primary), var(--bs-info));
}

.stats-card[data-color="success"]::before {
    background: linear-gradient(90deg, var(--bs-success), var(--bs-info));
}

.stats-card[data-color="warning"]::before {
    background: linear-gradient(90deg, var(--bs-warning), var(--bs-danger));
}

.stats-card[data-color="info"]::before {
    background: linear-gradient(90deg, var(--bs-info), var(--bs-primary));
}

/* Calendrier */
.calendar-grid .calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
    margin-bottom: 10px;
}

.calendar-grid .calendar-days span {
    text-align: center;
    font-weight: 600;
    font-size: 12px;
    color: #6c757d;
    padding: 5px;
}

.calendar-grid .calendar-dates {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
}

.calendar-date {
    text-align: center;
    padding: 8px 4px;
    font-size: 12px;
    cursor: pointer;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.calendar-date:hover {
    background-color: #e9ecef;
}

.calendar-date.today {
    background-color: var(--bs-primary);
    color: white;
    font-weight: 600;
}

.calendar-date.has-event {
    position: relative;
}

.calendar-date.has-event::after {
    content: '';
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 4px;
    background-color: var(--bs-warning);
    border-radius: 50%;
}

/* Timeline des activités */
.activity-timeline .activity-item {
    position: relative;
}

.activity-timeline .activity-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 11px;
    top: 30px;
    bottom: -15px;
    width: 1px;
    background-color: #e9ecef;
}

/* Animations */
@keyframes countUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.counter {
    animation: countUp 0.6s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .page-title-box .d-sm-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .calendar-grid .calendar-dates {
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
    }
}

/* Effets sur les tâches */
.task-item {
    transition: all 0.2s ease;
}

.task-item:hover {
    background-color: #f8f9fa !important;
    transform: translateX(5px);
}

.form-check-input:checked ~ .flex-grow-1 h6 {
    text-decoration: line-through;
    opacity: 0.6;
}
</style>

<script>
// Initialisation du dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs
    animateCounters();
    
    // Initialisation des graphiques
    initializeCharts();
    
    // Animation d'apparition des cartes
    animateCards();
    
    // Gestion des tâches
    initializeTasks();
});

// Animation des compteurs
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        let count = 0;
        const increment = target / 50;
        
        const timer = setInterval(() => {
            count += increment;
            if (count >= target) {
                count = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(count);
        }, 30);
    });
}

// Initialisation des graphiques
function initializeCharts() {
    // Graphique des propriétés
    const propertiesCtx = document.getElementById('propertiesChart')?.getContext('2d');
    if (propertiesCtx) {
        new Chart(propertiesCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($properties_chart_data['labels'] ?? []); ?>,
                datasets: [{
                    label: 'Propriétés ajoutées',
                    data: <?= json_encode($properties_chart_data['values'] ?? []); ?>,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f8f9fa'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Graphique des commissions
    const commissionsCtx = document.getElementById('commissionsChart')?.getContext('2d');
    if (commissionsCtx) {
        new Chart(commissionsCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($commissions_chart_data['labels'] ?? []); ?>,
                datasets: [{
                    label: 'Commissions (TND)',
                    data: <?= json_encode($commissions_chart_data['values'] ?? []); ?>,
                    backgroundColor: 'rgba(13, 202, 240, 0.8)',
                    borderColor: '#0dcaf0',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f8f9fa'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Graphique des activités (doughnut)
    const activitiesCtx = document.getElementById('activitiesChart')?.getContext('2d');
    if (activitiesCtx) {
        new Chart(activitiesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Visites', 'Appels', 'Emails', 'Réunions'],
                datasets: [{
                    data: [
                        <?= $activities_chart_data['visites'] ?? 0; ?>,
                        <?= $activities_chart_data['appels'] ?? 0; ?>,
                        <?= $activities_chart_data['emails'] ?? 0; ?>,
                        <?= $activities_chart_data['reunions'] ?? 0; ?>
                    ],
                    backgroundColor: [
                        '#0d6efd',
                        '#198754',
                        '#ffc107',
                        '#dc3545'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
}

// Animation des cartes
function animateCards() {
    const cards = document.querySelectorAll('.card');
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
}

// Gestion des tâches
function initializeTasks() {
    const taskCheckboxes = document.querySelectorAll('.task-item .form-check-input');
    
    taskCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const taskItem = this.closest('.task-item');
            if (this.checked) {
                taskItem.style.opacity = '0.6';
                // Ici vous pouvez ajouter une requête AJAX pour marquer la tâche comme terminée
            } else {
                taskItem.style.opacity = '1';
            }
        });
    });
}

// Fonction pour rafraîchir les données (appelée périodiquement)
function refreshDashboard() {
    // Ici vous pouvez ajouter des requêtes AJAX pour mettre à jour les données
    console.log('Rafraîchissement du dashboard...');
}

// Rafraîchissement automatique toutes les 5 minutes
setInterval(refreshDashboard, 300000);
</script>
