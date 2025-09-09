<?php
class ModelOrder extends Model
{
    public function addorder($ref, $cid, $data)
    {
        $statement = $this->db->prepare("INSERT INTO `orders` (`ref_no`,`cus_id`,`cus_name`,`cus_mobile`,`cus_address`,`order_details`,`total_amt`,`advance_amt`,total_paid,created_by) VALUES (?, ?, ?, ?, ?, ?, ? ,? ,? ,?)");
        $statement->execute(array($ref, $cid, $data['cus_name'], $data['cus_mobile'], $data['cus_address'], $data['order_details'], $data['total_amt'], $data['advance_amt'], $data['advance_amt'], user_id()));

        $id =  $this->db->lastInsertId();

        $due = $data['total_amt'] - $data['advance_amt'];
        $statement = $this->db->prepare("UPDATE `customer` SET `total_due` = total_due + ?  `id`= ?");
        $statement->execute(array($due, $cid));

        $paid_amount = $data['advance'];
        if ($account_id = 1 && $paid_amount > 0) {
            $ref_no = unique_transaction_ref_no();

            $source_id = 1;
            $title = 'Deposit for order advance';
            $details = 'Customer name: ' . $data['cus_name'];
            $image = 'NULL';
            $deposit_amount = $paid_amount;
            $transaction_type = 'deposit';

            $statement = $this->db->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, invoice_id, transaction_type, title, details, image, created_by) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->execute(array(store_id(), $account_id, $source_id, $ref_no, $ref, $transaction_type, $title, $details, $image, user_id()));
            $info_id = $this->db->lastInsertId();

            $statement = $this->db->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
            $statement->execute(array(store_id(), $info_id, $ref_no, $deposit_amount));

            $statement = $this->db->prepare("UPDATE `bank_account_to_store` SET `deposit` = `deposit` + {$deposit_amount} WHERE `store_id` = ? AND `account_id` = ?");
            $statement->execute(array(store_id(), $account_id));

            $statement = $this->db->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + {$deposit_amount} WHERE `id` = ?");
            $statement->execute(array($account_id));
        }

        return $id;
    }

    public function editorder($id, $data)
    {
        // Get old order
        $st = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $st->execute([$id]);
        $or = $st->fetch(PDO::FETCH_ASSOC);

        if (!$or) {
            return false; // order not found
        }

        // Calculate difference between old advance and new advance
        $oldAdvance = (float)$or['advance_amt'];
        $newAdvance = (float)$data['advance_amt'];

        $diff = $newAdvance - $oldAdvance;

        // Update order fields
        $statement = $this->db->prepare("
        UPDATE `orders` 
        SET cus_name      = ?,  
            cus_mobile    = ?, 
            cus_address   = ?, 
            order_details = ?, 
            total_amt     = ?, 
            advance_amt   = ?, 
            total_paid    = total_paid + ? 
        WHERE `id` = ?
    ");
        $statement->execute([
            $data['cus_name'],
            $data['cus_mobile'],
            $data['cus_address'],
            $data['order_details'],
            $data['total_amt'],
            $newAdvance,
            $diff,   // only difference added to total_paid
            $id
        ]);

        $transaction_model = registry()->get('loader')->model('transaction');
        $customer_model = registry()->get('loader')->model('customer');

        $cid = $or['cus_id'];
        $ref = $or['ref_no'];
        $details = 'Customer name: ' . $data['cus_name'];
        // withdraw old advance
        $transaction_model->withdrawal($oldAdvance, $details, $ref, "Withdrawal for update wrong order");
        $customer_model->editCustomerTotalDue($cid, $oldAdvance);

        // deposit new advance
        $transaction_model->deposite($newAdvance, $details, $ref, "Deposit for update wrong order");
        $customer_model->editCustomerTotalDue($cid, $newAdvance, 1);


        return $id;
    }


    public function updateOrderStatus($id, $sts)
    {
        $statement = $this->db->prepare("UPDATE orders SET `status` = ? WHERE id =?");
        $statement->execute([$sts, $id]);
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

    public function getCustomerOrders($id)
    {
        $statement = $this->db->prepare("SELECT * FROM `orders` WHERE `cus_id` = ?");
        $statement->execute(array($id));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
