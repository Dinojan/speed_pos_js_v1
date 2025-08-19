<?php 
class ModelSupplier extends Model 
{
    public function addSupplier($data) 
	{
    	$statement = $this->db->prepare("INSERT INTO `supplier` (s_name, s_mobile) VALUES (?, ?)");
    	$statement->execute(array($data['s_name'],  $data['s_mobile']));
    
    	return $this->db->lastInsertId();    
	}

	public function editSupplier($id, $data) 
	{    	
    	$statement = $this->db->prepare("UPDATE `supplier` SET s_name = ?,  `s_mobile` =? WHERE `id` = ?");
    	$statement->execute(array($data['s_name'],  $data['s_mobile'], $id));
    
    	return $id;
	}

	public function deleteSupplier($id) 
	{    	
    	$statement = $this->db->prepare("DELETE FROM `supplier` WHERE `id` = ? LIMIT 1");
    	$statement->execute(array($id));

        return $id;
	}

	public function getSupplier($id) 
	{
	    $statement = $this->db->prepare("SELECT * FROM `supplier` WHERE `id` = ?");
  		$statement->execute(array($id));
  		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function getSuppliers($data = array()) 
	{
		$statement = $this->db->prepare("SELECT * FROM `supplier`");
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}


}