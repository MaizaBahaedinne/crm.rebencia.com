<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class User_model extends CI_Model
{
	    protected $wp_db;
    public function __construct() {
        parent::__construct();
        $this->wp_db = $this->load->database('wordpress', TRUE);
    }

	/* ===================== UTILISATEURS APP ===================== */

	function userListingCount($searchText)
	{
		$this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.isAdmin, BaseTbl.createdDtm, Role.role');
		$this->db->from('tbl_users as BaseTbl');
		$this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
		if(!empty($searchText)) {
			$likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%' OR  BaseTbl.name  LIKE '%".$searchText."%' OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
			$this->db->where($likeCriteria);
		}
		$this->db->where('BaseTbl.isDeleted', 0);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function userListing($searchText, $page, $segment)
	{
		$this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.isAdmin, BaseTbl.createdDtm, Role.role, Role.status as roleStatus');
		$this->db->from('tbl_users as BaseTbl');
		$this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
		if(!empty($searchText)) {
			$likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%' OR  BaseTbl.name  LIKE '%".$searchText."%' OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
			$this->db->where($likeCriteria);
		}
		$this->db->where('BaseTbl.isDeleted', 0);
		$this->db->order_by('BaseTbl.userId', 'DESC');
		$this->db->limit($page, $segment);
		return $this->db->get()->result();
	}

	function getUserRoles()
	{
		$this->db->select('roleId, role, status as roleStatus');
		$this->db->from('tbl_roles');
		return $this->db->get()->result();
	}

	function checkEmailExists($email, $userId = 0)
	{
		$this->db->select('email');
		$this->db->from('tbl_users');
		$this->db->where('email', $email);
		$this->db->where('isDeleted', 0);
		if($userId != 0){ $this->db->where('userId !=', $userId); }
		return $this->db->get()->result();
	}

	function addNewUser($userInfo)
	{
		$this->db->trans_start();
		$this->db->insert('tbl_users', $userInfo);
		$insert_id = $this->db->insert_id();
		$this->db->trans_complete();
		return $insert_id;
	}

	function getUserInfo($userId)
	{
		$this->db->select('userId, name, email, mobile, isAdmin, roleId');
		$this->db->from('tbl_users');
		$this->db->where('isDeleted', 0);
		$this->db->where('userId', $userId);
		return $this->db->get()->row();
	}

	function editUser($userInfo, $userId)
	{
		$this->db->where('userId', $userId)->update('tbl_users', $userInfo);
		return true;
	}

	function deleteUser($userId, $userInfo)
	{
		$this->db->where('userId', $userId)->update('tbl_users', $userInfo);
		return $this->db->affected_rows();
	}

	function matchOldPassword($userId, $oldPassword)
	{
		$this->db->select('userId, password');
		$this->db->where('userId', $userId);
		$this->db->where('isDeleted', 0);
		$user = $this->db->get('tbl_users')->result();
		if(!empty($user) && verifyHashedPassword($oldPassword, $user[0]->password)) return $user; 
		return [];
	}

	function changePassword($userId, $userInfo)
	{
		$this->db->where('userId', $userId)->where('isDeleted', 0)->update('tbl_users', $userInfo);
		return $this->db->affected_rows();
	}

	function loginHistoryCount($userId, $searchText, $fromDate, $toDate)
	{
		$this->db->select('BaseTbl.userId, BaseTbl.sessionData, BaseTbl.machineIp, BaseTbl.userAgent, BaseTbl.agentString, BaseTbl.platform, BaseTbl.createdDtm');
		if(!empty($searchText)) $this->db->where("(BaseTbl.sessionData LIKE '%".$searchText."%')");
		if(!empty($fromDate)) $this->db->where("DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d') >=", date('Y-m-d', strtotime($fromDate)));
		if(!empty($toDate)) $this->db->where("DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d') <=", date('Y-m-d', strtotime($toDate)));
		if($userId >= 1) $this->db->where('BaseTbl.userId', $userId);
		$this->db->from('tbl_last_login as BaseTbl');
		return $this->db->get()->num_rows();
	}

	function loginHistory($userId, $searchText, $fromDate, $toDate, $page, $segment)
	{
		$this->db->select('BaseTbl.userId, BaseTbl.sessionData, BaseTbl.machineIp, BaseTbl.userAgent, BaseTbl.agentString, BaseTbl.platform, BaseTbl.createdDtm');
		$this->db->from('tbl_last_login as BaseTbl');
		if(!empty($searchText)) $this->db->where("(BaseTbl.sessionData LIKE '%".$searchText."%')");
		if(!empty($fromDate)) $this->db->where("DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d') >=", date('Y-m-d', strtotime($fromDate)));
		if(!empty($toDate)) $this->db->where("DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d') <=", date('Y-m-d', strtotime($toDate)));
		if($userId >= 1) $this->db->where('BaseTbl.userId', $userId);
		$this->db->order_by('BaseTbl.id', 'DESC')->limit($page, $segment);
		return $this->db->get()->result();
	}

	function getUserInfoById($userId)
	{
		$this->db->select('userId, name, email, mobile, roleId');
		$this->db->from('tbl_users');
		$this->db->where('isDeleted', 0)->where('userId', $userId);
		return $this->db->get()->row();
	}

	function getUserInfoWithRole($userId)
	{
		$this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.isAdmin, BaseTbl.roleId, Roles.role');
		$this->db->from('tbl_users as BaseTbl');
		$this->db->join('tbl_roles as Roles','Roles.roleId = BaseTbl.roleId');
		$this->db->where('BaseTbl.userId', $userId)->where('BaseTbl.isDeleted', 0);
		return $this->db->get()->row();
	}

	/* ===================== PROFIL WORDPRESS ===================== */

	function get_wp_user($user_id)
	{   
		// Utilisation directe des tables WordPress + vue crm_agents
		$query = $this->wp_db->query("
			SELECT 
				u.ID as user_id,
				u.user_login,
				u.user_email,
				u.user_nicename,
				u.display_name,
				u.user_registered,
				u.user_status,
				u.user_url,
				MAX(CASE WHEN um.meta_key = 'first_name' THEN um.meta_value END) as first_name,
				MAX(CASE WHEN um.meta_key = 'last_name' THEN um.meta_value END) as last_name,
				MAX(CASE WHEN um.meta_key = 'nickname' THEN um.meta_value END) as nickname,
				MAX(CASE WHEN um.meta_key = 'description' THEN um.meta_value END) as description,
				MAX(CASE WHEN um.meta_key = 'phone' THEN um.meta_value END) as phone,
				MAX(CASE WHEN um.meta_key = 'mobile' THEN um.meta_value END) as mobile,
				MAX(CASE WHEN um.meta_key = 'billing_address_1' THEN um.meta_value END) as address,
				MAX(CASE WHEN um.meta_key = 'billing_city' THEN um.meta_value END) as city,
				MAX(CASE WHEN um.meta_key = 'billing_country' THEN um.meta_value END) as country,
				a.agent_name,
				a.agency_name,
				a.agent_email,
				a.phone as agent_phone,
				a.mobile as agent_mobile,
				a.position,
				a.agent_avatar,
				a.agency_id
			FROM wp_Hrg8P_users u
			LEFT JOIN wp_Hrg8P_usermeta um ON u.ID = um.user_id
			LEFT JOIN wp_Hrg8P_crm_agents a ON u.ID = a.user_id
			WHERE u.ID = ?
			GROUP BY u.ID, u.user_login, u.user_email, u.user_nicename, u.display_name, 
					 u.user_registered, u.user_status, u.user_url,
					 a.agent_name, a.agency_name, a.agent_email, a.phone, a.mobile, 
					 a.position, a.agent_avatar, a.agency_id
		", [$user_id]);
		
		$user = $query->row();
		
		// Si pas de données utilisateur de base, retourner null
		if (!$user || empty($user->user_id)) {
			return null;
		}
		
		// Normaliser les données pour compatibilité avec la vue
		$user->name = $user->display_name ?: ($user->first_name . ' ' . $user->last_name) ?: $user->user_login;
		$user->email = $user->user_email;
		$user->bio = $user->description ?: '';
		$user->location = ($user->city ? $user->city : '') . ($user->country ? ', ' . $user->country : '');
		$user->biography = $user->description ?: '';
		$user->roles_string = $user->agent_name ? 'Agent Immobilier' : 'Utilisateur';
		
		// Utiliser les données agent si disponibles
		if ($user->agent_name) {
			$user->phone = $user->agent_phone ?: $user->phone;
			$user->mobile = $user->agent_mobile ?: $user->mobile;
			$user->name = $user->agent_name ?: $user->name;
			$user->email = $user->agent_email ?: $user->email;
		}
		
		return $user;
	}

	// === MÉTHODES POUR LES FILTRES D'ESTIMATIONS ===

	/**
	 * Récupérer tous les agents
	 */
	public function get_all_agents()
	{
		$query = $this->wp_db->query("
			SELECT DISTINCT u.ID, u.display_name, u.user_email
			FROM wp_Hrg8P_users u
			INNER JOIN wp_Hrg8P_crm_agents ca ON u.ID = ca.user_post_id
			WHERE ca.role = 'agent'
			ORDER BY u.display_name
		");
		
		return $query->result_array();
	}

	/**
	 * Récupérer toutes les agences
	 */
	public function get_all_agencies()
	{
		$query = $this->wp_db->query("
			SELECT ID, post_title as agency_name
			FROM wp_Hrg8P_posts
			WHERE post_type = 'houzez_agency'
			AND post_status = 'publish'
			ORDER BY post_title
		");
		
		return $query->result_array();
	}

	/**
	 * Récupérer les agents d'une agence
	 */
	public function get_agents_by_agency($agency_id)
	{
		if (!$agency_id) {
			return [];
		}

		$query = $this->wp_db->query("
			SELECT DISTINCT u.ID, u.display_name, u.user_email
			FROM wp_Hrg8P_users u
			INNER JOIN wp_Hrg8P_crm_agents ca ON u.ID = ca.user_post_id
			WHERE ca.agency_id = ?
			AND ca.role = 'agent'
			ORDER BY u.display_name
		", [$agency_id]);
		
		return $query->result_array();
	}

	/**
	 * Récupérer les informations d'un agent
	 */
	public function get_agent_info($agent_id)
	{
		$query = $this->wp_db->query("
			SELECT 
				u.ID,
				u.display_name,
				u.user_email,
				u.user_login,
				ca.role,
				ca.agency_id,
				ag.post_title as agency_name
			FROM wp_Hrg8P_users u
			LEFT JOIN wp_Hrg8P_crm_agents ca ON u.ID = ca.user_post_id
			LEFT JOIN wp_Hrg8P_posts ag ON ca.agency_id = ag.ID
			WHERE u.ID = ?
		", [$agent_id]);
		
		return $query->row_array();
	}

	
}

  