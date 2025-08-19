<?php 
class ModelProduct extends Model 
{
    public function addproduct($data) 
	{
    	$statement = $this->db->prepare("INSERT INTO `product` ( `p_code`, `p_name`, `c_id`, `s_id`, `wgt`, `cost`,  `qty`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    	$statement->execute(array(  $data['p_code'], $data['p_name'], $data['c_id'],  $data['s_id'],  $data['wgt'],  $data['cost'],  $data['qty']));
    
    	return $this->db->lastInsertId();    
	}

	public function editproduct($id, $data) 
	{    	
    	$statement = $this->db->prepare("UPDATE `product` SET p_code = ?,  `p_name` =?,`c_id` =?, s_id = ?, `wgt` =?, `cost` =?, `qty` =?  WHERE `id` = ?");
    	$statement->execute(array($data['p_code'],  $data['p_name'],  $data['c_id'],  $data['s_id'],  $data['wgt'],  $data['cost'],  $data['qty'],$id));
    
    	return $id;
	}

	public function deleteproduct($id) 
	{    	
    	$statement = $this->db->prepare("DELETE FROM `product` WHERE `id` = ? LIMIT 1");
    	$statement->execute(array($id));

        return $id;
	}

	public function getproduct($id) 
	{
	    $statement = $this->db->prepare("SELECT * FROM `product` WHERE `id` = ?");
  		$statement->execute(array($id));
  		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function getproducts($data = array()) 
	{
		$statement = $this->db->prepare("SELECT * FROM `product`");
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}


}