<?php
require_once(LIB_PATH.DS.'database.php');

/**
* This is a child class of Class DatabaseObject
* It involves methods relating to application user
*/
class User extends DatabaseObject
{
	protected static $table_name="users";
	protected static $db_fields = array('id', 'username', 'password', 'first_name', 'last_name', 'email','user_type');

	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;
	public $email;
	public $user_type;

	public function FullName()
		{
			if (isset($this->first_name) && isset($this->last_name)) {
				return $this->first_name . " " . $this->last_name;
			}else{
				return "";
			}
		}	
	public static function Authenticate($username="", $password="")
	{
		global $database;
		$username = $database->EscapeValue($username);
		$password = $database->EscapeValue($password);

		$sql = "SELECT * FROM users";
		$sql .= " WHERE username = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "LIMIT 1";
		$result_array = self::FindBySql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function VerifyUser($username="", $password="")
	{
		global $database;
		$user = self::Authenticate($username, $password);

		$sql = "SELECT * FROM users";
		$sql .= " WHERE username = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "LIMIT 1";
		$result_array = self::FindBySql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}


	public static function NoEdit($user_type)
	{
		return ($user_type == "Administrator") ? " disabled" : "";
	}
}
?>
