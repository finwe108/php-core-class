<?php
/**
* PURPOSE: A class to help work with sessions
*		primarily to manage loggin users in and out
* NOTE: avoid storing database related objects in sessions
*/
class Session
{
	private $logged_in = false;
	public $user_id;
	public $message;
	public $user_type;

	function __construct()
	{
		session_start();
		$this->CheckMessage();
		$this->CheckLogin();
		if ($this->logged_in) {
			// to do if user is logged in
		}else{
			// actions if user is not logged in
		}
	}

	public function IsLoggedIn()
	{
		return $this->logged_in;
	}

	public function Login($user)
	{
		// let database object find user based on username & password $database->authenticate($username,$password)
		if ($user) {
			$this->user_type = $_SESSION['user_type'] = $user->user_type;
			$this->user_id = $_SESSION['user_id'] = $user->id;
			$this->logged_in = true;
		}
	}

	public function Logout()
	{
		unset($_SESSION['user_id']);
		unset($_SESSION['user_type']);
		unset($this->user_id);
		unset($this->user_type);
		$this->logged_in = false;
	}

	public function Message($msg='')
	{
		if (!empty($msg)) {
			// set message
			$_SESSION['message'] = $msg;
		}else{
			// get message
			return $this->message;
		}
	}

	public function CheckLogin()
	{
		if (isset($_SESSION['user_id'])) {
			$this->user_id = $_SESSION['user_id'];
			$this->user_type = $_SESSION['user_type'];
			$this->logged_in = true;
		}else{
			unset($this->user_id);
			unset($this->user_type);
			$this->logged_in = false;
		}
	}

	public function CheckMessage()
	{
		// check if there is message in session
		if (isset($_SESSION['message'])) {
			// Add as attribute and erase the stored one
			$this->message = $_SESSION['message'];
			unset($_SESSION['message']);
		}else{
			$this->message = "";
		}
	}
}

$session = new Session();
$message = $session->Message();

?>