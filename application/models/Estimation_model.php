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
    $this->db->select("p.id, p.zone_id, z.nom as zone_nom, p.surface_habitable, p.valeur_min_estimee, p.valeur_estimee, p.valeur_max_estimee, p.loyer_potentiel, p.rentabilite, p.statut_dossier, p.created_at");
        $this->db->from($this->propertiesTable.' p');
        $this->db->join($this->zonesTable.' z','z.id = p.zone_id','left');
        if(!empty($filters['statut'])) { $this->db->where('p.statut_dossier', $filters['statut']); }
        if(!empty($filters['zone_id'])) { $this->db->where('p.zone_id', $filters['zone_id']); }
        $this->db->order_by('p.id','DESC');
        $this->db->limit($limit,$offset);
        return $this->db->get()->result_array();
    }

    public function count_estimations($filters = []) {
        if(!empty($filters['statut'])) { $this->db->where('statut_dossier', $filters['statut']); }
        if(!empty($filters['zone_id'])) { $this->db->where('zone_id', $filters['zone_id']); }
        return (int)$this->db->count_all_results($this->propertiesTable);
    }

    public function update_status($id, $status) {
        if(!in_array($status, $this->allowedStatus)) return false;
        $this->db->where('id',$id)->update($this->propertiesTable,[
            'statut_dossier' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->db->affected_rows() > 0;
    }

    public function get_allowed_status() { return $this->allowedStatus; }

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
}
