<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Estimation_model extends CI_Model {
    protected $zonesTable = 'crm_zones';
    protected $propertiesTable = 'crm_properties';
    protected $photosTable = 'crm_property_photos';
    protected $allowedStatus = ['en_cours','valide','rejete'];

    public function __construct() {
        parent::__construct();
    }

    public function get_zones() {
        return $this->db->order_by('nom','ASC')->get($this->zonesTable)->result();
    }

    public function get_zone($id) {
        return $this->db->get_where($this->zonesTable, ['id' => $id])->row();
    }

    public function create_zone($data) {
        $row = [
            'nom' => trim($data['nom'] ?? ''),
            'prix_m2_min' => isset($data['prix_m2_min']) ? (float)$data['prix_m2_min'] : null,
            'prix_m2_max' => isset($data['prix_m2_max']) ? (float)$data['prix_m2_max'] : null,
            'prix_m2_moyen' => (float)($data['prix_m2_moyen'] ?? 0),
            'rendement_locatif_moyen' => (float)($data['rendement_locatif_moyen'] ?? 0),
            'transport_score' => isset($data['transport_score']) ? (int)$data['transport_score'] : null,
            'commodites_score' => isset($data['commodites_score']) ? (int)$data['commodites_score'] : null,
            'securite_score' => isset($data['securite_score']) ? (int)$data['securite_score'] : null,
            'transport_description' => $data['transport_description'] ?? null,
            'commodites_description' => $data['commodites_description'] ?? null,
            'latitude' => $data['latitude'] !== '' ? $data['latitude'] : null,
            'longitude' => $data['longitude'] !== '' ? $data['longitude'] : null,
            'geometry' => isset($data['geometry']) ? $data['geometry'] : null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert($this->zonesTable, $row);
        return $this->db->insert_id();
    }

    public function update_zone($id, $data) {
        $row = [
            'nom' => trim($data['nom'] ?? ''),
            'prix_m2_min' => isset($data['prix_m2_min']) ? (float)$data['prix_m2_min'] : null,
            'prix_m2_max' => isset($data['prix_m2_max']) ? (float)$data['prix_m2_max'] : null,
            'prix_m2_moyen' => (float)($data['prix_m2_moyen'] ?? 0),
            'rendement_locatif_moyen' => (float)($data['rendement_locatif_moyen'] ?? 0),
            'transport_score' => isset($data['transport_score']) ? (int)$data['transport_score'] : null,
            'commodites_score' => isset($data['commodites_score']) ? (int)$data['commodites_score'] : null,
            'securite_score' => isset($data['securite_score']) ? (int)$data['securite_score'] : null,
            'transport_description' => $data['transport_description'] ?? null,
            'commodites_description' => $data['commodites_description'] ?? null,
            'latitude' => $data['latitude'] !== '' ? $data['latitude'] : null,
            'longitude' => $data['longitude'] !== '' ? $data['longitude'] : null,
            'geometry' => isset($data['geometry']) ? $data['geometry'] : null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('id', $id)->update($this->zonesTable, $row);
        return $this->db->affected_rows() > 0;
    }

    public function zone_has_properties($id) {
        return (int)$this->db->where('zone_id',$id)->count_all_results($this->propertiesTable) > 0;
    }

    public function delete_zone($id) {
        if($this->zone_has_properties($id)) return false; // sécurité : ne supprime pas si liée
        $this->db->where('id',$id)->delete($this->zonesTable);
        return $this->db->affected_rows() > 0;
    }

    public function save_property($data, $photos = []) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        // Champs adresse : s'assurer qu'ils existent même si vides
        foreach(['adresse_numero','adresse_rue','adresse_ville','adresse_cp','adresse_pays'] as $f) {
            if(!isset($data[$f])) $data[$f] = null;
        }
        // Objectif : location ou vente
        if(isset($data['objectif'])) {
            $data['objectif'] = ($data['objectif'] === 'location') ? 'location' : 'vente';
        } else {
            $data['objectif'] = 'vente'; // défaut
        }
        // Suppression des champs statut et type de demande
        unset($data['statut_dossier'], $data['type_demande']);
        $this->db->insert($this->propertiesTable, $data);
        $id = $this->db->insert_id();
        if(!empty($photos)) {
            foreach($photos as $p) {
                $this->db->insert('crm_property_photos', [
                    'property_id' => $id,
                    'file' => $p,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        return $id;
    }

    public function get_property($id) {
        $this->db->select("p.*, z.nom as zone_nom, z.prix_m2_min, z.prix_m2_max, z.prix_m2_moyen, z.rendement_locatif_moyen, z.transport_score, z.commodites_score, z.securite_score");
        $this->db->from($this->propertiesTable.' p');
        $this->db->join($this->zonesTable.' z','z.id = p.zone_id','left');
        $this->db->where('p.id',$id);
        $prop = $this->db->get()->row_array();
        if(!$prop) return null;
        $prop['photos'] = $this->db->get_where($this->photosTable,['property_id'=>$id])->result_array();
        // Champs adresse toujours présents (évite undefined)
        foreach(['adresse_numero','adresse_rue','adresse_ville','adresse_cp','adresse_pays'] as $f) {
            if(!isset($prop[$f])) $prop[$f] = '';
        }
        return $prop;
    }

    /**
     * Liste paginée des estimations
     */
    public function list_estimations($limit = 100, $offset = 0, $filters = []) {
        $this->db->select("p.id, p.zone_id, p.agent_id, p.statut_dossier, z.nom as zone_nom, p.surface_habitable, p.valeur_min_estimee, p.valeur_estimee, p.valeur_max_estimee, p.loyer_potentiel, p.rentabilite, p.objectif, p.created_at");
        $this->db->from($this->propertiesTable.' p');
        $this->db->join($this->zonesTable.' z','z.id = p.zone_id','left');
        if(!empty($filters['statut'])) { $this->db->where('p.statut_dossier', $filters['statut']); }
        if(!empty($filters['zone_id'])) { $this->db->where('p.zone_id', $filters['zone_id']); }
        $this->db->order_by('p.id','DESC');
        $this->db->limit($limit,$offset);
        return $this->db->get()->result_array();
    }

    public function count_estimations($filters = []) {
    // Suppression du filtre statut
        if(!empty($filters['zone_id'])) { $this->db->where('zone_id', $filters['zone_id']); }
        return (int)$this->db->count_all_results($this->propertiesTable);
    }

    public function update_status($id, $status) {
    // Suppression de la gestion du statut
    return false;
    }

    // Suppression de la gestion des statuts

    /**
     * Calcule estimation valeur vente, loyer et rentabilité
     * @param array $input
     * @param object $zone
     * @return array
     */
    public function compute_estimation(array $input, $zone) {
        $surface = (float)($input['surface_habitable'] ?? 0);
        $terrain = (float)($input['surface_terrain'] ?? 0);
        $prixM2Zone = $zone ? (float)$zone->prix_m2_moyen : 0; // colonne prix moyen
        $rendementMoyen = $zone ? (float)$zone->rendement_locatif_moyen : 0;
        $prixM2Min = $zone && isset($zone->prix_m2_min) && $zone->prix_m2_min>0 ? (float)$zone->prix_m2_min : ($prixM2Zone*0.9);
        $prixM2Max = $zone && isset($zone->prix_m2_max) && $zone->prix_m2_max>0 ? (float)$zone->prix_m2_max : ($prixM2Zone*1.1);

        $baseValeur = $surface * $prixM2Zone;
        $baseValeurMin = $surface * $prixM2Min;
        $baseValeurMax = $surface * $prixM2Max;

        $coef = 1.0;
        // Etat
        $etat = $input['etat_general'] ?? '';
        switch($etat){
            case 'neuf': $coef *= 1.10; break;
            case 'renove': $coef *= 1.05; break;
            case 'ancien': $coef *= 0.95; break;
            case 'a_renover': $coef *= 0.85; break;
        }
        // Etage (ex: plus haut étage valeur +2% par étage > 3 limité à +10%)
        $etage = (int)($input['etage'] ?? 0);
        if($etage > 3) { $coef *= min(1 + (($etage-3)*0.02), 1.10); }
        // Ascenseur bonus / malus
        $ascenseur = $input['ascenseur'] ?? '';
        if($etage >=3) {
            if($ascenseur === 'oui') $coef *= 1.03; else $coef *= 0.95;
        }
        // Extérieur
        $exterieur = $input['type_exterieur'] ?? '';
        if(in_array($exterieur, ['balcon','terrasse'])) $coef *= 1.03;
        if($exterieur === 'jardin') $coef *= 1.06;
        // Jardin flag (éviter double si déjà type_exterieur=jardin)
        if(($input['jardin'] ?? '') === 'oui' && $exterieur !== 'jardin') $coef *= 1.04;
        // Parking / garage
        if(($input['parking'] ?? '') === 'oui') $coef *= 1.04;
        // Piscine
        if(($input['piscine'] ?? '') === 'oui') $coef *= 1.07;
        // Sécurité
        if(($input['securite'] ?? '') === 'oui') $coef *= 1.02;
        // Syndic (léger malus coûts)
        if(($input['syndic'] ?? '') === 'oui') $coef *= 0.98;
        // Année construction (obsolescence simplifiée)
        $annee = (int)($input['annee_construction'] ?? 0);
        if($annee > 0) {
            $age = date('Y') - $annee;
            if($age < 5) $coef *= 1.05; elseif($age > 40) $coef *= 0.90;
        }
    // Cave / débarras
    if(($input['cave'] ?? '') === 'oui') $coef *= 1.015;
    // Cheminée
    if(($input['cheminee'] ?? '') === 'oui') $coef *= 1.01;
    // Meublé
    if(($input['meuble'] ?? '') === 'oui') $coef *= 1.015;
    // Salle de bain type
    $sdbType = $input['sdb_type'] ?? '';
    if($sdbType==='baignoire') $coef *= 1.005; elseif($sdbType==='mixte') $coef *= 1.01;
    // Sols
    $sol = strtolower($input['sol_type'] ?? '');
    if($sol==='marbre') $coef *= 1.02; elseif($sol==='parquet') $coef *= 1.015; // carrelage neutre
        // Classe énergie A..G
        $energie = strtoupper(trim($input['energie_classe'] ?? ''));
        $mapEnergie = [ 'A'=>0.03, 'B'=>0.02, 'C'=>0.01, 'D'=>0, 'E'=>-0.02, 'F'=>-0.04, 'G'=>-0.06 ];
        if(isset($mapEnergie[$energie])) $coef *= (1 + $mapEnergie[$energie]);
    // Sécurité additions
    if(($input['portail_auto'] ?? '') === 'oui') $coef *= 1.005;
    if(($input['gardien'] ?? '') === 'oui') $coef *= 1.01;
    if(($input['videosurveillance'] ?? '') === 'oui') $coef *= 1.01;
    if(($input['interphone'] ?? '') === 'oui') $coef *= 1.005;
    if(($input['alarme'] ?? '') === 'oui') $coef *= 1.015;
    // Services
    if(($input['fibre'] ?? '') === 'oui') $coef *= 1.01;
    if(($input['lave_linge'] ?? '') === 'oui') $coef *= 1.003;
    if(($input['seche_linge'] ?? '') === 'oui') $coef *= 1.003;
    $chauffe = strtolower($input['chauffe_eau'] ?? '');
    if($chauffe==='solaire') $coef *= 1.01; elseif($chauffe==='gaz') $coef *= 1.005;
    $gaz = strtolower($input['gaz_type'] ?? '');
    if($gaz==='ville') $coef *= 1.01; elseif($gaz==='propane') $coef *= 1.003;
        // Equipements bonus
        $equipements = $input['equipements'] ?? [];
        if(is_string($equipements)) { $equipements = explode(',', $equipements); }
        $equipements = array_map('trim',$equipements);
        $bonusEquip = 0;
        $mapBonus = [
            'cuisine_equipee' => 0.02,
            'climatisation' => 0.03,
            'chauffage' => 0.02,
            'isolation' => 0.02
        ];
        foreach($mapBonus as $k=>$b){ if(in_array($k,$equipements)) $bonusEquip += $b; }
        if($bonusEquip > 0.10) $bonusEquip = 0.10; // plafond +10%
        $coef *= (1+$bonusEquip);
        // Orientation (exposition soleil)
        $orientation = $input['orientation'] ?? '';
        if(in_array($orientation,['sud','sud-est','sud-ouest'])) $coef *= 1.02;
        // Scores zone (transports / commodités)
        $transportScore = $zone->transport_score ?? null;
        $commodScore = $zone->commodites_score ?? null;
        if($transportScore !== null) {
            if($transportScore > 3) $coef *= 1 + min(($transportScore-3)*0.01, 0.03);
            elseif($transportScore < 2) $coef *= 1 - min((2-$transportScore)*0.01, 0.02);
        }
        if($commodScore !== null) {
            if($commodScore > 3) $coef *= 1 + min(($commodScore-3)*0.01, 0.03);
            elseif($commodScore < 2) $coef *= 1 - min((2-$commodScore)*0.01, 0.02);
        }
        // Proximités spécifiques (si fournis dans input 0-5)
        $proximites = [
            'proximite_transports_score','proximite_commodites_score','proximite_ecoles_score','proximite_sante_score','proximite_commerces_score','proximite_espaces_verts_score','proximite_plage_score'
        ];
        foreach($proximites as $pKey){
            if(isset($input[$pKey]) && $input[$pKey] !== '') {
                $val = (int)$input[$pKey];
                if($val > 3) $coef *= 1 + min(($val-3)*0.005, 0.02); // +0.5% par point >3 limité 2%
                elseif($val < 2) $coef *= 1 - min((2-$val)*0.005, 0.01); // petit malus
            }
        }

        $valeurEstimee = round($baseValeur * $coef, 0);
        $valeurMin = round($baseValeurMin * $coef, 0);
        $valeurMax = round($baseValeurMax * $coef, 0);

        // Loyer potentiel approx : rendement moyen zone * valeur / 12
        $loyerPotentiel = 0;
        if($rendementMoyen > 0) {
            $loyerPotentiel = round(($valeurEstimee * ($rendementMoyen/100)) / 12, 0);
        }
        // Rentabilité calculée sur loyerPotentiel
        $rentabilite = 0;
        if($valeurEstimee > 0 && $loyerPotentiel > 0) {
            $rentabilite = round((($loyerPotentiel*12) / $valeurEstimee) * 100, 2);
        }
        return [
            'valeur_min_estimee' => $valeurMin,
            'valeur_estimee' => $valeurEstimee,
            'valeur_max_estimee' => $valeurMax,
            'loyer_potentiel' => $loyerPotentiel,
            'rentabilite' => $rentabilite,
            'coef_global' => round($coef,4)
        ];
    }

    public function update_proposition($id, $data) {
        $row = [];
        if(isset($data['proposition_agence']) && $data['proposition_agence']!=='') $row['proposition_agence'] = (float)$data['proposition_agence'];
        if(isset($data['proposition_commentaire'])) $row['proposition_commentaire'] = $data['proposition_commentaire'];
        if(empty($row)) return false;
        $row['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id',$id)->update($this->propertiesTable,$row);
        return $this->db->affected_rows()>0;
    }

    // === NOUVELLES MÉTHODES POUR CONTRÔLE D'ACCÈS PAR RÔLE ===

    /**
     * Récupérer toutes les estimations avec détails (pour admin)
     */
    public function get_all_estimations_with_details()
    {
        $query = $this->db->query("
            SELECT 
                p.id,
                p.type_propriete as titre,
                CONCAT(p.adresse_numero, ' ', p.adresse_rue, ', ', p.adresse_ville) as adresse,
                p.type_bien as type_propriete,
                p.surface_habitable as surface,
                p.adresse_ville as ville,
                p.adresse_ville as gouvernorat,
                p.valeur_estimee,
                p.latitude,
                p.longitude,
                p.created_at as date_creation,
                'en_attente' as statut,
                u.display_name as agent_nom,
                'Rebencia Immobilier' as agence_nom
            FROM {$this->propertiesTable} p
            LEFT JOIN wp_Hrg8P_prop_agen a ON p.agent_id = a.agent_post_id
            LEFT JOIN wp_Hrg8P_users u ON a.user_id = u.ID
            WHERE p.valeur_estimee IS NOT NULL
            ORDER BY p.created_at DESC
        ");
        
        return $query->result_array();
    }

    /**
     * Récupérer les estimations par agence (pour manager)
     */
    public function get_estimations_by_agency($agency_id)
    {
        if (!$agency_id) {
            return [];
        }

        $query = $this->db->query("
            SELECT 
                p.id,
                p.type_propriete as titre,
                CONCAT(p.adresse_numero, ' ', p.adresse_rue, ', ', p.adresse_ville) as adresse,
                p.type_bien as type_propriete,
                p.surface_habitable as surface,
                p.adresse_ville as ville,
                p.adresse_ville as gouvernorat,
                p.valeur_estimee,
                p.latitude,
                p.longitude,
                p.created_at as date_creation,
                'en_attente' as statut,
                u.display_name as agent_nom,
                'Rebencia Immobilier' as agence_nom
            FROM {$this->propertiesTable} p
            LEFT JOIN wp_Hrg8P_prop_agen a ON p.agent_id = a.agent_post_id
            LEFT JOIN wp_Hrg8P_users u ON a.user_id = u.ID
            WHERE a.agency_id = ? AND p.valeur_estimee IS NOT NULL
            ORDER BY p.created_at DESC
        ", [$agency_id]);
        
        return $query->result_array();
    }

    /**
     * Récupérer les estimations par agent (pour agent)
     */
    public function get_estimations_by_agent($agent_id)
    {
        $query = $this->db->query("
            SELECT 
                p.id,
                p.type_propriete as titre,
                CONCAT(p.adresse_numero, ' ', p.adresse_rue, ', ', p.adresse_ville) as adresse,
                p.type_bien as type_propriete,
                p.surface_habitable as surface,
                p.adresse_ville as ville,
                p.adresse_ville as gouvernorat,
                p.valeur_estimee,
                p.latitude,
                p.longitude,
                p.created_at as date_creation,
                'en_attente' as statut,
                u.display_name as agent_nom,
                'Rebencia Immobilier' as agence_nom
            FROM {$this->propertiesTable} p
            LEFT JOIN wp_Hrg8P_prop_agen a ON p.agent_id = a.agent_post_id
            LEFT JOIN wp_Hrg8P_users u ON a.user_id = u.ID
            WHERE p.agent_id = ? AND p.valeur_estimee IS NOT NULL
            ORDER BY p.created_at DESC
        ", [$agent_id]);
        
        return $query->result_array();
    }

    /**
     * Récupérer les détails d'une estimation
     */
    public function get_estimation_details($estimation_id)
    {
        $query = $this->db->query("
            SELECT 
                e.*,
                a.display_name as agent_name,
                a.user_email as agent_email,
                a.user_login as agent_username,
                ag.post_title as agency_name,
                c.nom as client_nom,
                c.prenom as client_prenom,
                c.email as client_email,
                c.contact_principal as client_phone,
                c.adresse as client_adresse
            FROM {$this->propertiesTable} e
            LEFT JOIN wp_Hrg8P_users a ON e.agent_id = a.ID
            LEFT JOIN wp_Hrg8P_prop_agen ca ON e.agent_id = ca.user_post_id
            LEFT JOIN wp_Hrg8P_posts ag ON ca.agency_id = ag.ID
            WHERE e.id = ?
        ", [$estimation_id]);
        
        return $query->row_array();
    }

    /**
     * Filtrer les estimations selon critères et rôle
     */
    public function get_filtered_estimations($filters, $role, $user_post_id)
    {
        $where_clauses = [];
        $params = [];

        // Filtre par rôle
        switch ($role) {
            case 'manager':
                $agency_id = $this->get_user_agency_id($user_post_id);
                if ($agency_id) {
                    $where_clauses[] = "ca.agency_id = ?";
                    $params[] = $agency_id;
                }
                break;
            case 'agent':
                $where_clauses[] = "e.agent_id = ?";
                $params[] = $user_post_id;
                break;
        }

        // Filtre par statut
        if (!empty($filters['statut'])) {
            $where_clauses[] = "e.statut_dossier = ?";
            $params[] = $filters['statut'];
        }

        // Filtre par agent (pour admin/manager)
        if (!empty($filters['agent_id']) && in_array($role, ['admin', 'manager'])) {
            $where_clauses[] = "e.agent_id = ?";
            $params[] = $filters['agent_id'];
        }

        // Filtre par période
        if (!empty($filters['periode'])) {
            $date_filter = $this->get_date_filter($filters['periode']);
            if ($date_filter) {
                $where_clauses[] = "e.created_at >= ?";
                $params[] = $date_filter;
            }
        }

        // Construire la requête
        $where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

        $query = $this->db->query("
            SELECT 
                e.*,
                a.display_name as agent_name
            FROM {$this->propertiesTable} e
            LEFT JOIN wp_Hrg8P_users a ON e.agent_id = a.ID
            LEFT JOIN wp_Hrg8P_prop_agen ca ON e.agent_id = ca.user_post_id
            {$where_sql}
            ORDER BY e.created_at DESC
        ", $params);
        
        return $query->result_array();
    }

    /**
     * Convertir la période en date de début
     */
    private function get_date_filter($periode)
    {
        switch ($periode) {
            case 'today':
                return date('Y-m-d 00:00:00');
            case 'week':
                return date('Y-m-d 00:00:00', strtotime('monday this week'));
            case 'month':
                return date('Y-m-01 00:00:00');
            case 'quarter':
                $quarter_start = ceil(date('n') / 3) * 3 - 2;
                return date('Y-' . sprintf('%02d', $quarter_start) . '-01 00:00:00');
            case 'year':
                return date('Y-01-01 00:00:00');
            default:
                return null;
        }
    }

    /**
     * Obtenir l'agence d'un utilisateur
     */
    private function get_user_agency_id($user_post_id)
    {
        $query = $this->db->query(
            "SELECT agency_id FROM wp_Hrg8P_prop_agen WHERE user_post_id = ?", 
            [$user_post_id]
        );
        
        $result = $query->row();
        return $result ? $result->agency_id : null;
    }

    /**
     * Statistiques des estimations par rôle
     */
    public function get_stats_by_role($role, $user_post_id)
    {
        $where_sql = "";
        $params = [];

        switch ($role) {
            case 'manager':
                $agency_id = $this->get_user_agency_id($user_post_id);
                if ($agency_id) {
                    $where_sql = "WHERE ca.agency_id = ?";
                    $params[] = $agency_id;
                }
                break;
            case 'agent':
                $where_sql = "WHERE e.agent_id = ?";
                $params[] = $user_post_id;
                break;
        }

        $query = $this->db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN statut_dossier = 'en_cours' THEN 1 ELSE 0 END) as en_cours,
                SUM(CASE WHEN statut_dossier = 'valide' THEN 1 ELSE 0 END) as validees,
                SUM(CASE WHEN statut_dossier = 'rejete' THEN 1 ELSE 0 END) as rejetees,
                SUM(prix_estimation) as montant_total,
                AVG(prix_estimation) as montant_moyen
            FROM {$this->propertiesTable} e
            LEFT JOIN wp_Hrg8P_prop_agen ca ON e.agent_id = ca.user_post_id
            {$where_sql}
        ", $params);
        
        return $query->row_array();
    }
}
