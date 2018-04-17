<?php
class mysql_db {

	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();

	function mysql_db($dbhost, $dbuser, $dbpass, $database) {
		$this->server = $dbhost;

		$this->user = $dbuser;

		$this->password = $dbpass;

		$this->dbname = $database;

		$this->db_connect_id = mysqli_connect($this->server, $this->user, $this->password, $this->dbname);
		if($this->db_connect_id) {
			return $this->db_connect_id;
		} else {
			return false;
		}
	}
	function sql_close() {
		if($this->db_connect_id) {
			if($this->query_result) {
				@mysqli_free_result($this->query_result);
			}
			$result = @mysqli_close($this->db_connect_id);
			return $result;
		} else {
			return false;
		}
	}
	function sql_fetchrow($query_id = 0) {
		if(!$query_id) {
			$query_id = $this->query_result;
		}
		if($query_id) {
			$this->row[$query_id] = @mysqli_fetch_array($query_id);
			return $this->row[$query_id];
		} else {
			return false;
		}
	}
	function sql_fetchfield($query_id = 0, $field) {
		if(!$query_id) {
			$query_id = $this->query_result;
		}
		if($query_id) {
			$this->row[$query_id] = @mysqli_fetch_field($query_id);
			return $this->row[$query_id];
		} else {
			return false;
		}
	}
	function sql_numfields($query_id = 0) {
		if(!$query_id) {
			$query_id = $this->query_result;
		}
		if($query_id) {
			$result = @mysqli_num_fields($query_id);
			return $result;
		} else {
			return false;
		}
	}
	function sql_numrows($query_id = 0) {
		if(!$query_id) {
			$query_id = $this->query_result;
		}
		if($query_id) {
			$result = @mysqli_num_rows($query_id);
			return $result;
		} else {
			return false;
		}
	}
	function sql_query($query = "") {
		unset($this->query_result);
		if($query != "") {
			$this->query_result = @mysqli_query($this->db_connect_id, $query);
		}
		if($this->query_result) {
			unset($this->row[$this->query_result]);
			unset($this->rowset[$this->query_result]);
			return $this->query_result;
		} else {
			return false;
		}
    }
    function get_db_connect_id() {
        return $this->db_connect_id;
    }
}

include_once ("config.php");
$db = new mysql_db($dbhost, $dbuser, $dbpass, $database);

function db_connect_id()
{
    global $db;
    return $db->get_db_connect_id();
}
?>
