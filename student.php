<?php
/**
* 
*/
class Student extends DatabaseObject
{
	protected static $table_name="students";
	protected static $db_fields = array('id', 'lrn', 'first_name', 'mid_name', 'last_name','ext_name', 'sex','birth_date', 'entry_year', 'stu_num');

	public $id;
	public $lrn;
	public $first_name;
	public $mid_name;
	public $last_name;
	public $ext_name;
	public $sex;
	public $birth_date;
	public $entry_year;
	public $stu_num;

// 	function __construct(argument)
// 	{
// 		# code...
// 	}

	// increment student number by one
	public static function GetNextId($entry_year)
	{
		global $database;
		$result_array = self::FindBySql("SELECT stu_num FROM ".self::$table_name." WHERE entry_year={$entry_year} ORDER BY stu_num DESC LIMIT 1");
		$result_set = !empty($result_array) ? array_shift($result_array) : false;
		$this->stu_num = $result_set->stu_num + 1;
	}

	public function StudentNumber()
	{	
		$n = str_pad($this->stu_num, 4,'0',STR_PAD_LEFT);
		return $this->entry_year."-".$n;
	}

}
?>