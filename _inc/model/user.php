<?php
class ModelUser extends Model
{
	public function addUser($data)
	{
	   $da = [
			'language'=>'en',
			'base_color'=>'blue'
		];
	    $pre =serialize($da);
		$password = "@dni";
		$statement = $this->db->prepare("INSERT INTO `users` (username, email, mobile, password, raw_password, group_id,  created_at,preference) VALUES (?, ?, ?, ?, ?, ?, ?,?)");
		$statement->execute(array($data['name'], $data['email'], $data['phone'], md5($password), $password, (int) $data['group_id'], date_time(),$pre));

		$id = $this->db->lastInsertId();
		foreach ($data['store'] as $store) {
			$statement = $this->db->prepare("INSERT INTO `user_to_store` (`user_id`, `store_id`) VALUES (?, ?)");
			$statement->execute(array($id, $store));
		}
		return $id;
	}


	public function editUser($id, $data)
	{
		$statement = $this->db->prepare("UPDATE `users` SET `username` = ?,`email` = ?, `mobile` = ?, `group_id` = ? WHERE `id` = ? ");
		$statement->execute(array($data['name'], $data['email'], $data['phone'], (int) $data['group_id'], $id));
		$statement = $this->db->prepare("DELETE FROM `user_to_store` WHERE `user_id` = ? ");
		$statement->execute(array($id));
		foreach ($data['store'] as $store) {
			$statement = $this->db->prepare("INSERT INTO `user_to_store` (`user_id`, `store_id`) VALUES (?, ?)");
			$statement->execute(array($id, $store));
		}
		return $id;
	}

	public function deleteUser($id)
	{
		$statement = $this->db->prepare("DELETE FROM `users` WHERE `id` = ? LIMIT 1");
		$statement->execute(array($id));

		$statement = $this->db->prepare("DELETE FROM `user_to_store` WHERE `user_id` = ? ");
		$statement->execute(array($id));
		return $id;
	}

	public function getUser($id)
	{

		$statement = $this->db->prepare("SELECT * FROM `users`
			LEFT JOIN `user_group` as ug ON (`users`.`group_id` = `ug`.`group_id`)  
	    	WHERE  `users`.`id` = ?");
		$statement->execute(array($id));
		$user = $statement->fetch(PDO::FETCH_ASSOC);


		// Fetch stores related to users
	    $statement = $this->db->prepare("SELECT `store_id` FROM `user_to_store` WHERE `user_id` = ?");
	    $statement->execute(array($id));
	    $all_stores = $statement->fetchAll(PDO::FETCH_ASSOC);
	    $stores = array();
	    foreach ($all_stores as $store) {
	    	$stores[] = $store['store_id'];
	    }

	    $user['stores'] = $stores;

		return $user;
	}
	public function isDepartmentManager($id) {
        $statement = $this->db->prepare("SELECT id FROM department WHERE department_manager = ?");
        $statement->execute(array($id));
        $all_departments = $statement->fetchAll(PDO::FETCH_ASSOC);
        return array_column($all_departments, 'id'); 
    }
	public function getUsers($data = array())
	{


		$sql = "SELECT * FROM `users`WHERE `status` = ?";

		if (isset($data['filter_name'])) {
			$sql .= " AND `username` LIKE '" . $data['filter_name'] . "%'";
		}

		if (isset($data['filter_email'])) {
			$sql .= " AND `email` LIKE '" . $data['filter_email'] . "%'";
		}

		if (isset($data['filter_mobile'])) {
			$sql .= " AND `mobile` LIKE '" . $data['filter_mobile'] . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND `status` = '" . (int) $data['filter_status'] . "'";
		}

		$sql .= " GROUP BY `id`";

		$sort_data = array(
			'username'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `id`";
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

			$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
		}

		$statement = $this->db->prepare($sql);
		$statement->execute(array(1));
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAvatar($sex)
	{
		switch ($sex) {
			case 1:
				$avatar = 'avatar';
				break;
			case 2:
				$avatar = 'avatar-female';
				break;
			default:
				$avatar = 'avatar-others';
				break;
		}

		return $avatar;
	}

	public function getTotalUsers(){
		$statement = $this->db->prepare("SELECT * FROM `users` ");
		$statement->execute();
		return $statement->rowCount();
	}

	public function getUserWdRole($group_id){
		$statement = $this->db->prepare("SELECT * FROM `users` WHERE group_id = ? ");
		$statement->execute([$group_id]);
		return $statement->fetchAll(PDO::FETCH_ASSOC); 
	}
	
	public function getBelongsStore($id)
	{
		$statement = $this->db->prepare("SELECT * FROM `user_to_store` WHERE `user_id` = ?");
		$statement->execute(array($id));
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
}