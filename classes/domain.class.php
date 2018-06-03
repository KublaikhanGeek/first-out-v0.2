<?
##################################################
# CLASS: domain.class.php 
#
##################################################

class domain extends baseObject {
	// MySQL database table name.
	var $table_name  = "domain";
	var $primary_key = "id";
	
	//Attributes
	var $id;
	var $domain_name;
	var $date_created;
	var $date_member_modified;
	var $date_admin_modified;
	var $date_expiry;
	# new
	var $date_whois_update;
	var $registered;

	var $registrant_name;
	var $registrant_address;
	var $registrant_address1;
	var $registrant_suburb;
	var $registrant_zipcode;
	var $registrant_country;
	var $registrant_email;
	var $registrant_phone;
	var $registrant_mobile;
	var $registrant_fax;
	
	# New 14/08/2007
	var $registration_fee;
	var $renewal_fee;

	var $registra_name;
	var $registra_url;
	var $registra_login;
	var $registra_password;
	var $auto_renew;
	var $FK_contact_id;

	# For Search Purposes
	var $select_columns   = "id, domain_name";
	var $from_tables;
	var $order_by_columns = "domain_name";
	var $where_clauses    = array();

	function domain($id=""){
		if(func_num_args() == 1){
			$this->setDomain($id);
		}
	}
	
	function setDomain($id){
		global $DB;

		baseObject::load($id);
	}

	
	function getId() {
		return $this->id;
	}
	
	function getDomain_name() {
		return $this->domain_name;
	}
	
	function getDate_created() {
		return $this->date_created;
	}
	
	function getDate_member_modified() {
		return $this->date_member_modified;
	}
	
	function getDate_admin_modified() {
		return $this->date_admin_modified;
	}
	
	function getDate_expiry($date_format="") {
		if(!empty($date_format)) {
			if($this->date_expiry != '0000-00-00') {
				return standard_format_date($this->date_expiry, $date_format);
			} else {
				return "n/a";
			}
		}

		return $this->date_expiry;
	}

	function getDate_whois_update() {
		return $this->date_whois_update;
	}

	function getRegistered() {
		return $this->registered;
	}

	function getExpiringPeriod() {

		$periods = array("0"=>"red","3"=>"red","6"=>"orange","15"=>"orange");

		foreach($periods as $period => $color) {
			if(calc_date(date("Y-m-d"), $period, 'month') > $this->date_expiry) return array($period,$color);
		}

		return false;
	}
	
	function getRegistrant_name() {
		return $this->registrant_name;
	}
	
	function getRegistrant_address() {
		return $this->registrant_address;
	}
	
	function getRegistrant_address1() {
		return $this->registrant_address1;
	}
	
	function getRegistrant_suburb() {
		return $this->registrant_suburb;
	}
	
	function getRegistrant_zipcode() {
		return $this->registrant_zipcode;
	}
	
	function getRegistrant_country() {
		return $this->registrant_country;
	}
	
	function getRegistrant_email() {
		return $this->registrant_email;
	}
	
	function getRegistrant_phone() {
		return $this->registrant_phone;
	}
	
	function getRegistrant_mobile() {
		return $this->registrant_mobile;
	}
	
	function getRegistrant_fax() {
		return $this->registrant_fax;
	}

	function getRegistration_fee() {
		return $this->registration_fee;	
	}

	function getRenewal_fee() {
		return $this->renewal_fee;	
	}
	
	function getRegistra_name() {
		return $this->registra_name;
	}
	
	function getRegistra_url() {
		return $this->registra_url;
	}
	
	function getRegistra_login() {
		return $this->registra_login;
	}
	
	function getRegistra_password() {
		return $this->registra_password;
	}
	
	function getAuto_renew() {
		return $this->auto_renew;
	}
	
	function getFK_contact_id() {
		return $this->FK_contact_id;
	}

	function getMainContactObject(){
		$mainContact = new user($this->FK_contact_id);
		return $mainContact;
	}

	function getMainContactInfo(){
		$contact = new user($this->FK_contact_id);
		if($contact->getFirst_name() != '' && $contact->getLast_name() != ''){
			/*
			$mainContactInfo = "<a class=smallblue href=# onclick=\"window.open('clientcontactadmin.php?FK_client_id=" . $this->client_id . "&r=" . $this->FK_contact_id . "','contact','width=660,height=393,resizable=yes,scrollbars=yes');\">" . 
			*/
			$mainContactInfo = $contact->getFirst_name() . " " . $contact->getLast_name(); 
			#. "</a>";
		}
		if($contact->getHome_phone() != ''){
			$mainContactInfo .= "<br><i>ph: </i>&nbsp;&nbsp;&nbsp;"  . $contact->getHome_phone();
		}
		if($contact->getMobile_phone() != ''){
			$mainContactInfo .= "<br><i>mob: </i>" . $contact->getMobile_phone();
		}
		if($contact->getFax1() != ''){
			$mainContactInfo .= "<br><i>fax: </i>&nbsp;&nbsp;" . $contact->getFax1();
		}

		return $mainContactInfo;
	}

	function getAllDomains(){
		global $DB;

		$sql = "SELECT * FROM " . $this->table_name;

		$domains_array = $DB->associative_array($sql);

		return $domains_array;
	}
	
	/*
	function getAllClientSites($FK_client_id){
		global $DB;
		
		if(func_num_args() == 0){
			$FK_client_id = $this->client_id;
		}

		$sql = "SELECT * 
				FROM site
				WHERE FK_client_id = '" . $FK_client_id . "' ORDER BY site_name";
	
		$sites_array = $DB->associative_array($sql);

		return $sites_array;
	}
	*/

	function getAllContacts($domain_id){
		global $DB;

		if(func_num_args() == 0){
			$domain_id = $this->id;
		}
		$db = $this->db;
		$sql = "SELECT * 
				FROM   user u LEFT JOIN domain_to_user du ON u.user_id = du.FK_user_id
				WHERE  u.user_type    = 'C'
				AND   du.FK_domain_id = '" . $domain_id . "'";
	
		$contacts_array = $DB->associative_array($sql);

		return $contacts_array;
	}

	function getClientAllowances(){
		global $DB;

		$sql = "SELECT * 
				FROM allowance 
				WHERE FK_client_id = '" . $this->client_id . "'
				ORDER BY allowance_name";

		$allowances_array = $DB->associative_array($sql);
		
		return $allowances_array;
	}

	function createDomain($props=array())  {
		global $DB;

		$time = date("Y-m-d");

		$default_props = array();

		if(!empty($_REQUEST['post_update'])) {
			$props = array('domain_name'   => $_REQUEST['domain_name'],
						   'date_created'  => $time,
						   #'date_member_modified' => $time,
						   'date_admin_modified' => $time,
						   'date_expiry' => $_REQUEST['date_expiry'],
						   'registrant_name' => $_REQUEST['registrant_name'],
						   'registrant_address' => $_REQUEST['registrant_address'],
						   'registrant_address1' => $_REQUEST['registrant_address1'],
						   'registrant_suburb' => $_REQUEST['registrant_suburb'],
						   'registrant_zipcode' => $_REQUEST['registrant_zipcode'],
						   'registrant_country' => $_REQUEST['registrant_country'],
						   'registrant_email' => $_REQUEST['registrant_email'],
						   'registrant_phone' => $_REQUEST['registrant_phone'],
						   'registrant_mobile' => $_REQUEST['registrant_mobile'],
						   'registrant_fax' => $_REQUEST['registrant_fax'],
						   'registra_name' => $_REQUEST['registra_name'],
						   'registra_url' => $_REQUEST['registra_url'],
						   'registra_login' => $_REQUEST['registra_login'],
						   'registra_password' => $_REQUEST['registra_password'],
						   'auto_renew' => $_REQUEST['auto_renew'],
						   'registration_fee' => $_REQUEST['registration_fee'],
						   'renewal_fee'	  => $_REQUEST['renewal_fee']);
		}
		
		# print_r($props);

		$props = array_merge($default_props, $props);

		return $this->create($props);
	}

	function updateWhoisData() {

		$props = array();

		$time = date("Y-m-d");

		$whois = new Whois();
		$domain_data = $whois->Lookup($this->domain_name);
		
		#####################################
		# ALERT: Below has to be stored in DB
		if(!empty($domain_data['regrinfo']['registered'])) {
			$props['registered'] = $domain_data['regrinfo']['registered'];
			$this->registered = $props['registered'];
		}
		
		# date_expiry
		$props['date_expiry'] = $domain_data['regrinfo']['domain']['expires'];
		$this->date_expiry    = $props['date_expiry'];

		# date_whois_update
		$props['date_whois_update'] = $time;
		$this->date_whois_update    = $props['date_whois_update'];

		$this->update($props);

	}

	function updateDomain($props=array())  {
		global $DB;

		$time = date("Y-m-d");

		$default_props = array();
	
		if(!empty($_REQUEST['post_update'])) {
			$props = array('domain_name'   => $_REQUEST['domain_name'],
						    #'date_member_modified' => $_REQUEST['date_member_modified'],
						   'date_admin_modified' => $time,
						   'date_expiry' => $_REQUEST['date_expiry'],
						   'registrant_name' => $_REQUEST['registrant_name'],
						   'registrant_address' => $_REQUEST['registrant_address'],
						   'registrant_address1' => $_REQUEST['registrant_address1'],
						   'registrant_suburb' => $_REQUEST['registrant_suburb'],
						   'registrant_zipcode' => $_REQUEST['registrant_zipcode'],
						   'registrant_country' => $_REQUEST['registrant_country'],
						   'registrant_email' => $_REQUEST['registrant_email'],
						   'registrant_phone' => $_REQUEST['registrant_phone'],
						   'registrant_mobile' => $_REQUEST['registrant_mobile'],
						   'registrant_fax' => $_REQUEST['registrant_fax'],
						   'registra_name' => $_REQUEST['registra_name'],
						   'registra_url' => $_REQUEST['registra_url'],
						   'registra_login' => $_REQUEST['registra_login'],
						   'registra_password' => $_REQUEST['registra_password'],
						   'auto_renew' => $_REQUEST['auto_renew'],
						   'FK_contact_id' => $_REQUEST['FK_contact_id'],
						   'registration_fee' => $_REQUEST['registration_fee'],
						   'renewal_fee'	  => $_REQUEST['renewal_fee']);
		}
		
		#$this->debug = 2;
		$this->update($props);
	}

	function deleteDomain($id){
		$sql = "DELETE FROM
			    $this->table_name 
				WHERE id = '" . $_id . "'";
		
		$DB->delete($sql);
	}

	function set_props($props = array()) {
		if(!empty($props)) {
			$object_vars = array_keys(get_object_vars($this));
			foreach($props as $prop => $value) {
				if(!empty($prop) && in_array($prop, $object_vars)) {
					if(is_object($this->$prop) && method_exists("set_props")) 
$this->$prop->set_props($value);
					else $this->$prop = $value;
				}
			}
		}
	}

	function search($props=array()){
		global $DB;

		$default_props = array("keyword" => "");

		$props = array_merge($default_props, $props);

		$this->select_columns   = "d.id, d.domain_name, d.registrant_name, d.date_expiry, d.date_created, CONCAT(u.first_name, ' ', u.last_name) as contact_name, u.user_id as contact_id";
		$this->from_tables      = $this->table_name . " d LEFT JOIN domain_to_user du ON d.id = du.FK_domain_id LEFT JOIN user u ON du.FK_user_id = u.user_id";
		
		#if( !empty($props['keyword']) ) {
		$this->where_clauses[] = ((sizeof($this->where_clauses)>0)?" AND ":"") . "(domain_name LIKE '%" . $props['keyword'] . "%' OR registrant_name LIKE '%" . $props['keyword'] . "%')";							
		#}
		
		if( !empty($props['contact_name']) ) {
			$this->where_clauses[] = ((sizeof($this->where_clauses)>0)?" AND ":"") . "(CONCAT(u.first_name, ' ', u.last_name) LIKE '%" . $props['contact_name'] . "%')";
		}

		if( !empty($props['contact_id']) ) {
			$this->where_clauses[] = ((sizeof($this->where_clauses)>0)?" AND ":"") . "(u.user_id = '" . $props['contact_id'] . "')";
		}

		if( !empty($props['orderby']) ) {
			$this->order_by_columns = $props['orderby'];
		} else {
			$this->order_by_columns = "domain_name";
		}
		
		
		$sql = "SELECT " . $this->select_columns . " " .
			   "FROM "   . $this->from_tables . " " .
			   "WHERE ";
				if( sizeof($this->where_clauses) > 0 ) {
					for($i=0; $i < sizeof($this->where_clauses); $i++){
						$sql .= $this->where_clauses[$i]; 
					}
				}

		$sql .= "ORDER BY " . $this->order_by_columns . " ";

		# debug
		# echo $sql;

		$result_array = $DB->associative_array_repeats($sql);
		
		# Return an array of employees
		return $result_array;
	}
}
?>