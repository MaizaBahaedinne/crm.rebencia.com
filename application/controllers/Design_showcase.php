<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Design_showcase extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Property_model');
        $this->load->model('Agent_model');
        $this->load->model('Agency_model');
    }

    /**
     * Vue d'ensemble des designs disponibles
     */
    public function index() {
        echo "<style>
            body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin: 0; background: #f8f9fa; }
            .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
            .design-card { 
                background: white; 
                border-radius: 12px; 
                box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
                margin: 20px 0; 
                overflow: hidden;
                transition: transform 0.3s ease;
            }
            .design-card:hover { transform: translateY(-5px); }
            .design-header { 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                color: white; 
                padding: 20px; 
                text-align: center;
            }
            .design-preview { 
                padding: 20px; 
                display: grid; 
                grid-template-columns: 1fr 1fr; 
                gap: 20px; 
                align-items: center;
            }
            .preview-image { 
                width: 100%; 
                height: 200px; 
                background: #f1f3f4; 
                border-radius: 8px; 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                font-size: 48px; 
                color: #9aa0a6;
            }
            .design-features { list-style: none; padding: 0; }
            .design-features li { 
                padding: 8px 0; 
                border-bottom: 1px solid #f0f0f0; 
                display: flex; 
                align-items: center;
            }
            .design-features li:before { 
                content: '✅'; 
                margin-right: 10px; 
            }
            .btn { 
                display: inline-block; 
                padding: 12px 24px; 
                background: #4285f4; 
                color: white; 
                text-decoration: none; 
                border-radius: 6px; 
                font-weight: 500;
                transition: background 0.3s ease;
            }
            .btn:hover { background: #3367d6; color: white; }
            .btn-outline { 
                background: transparent; 
                border: 2px solid #4285f4; 
                color: #4285f4; 
            }
            .btn-outline:hover { 
                background: #4285f4; 
                color: white; 
            }
            h1 { text-align: center; color: #333; margin-bottom: 40px; }
        </style>";
        
        echo "<div class='container'>";
        echo "<h1>🎨 Gallery des Designs Dashboard</h1>";
        
        // Design 1: Actuel
        echo "<div class='design-card'>";
        echo "<div class='design-header'>";
        echo "<h2>Design Actuel - Modern Bootstrap</h2>";
        echo "<p>Dashboard moderne avec Bootstrap 5 et Chart.js</p>";
        echo "</div>";
        echo "<div class='design-preview'>";
        echo "<div class='preview-image'>📊</div>";
        echo "<div>";
        echo "<ul class='design-features'>";
        echo "<li>Interface Bootstrap 5 moderne</li>";
        echo "<li>Cartes statistiques animées</li>";
        echo "<li>Graphiques Chart.js interactifs</li>";
        echo "<li>Top performers avec médailles</li>";
        echo "<li>Design responsive</li>";
        echo "<li>Couleurs professionnelles</li>";
        echo "</ul>";
        echo "<a href='" . base_url('dashboard/admin') . "' class='btn'>Voir le Dashboard</a>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
        // Design 2: Dark Mode
        echo "<div class='design-card'>";
        echo "<div class='design-header' style='background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);'>";
        echo "<h2>Dark Mode Premium</h2>";
        echo "<p>Version sombre élégante pour une utilisation prolongée</p>";
        echo "</div>";
        echo "<div class='design-preview'>";
        echo "<div class='preview-image' style='background: #1a1a1a; color: #fff;'>🌙</div>";
        echo "<div>";
        echo "<ul class='design-features'>";
        echo "<li>Thème sombre reposant</li>";
        echo "<li>Accents colorés néon</li>";
        echo "<li>Glassmorphism effects</li>";
        echo "<li>Animations fluides</li>";
        echo "<li>Contraste optimisé</li>";
        echo "<li>Mode professionnel</li>";
        echo "</ul>";
        echo "<a href='" . base_url('design_showcase/dark') . "' class='btn' style='background: #2c3e50;'>Voir Dark Mode</a>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
        // Design 3: Minimal
        echo "<div class='design-card'>";
        echo "<div class='design-header' style='background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);'>";
        echo "<h2>Minimal Clean</h2>";
        echo "<p>Design épuré focalisé sur l'essentiel</p>";
        echo "</div>";
        echo "<div class='design-preview'>";
        echo "<div class='preview-image' style='background: linear-gradient(45deg, #f8f9fa, #e9ecef);'>✨</div>";
        echo "<div>";
        echo "<ul class='design-features'>";
        echo "<li>Interface ultra-épurée</li>";
        echo "<li>Espaces blancs généreux</li>";
        echo "<li>Typographie élégante</li>";
        echo "<li>Micro-interactions subtiles</li>";
        echo "<li>Focus sur les données</li>";
        echo "<li>Navigation intuitive</li>";
        echo "</ul>";
        echo "<a href='" . base_url('design_showcase/minimal') . "' class='btn' style='background: #f5576c;'>Voir Minimal</a>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
        // Design 4: Executive
        echo "<div class='design-card'>";
        echo "<div class='design-header' style='background: linear-gradient(135deg, #134e5e 0%, #71b280 100%);'>";
        echo "<h2>Executive Dashboard</h2>";
        echo "<p>Design premium pour dirigeants avec métriques avancées</p>";
        echo "</div>";
        echo "<div class='design-preview'>";
        echo "<div class='preview-image' style='background: linear-gradient(45deg, #134e5e, #71b280); color: white;'>💼</div>";
        echo "<div>";
        echo "<ul class='design-features'>";
        echo "<li>Layout haute densité</li>";
        echo "<li>KPI avancés</li>";
        echo "<li>Comparaisons temporelles</li>";
        echo "<li>Alertes intelligentes</li>";
        echo "<li>Export PDF/Excel</li>";
        echo "<li>Vue stratégique</li>";
        echo "</ul>";
        echo "<a href='" . base_url('design_showcase/executive') . "' class='btn' style='background: #134e5e;'>Voir Executive</a>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
        // Design 5: Mobile First
        echo "<div class='design-card'>";
        echo "<div class='design-header' style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);'>";
        echo "<h2>Mobile-First Responsive</h2>";
        echo "<p>Optimisé pour smartphones et tablettes</p>";
        echo "</div>";
        echo "<div class='design-preview'>";
        echo "<div class='preview-image' style='background: linear-gradient(45deg, #667eea, #764ba2); color: white;'>📱</div>";
        echo "<div>";
        echo "<ul class='design-features'>";
        echo "<li>Interface tactile optimisée</li>";
        echo "<li>Cartes swipe natives</li>";
        echo "<li>Navigation par onglets</li>";
        echo "<li>Gestes intuitifs</li>";
        echo "<li>Performance mobile</li>";
        echo "<li>PWA Ready</li>";
        echo "</ul>";
        echo "<a href='" . base_url('design_showcase/mobile') . "' class='btn'>Voir Mobile-First</a>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
        echo "</div>";
    }

    /**
     * Dashboard Dark Mode
     */
    public function dark() {
        // Récupérer les données comme le dashboard normal
        $data = $this->get_dashboard_data();
        $data['theme'] = 'dark';
        $this->load->view('dashboard/admin_dark', $data);
    }

    /**
     * Dashboard Minimal
     */
    public function minimal() {
        $data = $this->get_dashboard_data();
        $data['theme'] = 'minimal';
        $this->load->view('dashboard/admin_minimal', $data);
    }

    /**
     * Dashboard Executive
     */
    public function executive() {
        $data = $this->get_dashboard_data();
        $data['theme'] = 'executive';
        $this->load->view('dashboard/admin_executive', $data);
    }

    /**
     * Dashboard Mobile-First
     */
    public function mobile() {
        $data = $this->get_dashboard_data();
        $data['theme'] = 'mobile';
        $this->load->view('dashboard/admin_mobile', $data);
    }

    /**
     * Récupère les données pour tous les dashboards
     */
    private function get_dashboard_data() {
        try {
            $agencies = $this->Agency_model->get_all_agencies();
            $agents = $this->Agent_model->get_all_agents_from_posts();
            $properties = $this->Property_model->get_from_property_agent_view();
            
            return [
                'stats' => [
                    'agencies' => count($agencies),
                    'agents' => count($agents),
                    'properties' => count($properties),
                    'active_agents' => 15,
                    'active_agencies' => 8,
                    'properties_with_agents' => 120,
                    'properties_with_agencies' => 135,
                    'revenue' => 2500000,
                    'growth' => 8.5,
                    'current_month_properties' => 25,
                    'last_month_properties' => 23
                ],
                'chart_data' => [
                    'monthly_sales' => [
                        ['month' => 'Jan 2025', 'count' => 12],
                        ['month' => 'Fév 2025', 'count' => 19],
                        ['month' => 'Mar 2025', 'count' => 15],
                        ['month' => 'Avr 2025', 'count' => 25],
                        ['month' => 'Mai 2025', 'count' => 22],
                        ['month' => 'Jun 2025', 'count' => 30]
                    ],
                    'properties_by_status' => [
                        ['status' => 'publish', 'count' => 145],
                        ['status' => 'draft', 'count' => 12],
                        ['status' => 'private', 'count' => 8]
                    ]
                ],
                'recent_activities' => [],
                'top_agents' => [],
                'top_agencies' => []
            ];
        } catch (Exception $e) {
            return [
                'stats' => ['agencies' => 0, 'agents' => 0, 'properties' => 0],
                'chart_data' => ['monthly_sales' => [], 'properties_by_status' => []],
                'recent_activities' => [],
                'top_agents' => [],
                'top_agencies' => []
            ];
        }
    }
}
