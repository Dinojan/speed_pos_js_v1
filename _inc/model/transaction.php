<?php
class ModelTransaction extends Model
{
    public function deposite($paid_amount, $details, $ref, $title)
    {
        $account_id = 1;
        if ($account_id = 1 && $paid_amount > 0) {
            $ref_no = unique_transaction_ref_no();

            $source_id = 1;
            // $title = 'Deposit for order advance';
            // $details = 'Customer name: ' . $data['cus_name'];
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
    }

    public function withdrawal($paid_amount, $details, $ref, $title)
    {
        $account_id = 1;
        if ($account_id = 1 && $paid_amount > 0) {
            $ref_no = unique_transaction_ref_no('withdraw');

            $source_id = 1;
            // $title = 'Deposit for order advance';
            // $details = 'Customer name: ' . $data['cus_name'];
            $image = 'NULL';
            $withdrawal_amount = $paid_amount;
            $transaction_type = 'withdraw';

            $statement = $this->db->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, invoice_id, transaction_type, title, details, image, created_by) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->execute(array(store_id(), $account_id, $source_id, $ref_no, $ref, $transaction_type, $title, $details, $image, user_id()));
            $info_id = $this->db->lastInsertId();

            $statement = $this->db->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
            $statement->execute(array(store_id(), $info_id, $ref_no, $withdrawal_amount));

            $statement = $this->db->prepare("UPDATE `bank_account_to_store` SET `withdraw` = `withdraw` + {$withdrawal_amount} WHERE `store_id` = ? AND `account_id` = ?");
            $statement->execute(array(store_id(), $account_id));

            $statement = $this->db->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` - {$withdrawal_amount} WHERE `id` = ?");
            $statement->execute(array($account_id));
        }
    }
}
