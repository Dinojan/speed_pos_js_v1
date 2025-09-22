<?php
class ModelProduct extends Model
{
	public function addproduct($data)
	{
		$statement = $this->db->prepare("INSERT INTO `product` ( `p_code`, `p_name`, `material`, `c_id`, `s_id`, `wgt`, `cost`, `status`,  `qty`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$statement->execute(array($data['p_code'], $data['p_name'], $data['material'], $data['c_id'],  $data['s_id'],  $data['wgt'],  $data['cost'], $data['sts'],  $data['qty']));

		return $this->db->lastInsertId();
	}

	public function editproduct($id, $data)
	{
		$statement = $this->db->prepare("UPDATE `product` SET p_code = ?,  `p_name` =?, `material` = ?, `c_id` =?, s_id = ?, `wgt` =?, `cost` =?, `status` =?, `qty` =?  WHERE `id` = ?");
		$statement->execute(array($data['p_code'],  $data['p_name'], $data['material'],  $data['c_id'],  $data['s_id'],  $data['wgt'],  $data['cost'], $data['sts'],  $data['qty'], $id));

		return $id;
	}

	public function updateProductStatus($id, $sts)
	{
		$statement = $this->db->prepare('SELECT status, prv_status FROM product WHERE id = ?');
		$statement->execute(array($id));
		$product = $statement->fetch(PDO::FETCH_ASSOC);

		$new_status = 0;
		if ($product['status'] == 2) {
			$new_status = $product['prv_status'];
		} else {
			$new_status = $sts;
		}

		$statement = $this->db->prepare("UPDATE product SET `status` = ?, prv_status = ? WHERE id = ?");
		$statement->execute([$new_status, $product['status'], $id]);
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

	// public function getProductsCategoryWise($c_id = null, $sts = 0)
	// {
	// 	$products = [];

	// 	// Prepare WHERE clause and execute params
	// 	if ($c_id == null) {
	// 		$where = "WHERE p.status = ?";
	// 		$execute = [$sts];
	// 	} else {
	// 		$where = "WHERE p.c_id = ? AND p.status = ?";
	// 		$execute = [$c_id, $sts];
	// 	}

	// 	// Get products for current category
	// 	$stmt = $this->db->prepare("SELECT p.*, mp.price AS material_price, mp.m_name AS material_name FROM product p LEFT JOIN material_price mp ON mp.m_id = p.material AND mp.id = (SELECT id FROM material_price WHERE m_id = p.material ORDER BY id DESC LIMIT 1) $where");
	// 	$stmt->execute($execute);
	// 	$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// 	if ($c_id !== null) {
	// 		$stmt = $this->db->prepare("SELECT id FROM category WHERE p_id = ?");
	// 		$stmt->execute([$c_id]);
	// 		$childCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// 		foreach ($childCategories as $child) {
	// 			// Recursive call to get products of child category
	// 			$childProducts = $this->getProductsCategoryWise($child['id'], $sts);
	// 			$products = array_merge($products, $childProducts);
	// 		}
	// 	}

	// 	return $products;
	// }

	// public function getProductsBySearch($barcode = null, $name_or_barcode = null, $sts = 0)
	// {
	// 	$products = [];

	// 	// Prepare WHERE clause and execute params
	// 	if ($barcode != null && $name_or_barcode == null) {
	// 		$where = "WHERE p.p_code = ? AND p.status = ?";
	// 		$execute = [$barcode, $sts];
	// 	} else {
	// 		$where = "WHERE (p.p_name LIKE ? OR p.p_code LIKE ?) AND p.status = ?";
	// 		$searchTerm = "%$name_or_barcode%";
	// 		$execute = [$searchTerm, $searchTerm, $sts];
	// 	}
	// 	// Get products for current category
	// 	$stmt = $this->db->prepare("SELECT p.*, mp.price AS material_price, mp.m_name AS material_name FROM product p LEFT JOIN material_price mp ON mp.m_id = p.material AND mp.id = (SELECT id FROM material_price WHERE m_id = p.material ORDER BY id DESC LIMIT 1) $where");
	// 	$stmt->execute($execute);
	// 	$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// 	return $products;
	// }

	public function getProductsCategoryWise($c_id = null, $sts = 0)
	{
		$products = [];

		// Prepare WHERE clause
		if ($c_id === null) {
			$where = "WHERE status = ?";
			$execute = [$sts];
		} else {
			$where = "WHERE c_id = ? AND status = ?";
			$execute = [$c_id, $sts];
		}

		// Fetch products
		$stmt = $this->db->prepare("SELECT * FROM product $where");
		$stmt->execute($execute);
		$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// Fetch latest material_price and name for each product
		foreach ($products as &$product) {
			if ($product['material'] == 2 && in_array($product['karate'], [22, 24])) {
				// For m_id 2 with karate 22 or 24
				$stmt = $this->db->prepare("
                SELECT m_name, price FROM material_price
                WHERE m_id = ? AND karate = ?
                ORDER BY created_at DESC LIMIT 1
            ");
				$stmt->execute([$product['material'], $product['karate']]);
			} else {
				// For other materials
				$stmt = $this->db->prepare("
                SELECT m_name, price FROM material_price
                WHERE m_id = ?
                ORDER BY created_at DESC LIMIT 1
            ");
				$stmt->execute([$product['material']]);
			}

			$materialData = $stmt->fetch(PDO::FETCH_ASSOC);
			$product['material_name'] = $materialData['m_name'];
			$product['material_price'] = $materialData['price'];
		}

		// Recursive fetch child categories
		if ($c_id !== null) {
			$stmt = $this->db->prepare("SELECT id FROM category WHERE p_id = ?");
			$stmt->execute([$c_id]);
			$childCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

			foreach ($childCategories as $child) {
				$childProducts = $this->getProductsCategoryWise($child['id'], $sts);
				$products = array_merge($products, $childProducts);
			}
		}

		return $products;
	}


	public function getProductsBySearch($barcode = null, $name_or_barcode = null, $sts = 0)
	{
		$products = [];

		if ($barcode != null && $name_or_barcode == null) {
			$where = "WHERE p_code = ? AND status = ?";
			$execute = [$barcode, $sts];
		} else {
			$where = "WHERE (p_name LIKE ? OR p_code LIKE ?) AND status = ?";
			$searchTerm = "%$name_or_barcode%";
			$execute = [$searchTerm, $searchTerm, $sts];
		}

		$stmt = $this->db->prepare("SELECT * FROM product $where");
		$stmt->execute($execute);
		$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// Fetch latest material_price and name for each product
		foreach ($products as &$product) {
			if ($product['material'] == 2 && in_array($product['karate'], [22, 24])) {
				// For m_id 2 with karate 22 or 24
				$stmt = $this->db->prepare("
                SELECT m_name, price FROM material_price
                WHERE m_id = ? AND karate = ?
                ORDER BY created_at DESC LIMIT 1
            ");
				$stmt->execute([$product['material'], $product['karate']]);
			} else {
				// For other materials
				$stmt = $this->db->prepare("
                SELECT m_name, price FROM material_price
                WHERE m_id = ?
                ORDER BY created_at DESC LIMIT 1
            ");
				$stmt->execute([$product['material']]);
			}

			$materialData = $stmt->fetch(PDO::FETCH_ASSOC);
			$product['material_name'] = $materialData['m_name'];
			$product['material_price'] = $materialData['price'];
		}


		return $products;
	}

	// public function get_products_count($sts = 0)
	// {
	// 	$statement = $this->db->prepare("SELECT id FROM product WHERE status = ?");
	// 	$statement->execute([$sts]);
	// 	$count = $statement->rowCount();
	// }

	// public function getStockCheckingHistory($product_id) {
	// 	$stmt = $this->db->prepare("SELECT * FROM product WHERE id = ?");
	// 	$stmt->execute([$product_id]);
	// 	$stock = $stmt->fetch(PDO::FETCH_ASSOC);

	// 	$stmt = $this->db->prepare("SELECT created_at, checked_by FROM stock_checking WHERE p_id = ? ORDER BY created_at DESC");
	// 	$stmt->execute([$product_id]);
	// 	$history = $stmt->fetch(PDO::FETCH_ASSOC);

	// 	return $stock + 
	// }
}
