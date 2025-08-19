<?php 
class ModelUsergroup extends Model 
{
    public function adduserGroup($data) 
	{
    	$statement = $this->db->prepare("INSERT INTO `user_group` (g_name) VALUES (?)");
    	$statement->execute(array($data['group_name']));
    
    	return $this->db->lastInsertId();    
	}

	public function edituserGroup($role_id, $data, $permission) 
	{    	
    	$statement = $this->db->prepare("UPDATE `user_group` SET g_name = ?,  `permission` =? WHERE `group_id` = ?");
    	$statement->execute(array($data['group_name'],  serialize($permission), $role_id));
    
    	return $role_id;
	}

	public function deleteuserGroup($role_id) 
	{    	
    	$statement = $this->db->prepare("DELETE FROM `user_group` WHERE `group_id` = ? LIMIT 1");
    	$statement->execute(array($role_id));

        return $role_id;
	}

	public function getuserGroup($role_id) 
	{
	    $statement = $this->db->prepare("SELECT * FROM `user_group` WHERE `group_id` = ?");
  		$statement->execute(array($role_id));
  		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function getUserGroups($data = array()) 
	{
		$sql = "SELECT * FROM `user_group` WHERE 1=1";

		if (isset($data['filter_name'])) {
			$sql .= " AND `g_name` LIKE '" . $data['filter_name'] . "%'";
		}

		$sql .= " GROUP BY group_id";

		$sort_data = array(
			'g_name'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY g_name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$statement = $this->db->prepare($sql);
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function totalUser($group_id)
	{
		$statement = $this->db->prepare("SELECT * FROM `users` WHERE  `group_id` = ?");
		$statement->execute(array( $group_id));
		return $statement->rowCount();

	}
}