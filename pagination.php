<?php 
/**
* PURPOSE: A helper class for paginating records
* 		making it easy to read.
*/
class Pagination
{
	public $current_page;
	public $per_page;
	public $total_count;
	
	function __construct($page=1,$per_page=20,$total_count=0)
	{
		$this->current_page = (int)$page;
		$this->per_page = (int)$per_page;
		$this->total_count = (int)$total_count;
	}

	public function Offset()
	{
		// assuming 20 items per page
		// page 1 has an offset of 0 (1-1) * 20
		// page 2 has an offset of 20 (2-1) * 20
		// in other words, page 2 starts with item 21
		return ($this->current_page - 1) * $this->per_page;
	}

	public function TotalPages()
	{
		return ceil($this->total_count/$this->per_page);
	}

	public function PreviousPage()
	{
		return $this->current_page - 1;
	}

	public function NextPage()
	{
		return $this->current_page + 1;
	}

	public function HasPreviousPage()
	{
		return $this->PreviousPage() >= 1 ? true : false;
	}

	public function HasNextPage()
	{
		return $this->NextPage() <= $this->TotalPages() ? true : false;
	}
}
?>