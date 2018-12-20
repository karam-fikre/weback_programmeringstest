<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `todocomments` (
	`todocommentsId` int(11) NOT NULL auto_increment,
	`todoId` INT NOT NULL,
	`comments` VARCHAR(255) NOT NULL, PRIMARY KEY  (`todocommentsId`)) ENGINE=MyISAM;
*/

/**
* <b>todoComments</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.2 / PHP5.1
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5.1&wrapper=pog&objectName=todoComments&attributeList=array+%28%0A++0+%3D%3E+%27todoId%27%2C%0A++1+%3D%3E+%27comments%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27INT%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A%29
*/
include_once('class.pog_base.php');
class todoComments extends POG_Base
{
	public $todocommentsId = '';

	/**
	 * @var INT
	 */
	public $todoId;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $comments;
	
	public $pog_attribute_type = array(
		"todocommentsId" => array('db_attributes' => array("NUMERIC", "INT")),
		"todoId" => array('db_attributes' => array("NUMERIC", "INT")),
		"comments" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		);
	public $pog_query;
	
	
	/**
	* Getter for some private attributes
	* @return mixed $attribute
	*/
	public function __get($attribute)
	{
		if (isset($this->{"_".$attribute}))
		{
			return $this->{"_".$attribute};
		}
		else
		{
			return false;
		}
	}
	
	function __construct($todoId='', $comments='')
	{
		$this->todoId = $todoId;
		$this->comments = $comments;
	}
	
	
	/**
	* Gets object from database
	* @param integer $todocommentsId 
	* @return object $todoComments
	*/
	function Get($todocommentsId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `todocomments` where `todocommentsId`='".intval($todocommentsId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->todocommentsId = $row['todocommentsId'];
			$this->todoId = $this->Unescape($row['todoId']);
			$this->comments = $this->Unescape($row['comments']);
		}
		return $this;
	}
	

	/*get the comments for the spesific ToDo */
	function GetByTodoId($todoId)
	{
		$todocommentsList = Array();
		$connection = Database::Connect();
		$this->pog_query = "SELECT * FROM `todoComments` WHERE `todoId` IN (".$todoId.") ";
		
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$todocomments = new $thisObjectName();
			$todocomments->todocommentsId = $row['todocommentsId'];
			$todocomments->todoId = $this->Unescape($row['todoId']);
			$todocomments->comments = $this->Unescape($row['comments']);
			$todocommentsList[] = $todocomments;
		}
		return $todocommentsList;
	}
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $todocommentsList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `todocomments` ";
		$todocommentsList = Array();
		if (sizeof($fcv_array) > 0)
		{
			$this->pog_query .= " where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$this->pog_query .= " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) != 1)
					{
						$this->pog_query .= " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET')
					{
						if ($GLOBALS['configuration']['db_encoding'] == 1)
						{
							$value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : "'".$fcv_array[$i][2]."'";
							$this->pog_query .= "BASE64_DECODE(`".$fcv_array[$i][0]."`) ".$fcv_array[$i][1]." ".$value;
						}
						else
						{
							$value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$this->Escape($fcv_array[$i][2])."'";
							$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
						}
					}
					else
					{
						$value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$fcv_array[$i][2]."'";
						$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
					}
				}
			}
		}
		if ($sortBy != '')
		{
			if (isset($this->pog_attribute_type[$sortBy]['db_attributes']) && $this->pog_attribute_type[$sortBy]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$sortBy]['db_attributes'][0] != 'SET')
			{
				if ($GLOBALS['configuration']['db_encoding'] == 1)
				{
					$sortBy = "BASE64_DECODE($sortBy) ";
				}
				else
				{
					$sortBy = "$sortBy ";
				}
			}
			else
			{
				$sortBy = "$sortBy ";
			}
		}
		else
		{
			$sortBy = "todocommentsId";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$todocomments = new $thisObjectName();
			$todocomments->todocommentsId = $row['todocommentsId'];
			$todocomments->todoId = $this->Unescape($row['todoId']);
			$todocomments->comments = $this->Unescape($row['comments']);
			$todocommentsList[] = $todocomments;
		}
		return $todocommentsList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $todocommentsId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$rows = 0;
		if ($this->todocommentsId!=''){
			$this->pog_query = "select `todocommentsId` from `todocomments` where `todocommentsId`='".$this->todocommentsId."' LIMIT 1";
			$rows = Database::Query($this->pog_query, $connection);
		}
		if ($rows > 0)
		{
			$this->pog_query = "update `todocomments` set 
			`todoId`='".$this->Escape($this->todoId)."', 
			`comments`='".$this->Escape($this->comments)."' where `todocommentsId`='".$this->todocommentsId."'";
		}
		else
		{
			$this->pog_query = "insert into `todocomments` (`todoId`, `comments` ) values (
			'".$this->Escape($this->todoId)."', 
			'".$this->Escape($this->comments)."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->todocommentsId == "")
		{
			$this->todocommentsId = $insertId;
		}
		return $this->todocommentsId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $todocommentsId
	*/
	function SaveNew($comment,$todoId)
	{
		$this->todocommentsId = '';
		$this->todoId= $todoId;
		$this->comments = $comment;
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `todocomments` where `todocommentsId`='".$this->todocommentsId."'";
		return Database::NonQuery($this->pog_query, $connection);
	}
	
	
	/**
	* Deletes a list of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param bool $deep 
	* @return 
	*/
	function DeleteList($fcv_array)
	{
		if (sizeof($fcv_array) > 0)
		{
			$connection = Database::Connect();
			$pog_query = "delete from `todocomments` where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$pog_query .= " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) !== 1)
					{
						$pog_query .= " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET')
					{
						$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$this->Escape($fcv_array[$i][2])."'";
					}
					else
					{
						$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
					}
				}
			}
			return Database::NonQuery($pog_query, $connection);
		}
	}
}
?>