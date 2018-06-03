<?    
##############################################
#Database abstraction layer, to be changed
##############################################

#error_reporting(15);
class Db {

	var $host;          # Abbr: h
	var $login;         # Abbr: l
	var $password;      # Abbr: p
	var $name;          # Abbr: n
	
	var $ptr;           # The current database pointer

	var $error_msg;     # Current error message;
	var $print_errors;  # Print errors as they occur on not;

	 ##################################
	# Constructor
	function Db ($n, $l = "root", $p = "", $h = "localhost") {
		$this->print_errors = 1;
		if ($n) { # If a db has been specified, connect
			$this->connect($n,$l,$p,$h);
		}
	}

	 ###############################
	# Connect to a certain database
	function connect($n, $l, $p, $h) {
		if (!$l) { # Use last login/password
			$l = $this->login;
			$p = $this->password;
		}
		# If they want to connect to a new server, let em
		if ($h && $h != $this->host) { 
			if ($this->ptr) { # Close old ptr
				mysql_close($this->ptr);
			}
			if (!($this->ptr = mysql_pconnect($h, $l, $p))) {
				$this->_set_error("Unable to connect to database server: $h. Login: $l. Password: ".(($p)?"Yes":"No"));
				return 0;
			}
			$this->host     = $h;
			$this->login    = $l;
			$this->password = $p;
		}

		# Select specified database name
		if (!$n) {
			$this->_set_error("Unable to select database: no name specified.");
			return 0;
		}
		$this->name = $n;
		$this->_use();
		return 1;

	}
	
	 ##########################################
	# Returns the amount of rows in a result
	

	 ##########################################
	# Ensures we are using the corrent database
	function _use() {
		global $DB_CURRENT_NAME;
		if ($this->name == $DB_CURRENT_NAME[$this->host]) return 1;
		if (!mysql_select_db($this->name, $this->ptr)) {
			$this->_set_error("Unable to select database ($n): ".mysql_error($this->ptr));
			return 0;
		}
		$DB_CURRENT_NAME[$this->host] = $this->name;
		return 1;
	}

	 #####################################
	# Disconnect from the database server
	function disconnect() {
		return mysql_close($this->ptr);
	}


	  #############################################
	 # select from database, return query handle
	function select($query) {
		if (!$this->_use()) return 0;
		if (!($result = mysql_query($query, $this->ptr))) {
			$this->_set_error("Unable to SELECT [$query]: ".mysql_error($this->ptr));
			return 0;
		} else {
			return $result;
		}
	}


	  ##################################################################################
	 # insert into database, return value of auto_increment field, or the rows affected
	function insert($query) {
		if (!$this->_use()) return 0;
		if (!mysql_query($query, $this->ptr)) {
			$this->_set_error("Unable to INSERT [$query]: ".mysql_error($this->ptr));
		} else {
			if ($id = $this->single_element("SELECT last_insert_id()")) {
				return $id;
			} else {
				 return mysql_affected_rows($this->ptr);
			}
		}
	}


	  ########################################
	 # update database, return rows changed 
	function update($query) {
		if (!$this->_use()) return 0;
		if (!mysql_query($query, $this->ptr)) {
			$this->_set_error("Unable to UPDATE [$query]: ".mysql_error($this->ptr));
		} else {
			return mysql_affected_rows($this->ptr);
		}
	}


	  #############################################
	 # delete from database, return rows deleted
	function delete($query) {
		if (!$this->_use()) return 0;
		if (!mysql_query($query, $this->ptr)) {
			$this->_set_error("Unable to DELETE [$query]: ".mysql_error($this->ptr));
		} else {
			return mysql_affected_rows($this->ptr);
		}
	}


	  ################################################
	 # Combined auto-detect database query function
	function query($query) {
		if (strtoupper(substr(ltrim($query), 0, 6)) == "SELECT") return $this->select($query);
		if (strtoupper(substr(ltrim($query), 0, 6)) == "UPDATE") return $this->update($query);
		if (strtoupper(substr(ltrim($query), 0, 6)) == "INSERT") return $this->insert($query);
		if (strtoupper(substr(ltrim($query), 0, 6)) == "DELETE") return $this->delete($query);
		$this->_set_error("Unable to determine query type [$query].");
	}


	  ################################################
	 # mysql_fetch_array except with slashstripping
	function row($result) {
		$row = mysql_fetch_array($result);
		for($val = reset($row); $val; $val = next($row)) {
			if (is_string($val))
				$row[key($row)] = stripslashes($val);
			else
				$row[key($row)] = $val;
		}
		reset($row);
		return $row;
	}

	  #####################################################################
	 # takes a query for a single row and returns the row, slashstripped 
	function single_row($query) {
		$result = $this->select($query);
		$row = $this->row($result);
		mysql_free_result($result);
		return $row;
	}


	  #####################################################################################
	 # takes a reseult and return a stripslashed array of values in a column
	function column($result, $index) {
		if (!$index) $index = 0;
		$array = array();
		for ($i = 0; $i < mysql_num_rows($result); $i++) {
			$val = mysql_result($result,$i,$index);
			if (is_string($val)) {
				$array[] = stripslashes($val);
			} else {
				$array[] = $val;
			}
		}
		return $array;
	}

	  
	  #####################################################################
	 # takes a query for a single column and returns it
	#####################################################################

	function single_column($query) {
		$result = $this->select($query);
		$column = $this->column($result);
		mysql_free_result($result);
		return $column;
	}


	  ####################################################################
	 # takes a query for a single element and returns it, slashstripped
	function single_element($query) {
		$result = $this->select($query);
		$element = mysql_result($result,0,0);
		if (is_string($element)) $element = stripslashes($element);
		mysql_free_result($result);
		return $element;
	}

	   ################################################
	  # Like single row, but returns only the keys with 
	 # fieldnames, not the column number
	 function associative_row($query) {
		$row = $this->single_row($query);
		for(reset($row); $key = key($row); next($row)) {
			if (is_int($key)) unset($row[$key]);
		}
		return $row;
	 }

	   ###############################################################
	  # Returns an array of rows, using the first column as the key #
	 # if column count > 2, values are an array, else just a value
	function associative_array($query) {
		$result = $this->select($query);
		$array = array();
		if (mysql_num_fields($result) > 2) {
			while($row = mysql_fetch_array($result)) {
				for($val = reset($row); $val; $val = next($row)) {
					if (is_string($val)){
						$row[key($row)] = stripslashes($val);
					} else {
						$row[key($row)] = $val;
					}
				}
				reset($row);
				$array[$row[0]] = $row;
			}
		} else {
			while($row = mysql_fetch_row($result)) {
				$array[$row[0]] = ((is_string($row[1]))?stripslashes($row[1]):$row[1]);
			}
		}
		mysql_free_result($result);
		return $array;
	}

	   ###############################################################
	  # Returns an array of rows, using the first column as the key #
	 # if column count > 2, values are an array, else just a value
	function associative_array_repeats($query) {
		$result = $this->select($query);
		$array = array();
		if (mysql_num_fields($result) > 2) {
			$i=0;
			while($row = mysql_fetch_array($result)) {
				for($val = reset($row); $val; $val = next($row)) {
					if (is_string($val)){
						$row[key($row)] = stripslashes($val);
					} else {
						$row[key($row)] = $val;
					}
				}
			
				reset($row);
				$array[$i] = $row;
				$i++;
			}
			
		} else {
			$i=0;
			while($row = mysql_fetch_row($result)) {
				$array[$i] = ((is_string($row[1]))?stripslashes($row[1]):$row[1]);
				$i++;
			}
		}
		mysql_free_result($result);

		return $array;
	}

	   ###############################################################
	  # Returns an array of rows, using the first column as the key #
	 # if column count > 2, values are an array, else just a value
	function associative_array2($query) {
		$result = $this->select($query);
		$array = array();
		while($row = mysql_fetch_array($result)) {
			for($val = reset($row); $val; $val = next($row)) {
				if (is_string($val)){
					$row[key($row)] = stripslashes($val);
				} else {
					$row[key($row)] = $val;
				}
			}
			reset($row);
			$array[] = $row;
		}
		mysql_free_result($result);
		return $array;
	}
	

	 ################################
	# Sets the current error message
	function _set_error($m) {
		$this->error_msg = $m;
		if ($this->print_errors) echo("<br><b>DATABASE ERROR:</b> $m<br>");
	}

	 ###################################
	# Returns the current error message
	function error_reporting($val) {
		$this->print_errors = $val;
	}

	 ###################################
	# Returns the current error message
	function error() {
		return $this->error_msg;
	}

}


?>