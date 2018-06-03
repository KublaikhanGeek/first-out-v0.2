<?
##################################################
# site.obj
# site CLASS 
#
##################################################

class site{
	// MySQL database table name.
	var $table_name;
	
	//Attributes
	var $site_id;			  # int(11)
	var $FK_client_id;		  # int(11)
	var $site_name;			  # varchar(50)
	var $country;			  # set('Australia')
	var $address;			  # varchar(50)
	var $suburb;			  # varchar(50)
	var $state;				  # varchar(10)
	var $postcode;			  # varchar(8)
	var $phone1;			  # varchar(15)
	var $phone2;			  # varchar(15)
	var $fax1;				  # varchar(15)
	var $fax2;				  # varchar(15)
	var $FK_rep_id;			  # int(11)
	var $FK_contact_id;		  # int(11)
	var $FK_profit_centre_id; # int(11)
	var $FK_company_id;		  # int(11)

	# For Search Purposes
	var $select_columns   = "site_id, site_name, address";
	var $from_tables;
	var $order_by_columns = "site_name";
	var $where_clauses    = array();

	function site($id){
		global $DB;
		#assign the global $DB database object to a local one
		#in this class
		$this->db = $DB;

		$this->table_name = "site";
		if(func_num_args() == 1){
			$this->setSite($id);
		}
	}
	
	function setSite($id){
		$sql = "SELECT * FROM " . $this->table_name . " WHERE site_id = '" . $id . "'"; 
		$db = $this->db;
		$result_array = $db->single_row($sql);
		
		#Instantiate the object
		$this->site_id	     = $result_array[site_id];
		$this->FK_client_id  = $result_array[FK_client_id];
		$this->site_name     = $result_array[site_name];
		$this->country       = $result_array[country];
		$this->address       = $result_array[address];
		$this->suburb        = $result_array[suburb];
		$this->state         = $result_array[state];
		$this->postcode      = $result_array[postcode];
		$this->phone1        = $result_array[phone1];
		$this->phone2        = $result_array[phone2];
		$this->fax1          = $result_array[fax1];
		$this->fax2          = $result_array[fax2];
		$this->FK_rep_id     = $result_array[FK_rep_id];
		$this->FK_contact_id = $result_array[FK_contact_id];
		$this->FK_profit_centre_id = $result_array[FK_profit_centre_id];
		$this->FK_company_id = $result_array[FK_company_id];
	}
	
	function getSite_id(){
		return $this->site_id;
	}

	function getFK_client_id(){
		return $this->FK_client_id;
	}

	function getSite_name(){
		return $this->site_name;
	}

	function getCountry(){
		return $this->country;
	}

	function getAddress(){
		return $this->address;
	}

	function getSuburb(){
		return $this->suburb;
	}

	function getState(){
		return $this->state;
	}

	function getPostcode(){
		return $this->postcode;
	}

	function getPhone1(){
		return $this->phone1;
	}

	function getPhone2(){
		return $this->phone2;
	}

	function getFax1(){
		return $this->fax1;
	}

	function getFax2(){
		return $this->fax2;
	}

	function getClientObject(){
		$client = new client($this->FK_client_id);
		return $client;
	}

	function getFK_rep_id(){
		return $this->FK_rep_id;
	}

	function getFK_contact_id(){
		return $this->FK_contact_id;
	}

	function getFK_profit_centre_id(){
		return $this->FK_profit_centre_id;
	}

	function getFK_company_id(){
		return $this->FK_company_id;
	}
	
	function getSiteContactObject(){
		$contact = new user($this->FK_contact_id);
		return $contact;
	}

	function getSiteContactInfo(){
		$contact = new user($this->FK_contact_id);
		if($contact->getFirst_name() != '' && $contact->getLast_name() != ''){
			/*
			$siteContactInfo = "<a class=smallblue href=# onclick=\"window.open('clientcontactadmin.php?FK_client_id=" . $this->FK_client_id . "&r=" . $this->FK_contact_id . "','contact','width=660,height=393,resizable=yes,scrollbars=yes');\">" . */
			
			$siteContactInfo = $contact->getFirst_name() . " " . $contact->getLast_name();
			
			#. "</a>";
		}
		if($contact->getHome_phone() != ''){
			$siteContactInfo .= "<br><i>ph: </i>&nbsp;&nbsp;&nbsp;"  . $contact->getHome_phone();
		}
		if($contact->getMobile_phone() != ''){
			$siteContactInfo .= "<br><i>mob: </i>" . $contact->getMobile_phone();
		}
		if($contact->getFax1() != ''){
			$siteContactInfo .= "<br><i>fax: </i>&nbsp;&nbsp;" . $contact->getFax1();
		}

		return $siteContactInfo;
	}

	function createSite($FK_client_id,$site_name,$country,$address,$suburb,
						$state,$postcode,$phone1,$phone2,$fax1,$fax2,$FK_rep_id,$FK_contact_id, $FK_profit_centre_id,$FK_company_id)  {
		$sql1 = "INSERT INTO $this->table_name 
			          (site_id,FK_client_id,site_name,country,address,suburb,
					   state,postcode,phone1,phone2,fax1,fax2,FK_rep_id,
					   FK_contact_id,FK_profit_centre_id,FK_company_id) 
				VALUES('','$FK_client_id','$site_name','$country','$address','$suburb',
						'$state','$postcode','$phone1','$phone2','$fax1','$fax2','$FK_rep_id','$FK_contact_id','$FK_profit_centre_id','$FK_company_id')";		
		$db = $this->db;
		$db->insert($sql1);
		
		$sql2 = "SELECT MAX(site_id) FROM $this->table_name;";
		$newsite = $db->single_element($sql2);
		
		return $newsite;
	}

	function updateSite($site_id,$site_name,$country,$address,$suburb,
						$state,$postcode,$phone1,$phone2,$fax1,$fax2,
						$FK_rep_id,$FK_contact_id,$FK_profit_centre_id,$FK_company_id)  {
		
		$sql3 = "UPDATE $this->table_name
				 SET   site_name		   = '$site_name',
					   country			   = '$country',
					   address			   = '$address',
					   suburb			   = '$suburb',
					   state			   = '$state',
					   postcode			   = '$postcode',
					   phone1			   = '$phone1',
					   phone2			   = '$phone2',
					   fax1				   = '$fax1', 
					   fax2		 	       = '$fax2',
					   FK_rep_id           = '$FK_rep_id',
					   FK_contact_id       = '$FK_contact_id',
					   FK_profit_centre_id = '$FK_profit_centre_id',
					   FK_company_id	   = '$FK_company_id'
				 WHERE site_id    = '$site_id'";
		$db = $this->db;
		$db->update($sql3);
	}

	function deleteSite($site_id){
		$sql4 = "DELETE FROM
				 $this->table_name 
				 WHERE site_id = '$site_id'";
		$db = $this->db;
		$db->delete($sql4);
	}

	function search($keyword){
		$this->select_columns   = "site_id, site_name, address";
		$this->from_tables      = $this->table_name; 
		$this->where_clauses    = array("site_name LIKE '%" . $keyword . "%'");
	    $this->order_by_columns = "site_name";

		
		
		$sql = "SELECT " . $this->select_columns . " " .
			   "FROM "   . $this->from_tables . " " .
			   "WHERE ";
				for($i=0; $i < sizeof($this->where_clauses); $i++){
					if($i > 0){
						$sql .= " OR ";
					}
				$sql .= $this->where_clauses[$i] . " "; 
				}

		$sql .= "ORDER BY " . $this->order_by_columns . " ";

		$db = $this->db;
		$result_array = $db->associative_array($sql);
		
		# Return an array of employees
		return $result_array;
	}
}
?>