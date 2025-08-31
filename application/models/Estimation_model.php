<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Estimation_model extends CI_Model {
    protected $zonesTable = 'crm_zones';
    protected $propertiesTable = 'crm_properties';

    public function __construct() {
        parent::__construct();
    }

    public function get_zones() {
        return $this->db->order_by('nom','ASC')->get($this->zonesTable)->result();
    }

    public function get_zone($id) {
        return $this->db->get_where($this->zonesTable, ['id' => $id])->row();
    }

    public function save_property($data, $photos = []) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
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
        $prop = $this->db->get_where($this->propertiesTable, ['id'=>$id])->row_array();
        if(!$prop) return null;
        $prop['photos'] = $this->db->get_where('crm_property_photos',['property_id'=>$id])->result_array();
        return $prop;
    }

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

        $baseValeur = $surface * $prixM2Zone;

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
        // Extérieur
        $exterieur = $input['type_exterieur'] ?? '';
        if(in_array($exterieur, ['balcon','terrasse'])) $coef *= 1.03;
        if($exterieur === 'jardin') $coef *= 1.06;
        // Parking / garage
        if(($input['parking'] ?? '') === 'oui') $coef *= 1.04;
        // Année construction (obsolescence simplifiée)
        $annee = (int)($input['annee_construction'] ?? 0);
        if($annee > 0) {
            $age = date('Y') - $annee;
            if($age < 5) $coef *= 1.05; elseif($age > 40) $coef *= 0.90;
        }
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

        $valeurEstimee = round($baseValeur * $coef, 0);

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
            'valeur_estimee' => $valeurEstimee,
            'loyer_potentiel' => $loyerPotentiel,
            'rentabilite' => $rentabilite,
            'coef_global' => round($coef,4)
        ];
    }
}
