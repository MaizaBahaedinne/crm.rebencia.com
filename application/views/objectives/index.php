<?php $this->load->view('includes/header'); ?>

<!-- CSS Premium pour les Objectifs -->
<style>
.objectives-premium {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    position: relative;
}

.objectives-premium::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" fill-opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}

.glass-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.glass-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 35px 60px rgba(0, 0, 0, 0.15);
}

.stats-card {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.05));
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 24px;
    padding: 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.stats-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: all 0.6s;
    transform: rotate(-45deg) translateX(-100%);
}

.stats-card:hover::before {
    transform: rotate(-45deg) translateX(100%);
}

.stats-card:hover {
    transform: translateY(-10px) scale(1.02);
    border-color: rgba(255, 255, 255, 0.5);
}

.stats-number {
    font-size: 3rem;
    font-weight: 700;
    background: linear-gradient(135deg, #fff, #f8f9fa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.stats-label {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
    font-size: 1.1rem;
    letter-spacing: 0.5px;
}

.stats-icon {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: rgba(255, 255, 255, 0.8);
}

.premium-header {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    margin-bottom: 2rem;
    padding: 2rem;
    color: white;
}

.premium-title {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #fff, #f8f9fa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 0.5rem;
}

.breadcrumb-premium {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50px;
    padding: 0.5rem 1.5rem;
    backdrop-filter: blur(10px);
}

.breadcrumb-premium .breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb-premium .breadcrumb-item a:hover {
    color: white;
}

.breadcrumb-premium .breadcrumb-item.active {
    color: white;
    font-weight: 600;
}

.action-card {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 20px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.btn-premium {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 15px;
    padding: 0.75rem 2rem;
    color: white;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-premium::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: all 0.6s;
}

.btn-premium:hover::before {
    left: 100%;
}

.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.month-selector {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 15px;
    color: white;
    backdrop-filter: blur(10px);
}

.month-selector:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1);
}

.objectives-table {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    overflow: hidden;
}

.table-premium {
    margin: 0;
    color: white;
}

.table-premium thead th {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: white;
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 1.5rem 1rem;
}

.table-premium tbody td {
    border-color: rgba(255, 255, 255, 0.1);
    padding: 1.25rem 1rem;
    vertical-align: middle;
}

.progress-premium {
    height: 8px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.2);
    overflow: hidden;
}

.progress-premium .progress-bar {
    border-radius: 10px;
    background: linear-gradient(90deg, #00d4aa, #00d4ff);
    transition: width 0.6s ease;
}

.floating-action {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    z-index: 1000;
}

.fab-premium {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    transition: all 0.3s ease;
}

.fab-premium:hover {
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.pulse-animation {
    animation: pulse 2s infinite;
}
</style>

<div class="objectives-premium">
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                
                <!-- Premium Header -->
                <div class="premium-header animate-fadeInUp">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="premium-title">Dashboard des Objectifs</h1>
                            <p class="mb-0" style="color: rgba(255, 255, 255, 0.8); font-size: 1.1rem;">
                                Suivez et gérez les performances de vos équipes
                            </p>
                        </div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-premium mb-0">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url('dashboard'); ?>">
                                        <i class="ri-home-4-line me-1"></i>Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Objectifs</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Month Selector and Actions -->
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="action-card animate-fadeInUp" style="animation-delay: 0.2s;">
                            <h6 class="mb-3" style="color: white; font-weight: 600;">
                                <i class="ri-calendar-line me-2"></i>Période d'analyse
                            </h6>
                            <form method="GET" action="<?php echo base_url('objectives'); ?>">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <label class="form-label" style="color: rgba(255, 255, 255, 0.9);">
                                            Sélectionner le mois
                                        </label>
                                        <input type="month" class="form-control month-selector" name="month" 
                                               value="<?php echo $current_month; ?>" 
                                               onchange="this.form.submit()">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-premium w-100">
                                            <i class="ri-search-line me-1"></i>Analyser
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="action-card animate-fadeInUp" style="animation-delay: 0.4s;">
                            <h6 class="mb-3" style="color: white; font-weight: 600;">
                                <i class="ri-settings-3-line me-2"></i>Actions rapides
                            </h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="<?php echo base_url('objectives/set_monthly'); ?>" 
                                   class="btn btn-premium flex-fill">
                                    <i class="ri-target-line me-1"></i>Définir Objectifs
                                </a>
                                <button class="btn btn-premium flex-fill" onclick="exportData()">
                                    <i class="ri-download-line me-1"></i>Exporter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Statistics Overview Premium -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stats-card animate-fadeInUp" style="animation-delay: 0.6s;">
                            <div class="stats-icon">
                                <i class="ri-group-line"></i>
                            </div>
                            <div class="stats-number pulse-animation">
                                <?php echo $summary['agents_with_objectives'] ?? 0; ?>
                            </div>
                            <div class="stats-label">Agents avec Objectifs</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stats-card animate-fadeInUp" style="animation-delay: 0.8s;">
                            <div class="stats-icon">
                                <i class="ri-file-list-3-line"></i>
                            </div>
                            <div class="stats-number pulse-animation">
                                <?php echo $summary['total_objectives'] ?? 0; ?>
                            </div>
                            <div class="stats-label">Objectifs Définis</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stats-card animate-fadeInUp" style="animation-delay: 1.0s;">
                            <div class="stats-icon">
                                <i class="ri-trophy-line"></i>
                            </div>
                            <div class="stats-number pulse-animation">
                                <?php echo number_format($summary['avg_completion'] ?? 0, 1); ?>%
                            </div>
                            <div class="stats-label">Taux Moyen</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stats-card animate-fadeInUp" style="animation-delay: 1.2s;">
                            <div class="stats-icon">
                                <i class="ri-money-euro-circle-line"></i>
                            </div>
                            <div class="stats-number pulse-animation">
                                <?php echo number_format($summary['total_revenue_target'] ?? 0 / 1000, 0); ?>K€
                            </div>
                            <div class="stats-label">CA Objectif</div>
                        </div>
                    </div>
                </div>
                <!-- Objectives Table Premium -->
                <div class="objectives-table animate-fadeInUp" style="animation-delay: 1.4s;">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0" style="color: white; font-weight: 600;">
                                <i class="ri-table-line me-2"></i>Objectifs du mois
                            </h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-premium btn-sm" onclick="refreshData()">
                                    <i class="ri-refresh-line me-1"></i>Actualiser
                                </button>
                                <a href="<?php echo base_url('objectives/set_monthly'); ?>" 
                                   class="btn btn-premium btn-sm">
                                    <i class="ri-add-line me-1"></i>Ajouter
                                </a>
                            </div>
                        </div>

            <!-- Objectives Dashboard Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                        
                        <?php if (!empty($objectives_data)): ?>
                            <div class="table-responsive">
                                <table class="table table-premium">
                                    <thead>
                                        <tr>
                                            <th style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                <i class="ri-user-3-line me-2"></i>Agent
                                            </th>
                                            <th style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                <i class="ri-file-list-3-line me-2"></i>Estimations
                                            </th>
                                            <th style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                <i class="ri-phone-line me-2"></i>Contacts
                                            </th>
                                            <th style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                <i class="ri-exchange-line me-2"></i>Transactions
                                            </th>
                                            <th style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                <i class="ri-money-euro-circle-line me-2"></i>Revenus
                                            </th>
                                            <th style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                <i class="ri-trophy-line me-2"></i>Performance
                                            </th>
                                            <th style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                <i class="ri-settings-3-line me-2"></i>Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($objectives_data as $objective): ?>
                                            <tr style="transition: all 0.3s ease;" 
                                                onmouseover="this.style.background='rgba(255,255,255,0.1)'" 
                                                onmouseout="this.style.background='transparent'">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-premium me-3">
                                                            <div style="width: 45px; height: 45px; border-radius: 50%; 
                                                                        background: linear-gradient(135deg, #667eea, #764ba2);
                                                                        display: flex; align-items: center; justify-content: center;
                                                                        color: white; font-weight: 600; font-size: 1.1rem;">
                                                                <?php echo strtoupper(substr($objective->agent_name ?? 'A', 0, 1)); ?>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div style="color: white; font-weight: 600; font-size: 1rem;">
                                                                <?php echo htmlspecialchars($objective->agent_name ?? 'Agent'); ?>
                                                            </div>
                                                            <small style="color: rgba(255, 255, 255, 0.7);">
                                                                ID: <?php echo $objective->agent_id; ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="objective-metric">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span style="color: rgba(255, 255, 255, 0.9); font-weight: 500;">
                                                                <?php echo $objective->estimations_count ?? 0; ?> / <?php echo $objective->estimations_target; ?>
                                                            </span>
                                                            <span style="color: #00d4aa; font-weight: 600;">
                                                                <?php 
                                                                $progress = min(100, $objective->estimations_progress ?? 0);
                                                                echo number_format($progress, 1); 
                                                                ?>%
                                                            </span>
                                                        </div>
                                                        <div class="progress-premium">
                                                            <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="objective-metric">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span style="color: rgba(255, 255, 255, 0.9); font-weight: 500;">
                                                                <?php echo $objective->contacts_count ?? 0; ?> / <?php echo $objective->contacts_target; ?>
                                                            </span>
                                                            <span style="color: #00d4aa; font-weight: 600;">
                                                                <?php 
                                                                $progress = min(100, $objective->contacts_progress ?? 0);
                                                                echo number_format($progress, 1); 
                                                                ?>%
                                                            </span>
                                                        </div>
                                                        <div class="progress-premium">
                                                            <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="objective-metric">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span style="color: rgba(255, 255, 255, 0.9); font-weight: 500;">
                                                                <?php echo $objective->transactions_count ?? 0; ?> / <?php echo $objective->transactions_target; ?>
                                                            </span>
                                                            <span style="color: #00d4aa; font-weight: 600;">
                                                                <?php 
                                                                $progress = min(100, $objective->transactions_progress ?? 0);
                                                                echo number_format($progress, 1); 
                                                                ?>%
                                                            </span>
                                                        </div>
                                                        <div class="progress-premium">
                                                            <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="objective-metric">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span style="color: rgba(255, 255, 255, 0.9); font-weight: 500;">
                                                                <?php echo number_format($objective->revenue_current ?? 0); ?>€ / <?php echo number_format($objective->revenue_target); ?>€
                                                            </span>
                                                            <span style="color: #00d4aa; font-weight: 600;">
                                                                <?php 
                                                                $progress = min(100, $objective->revenue_progress ?? 0);
                                                                echo number_format($progress, 1); 
                                                                ?>%
                                                            </span>
                                                        </div>
                                                        <div class="progress-premium">
                                                            <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <?php 
                                                        $overall = $objective->overall_progress ?? 0;
                                                        $badge_class = $overall >= 80 ? 'success' : ($overall >= 60 ? 'warning' : 'danger');
                                                        $badge_color = $overall >= 80 ? '#00d4aa' : ($overall >= 60 ? '#ffc107' : '#ff6b6b');
                                                        ?>
                                                        <div style="display: inline-block; padding: 0.5rem 1rem; 
                                                                    background: rgba(255, 255, 255, 0.1); 
                                                                    border: 2px solid <?php echo $badge_color; ?>;
                                                                    border-radius: 50px; color: <?php echo $badge_color; ?>;
                                                                    font-weight: 600;">
                                                            <?php echo number_format($overall, 1); ?>%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-premium btn-sm" 
                                                                onclick="viewDetails(<?php echo $objective->agent_id; ?>)">
                                                            <i class="ri-eye-line"></i>
                                                        </button>
                                                        <button class="btn btn-premium btn-sm" 
                                                                onclick="editObjective(<?php echo $objective->agent_id; ?>)">
                                                            <i class="ri-edit-line"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div style="opacity: 0.6;">
                                    <i class="ri-file-list-3-line" style="font-size: 4rem; color: rgba(255, 255, 255, 0.5);"></i>
                                    <h5 style="color: rgba(255, 255, 255, 0.8); margin-top: 1rem;">
                                        Aucun objectif défini pour ce mois
                                    </h5>
                                    <p style="color: rgba(255, 255, 255, 0.6);">
                                        Commencez par définir des objectifs pour vos agents
                                    </p>
                                    <a href="<?php echo base_url('objectives/set_monthly'); ?>" 
                                       class="btn btn-premium mt-3">
                                        <i class="ri-add-line me-2"></i>Définir des Objectifs
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Floating Action Button -->
                <div class="floating-action">
                    <button class="fab-premium" onclick="quickActions()" title="Actions rapides">
                        <i class="ri-add-line"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- JavaScript Premium -->
<script>
// Animation d'entrée progressive
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter des effets d'hover dynamiques
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach((card, index) => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-15px) scale(1.03)';
            this.style.boxShadow = '0 40px 80px rgba(0, 0, 0, 0.2)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-5px) scale(1)';
            this.style.boxShadow = '0 25px 45px rgba(0, 0, 0, 0.1)';
        });
    });

    // Animation des barres de progression
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
});

// Actions rapides
function quickActions() {
    const actions = [
        { 
            text: 'Définir Objectifs Mensuels', 
            icon: 'ri-target-line',
            action: () => window.location.href = '<?php echo base_url("objectives/set_monthly"); ?>'
        },
        { 
            text: 'Voir Performances', 
            icon: 'ri-bar-chart-line',
            action: () => window.location.href = '<?php echo base_url("objectives/performance"); ?>'
        },
        { 
            text: 'Exporter Données', 
            icon: 'ri-download-line',
            action: () => exportData()
        },
        { 
            text: 'Actualiser', 
            icon: 'ri-refresh-line',
            action: () => window.location.reload()
        }
    ];

    let menuHTML = '<div class="quick-menu" style="position: fixed; bottom: 5rem; right: 2rem; z-index: 1001;">';
    actions.forEach((action, index) => {
        menuHTML += `
            <div class="quick-action-item" style="
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white; padding: 1rem; margin-bottom: 0.5rem;
                border-radius: 50px; cursor: pointer; 
                transform: translateX(100px); opacity: 0;
                transition: all 0.3s ease; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
                animation: slideInRight 0.5s forwards ${index * 0.1}s;
            " onclick="${action.action.toString().replace('() => ', '')}">
                <i class="${action.icon} me-2"></i>${action.text}
            </div>
        `;
    });
    menuHTML += '</div>';

    // Ajouter le style CSS pour l'animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .quick-action-item:hover {
            transform: translateX(-5px) scale(1.05) !important;
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6) !important;
        }
    `;
    document.head.appendChild(style);

    // Retirer le menu existant s'il y en a un
    const existingMenu = document.querySelector('.quick-menu');
    if (existingMenu) {
        existingMenu.remove();
        return;
    }

    // Ajouter le nouveau menu
    document.body.insertAdjacentHTML('beforeend', menuHTML);

    // Retirer le menu après 5 secondes ou au clic ailleurs
    setTimeout(() => {
        const menu = document.querySelector('.quick-menu');
        if (menu) menu.remove();
    }, 5000);
}

// Fonctions d'action
function viewDetails(agentId) {
    window.location.href = `<?php echo base_url("objectives/agent/"); ?>${agentId}`;
}

function editObjective(agentId) {
    window.location.href = `<?php echo base_url("objectives/set_monthly?agent="); ?>${agentId}`;
}

function refreshData() {
    // Animation de refresh
    const fab = document.querySelector('.fab-premium');
    fab.style.transform = 'rotate(360deg)';
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

function exportData() {
    // Animation d'export
    const notification = document.createElement('div');
    notification.innerHTML = `
        <div style="
            position: fixed; top: 2rem; right: 2rem; z-index: 1002;
            background: linear-gradient(135deg, #00d4aa, #00d4ff);
            color: white; padding: 1rem 2rem; border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 212, 170, 0.4);
            animation: slideInDown 0.5s ease;
        ">
            <i class="ri-download-line me-2"></i>Export des données en cours...
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
        // Ici, déclencher le vrai export
        window.print(); // Ou autre logique d'export
    }, 2000);
}

// Effet de particules (optionnel)
function createParticleEffect() {
    for (let i = 0; i < 3; i++) {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: fixed; width: 4px; height: 4px; background: white;
            border-radius: 50%; pointer-events: none; z-index: 999;
            top: ${Math.random() * 100}vh; left: ${Math.random() * 100}vw;
            animation: float ${3 + Math.random() * 4}s linear infinite;
            opacity: ${0.1 + Math.random() * 0.3};
        `;
        document.body.appendChild(particle);
        
        setTimeout(() => particle.remove(), 7000);
    }
}

// Style pour l'animation de flottement des particules
const particleStyle = document.createElement('style');
particleStyle.textContent = `
    @keyframes float {
        0% { transform: translateY(100vh) rotate(0deg); }
        100% { transform: translateY(-10vh) rotate(360deg); }
    }
`;
document.head.appendChild(particleStyle);

// Créer des particules périodiquement
setInterval(createParticleEffect, 3000);
</script>

<?php $this->load->view('includes/footer'); ?>
