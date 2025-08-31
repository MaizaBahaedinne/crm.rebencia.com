<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Wp_property_model extends CI_Model {
    /** @var CI_DB_mysqli_driver */
    protected $wp_db;

    public function __construct() {
        parent::__construct();
        // Charge la connexion wordpress définie dans database.php
        $this->wp_db = $this->load->database('wordpress', TRUE);
    }

    public function list_simple($limit = 100) {
        if(!$this->wp_db) return [];
        $this->wp_db->select('ID, post_title');
        $this->wp_db->where('post_type','property');
        $this->wp_db->where_in('post_status',['publish','pending']);
        $this->wp_db->order_by('post_date','DESC');
        $this->wp_db->limit($limit);
        return $this->wp_db->get('wp_posts')->result_array();
    }

    public function search($q, $limit=50) {
        if(!$this->wp_db) return [];
        $this->wp_db->select('ID, post_title');
        $this->wp_db->where('post_type','property');
        $this->wp_db->like('post_title', $q);
        $this->wp_db->limit($limit);
        return $this->wp_db->get('wp_posts')->result_array();
    }
}
