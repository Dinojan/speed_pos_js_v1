<?php 
class ModelOrder extends Model 
{
    public function addorder($ref ,$cid,$data) 
	{
    	$statement = $this->db->prepare("INSERT INTO `orders` (`ref_no`,`cus_id`,`cus_name`,`cus_mobile`,`cus_address`,`order_details`,`total_amt`,`advance_amt`) VALUES (?, ?, ?, ?, ? ,? ,? ,?)");
    	$statement->execute(array($ref,$cid, $data['cus_name'], $data['cus_mobile'], $data['cus_address'], $data['order_details'], $data['total_amt'],$data['advance_amt']));
    
    	return $this->db->lastInsertId();    
	}

	public function editorder($id, $data) 
	{    	
    	$statement = $this->db->prepare("UPDATE `orders` SET cus_name = ?,  `cus_mobile` =?,`cus_address` =? ,`order_details` =? ,`total_amt` =? ,`advance_amt` =?   WHERE `id` = ?");
    	$statement->execute(array($data['cus_name'],  $data['cus_mobile'],  $data['cus_address'], $data['order_details'], $data['total_amt'], $data['advance_amt'],$id));
    
    	return $id;
	}

    public function updateOrderStatus($id,$sts){
        $statement = $this->db->prepare("UPDATE orders SET `status` = ? WHERE id =?");
        $statement->execute([$sts,$id]);
        return $id;
    }

	public function deleteorder($id) 
	{    	
    	$statement = $this->db->prepare("DELETE FROM `orders` WHERE `id` = ? LIMIT 1");
    	$statement->execute(array($id));

        return $id;
	}

	public function getorder($id) 
	{
	    $statement = $this->db->prepare("SELECT * FROM `orders` WHERE `id` = ?");
  		$statement->execute(array($id));
  		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function getorders($data = array()) 
	{
		$statement = $this->db->prepare("SELECT * FROM `orders`");
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}


}