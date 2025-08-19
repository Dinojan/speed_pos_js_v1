<?php 
class Store {
    private $registry;
	private $request;
	private $db;
	private $session;
	private $data;
    public function __construct($registry) {
        $this->registry = $registry;
        $this->request = $this->registry->get('request');
        $this->db = registry()->get('db');

		$this->session = registry()->get('session');

		if (!isset($this->session->data['store_id'])) {
			$this->session->data['store_id'] = 1;
		}

        if (isset($this->session->data['store_id'])) {

			$store_id = $this->session->data['store_id'];
            $statement = $this->db->prepare("SELECT * FROM `stores` WHERE `store_id` = ?");
			$statement->execute(array($store_id));
			$this->data = $statement->fetch(PDO::FETCH_ASSOC);

			if (isset($this->data['store_id'])) {
				$this->session->data['store_id'] = $this->data['store_id'];
			}
		}
    }

    public function openTheStore($store_id = 1) 
	{
		$store_id = $store_id ? (int)$store_id : 1;
        $statement = $this->db->prepare("SELECT * FROM `stores` WHERE `store_id` = ?");
		$statement->execute(array($store_id));
		$store = $statement->fetch(PDO::FETCH_ASSOC);
		if (isset($store['store_id'])) {
			unset($this->session->data['store_id']);
			$this->session->data['store_id'] = $store['store_id'];
		}
	}

	public function setStore($store_id)
	{
		$statement = $this->db->prepare("SELECT * FROM `stores` WHERE `store_id` = ?");
		$statement->execute(array($store_id));
		$this->data = $statement->fetch(PDO::FETCH_ASSOC);
	}
    public function getAll()
	{
		return $this->data;
	}

	public function get($key) 
	{
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}

	public function isMultiStore()
	{
		$statement = $this->db->prepare("SELECT * FROM `stores`");
		$statement->execute();

		return $statement->rowCount();
	}

}