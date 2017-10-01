<?php
/**
* PURPOSE: A static class that will be the parent of most objects
*	dont forget to require the database object
*/
require_once(LIB_PATH.DS.'database.php');

class DatabaseObject
{
	protected static $table_name; // required for child class with database table name as value
	protected static $db_fields = array(); // required as well for child class with field names as array values
	
	public static function FindAll()
	{
		// the name speaks for itself
		return static::FindBySql("SELECT * FROM ".static::$table_name);
	}

	public static function FindById($id=0)
	{
		global $database;
		$result_array = static::FindBySql("SELECT * FROM ".static::$table_name." WHERE id=".$database->EscapeValue($id)." LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function FindBySql($sql='')
	{
		global $database;
		$result_set = $database->Query($sql);
		$object_array = array();
		while ($row = $database->FetchArray($result_set)) {
			$object_array[] = static::Instantiate($row);
		}
		return $object_array;
	}

	public static function FindByField($field_name, $value)
	{	
		global $database;
		$result_set = static::FindBySql("SELECT * FROM ".static::$table_name." WHERE ".$database->EscapeValue($field_name)."= '".$database->EscapeValue($value)."'");
		$object_array = array();
		while ($row = $database->FetchArray($result_set)) {
			$object_array[] = static::Instantiate($row);
		}
		return $object_array;
	}

	public static function CountAll()
	{
		global $database;
		$sql = "SELECT COUNT(*) FROM ".static::$table_name;
		$result_set = $database->Query($sql);
		$row = $database->FetchArray($result_set);
		return array_shift($row);
	}

	private static function Instantiate($record)
	{
		$object = new static;

		foreach ($record as $attribute => $value) {
			if ($object->HasAttribute($attribute)) {
				$object->$attribute = $value;
			}
		}
		return $object;
	}

	private function HasAttribute($attribute)
	{
		// check to see if key exists
		return array_key_exists($attribute, $this->Attributes());
	}

	protected function Attributes()
	{
		// return an array of attribute namees and their values
		$attributes = array();
		foreach (static::$db_fields as $field) {
			if (property_exists($this, $field)) {
				$attributes[$field] = $this->$field;
			}
		}
		return $attributes;
	}

	protected function SanitizedAttributes()
	{
		global $database;
		$clean_attributes = array();
		foreach ($this->Attributes() as $key => $value) {
			$clean_attributes[$key] = $database->EscapeValue($value);
		}
		return $clean_attributes;
	}

	public function Save()
		{
			return isset($this->id) ? $this->Update() : $this->Create();
		}	

	public function Create()
		{
			global $database;

			$attributes = $this->SanitizedAttributes();
			$sql = "INSERT INTO ".static::$table_name." (";
			$sql .= join(", ", array_keys($attributes));
			$sql .= ") VALUES ('";
			$sql .= join("', '", array_values($attributes));
			$sql .="')";
			if ($database->Query($sql)) {
				$this->id = $database->InsertId();
				return true;
			}else{
				return false;
			}
		}

	public function Update()
		{
			global $database;
			$attributes = $this->SanitizedAttributes();
			$attribute_pairs = array();
			foreach ($attributes as $key => $value) {
				$attribute_pairs[] = "{$key}='{$value}'";
			}
			$sql = "UPDATE ".static::$table_name." SET ";
			$sql .= join(", ", $attribute_pairs);
			$sql .= " WHERE id=".$database->EscapeValue($this->id);
			$database->Query($sql);
			return ($database->AffectedRows() == 1) ? true : false;
		}	

	public function Delete()
	{
		global $database;
		$sql = "DELETE FROM ".static::$table_name;
		$sql .= " WHERE id=".$database->EscapeValue($this->id);
		$sql .= " LIMIT 1";
		$database->Query($sql);
		return ($database->AffectedRows() == 1) ? true : false;

		// NOTE: nag exist pa rin ang instance ng user kahit wala na sa database
		// pwedeng gamit: echo $user->name . " was deleted";
		// Hindi na pwedeng mag $user->Update kasi wala na sa db.
	}
}
?>