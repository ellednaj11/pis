<?php

namespace App\Models\OrderPayment;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payment_tbl';
    protected $primaryKey = 'id'; // Adjust according to your table's primary key
    protected $allowedFields = ['p_trans_no', 
                                'payment_method', 
                                'payment_receipt_no',
                                'payment_date', 
                                'op_id', 
                                'payable_amount',
                                'amount_paid_cash', 
                                'amount_paid_check', 
                                'check_info',
                                'total_amount_paid', 
                                'total_amount_credited',
                                'amount_used_receipt',
                                'status', 
                                'embInput',
                                'created_by',
                                'created_date',
                                'verified_by',
                                'verified_date',
                                'cancel_by',
                                'cancel_date',
                                'remarks']; // List your table fields here

    // Additional settings and methods specific to op_header_tbl
}
