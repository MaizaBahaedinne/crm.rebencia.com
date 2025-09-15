<?php $this->load->view('includes/header'); ?>

<!-- CSS Premium Glassmorphisme -->
<style>
.objectives-premium {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    position: relative;
    overflow: hidden;
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
    animation: float 20s linear infinite;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0px); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.glass-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.glass-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.glass-card:hover::before {
    left: 100%;
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
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.6s;
}

.stats-card:hover::before {
    transform: translateX(100%);
}

.stats-card:hover {
    transform: translateY(-10px) scale(1.02);
    border-color: rgba(255, 255, 255, 0.5);
}

.stats-number {
    font-size: 3rem;
    font-weight: 800;
    color: white;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    margin-bottom: 0.5rem;
    animation: pulse 2s infinite;
}

.stats-label {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
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

.table-premium {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.table-premium thead th {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: white;
    font-weight: 600;
    padding: 1.5rem;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.table-premium tbody td {
    border: none;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1.5rem;
    color: rgba(255, 255, 255, 0.9);
    background: rgba(255, 255, 255, 0.05);
}

.table-premium tbody tr:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: scale(1.01);
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
    transition: left 0.5s;
}

.btn-premium:hover::before {
    left: 100%;
}

.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    color: white;
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
    color: white;
    border: none;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    font-size: 1.5rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.fab-premium:hover {
    transform: scale(1.1) rotate(90deg);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
}

.progress-premium {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    overflow: hidden;
    height: 8px;
}

.progress-premium .progress-bar {
    background: linear-gradient(90deg, #00ff87, #60efff);
    border-radius: 10px;
    transition: width 1s ease;
    position: relative;
}

.progress-premium .progress-bar::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: shimmer 2s infinite;
}

.badge-premium {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.badge-success {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.badge-warning {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.badge-danger {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.particle {
    position: fixed;
    pointer-events: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    z-index: -1;
}

.empty-state-premium {
    text-align: center;
    padding: 4rem 2rem;
    color: rgba(255, 255, 255, 0.8);
}

.empty-state-premium i {
    font-size: 4rem;
    color: rgba(255, 255, 255, 0.3);
    margin-bottom: 2rem;
    animation: pulse 2s infinite;
}
</style>

<div class="objectives-premium">
    <div class="container-fluid">
        
        <!-- En-tête Premium -->
        <div class="premium-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="premium-title">
                        <i class="ri-target-line me-3"></i>Gestion des Objectifs Premium
                    </h1>
                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                        Tableau de bord avancé pour le suivi des performances
                    </p>
                </div>
                <div>
                    <a href="<?php echo base_url('objectives/set_monthly'); ?>" class="btn btn-premium">
                        <i class="ri-add-line me-2"></i>Nouvel Objectif
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistiques Premium -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="ri-target-line"></i>
                    </div>
                    <div class="stats-number"><?php echo isset($total_objectives) ? $total_objectives : '24'; ?></div>
                    <div class="stats-label">Objectifs Totaux</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="ri-check-line"></i>
                    </div>
                    <div class="stats-number"><?php echo isset($completed_objectives) ? $completed_objectives : '18'; ?></div>
                    <div class="stats-label">Atteints</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="ri-time-line"></i>
                    </div>
                    <div class="stats-number"><?php echo isset($pending_objectives) ? $pending_objectives : '6'; ?></div>
                    <div class="stats-label">En Cours</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="ri-trophy-line"></i>
                    </div>
                    <div class="stats-number"><?php echo isset($completion_rate) ? $completion_rate : '89'; ?>%</div>
                    <div class="stats-label">Taux de Réussite</div>
                </div>
            </div>
        </div>

        <!-- Tableau Premium -->
        <div class="glass-card">
            <div class="table-responsive">
                <?php if(isset($objectives) && !empty($objectives)): ?>
                    <table class="table table-premium">
                        <thead>
                            <tr>
                                <th>Agent</th>
                                <th>Période</th>
                                <th>Objectif</th>
                                <th>Réalisé</th>
                                <th>Progression</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($objectives as $objective): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                                <?php echo strtoupper(substr($objective['agent_name'], 0, 2)); ?>
                                            </div>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: white;"><?php echo $objective['agent_name']; ?></div>
                                            <small style="color: rgba(255, 255, 255, 0.6);"><?php echo $objective['agent_email']; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;"><?php echo date('F Y', strtotime($objective['month'])); ?></div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; font-size: 1.1rem;"><?php echo number_format($objective['target_amount']); ?> €</div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #22c55e; font-size: 1.1rem;"><?php echo number_format($objective['achieved_amount']); ?> €</div>
                                </td>
                                <td>
                                    <?php 
                                    $progress = ($objective['achieved_amount'] / $objective['target_amount']) * 100;
                                    $progress = min(100, $progress);
                                    ?>
                                    <div class="d-flex align-items-center">
                                        <div class="progress-premium me-3" style="width: 120px;">
                                            <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                                        </div>
                                        <span style="font-weight: 600; min-width: 50px;"><?php echo number_format($progress, 1); ?>%</span>
                                    </div>
                                </td>
                                <td>
                                    <?php if($progress >= 100): ?>
                                        <span class="badge-premium badge-success">✓ Atteint</span>
                                    <?php elseif($progress >= 75): ?>
                                        <span class="badge-premium badge-warning">⚡ En cours</span>
                                    <?php else: ?>
                                        <span class="badge-premium badge-danger">⚠ À risque</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-premium btn-sm" onclick="viewObjective(<?php echo $objective['id']; ?>)">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <button class="btn btn-premium btn-sm" onclick="editObjective(<?php echo $objective['id']; ?>)">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state-premium">
                        <i class="ri-target-line"></i>
                        <h3 style="color: white; font-weight: 600; margin-bottom: 1rem;">
                            Aucun objectif défini
                        </h3>
                        <p style="color: rgba(255, 255, 255, 0.6); margin-bottom: 2rem;">
                            Commencez par définir des objectifs pour vos agents
                        </p>
                        <a href="<?php echo base_url('objectives/set_monthly'); ?>" class="btn btn-premium">
                            <i class="ri-add-line me-2"></i>Définir des Objectifs
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bouton d'action flottant -->
        <div class="floating-action">
            <button class="fab-premium" onclick="quickActions()" title="Actions rapides">
                <i class="ri-add-line"></i>
            </button>
        </div>

    </div>
</div>

<script>
// Fonctions JavaScript
function viewObjective(id) {
    window.location.href = '<?php echo base_url("objectives/view/"); ?>' + id;
}

function editObjective(id) {
    window.location.href = '<?php echo base_url("objectives/edit/"); ?>' + id;
}

function quickActions() {
    window.location.href = '<?php echo base_url("objectives/set_monthly"); ?>';
}

// Effet de particules flottantes
function createParticleEffect() {
    for(let i = 0; i < 3; i++) {
        setTimeout(() => {
            createParticle();
        }, i * 1000);
    }
}

function createParticle() {
    const particle = document.createElement('div');
    particle.className = 'particle';
    
    const size = Math.random() * 4 + 2;
    particle.style.cssText = `
        width: ${size}px;
        height: ${size}px;
        left: ${Math.random() * 100}vw;
        animation: float ${3 + Math.random() * 4}s linear infinite;
        opacity: ${0.1 + Math.random() * 0.3};
    `;
    document.body.appendChild(particle);
    
    setTimeout(() => particle.remove(), 7000);
}

// Style pour l'animation des particules
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
