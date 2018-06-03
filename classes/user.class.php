<?php
##################################################
# user.obj
# USER CLASS - Stores systems user info to be
# inherited by classes student, marker, lecturer
##################################################

class user extends baseObject {
	// attributes
	var $table_name;

	var $user_id;
	var $user_name;
	var $password;
	var $user_type;       #set('S','M','R','C');
	var $status;

	var $title;
	var $first_name;
	var $last_name;
	var $address;
	var $suburb;
	var $state;
	var $postcode;
	var $home_phone;
	var $mobile_phone;
	var $email;
	var $fax1;
	var $fax2;
	var $FK_domain_id;
	var $FK_country_id;
	var $date_created;
	var $date_modified;

	# vars for login system
	var $hidden_hash_var;
	var $LOGGED_IN;
	var $id_hash;
	var $feedback;

	# Class level database object
	var $db;

	# For Search Purposes
	var $select_columns;
	var $from_tables;
	var $order_by_columns;
	var $where_columns  = array();

	function user($id){
		global $DB;
		
		# Make the variable below global
		$this->hidden_hash_var = "your_password_here";

		#clear it out in case someone sets it in the URL or something
		$this->LOGGED_IN       = false;
		unset($this->LOGGED_IN);

		#assign the global $DB database object to a local one
		#in this class
		$this->db = $DB;
		$this->table_name = "user";
		
		if(func_num_args() == 1){
			$this->setUser($id);
		}
	}
	
	function setUser($id){
		$sql = "SELECT * FROM " . $this->table_name . " WHERE user_id = '" . $id . "'"; 
		$db = $this->db;
		$result_array = $db->single_row($sql);
		
		#Instantiate the object
		$this->setUser_id($result_array['user_id']);
		$this->setUser_name($result_array['user_name']);
		$this->setPassword($result_array['password']);
		$this->setUser_type($result_array['user_type']);
		$this->setStatus($result_array['status']);
		$this->setFirst_name($result_array['first_name']);
		$this->setLast_name($result_array['last_name']);
		$this->setPosition($result_array['position']);

		$this->setTitle($result_array['title']);
		$this->setAddress($result_array['address']);
		$this->setAddress1($result_array['address1']);
		$this->setSuburb($result_array['suburb']);
		$this->setState($result_array['state']);
		$this->setPostcode($result_array['postcode']);
		$this->setHome_phone($result_array['home_phone']);
		$this->setMobile_phone($result_array['mobile_phone']);
		$this->setEmail($result_array['email']);
		$this->setFax1($result_array['fax1']);
		$this->setFax2($result_array['fax2']);
		$this->setFK_domain_id($result_array['FK_domain_id']);
		$this->setFK_country_id($result_array['FK_country_id']);
		$this->setDate_created($result_array['date_created']);
		$this->setDate_modified($result_array['date_modified']);

	}

	function setUser_id($userid){
		$this->user_id = $userid;
	}

	function getUser_id() {
		return $this->user_id;
	}

	function setUser_name($uname){
		$this->user_name = $uname;
	}

	function getUser_name(){
		return $this->user_name;
	}
	
	function setPassword($pword){
		$this->password = $pword;
	}

	function getPassword(){
		return $this->password;
	}

	function setUser_type($utype){
		$this->user_type = $utype;
	}

	function getUser_type(){
		return $this->user_type;
	}

	function setStatus($status){
		$this->status = $status;
	}

	function getStatus(){
		return $this->status;
	}
	
	# User's personal Details
	function setFirst_name($fname){
		$this->first_name = $fname;
	}
	
	function getFirst_name(){
		return $this->first_name;
	}

	function setLast_name($lname){
		$this->last_name = $lname;
	}
	
	function getLast_name(){
		return $this->last_name;
	}

	function setPosition($position){
		$this->position = $position;
	}
	
	function getPosition(){
		return $this->position;
	}
	
	function setTitle($title){
		$this->title = $title;
	}

	function getTitle(){
		return $this->title;
	}
	
	function setAddress($address){
		$this->address = $address;
	}

	function getAddress(){
		return $this->address;
	}

	function setAddress1($address1){
		$this->address1 = $address1;
	}

	function getAddress1(){
		return $this->address1;
	}
	
	function setSuburb($suburb){
		$this->suburb = $suburb;
	}

	function getSuburb(){
		return $this->suburb;
	}
	
	function setState($state){
		$this->state = $state;
	}

	function getState(){
		return $this->state;
	}
	
	function setPostcode($postcode){
		$this->postcode = $postcode;
	}

	function getPostcode(){
		return $this->postcode;
	}
	
	function setHome_phone($home_phone){
		$this->home_phone = $home_phone;
	}

	function getHome_phone(){
		return $this->home_phone;
	}

	function setMobile_phone($mobile_phone){
		$this->mobile_phone = $mobile_phone;
	}

	function getMobile_phone(){
		return $this->mobile_phone;
	}
	
	function setEmail($email){
		$this->email = $email;
	}

	function getEmail(){
		return $this->email;
	}
	
	function setFax1($fax1){
		$this->fax1 = $fax1;
	}

	function getFax1(){
		return $this->fax1;
	}

	function setFax2($fax2){
		$this->fax2 = $fax2;
	}

	function getFax2(){
		return $this->fax2;
	}

	function setFK_domain_id($FK_domain_id){
		$this->FK_domain_id = $FK_domain_id;
	}
	
	function getFK_domain_id(){
		return $this->FK_domain_id;
	}

	function setFK_country_id($FK_country_id){
		$this->FK_country_id = $FK_country_id;
	}
	
	function getFK_country_id(){
		return $this->FK_country_id;
	}

	function setDate_created($date_created){
		$this->date_created = $date_created;
	}

	function getDate_created(){
		return $this->date_created;
	}

	function setDate_modified($date_modified){
		$this->date_modified = $date_modified;
	}

	function getDate_modified(){
		return $this->date_modified;
	}

	function getDomainObject(){
		$domain = new domain($this->FK_domain_id);
		return $domain;
	}

	function getUserTypeArray(){
		return array('M'=>'System Administrator','C'=>'Client Contact');
	}

	function getAllReps(){
		$db = $this->db;
		$sql = "SELECT * 
				FROM $this->table_name 
				WHERE user_type = 'R'
				ORDER BY last_name";

		$reps_array = $db->associative_array($sql);
		return $reps_array;
	}
	
	function user_isloggedin($permitted) {

		$this->user_type = substr($_COOKIE['user_details'],0,1);
		//have we already run the hash checks? 
		//If so, return the pre-set var
		#echo eregi($this->user_type, $permitted);
		if($permitted != ''){
			$cond = ereg($this->user_type, $permitted);
		}else{
			$cond = 1;
		}

		if (isset($this->LOGGED_IN) && $cond) {
			return $this->LOGGED_IN;
		}

		if ($_COOKIE['USER_NAME'] && $_COOKIE['id_hash']) {
			$hash=md5($_COOKIE['USER_NAME'] . $this->hidden_hash_var);
			if (($hash == $_COOKIE['id_hash']) &&  $cond) {
				$this->LOGGED_IN=true;
				return "true";
			} else {
				$this->LOGGED_IN=false;	
					echo "<script language=javascript>alert('You are not authorised to view this page.'); document.location = '/domain_sys/index.php'</script>";
				return "false";
			}
		} else {
			$this->LOGGED_IN=false;
			echo "<script language=javascript>alert('You are not authorised to view this page.'); document.location = '/domain_sys/index.php'</script>";
			return "false";
		}
	}

	function user_login($user_name,$password) {
		
		if (!$user_name || !$password) {
			$this->feedback .=  ' ERROR - Missing user name or password ';
			return false;
		} else {
			
			$sql = "SELECT * 
					FROM $this->table_name
				    WHERE user_name = '$user_name' 
					AND    password = '" . $password . "'";
			#echo $sql;
			$db = $this->db;
			$result_array = $db->single_row($sql);
			#$result=db_query($sql);
			if (!$result_array || sizeof($result_array) < 1){
				$this->feedback .=  ' ERROR - User not found or password incorrect ';
				return false;
			} else {
				if ($result_array['status'] == '1') {
					$this->setUser_id($result_array['user_id']);
					$this->setFirst_name($result_array['first_name']);
					$this->setLast_name($result_array['last_name']);
					$this->setUser_type($result_array['user_type']);
					$this->user_set_tokens($user_name);
					$this->feedback .=  ' SUCCESSFULL LOGIN ' . $user_name;
					return true;
				} else {
					$this->feedback .=  ' ERROR - You haven\'t Confirmed Your Account Yet ';
					return false;
				}
			}
		}
	}

	function user_set_tokens($user_name_in) {
		
		if (!$user_name_in) {
			$this->feedback .=  ' ERROR - User Name Missing When Setting  Tokens ';
			return false;
		}
		
		$USER_NAME    = strtolower($user_name_in);
		$id_hash      = md5($USER_NAME . $this->hidden_hash_var);
		$user_details = $this->user_type . $this->first_name . " " . $this->last_name;
		$USER_ID      = $this->user_id;

		setcookie('USER_NAME'   , $USER_NAME,(time()+2592000),   '/','',0);
		setcookie('user_details', $user_details,(time()+2592000),'/','',0);
		setcookie('id_hash'     , $id_hash,(time()+2592000),     '/','',0);
		setcookie('USER_ID'     , $USER_ID,(time()+2592000),     '/','',0);
		
		#echo "cookie:" . $_COOKIE['USER_NAME'];
		##############################
		# Fix this below
		$_COOKIE['USER_NAME']    = $USER_NAME;
		$_COOKIE['user_details'] = $user_details;
		$_COOKIE['id_hash']		 = $id_hash;
		$_COOKIE['USER_ID']		 = $USER_ID;
	}
	
	function user_change_password ($new_password1,$new_password2,$change_user_name,$old_password) {
	
		//new passwords present and match?
		if ($new_password1 && ($new_password1==$new_password2)) {
			//is this password long enough?
			if ($this->account_pwvalid($new_password1)) {
				//all vars are present?
				if ($change_user_name && $old_password) {
					//lower case everything
					$change_user_name=strtolower($change_user_name);
					$old_password=strtolower($old_password);
					$new_password1=strtolower($new_password1);
					
					$sql="SELECT * 
						  FROM $this->table_name 
						  WHERE user_name='$change_user_name' 
						  AND password='". md5($old_password) ."'";
					$db = $this->db;
					$result_array = $db->single_row($sql); 
					
					if (!$result_array || sizeof($sql) < 1) {
						$this->feedback .= ' User not found or bad password '.db_error();
						return false;
					} else {
						$sql="UPDATE user 
							  SET password='". md5($new_password1). "' ".
						     "WHERE user_name='$change_user_name' 
							  AND password='". md5($old_password). "'";
							  
						$db->update($sql);
						/*
						if (!$result || db_affected_rows($result) < 1) {
							$this->feedback .= ' NOTHING Changed '.db_error();
							return false;
						} else {
							$this->feedback .= ' Password Changed ';
							return true;
						}
						*/
					}
				} else {
					$this->feedback .= ' Must Provide User Name And Old Password ';
					return false;
				}
			} else {
				$this->feedback .= ' New Passwords Doesn\'t Meet Criteria ';
				return false;
			}
		} else {
			return false;
			$this->feedback .= ' New Passwords Must Match ';
		}
	}
	
	function account_pwvalid($pw) {
		if (strlen($pw) < 20) {
			$this->feedback .= " Password must be at least 6 characters. ";
			return false;
		}	
		return true;
	}

	function user_logout() {

		setcookie('USER_NAME','',(time()+2592000),'/','',0);
		setcookie('id_hash','',(time()+2592000),'/','',0);
		setcookie('user_details','',(time()+2592000),'/','',0);
		setcookie('USER_ID','',(time()+2592000),'/','',0);

		unset($_COOKIE['USER_NAME']);
		unset($_COOKIE['id_hash']);
		unset($_COOKIE['user_details']);
		unset($_COOKIE['USER_ID']);

		echo "<script language=javascript>
			  alert('You have now logged out of Domain Sys');
			  </script>";
	}

	function createUser($user_name,$password,$user_type,$status,$title,$first_name,$last_name,$position,
						$address,$address1,$suburb,$state,$postcode,$home_phone,$mobile_phone,$email,$fax1,$fax2,$FK_domain_id,$FK_country_id){
		
		$current_date = mysql_date_format();

		$sql1 = "INSERT INTO $this->table_name 
			          (user_name,password,user_type,status,title,first_name,last_name,position,
					   address,address1,suburb,state,postcode,home_phone,mobile_phone,email,fax1,
					   fax2,FK_domain_id,FK_country_id,date_created,date_modified) 
				VALUES('$user_name','$password','$user_type','$status','$title','$first_name','$last_name','$position',
					'$address','$address1','$suburb','$state','$postcode','$home_phone','$mobile_phone','$email','$fax1',
					'$fax2','$FK_domain_id','$FK_country_id','$current_date','$current_date')";		
		$db = $this->db;
		$db->insert($sql1);
		
		$sql2 = "SELECT MAX(user_id) FROM $this->table_name;";
		$newuser = $db->single_element($sql2);

		$this->user_id = $newuser;

		return $newuser;
	}

	function assignDomain( $props = array() ) {
		$this->create(array("FK_domain_id" => $props['FK_domain_id'],
							"FK_user_id"   => $this->user_id), "domain_to_user");
	}

	function unassignDomain( $props = array() ) {
		$this->delete(array("FK_domain_id" => $props['FK_domain_id'],
							"FK_user_id"   => $this->user_id), "domain_to_user");
	}

	function updateUser($user_id,$new_user_name,$new_password,$user_type,$status,$title,$first_name,$last_name,
		 	   $position,$address,$address1,$suburb,$state,$postcode,$home_phone,$mobile_phone,$email,$fax1,$fax2,$FK_domain_id,$FK_country_id)  {

		$current_date = mysql_date_format();

		$sql3 = "UPDATE $this->table_name
				 SET   user_type    = '$user_type',
					   status       = '$status',
					   title        = '$title',
					   first_name   = '$first_name',
					   last_name    = '$last_name',
					   position     = '$position',
					   address      = '$address',
					   address1     = '$address1',
					   suburb       = '$suburb',
					   state        = '$state',
					   postcode     = '$postcode',
					   home_phone   = '$home_phone',
					   mobile_phone = '$mobile_phone',
					   email        = '$email',
					   fax1         = '$fax1',
					   fax2			= '$fax2',
					   FK_domain_id  = '$FK_domain_id',
					   FK_country_id = '$FK_country_id',
					   date_modified = '$current_date'
				 WHERE user_id       = '$user_id'";
		$db = $this->db;

		$this->user_id = $user_id;

		$db->update($sql3);
	}

	function deleteUser($user_id){
		$sql4 = "DELETE FROM
				 $this->table_name 
				 WHERE user_id = '$user_id'";
		$db = $this->db;
		$db->delete($sql4);
	}

	function search($keyword, $user_type){
		$this->select_columns   = "user_id, first_name, last_name";
		$this->from_tables      = $this->table_name;
		
		$this->where_clauses[] = "user_type != 'S'";
	
		#WHY did 0 by itself work for worksheet Search!!!!!
		if($user_type != '0' && $user_type != ''){
			$this->where_clauses[] = "user_type = '$user_type'";
		}
		
		if(strlen($keyword) > 1){
			$this->where_clauses[] = "(first_name LIKE '%$keyword%' OR last_name LIKE '%$keyword%')";
		}

		$this->order_by_columns = "last_name";
		
		$sql = "SELECT " . $this->select_columns . " " .
			   "FROM "   . $this->from_tables . " " .
			   "WHERE ";
				for($i=0; $i < sizeof($this->where_clauses); $i++){
					if($i > 0){
						$sql .= " AND ";
					}
				$sql .= $this->where_clauses[$i] . " "; 
				}

		$sql .= "ORDER BY " . $this->order_by_columns . " ";
		#echo $sql;
		$db = $this->db;
		$result_array = $db->associative_array($sql);
		
		# Return an array of employees
		return $result_array;
	}
}
?>