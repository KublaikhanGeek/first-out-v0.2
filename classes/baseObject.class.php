<?
class baseObject {

	var $table_name = '';
	var $primary_key = 'id';

	var $error_message = array();
	var $update_message = array();

	var $debug = 0;

	function baseObject($props=array()) {
		$this->set_props($props);
	}

	function getUpdate_message() {
		return $this->update_message;
	}

	function getError_message() {
		return $this->error_message;
	}

	function getMessages($key='', $type='', $clear_messages=1, 
$show_formatting=1) {
		$str = '';
		if( !empty($key) ) {
			if(!empty($this->update_message[$key]) ) {
				if($show_formatting) $str .= "<span class=\"updatetext\">";
				foreach( $this->update_message[$key] as $k => $message ) {
					if( empty($type) || (!empty($type) && $k == $type) ) {
						$str .= $message;
					}
				}
				if($show_formatting) $str .= "</span>";
			}
			if(!empty($this->error_message[$key]) ) {
				if($show_formatting) $str .= "<span class=\"errortext\">";
				foreach( $this->error_message[$key] as $k => $message ) {
					if( empty($type) || (!empty($type) && $k == $type) ) {
						$str .= $message;
					}
				}
				if($show_formatting) $str .= "</span>";
			}
			if($clear_messages) $this->clearMessages($key);
		} else {
			if(!empty($this->update_message) ) {
				if($show_formatting) $str .= "<span class=\"updatetext\">";
				foreach( $this->update_message as $k => $message ) {
					if(is_array($message)) {
						foreach($message as $k2 => $mess) {
							if(empty($type) || (!empty($type) && $k2 == $type)) {
								$str .= $mess;
							}
						}
					} else {
						if(empty($type) || (!empty($type) && $k == $type)) {
							$str .= $message;
						}
					}
				}
				if($show_formatting) $str .= "</span>";
			}
			if(!empty($this->error_message) ) {
				if($show_formatting) $str .= "<span class=\"errortext\">";
				foreach( $this->error_message as $k => $message ) {
					if(is_array($message)) {
						foreach($message as $k2 => $mess) {
							if(empty($type) || (!empty($type) && $k2 == $type)) {
								$str .= $mess;
							}
						}
					} else {
						if(empty($type) || (!empty($type) && $k == $type)) {
							$str .= $message;
						}
					}
				}
				if($show_formatting) $str .= "</span>";
			}
			if($clear_messages) $this->clearMessages();
		}

		return $str;
	}

	function setError_message($message, $key='', $type='') {
		if( !empty($message) ) {
			if( !empty($key) ) {
				if( !empty($type) ) {
					$this->error_message[$key][$type] = $message;
				} else {
					$this->error_message[$key][] = $message;
				}
			} else {
				if( !empty($type) ) {
					$this->error_message[$type] = $message;
				} else {
					$this->error_message[] = $message;
				}
			}
		}
	}

	function setUpdate_message($message, $key='', $type='') {
		if( !empty($message) ) {
			if( !empty($key) ) {
				if( !empty($type) ) {
					$this->update_message[$key][$type] = $message;
				} else {
					$this->update_message[$key][] = $message;
				}
			} else {
				if( !empty($type) ) {
					$this->update_message[$type] = $message;
				} else {
					$this->update_message[] = $message;
				}
			}
		}

	}

	function clearMessages($key='', $type='') {
		if( !empty($key) ) {
			if(!empty($this->error_message[$key])) {
				if(!empty($type) && isset($this->error_message[$key][$type])) {
					unset($this->error_message[$key][$type]);
				} else {
					$this->error_message[$key] = array();
				}
			}
			if(!empty($this->update_message[$key])) {
				if(!empty($type) && isset($this->update_message[$key][$type])) {
					unset($this->update_message[$key][$type]);
				} else {
					$this->update_message[$key] = array();
				}
			}
		} else {
			if(!empty($type)) {
				foreach($this->error_message as $test_key => $test_type) {
					if(is_array($test_type)) {
						if(isset($test_type[$type])) 
unset($this->error_message[$test_key][$test_type]);
					} else if ($test_key == $type) {
						unset($this->error_message[$test_key]);
					}
				}
				foreach($this->update_message as $test_key => $test_type) {
					if(is_array($test_type)) {
						if(isset($test_type[$type])) 
unset($this->error_message[$test_key][$test_type]);
					} else if ($test_key == $type) {
						unset($this->update_message[$test_key]);
					}
				}
			} else {
				$this->error_message = array();
				$this->update_message = array();
			}
		}
	}


	function messagesEmpty($key='', $type='', $message_type='') {
		if(empty($key)) {
			if(empty($message_type)) {
				if(empty($this->update_message) && empty($this->error_message)) return 
true;
				else return false;
			} else if ($message_type == 'error') {
				return empty($this->error_message);
			} else if ($message_type == 'update') {
				return empty($this->update_message);
			}
		} else {
			if(!empty($type)) {
				if(empty($message_type)) {
					if (empty($this->update_message[$key][$type]) && 
empty($this->error_message[$key][$type])) return true;
					else return false;
				} else if ($message_type == 'error') {
					return empty($this->error_message[$key][$type]);
				} else if ($message_type == 'update') {
					return empty($this->update_message[$key][$type]);
				}
			} else {
				if(empty($message_type)) {
					if (empty($this->update_message[$key]) && 
empty($this->error_message[$key])) return true;
					else return false;
				} else if ($message_type == 'error') {
					return empty($this->error_message[$key]);
				} else if ($message_type == 'update') {
					return empty($this->update_message[$key]);
				}
			}
		}
		return true;
	}

	function messageTypeExists($type='') {
		if(!empty($type)) {
			foreach($this->update_messages as $key => $messages) {
				if(is_array($messages)) {
					foreach($messages as $k => $mess) {
						if($k == $type) return true;
					}
				} else {
					if($key == $type) return true;
				}
			}
			foreach($this->error_messages as $key => $messages) {
				if(is_array($messages)) {
					foreach($messages as $k => $mess) {
						if($k == $type) return true;
					}
				} else {
					if($key == $type) return true;
				}
			}
		}
		return false;
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

	function setDebug($value) {
		$this->debug = $value;
		$object_vars = array_keys(get_object_vars($this));
		foreach($object_vars as $prop) {
			if(is_array($this->$prop)) {
				foreach($this->$prop as $id => $data) {
					if( is_object($this->{$prop}[$id]) && 
method_exists($this->{$prop}[$id], 'setDebug') ) {
						#echo "prop: ".$prop."<br />";
						#echo "before:";
						#pre_echo($this->{$prop}[$id]);
						$this->{$prop}[$id]->setDebug($value);
						#echo "after:";
						#pre_echo($this->{$prop}[$id]);

					}
				}
			}
			if(is_object($this->$prop) && method_exists($this->$prop, 'setDebug')) {
				#echo "id: $this->id | class: ".get_class($this)." | object: ".$prop."<br />";
				$this->$prop->setDebug($value);
			}
		}
	}

	function create($props, $table_name='') {
		global $DB;
		if(empty($table_name) && !empty($this->table_name)) $table_name = 
$this->table_name;
		if(!empty($props) && !empty($table_name)) {
			$create_string = "";

			foreach($props as $prop => $value) $create_string .= 
((empty($create_string))?"":", ")."'".mysql_escape_string($value)."'";

			$sql = "INSERT INTO $table_name (".implode(", ", array_keys($props)).") 
VALUES (".$create_string.")";

			# debug
			if($this->debug) echo $sql . "<br />";

			if($this->debug < 2) {
				$id = $DB->insert($sql);
				if($id !== false) {
					if($table_name == $this->table_name) {
						$primary_key = $this->primary_key;
						$this->$primary_key = $id;
						$this->set_props($props);
						return $this->$primary_key;
					}
					return $id;
				} else if( mysql_error($DB->ptr) != '' ) {
					$this->setError_message('Error creating 
'.ucwords(get_class($this))."<br />");
				}

			}
		}
		return false;
	}

	function update($props, $id=null, $table_name="", $primary_key="") {
		global $DB;

		if(empty($primary_key) && !empty($this->primary_key)) $primary_key = 
$this->primary_key;
		if(empty($id) && !empty($this->$primary_key)) $id = $this->$primary_key;
		if(empty($table_name) && !empty($this->table_name)) $table_name = 
$this->table_name;

		if(!empty($props) && !empty($table_name) && !empty($primary_key)) {

			$update_string = "";
			foreach($props as $prop => $value) $update_string .= 
((empty($update_string))?"":", 
").$prop."='".mysql_escape_string($value)."'";

			$sql = '';
			if(is_array($id)) {
				foreach($id as $key => $value) $sql .= ((empty($sql))?"":" AND 
").$key."='".mysql_escape_string($value)."'";
			} else {
				$sql = $primary_key." = '".$id."'";
			}
			$sql = "UPDATE " . $table_name . " SET " . $update_string . " WHERE " . 
$sql;

			if($this->debug) echo $sql . "<br />";

			if($this->debug < 2) {
				$updated_rows = $DB->update($sql);

				if( mysql_error($DB->ptr) != '' ) {
					$this->setError_message('Error updating 
'.ucwords(get_class($this))."<br />");
				}
				#if(testmode()) echo "table name: $table_name<br />";
				if($updated_rows > 0) {
					#if(testmode()) echo "successfully updated $table_name<br />";
					if($table_name == $this->table_name) $this->set_props($props);
					return true;
				}
			}
		}
		return false;
	}

	function delete($id=0, $table_name='', $primary_key='') {
		global $DB;
		if(empty($primary_key) && !empty($this->primary_key)) $primary_key = 
$this->primary_key;
		if(empty($id) && !empty($this->$primary_key)) $id = $this->$primary_key;
		if(empty($table_name) && !empty($this->table_name)) $table_name = 
$this->table_name;
		if(!empty($id)) {
			$sql = '';
			if(is_array($id)) {
				foreach($id as $key => $value) $sql .= ((empty($sql))?"":" AND 
").$key."='".mysql_escape_string($value)."'";
			} else {
				$sql = $primary_key." = '".$id."'";
			}
			$sql = "DELETE FROM " . $table_name . " WHERE ".$sql;

			# echo $sql;

			if($this->debug) echo $sql."<br />";
			if($this->debug <= 1) {
				if($DB->delete($sql)) {
					return true;
				}
			}
		}
		return false;
	}


	function load($id) {

		global $DB;

		if($id==0 || !preg_match("/^[0-9]+$/", $id)) return false;

			// Load simple database fields
		$row = $DB->single_row("SELECT *
								FROM $this->table_name
								WHERE $this->primary_key = '$id'");

		#pre_echo($db_listing);

		if(empty($row[$this->primary_key])) return false;
		$this->set_props($row);
		$primary_key = $this->primary_key;
		return $this->$primary_key;

	}


}
?>
