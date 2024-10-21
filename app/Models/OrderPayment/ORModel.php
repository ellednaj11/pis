<?php

namespace App\Models\OrderPayment;

use CodeIgniter\Model;

class ORModel extends Model {
    protected $table = 'official_receipt';
    protected $primaryKey = 'id';
    protected $allowedFields = ['or_trans_num','payment_id', 'op_id', 'receipt_book_id','official_receipt_no','official_receipt_date','fund_code','bank_name','bank_account', 'status', 'amount', 'amount_due','created_by','created_date','cancel_by','cancel_date'];
}
