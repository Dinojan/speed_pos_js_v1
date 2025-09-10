<?php
class ModelCustomer extends Model
{
	public function addcustomer($data)
	{
		$statement = $this->db->prepare("INSERT INTO `customer` ( `c_name`, `c_mobile`, `c_address`) VALUES (?, ?, ?)");
		$statement->execute(array($data['c_name'], $data['c_mobile'], $data['c_address']));

		return $this->db->lastInsertId();
	}

	public function editcustomer($id, $data)
	{
		$statement = $this->db->prepare("UPDATE `customer` SET c_name = ?,  `c_mobile` =?,`c_address` =?  WHERE `id` = ?");
		$statement->execute(array($data['c_name'],  $data['c_mobile'],  $data['c_address'], $id));

		return $id;
	}

	public function editCustomerTotalDue($id, $amount, $type = 0)
	{
		if ($type == 0) {
			$transaction_query = "total_due = total_due + ?";
		} else {
			$transaction_query = "total_due = total_due - ?";
		}
		$statement = $this->db->prepare("UPDATE `customer` SET $transaction_query WHERE `id` = ?");
		$statement->execute(array($amount, $id));

		return $id;
	}

	public function updateCustomerStatus($id, $sts)
	{
		$statement = $this->db->prepare("UPDATE customer SET `status` = ? WHERE id =?");
		$statement->execute([$sts, $id]);
		return $id;
	}

	public function deletecustomer($id)
	{
		$statement = $this->db->prepare("DELETE FROM `customer` WHERE `id` = ? LIMIT 1");
		$statement->execute(array($id));

		return $id;
	}

	public function getcustomer($id)
	{
		$statement = $this->db->prepare("SELECT * FROM `customer` WHERE `id` = ?");
		$statement->execute(array($id));
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function getcustomers($sts)
	{
		$statement = $this->db->prepare("SELECT * FROM `customer` WHERE status  = '$sts'");
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
}
