<?php
class ModelPayment extends Model
{
    public function addPayment($data)
    {

        $statement = $this->db->prepare("SELECT * FROM customer WHERE id = ?");
        $statement->execute(array($data['cus_id']));
        $customer = $statement->fetch(PDO::FETCH_ASSOC);

        $c_name = $customer['c_name'];

        $statement = db()->prepare("
            INSERT INTO `payments` (`order_id`, `order_no`, `c_id`, `amount`, `note`, `created_by`) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $statement->execute(array(
            $data['order_id'],
            $data['order_no'],
            $data['cus_id'],
            $data['paid_amount'],
            $data['note'],
            user_id()
        ));

        $payment_id = $this->db->lastInsertId();

        // Update total_paid in orders table
        $update = db()->prepare("
            UPDATE `orders` 
            SET total_paid = total_paid + ? 
            WHERE id = ?
        ");
        $update->execute(array(
            $data['paid_amount'],
            $data['order_id']
        ));

        $statement = $this->db->prepare("UPDATE `customer` SET  `total_due` = total_due - ? WHERE id = ?");
        $statement->execute(array($data['paid_amount'], $data['cus_id']));

        $paid_amount = $data['paid_amount'];
        if ($account_id = 1 && $paid_amount > 0) {
            $ref_no = unique_transaction_ref_no();

            $source_id = 1;
            $title = 'Deposit for order payment';
            $details = 'Customer name: ' . $c_name;
            $image = 'NULL';
            $deposit_amount = $paid_amount;
            $transaction_type = 'deposit';

            $statement = $this->db->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, invoice_id, transaction_type, title, details, image, created_by) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->execute(array(store_id(), $account_id, $source_id, $ref_no, $data['order_no'], $transaction_type, $title, $details, $image, user_id()));
            $info_id = $this->db->lastInsertId();

            $statement = $this->db->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
            $statement->execute(array(store_id(), $info_id, $ref_no, $deposit_amount));

            $statement = $this->db->prepare("UPDATE `bank_account_to_store` SET `deposit` = `deposit` + {$deposit_amount} WHERE `store_id` = ? AND `account_id` = ?");
            $statement->execute(array(store_id(), $account_id));

            $statement = $this->db->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + {$deposit_amount} WHERE `id` = ?");
            $statement->execute(array($account_id));
        }

        return $payment_id;
    }

    public function get_order_last_payment($id)
    {
        $statement = $this->db->prepare("SELECT * FROM payments WHERE order_id = ?  ORDER BY created_at DESC LIMIT 1");
        $statement->execute(array($id));
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}
