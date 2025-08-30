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
	/** @var CI_DB_query_builder|null */
	protected $wp_db = null;

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
		$this->wp_db = $this->load->database('wordpress', TRUE);
		$db = $this->wp_db;
		if(method_exists($db,'table_exists') && !$db->table_exists('v_users_profile')) return null;
		$db->select('*')->from('v_users_profile')->where('user_id', (int)$user_id);
		$query = $db->get();
		if($query->num_rows()==0) return null;
		$user = $query->row();
		// Roles via role_json/capabilities
		$raw = $user->role_json ?? ($user->capabilities ?? '');
		$user->roles = [];
		if($raw){
			$uns = @unserialize($raw);
			if(is_array($uns)) $user->roles = array_keys(array_filter($uns));
		}
		return $user;
	}

	/**
	 * Récupère le profil via la vue agrégée (assume nom: v_users_profile)
	 * Vue attendue (exemple colonnes): user_id,user_login,user_email,user_registered,user_status,first_name,last_name,nickname,capabilities
	 */
	function get_wp_user_profile($user_id)
	{
		$db = $this->load->database('wordpress', TRUE);
		$view = 'v_users_profile';
		if(method_exists($db,'table_exists') && !$db->table_exists($view)) return null;
		$q = $db->get_where($view, ['user_id' => (int)$user_id]);
		if(!$q || $q->num_rows()==0) return null;
		$r = $q->row_array();
		$roles = [];
		$raw = $r['capabilities'] ?? '';
		if($raw){
			$uns = @unserialize($raw);
			if(is_array($uns)) {
				$roles = array_keys(array_filter($uns));
			} else {
				$json = json_decode($raw, true);
				if(is_array($json)) $roles = array_keys(array_filter($json));
			}
		}
		$first = $r['first_name'] ?? '';
		$last = $r['last_name'] ?? '';
		$nickname = $r['nickname'] ?? '';
		$display = trim($first.' '.$last);
		if($display==='') $display = $nickname ?: ($r['user_login'] ?? '');
		$profile = [
			'id' => (int)$r['user_id'],
			'login' => $r['user_login'] ?? '',
			'email' => $r['user_email'] ?? '',
			'registration_date' => $r['user_registered'] ?? '',
			'status' => $r['user_status'] ?? '',
			'first_name' => $first,
			'last_name' => $last,
			'nickname' => $nickname,
			'display_name' => $display,
			'biography' => '',
			'description' => '',
			'phone' => '',
			'mobile' => '',
			'whatsapp' => '',
			'skype' => '',
			'agency_id' => '',
			'agency_name' => '',
			'agency_user_id' => '',
			'roles' => $roles
		];

		// Enrichissement meta manquantes (phone, mobile, description) si disponibles
		$missingMeta = [];
		if($profile['phone']==='') $missingMeta[] = 'phone';
		if($profile['mobile']==='') $missingMeta[] = 'mobile';
		if($profile['description']==='') $missingMeta[] = 'description';
		// Recherche variantes possibles
		$metaKeys = ['description','phone','mobile','houzez_phone','houzez_mobile','whatsapp','houzez_whatsapp'];
		if(!empty($missingMeta)){
			$uid = (int)$user_id;
			// Préfixe WordPress (tel que dans ta vue) — adapter si différent
			$umetaTable = 'wp_Hrg8P_usermeta';
			if($db->table_exists($umetaTable)){
				$db->where('user_id', $uid);
				$db->where_in('meta_key', $metaKeys);
				$m = $db->get($umetaTable)->result_array();
				foreach($m as $rowM){
					$k = $rowM['meta_key']; $v = $rowM['meta_value'];
					if(in_array($k,['houzez_phone']) && $profile['phone']==='') $profile['phone']=$v;
					if(in_array($k,['houzez_mobile']) && $profile['mobile']==='') $profile['mobile']=$v;
					if($k==='phone' && $profile['phone']==='') $profile['phone']=$v;
					if($k==='mobile' && $profile['mobile']==='') $profile['mobile']=$v;
					if(in_array($k,['whatsapp','houzez_whatsapp']) && $profile['whatsapp']==='') $profile['whatsapp']=$v;
					if($k==='description' && $profile['description']==='') $profile['description']=$v;
				}
			}
		}
		return $profile;
	}
}

  