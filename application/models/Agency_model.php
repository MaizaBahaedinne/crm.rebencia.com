<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agency_model extends CI_Model {
    /**
     * Connexion DB WordPress
     * @var CI_DB_query_builder
     */
    protected $wp_db;
    protected $users_table;
    protected $usermeta_table;
    protected $posts_table;
    protected $postmeta_table;
    protected $capabilities_key; // meta_key des rôles sérialisés

    protected $wanted_meta_keys = [
        'agency_email',
        'agency_phone',
        'agency_address',
        'agency_logo',
        'agency_website',
        'agency_facebook',
        'agency_instagram',
        'agency_whatsapp',
        'agency_linkedin'
    ];

    public function __construct() {
        parent::__construct();
        $this->wp_db = $this->load->database('wordpress', TRUE);
        // Normalisation des noms avec dbprefix (qui contient déjà wp_Hrg8P_)
        $this->users_table    = $this->wp_db->dbprefix('users');
        $this->usermeta_table = $this->wp_db->dbprefix('usermeta');
        $this->posts_table    = $this->wp_db->dbprefix('posts');
        $this->postmeta_table = $this->wp_db->dbprefix('postmeta');
        $this->capabilities_key = $this->wp_db->dbprefix('capabilities'); // ex: wp_Hrg8P_capabilities
    }

    /**
     * Retourne toutes les agences (users ayant le rôle houzez_agency)
     * Optimisé: une seule requête users + une requête metadatas globale
     * @return object[]
     */
    public function get_all_agencies() {
        $agencies = $this->wp_db->select('u.ID,u.user_login,u.user_email,u.display_name,u.user_registered')
            ->from($this->users_table.' u')
            ->join($this->usermeta_table.' m', 'u.ID = m.user_id AND m.meta_key = '.$this->wp_db->escape($this->capabilities_key), 'inner', false)
            ->like('m.meta_value', 'houzez_agency')
            ->get()->result();

        if (empty($agencies)) return [];
        $ids = array_map(function($a){ return (int)$a->ID; }, $agencies);

        // Récupérer en bloc les métadatas requises
        $meta_rows = $this->wp_db->select('user_id, meta_key, meta_value')
            ->from($this->usermeta_table)
            ->where_in('user_id', $ids)
            ->where_in('meta_key', $this->wanted_meta_keys)
            ->get()->result();

        $byUser = [];
        foreach ($meta_rows as $r) {
            if(!isset($byUser[$r->user_id])) $byUser[$r->user_id] = [];
            $byUser[$r->user_id][$r->meta_key] = $r->meta_value;
        }

        foreach ($agencies as $a) {
            $m = $byUser[$a->ID] ?? [];
            foreach ($this->wanted_meta_keys as $k) {
                $prop = $k; // on mappe directement
                $a->$prop = $m[$k] ?? '';
            }
        }
        return $agencies;
    }

    /** Une agence avec métadonnées */
    public function get_agency($agency_id) {
        $agency = $this->wp_db->where('ID', (int)$agency_id)->get($this->users_table)->row();
        if(!$agency) return null;
        $meta_rows = $this->wp_db->select('meta_key, meta_value')
            ->where('user_id', (int)$agency_id)
            ->where_in('meta_key', $this->wanted_meta_keys)
            ->get($this->usermeta_table)->result();
        foreach ($meta_rows as $r) { $agency->{$r->meta_key} = $r->meta_value; }
        return $agency;
    }

    /** Créer une agence (attention: ne gère pas mot de passe WP/roles) */
    public function create_agency($data) {
    $this->wp_db->insert($this->users_table, $data);
    return $this->getInsertId();
    }

    public function update_agency($agency_id, $data) {
        return $this->wp_db->where('ID', (int)$agency_id)->update($this->users_table, $data);
    }

    public function delete_agency($agency_id) {
        return $this->wp_db->where('ID', (int)$agency_id)->delete($this->users_table);
    }

    /** Wrapper pour insert_id (compat analyseur) */
    protected function getInsertId() {
        if(method_exists($this->wp_db, 'insert_id')) {
            return $this->wp_db->insert_id();
        }
        return 0;
    }

    /**
     * Statistiques simples: nombre d'agents rattachés + propriétés.
     * @param int $agency_id
     * @return array
     */
    public function get_agency_stats($agency_id) {
        return [
            'agents' => $this->count_agents($agency_id),
            'properties' => $this->count_properties($agency_id)
        ];
    }

    /** Compte les agents liés via usermeta.houzez_agency_id */
    public function count_agents($agency_id) {
        return (int)$this->wp_db->from($this->users_table.' u')
            ->join($this->usermeta_table.' m', 'u.ID = m.user_id AND m.meta_key = "houzez_agency_id"', 'inner', false)
            ->where('m.meta_value', (int)$agency_id)
            ->count_all_results();
    }

    /**
     * Compte les propriétés associées à l'agence.
     * Hypothèse: post_type = 'property' et postmeta (meta_key=fave_property_agency) = agency_id
     * Adapter si autre clé utilisée.
     */
    public function count_properties($agency_id) {
        return (int)$this->wp_db->from($this->posts_table.' p')
            ->join($this->postmeta_table.' pm', 'p.ID = pm.post_id AND pm.meta_key = "fave_property_agency"', 'inner', false)
            ->where('pm.meta_value', (int)$agency_id)
            ->where('p.post_type','property')
            ->where('p.post_status !=','trash')
            ->count_all_results();
    }
}
