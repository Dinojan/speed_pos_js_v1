<?php 
class ModelCategory extends Model 
{
    public function addCategory($data) 
	{
    	$statement = $this->db->prepare("INSERT INTO `category` (c_name,p_id,sts) VALUES (?,?,?)");
    	$statement->execute(array($data['c_name'],$data['p_id'],1));
    
    	return $this->db->lastInsertId();    
	}

	public function editCategory($id, $data) 
	{    	
    	$statement = $this->db->prepare("UPDATE `category` SET c_name = ?,  `p_id` =? WHERE `id` = ?");
    	$statement->execute(array($data['c_name'],$data['p_id'],$id));
    
    	return $id;
	}

	public function deleteCategory($id) 
	{    	
    	$statement = $this->db->prepare("DELETE FROM `category` WHERE `id` = ? LIMIT 1");
    	$statement->execute(array($id));

        return $id;
	}

	public function getCategory($id) 
	{
	    $statement = $this->db->prepare("SELECT * FROM `category` WHERE `id` = ?");
  		$statement->execute(array($id));
  		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function getCategories($sts) 
	{
        $where = ' 1=1';
        if($sts != '') {
            $where .= ' AND `sts` = ' . $sts;
        }
		$statement = $this->db->prepare("SELECT * FROM `category` WHERE $where");
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}


}