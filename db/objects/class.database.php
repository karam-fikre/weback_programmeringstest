<?php
/**
* <b>Database Connection</b> class.
* @author Php Object Generator
* @version 3.0 / PHP5.1
* @see http://www.phpobjectgenerator.com/
* @copyright Free for personal & commercial use. (Offered under the BSD license)
*/
 Class Database
{
	static $queryLog = FALSE;
	static $queryLogFile = FALSE;
	static $exceptions = FALSE;


	private function __construct()
	{
		$databaseName = $GLOBALS['configuration']['db'];
		$driver = $GLOBALS['configuration']['pdoDriver'];
		$serverName = $GLOBALS['configuration']['host'];
		$databaseUser = $GLOBALS['configuration']['user'];
		$databasePassword = $GLOBALS['configuration']['pass'];
		$databasePort = $GLOBALS['configuration']['port'];

		self::$queryLogFile = self::$queryLog
			? fopen($GLOBALS['configuration']['queryLog'], 'w+')
			: FALSE;
		
		if (!isset($this->connection))
		{
			$this->connection = new PDO($driver.':host='.$serverName.';port='.$databasePort.';dbname='.$databaseName, $databaseUser, $databasePassword);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		if (!$this->connection)
		{
			throw new Exception('I cannot connect to the database. Please edit configuration.php with your database configuration.');
		}
	}

	public static function Connect()
	{
		static $database = null;
		if (!isset($database))
		{
			$database = new Database();
		}
		return $database->connection;
	}

	public static function Reader($query, $connection)
	{
		try
		{
			$result = $connection->Query($query);
			if (self::$queryLog) self::logQuery($query);
		}
		catch(PDOException $e)
		{
			if (self::$exceptions) {
				throw $e;
			} else {
				return false;
			}
		}
		return $result;
	}

	public static function Read($result)
	{
		try
		{
			return $result->fetch();
		}
		catch (PDOException $e)
		{
			if (self::$exceptions) {
				throw $e;
			} else {
				return false;
			}
		}
	}

	public static function NonQuery($query, $connection)
	{
		try
		{
			$r = $connection->query($query);
			if (self::$queryLog) self::logQuery($query);
			if ($r === false)
			{
				return 0;
			}
			return $r->rowCount();
		}
		catch (PDOException $e)
		{
			if (self::$exceptions) {
				throw $e;
			} else {
				return false;
			}
		}

	}

	public static function Query($query, $connection)
	{
		try
		{
			$i = 0;
			$r = $connection->query($query);
			if (self::$queryLog) self::logQuery($query);
			foreach ($r as $row)
			{
				$i++;
			}
			return  $i;
		}
		catch (PDOException $e)
		{
			if (self::$exceptions) {
				throw $e;
			} else {
				return false;
			}
		}
	}

	public static function InsertOrUpdate($query, $connection)
	{
		try
		{
			$r = $connection->query($query);
			if (self::$queryLog) self::logQuery($query);
			return $connection->lastInsertId();
		}
		catch (PDOException $e)
		{
			if (self::$exceptions) {
				throw $e;
			} else {
				return false;
			}
		}
	}

	protected static function logQuery($query) {
		fwrite(self::$queryLogFile, $query . "\n");
	}
}
?>