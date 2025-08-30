<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

/**
 * Class : BaseController
 * Base Class to control over all the classes
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class BaseController extends CI_Controller {
	protected $role = '';
	protected $vendorId = '';
	protected $name = '';
	protected $roleText = '';
	protected $isAdmin = 0;
	protected $accessInfo = [];
	protected $global = array ();
	protected $lastLogin = '';
	protected $module = '';
	protected $wp_avatar = '';

	/**
	 * This is default constructor
	 */
	public function __construct() {
		parent::__construct();

	// Chargement global des librairies et helpers essentiels
	$this->load->library('session');
	$this->load->library('pagination');
	$this->load->helper('url');
	}
	
	/**
	 * Cette fonction vérifie si l'utilisateur est connecté
	 */
	function isLoggedIn() {
		$CI =& get_instance();
		$isLoggedIn = $CI->session->userdata('isLoggedIn');
		
		if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
			redirect('login');
		} else {
			$this->role = $CI->session->userdata('role');
			$this->vendorId = $CI->session->userdata('userId');
			$this->name = $CI->session->userdata('name');
			$this->roleText = $CI->session->userdata('roleText');
			$this->lastLogin = $CI->session->userdata('lastLogin');
			$this->isAdmin = $CI->session->userdata('isAdmin');
			$this->accessInfo = $CI->session->userdata('accessInfo');
			// Normalisation pour éviter warnings si null
			if(!is_array($this->accessInfo)) { $this->accessInfo = []; }
			$this->wp_avatar = $CI->session->userdata('wp_avatar');

			$this->global['name'] = $this->name;
			$this->global['role'] = $this->role;
			$this->global['role_text'] = $this->roleText;
			$this->global['last_login'] = $this->lastLogin;
			$this->global['is_admin'] = $this->isAdmin;
			$this->global['access_info'] = $this->accessInfo;
			$this->global['wp_avatar'] = $this->wp_avatar;
		}
	}
	
	/**
	 * This function is used to check the access
	 */
	function isAdmin() {
		if ($this->isAdmin == SYSTEM_ADMIN) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * This function is used to check the user having list access or not
	 */
	protected function hasListAccess() {
		if ($this->isAdmin() ||
			(is_array($this->accessInfo) && array_key_exists($this->module, $this->accessInfo) 
			&& (isset($this->accessInfo[$this->module]['list']) && $this->accessInfo[$this->module]['list'] == 1 
			|| isset($this->accessInfo[$this->module]['total_access']) && $this->accessInfo[$this->module]['total_access'] == 1))) {
			return true;
		}
		return false;
	}

	/**
	 * This function is used to check the user having create access or not
	 */
	protected function hasCreateAccess() {
		if ($this->isAdmin() ||
			(is_array($this->accessInfo) && array_key_exists($this->module, $this->accessInfo) 
			&& (isset($this->accessInfo[$this->module]['create_records']) && $this->accessInfo[$this->module]['create_records'] == 1 
			|| isset($this->accessInfo[$this->module]['total_access']) && $this->accessInfo[$this->module]['total_access'] == 1))) {
			return true;
		}
		return false;
	}

	/**
	 * This function is used to check the user having update access or not
	 */
	protected function hasUpdateAccess() {
		if ($this->isAdmin() ||
			(is_array($this->accessInfo) && array_key_exists($this->module, $this->accessInfo) 
			&& (isset($this->accessInfo[$this->module]['edit_records']) && $this->accessInfo[$this->module]['edit_records'] == 1 
			|| isset($this->accessInfo[$this->module]['total_access']) && $this->accessInfo[$this->module]['total_access'] == 1))) {
			return true;
		}
		return false;
	}

	/**
	 * This function is used to check the user having delete access or not
	 */
	protected function hasDeleteAccess() {
		if ($this->isAdmin() ||
			(is_array($this->accessInfo) && array_key_exists($this->module, $this->accessInfo) 
			&& (isset($this->accessInfo[$this->module]['delete_records']) && $this->accessInfo[$this->module]['delete_records'] == 1 
			|| isset($this->accessInfo[$this->module]['total_access']) && $this->accessInfo[$this->module]['total_access'] == 1))) {
			return true;
		}
		return false;
	}

	/**
	 * This function is used to load the set of views
	 */
	function loadThis() {
		$this->global ['pageTitle'] = 'CodeInsect : Access Denied';
		
		$this->load->view ( 'includes/header', $this->global );
		$this->load->view ( 'general/access' );
		$this->load->view ( 'includes/footer' );
	}
	
	/**
	 * This function is used to logged out user from system
	 */
	function logout() {
		$CI =& get_instance();
		$CI->session->sess_destroy();
		redirect('login');
	}

		/**
		 * This function used to load views
		 * @param {string} $viewName : This is view name
		 * @param {mixed} $headerInfo : This is array of header information
		 * @param {mixed} $pageInfo : This is array of page information
		 * @param {mixed} $footerInfo : This is array of footer information
		 * @return {null} $result : null
		 */
		function loadViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){
			// pre($this->global); die;
			$this->load->view('includes/header', $headerInfo);
			$this->load->view($viewName, $pageInfo);
			$this->load->view('includes/footer', $footerInfo);
		}
	
	/**
	 * Fournit les ressources de pagination
	 */
	function paginationCompress($link, $count, $perPage = 10, $segment = SEGMENT) {
		$CI =& get_instance();
		$CI->load->library('pagination');
		$CI->load->library('uri');

		$config['base_url'] = base_url() . $link;
		$config['total_rows'] = $count;
		$config['uri_segment'] = $segment;
		$config['per_page'] = $perPage;
		$config['num_links'] = 5;
		$config['full_tag_open'] = '<nav><ul class="pagination">';
		$config['full_tag_close'] = '</ul></nav>';
		$config['first_tag_open'] = '<li class="arrow">';
		$config['first_link'] = 'First';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '<li class="arrow">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li class="arrow">';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="arrow">';
		$config['last_link'] = 'Last';
		$config['last_tag_close'] = '</li>';

		$CI->pagination->initialize($config);
		$page = $config['per_page'];
		$segment = $CI->uri->segment($segment);

		return array(
			"page" => $page,
			"segment" => $segment
		);
	}
}