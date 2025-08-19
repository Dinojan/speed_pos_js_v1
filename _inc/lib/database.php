<?php

class Database extends PDO
{
	public $log = NULL;
	public $db = NULL;
	public $statement = NULL;
	public $option = NULL;

	public function __construct($dsn, $username = null, $password = null, $driver_options = array())
	{
		// $this->log = new Log('sql.txt'); // Uncomment if you have a Log class

		$default_options = [
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		];

		$options = array_replace($default_options, $driver_options);
		parent::__construct($dsn, $username, $password, $options);
	}

	public function prepare($statement, $option = array())
	{
		$this->statement = $statement;
		$this->option = $option;
		$this->db = parent::prepare($this->statement, $this->option);
		return $this;
	}

	public function execute($args = null)
	{
		if (defined('SYNCHRONIZATION') && SYNCHRONIZATION) {
			if (
				(
					stripos($this->statement, 'INSERT') !== false ||
					stripos($this->statement, 'UPDATE') !== false ||
					stripos($this->statement, 'DELETE') !== false
				) &&
				stripos($this->statement, "UPDATE `users` SET `ip` = ? WHERE `id` = ?") === false
			) {
				if ($this->log) {
					$this->log->simplyWrite($this->statement . '|' . serialize($args));
				}
			}
		}

		return $this->db->execute($args);
	}

	public function fetch($constant = null)
	{
		return $this->db->fetch($constant);
	}

	public function fetchAll($constant = null)
	{
		return $this->db->fetchAll($constant);
	}

	public function rowCount()
	{
		return $this->db->rowCount();
	}

	public function lastInsertId($seqname = null)
	{
		return parent::lastInsertId($seqname);
	}
}
