<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Hero Header Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="hero-header-card">
                        <div class="hero-bg-pattern"></div>
                        <div class="hero-content">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="hero-text">
                                        <h1 class="hero-title">Dashboard Administrateur</h1>
                                        <p class="hero-subtitle">Pilotez votre empire immobilier avec des données en temps réel</p>
                                        <div class="hero-stats">
                                            <div class="hero-stat-item">
                                                <span class="hero-stat-number">€<?php echo number_format($stats['total_revenue'] ?? 0, 0, ',', ' '); ?></span>
                                                <span class="hero-stat-label">Chiffre d'affaires</span>
                                            </div>
                                            <div class="hero-stat-item">
                                                <span class="hero-stat-number"><?php echo $stats['growth_rate'] ?? '0%'; ?></span>
                                                <span class="hero-stat-label">Croissance</span>
                                            </div>
                                            <div class="hero-stat-item">
                                                <span class="hero-stat-number"><?php echo date('d M Y'); ?></span>
                                                <span class="hero-stat-label">Dernière MAJ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-end">
                                    <div class="hero-actions">
                                        <button class="btn btn-hero-primary">
                                            <i class="ri-download-line me-2"></i>Exporter Rapport
                                        </button>
                                        <button class="btn btn-hero-secondary">
                                            <i class="ri-settings-3-line me-2"></i>Paramètres
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Cards Premium -->
            <div class="row g-4 mb-4">
                <!-- Agencies Card -->
                <div class="col-xl-3 col-md-6">
                    <div class="kpi-card kpi-primary">
                        <div class="kpi-header">
                            <div class="kpi-icon">
                                <i class="ri-building-4-line"></i>
                            </div>
                            <div class="kpi-trend up">
                                <i class="ri-arrow-up-line"></i>
                                <span><?php echo $stats['growth_rate'] ?? '0%'; ?></span>
                            </div>
                        </div>
                        <div class="kpi-body">
                            <h2 class="kpi-number" data-target="<?php echo $stats['agencies']; ?>">0</h2>
                            <p class="kpi-label">Agences Partenaires</p>
                            <div class="kpi-details">
                                <span class="detail-item">
                                    <i class="ri-checkbox-circle-line text-success"></i>
                                    <?php echo $stats['active_agencies']; ?> Actives
                                </span>
                                <span class="detail-item">
                                    <i class="ri-home-4-line text-info"></i>
                                    <?php echo $stats['properties_with_agencies']; ?> Biens
                                </span>
                            </div>
                        </div>
                        <div class="kpi-footer">
                            <a href="<?php echo base_url('agencies'); ?>" class="kpi-link">
                                Voir les agences <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Agents Card -->
                <div class="col-xl-3 col-md-6">
                    <div class="kpi-card kpi-success">
                        <div class="kpi-header">
                            <div class="kpi-icon">
                                <i class="ri-user-star-line"></i>
                            </div>
                            <div class="kpi-trend up">
                                <i class="ri-arrow-up-line"></i>
                                <span><?php echo $stats['growth_rate'] ?? '0%'; ?></span>
                            </div>
                        </div>
                        <div class="kpi-body">
                            <h2 class="kpi-number" data-target="<?php echo $stats['agents']; ?>">0</h2>
                            <p class="kpi-label">Agents Immobiliers</p>
                            <div class="kpi-details">
                                <span class="detail-item">
                                    <i class="ri-medal-line text-warning"></i>
                                    <?php echo $stats['top_performers'] ?? '15'; ?> Top Performers
                                </span>
                                <span class="detail-item">
                                    <i class="ri-calendar-check-line text-primary"></i>
                                    <?php echo $stats['active_agents'] ?? $stats['agents']; ?> Actifs
                                </span>
                            </div>
                        </div>
                        <div class="kpi-footer">
                            <a href="<?php echo base_url('agents'); ?>" class="kpi-link">
                                Gérer les agents <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Properties Card -->
                <div class="col-xl-3 col-md-6">
                    <div class="kpi-card kpi-info">
                        <div class="kpi-header">
                            <div class="kpi-icon">
                                <i class="ri-home-smile-line"></i>
                            </div>
                            <div class="kpi-trend up">
                                <i class="ri-arrow-up-line"></i>
                                <span><?php echo $stats['growth_rate'] ?? '0%'; ?></span>
                            </div>
                        </div>
                        <div class="kpi-body">
                            <h2 class="kpi-number" data-target="<?php echo $stats['properties']; ?>">0</h2>
                            <p class="kpi-label">Biens Immobiliers</p>
                            <div class="kpi-details">
                                <span class="detail-item">
                                    <i class="ri-shopping-bag-3-line text-success"></i>
                                    <?php echo $stats['properties_for_sale'] ?? '0'; ?> À vendre
                                </span>
                                <span class="detail-item">
                                    <i class="ri-key-2-line text-warning"></i>
                                    <?php echo $stats['properties_for_rent'] ?? '0'; ?> À louer
                                </span>
                            </div>
                        </div>
                        <div class="kpi-footer">
                            <a href="<?php echo base_url('properties'); ?>" class="kpi-link">
                                Explorer les biens <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="col-xl-3 col-md-6">
                    <div class="kpi-card kpi-warning">
                        <div class="kpi-header">
                            <div class="kpi-icon">
                                <i class="ri-money-euro-circle-line"></i>
                            </div>
                            <div class="kpi-trend up">
                                <i class="ri-arrow-up-line"></i>
                                <span><?php echo $stats['growth_rate'] ?? '0%'; ?></span>
                            </div>
                        </div>
                        <div class="kpi-body">
                            <h2 class="kpi-number">€<?php echo number_format($stats['total_revenue'] ?? 0, 0, ',', ' '); ?></h2>
                            <p class="kpi-label">Revenus Totaux</p>
                            <div class="kpi-details">
                                <span class="detail-item">
                                    <i class="ri-line-chart-line text-success"></i>
                                    Ce mois: €<?php echo number_format($stats['monthly_revenue'] ?? 0, 0, ',', ' '); ?>
                                </span>
                                <span class="detail-item">
                                    <i class="ri-calendar-line text-info"></i>
                                    Cette année: €<?php echo number_format($stats['yearly_revenue'] ?? 0, 0, ',', ' '); ?>
                                </span>
                            </div>
                        </div>
                        <div class="kpi-footer">
                            <a href="<?php echo base_url('reports/financial'); ?>" class="kpi-link">
                                Rapport financier <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Analytics Section -->
            <div class="row g-4 mb-4">
                <!-- Revenue Analytics -->
                <div class="col-xl-8">
                    <div class="analytics-card">
                        <div class="analytics-header">
                            <div class="analytics-title">
                                <h5 class="mb-0">Analyse des Revenus</h5>
                                <p class="text-muted">Évolution des performances sur 12 mois</p>
                            </div>
                            <div class="analytics-controls">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary active">12M</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary">6M</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary">3M</button>
                                </div>
                            </div>
                        </div>
                        <div class="analytics-body">
                            <canvas id="revenueChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="col-xl-4">
                    <div class="analytics-card">
                        <div class="analytics-header">
                            <div class="analytics-title">
                                <h5 class="mb-0">Répartition des Ventes</h5>
                                <p class="text-muted">Par type de bien</p>
                            </div>
                        </div>
                        <div class="analytics-body">
                            <canvas id="propertyTypeChart" height="300"></canvas>
                        </div>
                        <div class="analytics-legend">
                            <?php 
                            $statusData = $chart_data['properties_by_status'] ?? [];
                            $total = array_sum(array_column($statusData, 'count'));
                            $colorClasses = ['bg-primary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-info'];
                            foreach ($statusData as $index => $status):
                                $percentage = $total > 0 ? round(($status['count'] / $total) * 100) : 0;
                                $colorClass = $colorClasses[$index] ?? 'bg-secondary';
                            ?>
                            <div class="legend-item">
                                <span class="legend-color <?php echo $colorClass; ?>"></span>
                                <span class="legend-label"><?php echo ucfirst($status['status']); ?> (<?php echo $percentage; ?>%)</span>
                            </div>
                            <?php endforeach; ?>
                            <?php if (empty($statusData)): ?>
                            <div class="legend-item">
                                <span class="legend-color bg-muted"></span>
                                <span class="legend-label">Aucune donnée (0%)</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Tables -->
            <div class="row g-4 mb-4">
                <!-- Top Performers -->
                <div class="col-xl-6">
                    <div class="performance-card">
                        <div class="performance-header">
                            <h5 class="performance-title">
                                <i class="ri-trophy-line text-warning me-2"></i>
                                Top Agents Performers
                            </h5>
                            <a href="<?php echo base_url('agents/performance'); ?>" class="btn btn-sm btn-outline-primary">
                                Voir tout
                            </a>
                        </div>
                        <div class="performance-body">
                            <?php if (!empty($top_agents)): ?>
                                <?php foreach (array_slice($top_agents, 0, 5) as $index => $agent): ?>
                                    <div class="performer-item">
                                        <div class="performer-rank">
                                            <?php if ($index === 0): ?>
                                                <i class="ri-medal-line text-warning"></i>
                                            <?php elseif ($index === 1): ?>
                                                <i class="ri-medal-line text-muted"></i>
                                            <?php elseif ($index === 2): ?>
                                                <i class="ri-medal-line text-warning" style="color: #cd7f32 !important;"></i>
                                            <?php else: ?>
                                                <span class="rank-number"><?php echo $index + 1; ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="performer-avatar">
                                            <img src="<?php echo $agent['avatar_url'] ?? base_url('assets/images/users/avatar-1.jpg'); ?>" 
                                                 alt="<?php echo htmlspecialchars($agent['display_name'] ?? 'Agent'); ?>" 
                                                 class="rounded-circle">
                                        </div>
                                        <div class="performer-info">
                                            <h6 class="performer-name"><?php echo htmlspecialchars($agent['display_name'] ?? 'Agent Inconnu'); ?></h6>
                                            <p class="performer-agency"><?php echo htmlspecialchars($agent['agency_name'] ?? 'Agence'); ?></p>
                                        </div>
                                        <div class="performer-stats">
                                            <div class="stat-value"><?php echo $agent['total_properties'] ?? 0; ?></div>
                                            <div class="stat-label">Propriétés</div>
                                        </div>
                                        <div class="performer-badge">
                                            <span class="badge bg-success-subtle text-success">
                                                €<?php echo number_format(($agent['estimated_revenue'] ?? 0), 0, ',', ' '); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="ri-user-search-line text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">Aucun agent trouvé</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Top Agencies -->
                <div class="col-xl-6">
                    <div class="performance-card">
                        <div class="performance-header">
                            <h5 class="performance-title">
                                <i class="ri-building-line text-primary me-2"></i>
                                Agences Leaders
                            </h5>
                            <a href="<?php echo base_url('agencies/stats'); ?>" class="btn btn-sm btn-outline-primary">
                                Voir tout
                            </a>
                        </div>
                        <div class="performance-body">
                            <?php if (!empty($top_agencies)): ?>
                                <?php foreach (array_slice($top_agencies, 0, 5) as $index => $agency): ?>
                                    <div class="performer-item">
                                        <div class="performer-rank">
                                            <?php if ($index === 0): ?>
                                                <i class="ri-medal-line text-warning"></i>
                                            <?php elseif ($index === 1): ?>
                                                <i class="ri-medal-line text-muted"></i>
                                            <?php elseif ($index === 2): ?>
                                                <i class="ri-medal-line text-warning" style="color: #cd7f32 !important;"></i>
                                            <?php else: ?>
                                                <span class="rank-number"><?php echo $index + 1; ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="performer-avatar">
                                            <div class="agency-logo">
                                                <i class="ri-building-4-line"></i>
                                            </div>
                                        </div>
                                        <div class="performer-info">
                                            <h6 class="performer-name"><?php echo htmlspecialchars($agency['agency_name'] ?? 'Agence'); ?></h6>
                                            <p class="performer-agency"><?php echo $agency['total_agents'] ?? 0; ?> agents</p>
                                        </div>
                                        <div class="performer-stats">
                                            <div class="stat-value"><?php echo $agency['total_properties'] ?? 0; ?></div>
                                            <div class="stat-label">Biens</div>
                                        </div>
                                        <div class="performer-badge">
                                            <span class="badge bg-primary-subtle text-primary">
                                                Actif
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="ri-building-line text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">Aucune agence trouvée</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="row">
                <div class="col-12">
                    <div class="activity-card">
                        <div class="activity-header">
                            <h5 class="activity-title">
                                <i class="ri-time-line text-info me-2"></i>
                                Activités Récentes
                            </h5>
                            <div class="activity-controls">
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="ri-refresh-line me-1"></i>Actualiser
                                </button>
                            </div>
                        </div>
                        <div class="activity-body">
                            <?php if (!empty($recent_activities)): ?>
                                <?php foreach ($recent_activities as $activity): ?>
                                    <div class="activity-item">
                                        <div class="activity-icon">
                                            <i class="ri-home-4-line"></i>
                                        </div>
                                        <div class="activity-content">
                                            <p class="activity-text">Nouvelle propriété: <?php echo htmlspecialchars($activity['post_title'] ?? 'Propriété'); ?></p>
                                            <small class="activity-time text-muted">Agent: <?php echo htmlspecialchars($activity['agent_name'] ?? 'Non assigné'); ?> - <?php echo date('d/m/Y', strtotime($activity['post_date'] ?? 'now')); ?></small>
                                        </div>
                                        <div class="activity-status">
                                            <span class="badge bg-success-subtle text-success">
                                                Nouvelle
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="ri-history-line text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">Aucune activité récente</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
/* Hero Header */
.hero-header-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    color: white;
    min-height: 200px;
}

.hero-bg-pattern {
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/><circle cx="20" cy="20" r="1" fill="white" opacity="0.1"/><circle cx="80" cy="30" r="1.5" fill="white" opacity="0.1"/></svg>');
    background-repeat: repeat;
}

.hero-content {
    position: relative;
    z-index: 2;
    padding: 40px 30px;
}

.hero-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    background: linear-gradient(45deg, #fff, #f8f9ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 30px;
}

.hero-stats {
    display: flex;
    gap: 40px;
    flex-wrap: wrap;
}

.hero-stat-item {
    display: flex;
    flex-direction: column;
}

.hero-stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    color: #fff;
}

.hero-stat-label {
    font-size: 0.85rem;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.hero-actions {
    display: flex;
    gap: 15px;
    flex-direction: column;
}

.btn-hero-primary {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    backdrop-filter: blur(10px);
    border-radius: 10px;
    padding: 12px 24px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-hero-primary:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    transform: translateY(-2px);
}

.btn-hero-secondary {
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 10px;
    padding: 12px 24px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-hero-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

/* KPI Cards Premium */
.kpi-card {
    background: white;
    border-radius: 20px;
    padding: 0;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.kpi-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}

.kpi-primary { border-left: 4px solid #6366f1; }
.kpi-success { border-left: 4px solid #10b981; }
.kpi-info { border-left: 4px solid #06b6d4; }
.kpi-warning { border-left: 4px solid #f59e0b; }

.kpi-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 25px 25px 0;
}

.kpi-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.kpi-primary .kpi-icon { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
.kpi-success .kpi-icon { background: linear-gradient(135deg, #10b981, #059669); }
.kpi-info .kpi-icon { background: linear-gradient(135deg, #06b6d4, #0891b2); }
.kpi-warning .kpi-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }

.kpi-trend {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

.kpi-trend.up {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.kpi-body {
    padding: 15px 25px;
}

.kpi-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    line-height: 1;
}

.kpi-label {
    font-size: 0.95rem;
    color: #6b7280;
    margin: 5px 0 15px 0;
    font-weight: 500;
}

.kpi-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    color: #6b7280;
}

.kpi-footer {
    padding: 0 25px 25px;
}

.kpi-link {
    color: #6366f1;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
}

.kpi-link:hover {
    color: #4f46e5;
    gap: 10px;
}

/* Analytics Cards */
.analytics-card {
    background: white;
    border-radius: 20px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.analytics-header {
    padding: 25px 25px 0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.analytics-title h5 {
    font-weight: 700;
    color: #1f2937;
}

.analytics-body {
    padding: 25px;
}

.analytics-legend {
    padding: 0 25px 25px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    font-size: 0.9rem;
    color: #6b7280;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 3px;
}

/* Performance Cards */
.performance-card {
    background: white;
    border-radius: 20px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.performance-header {
    padding: 25px 25px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.performance-title {
    font-weight: 700;
    color: #1f2937;
    display: flex;
    align-items: center;
}

.performance-body {
    padding: 20px 25px 25px;
}

.performer-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f3f4f6;
    gap: 15px;
}

.performer-item:last-child {
    border-bottom: none;
}

.performer-rank {
    width: 30px;
    text-align: center;
    font-size: 1.2rem;
}

.rank-number {
    font-weight: 700;
    color: #6b7280;
}

.performer-avatar img {
    width: 45px;
    height: 45px;
    object-fit: cover;
}

.agency-logo {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.performer-info {
    flex-grow: 1;
}

.performer-name {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.performer-agency {
    font-size: 0.8rem;
    color: #6b7280;
    margin: 2px 0 0 0;
}

.performer-stats {
    text-align: center;
    margin-right: 15px;
}

.stat-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Activity Card */
.activity-card {
    background: white;
    border-radius: 20px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.activity-header {
    padding: 25px 25px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.activity-title {
    font-weight: 700;
    color: #1f2937;
}

.activity-body {
    padding: 20px 25px 25px;
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f3f4f6;
    gap: 15px;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
}

.activity-content {
    flex-grow: 1;
}

.activity-text {
    margin: 0;
    font-size: 0.9rem;
    color: #1f2937;
}

.activity-time {
    font-size: 0.8rem;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-stats {
        gap: 20px;
    }
    
    .hero-actions {
        margin-top: 20px;
    }
    
    .kpi-number {
        font-size: 2rem;
    }
}

/* Animation for numbers */
@keyframes countUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.kpi-number {
    animation: countUp 0.8s ease-out;
}
</style>

<!-- Chart.js and Animation Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate numbers
    function animateNumbers() {
        const numbers = document.querySelectorAll('.kpi-number[data-target]');
        numbers.forEach(number => {
            const target = parseInt(number.getAttribute('data-target'));
            const duration = 2000; // 2 seconds
            const start = performance.now();
            
            function animate(currentTime) {
                const elapsed = currentTime - start;
                const progress = Math.min(elapsed / duration, 1);
                const current = Math.floor(progress * target);
                
                number.textContent = current.toLocaleString();
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            }
            
            requestAnimationFrame(animate);
        });
    }
    
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: <?php 
                    $months = [];
                    for ($i = 11; $i >= 0; $i--) {
                        $months[] = date('M Y', strtotime("-$i months"));
                    }
                    echo json_encode($months);
                ?>,
                datasets: [{
                    label: 'Revenus',
                    data: <?php echo json_encode($chart_data['revenues'] ?? array_fill(0, 12, 0)); ?>,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                    pointRadius: 6
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
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '€' + (value / 1000) + 'K';
                            }
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
    
    // Property Type Chart
    const propertyCtx = document.getElementById('propertyTypeChart');
    if (propertyCtx) {
        <?php 
        $statusData = $chart_data['properties_by_status'] ?? [];
        $labels = [];
        $data = [];
        $colors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
        foreach ($statusData as $index => $status) {
            $labels[] = ucfirst($status['status']);
            $data[] = $status['count'];
        }
        // Si pas de données, utiliser des données par défaut
        if (empty($labels)) {
            $labels = ['En attente', 'Publié', 'Vendu'];
            $data = [0, 0, 0];
        }
        ?>
        new Chart(propertyCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: <?php echo json_encode(array_slice($colors, 0, count($labels))); ?>,
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    
    // Start animations
    setTimeout(animateNumbers, 300);
});
</script>
