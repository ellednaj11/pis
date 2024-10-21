<?php

namespace App\Models\OrderPayment;

use CodeIgniter\Model;

class OpHeaderModel extends Model
{
    protected $table = 'op_hdr_tbl';
    protected $primaryKey = 'id'; // Adjust according to your table's primary key
    protected $allowedFields = ['trans_no', 
                                'order_payment_no', 
                                'issued_date',
                                'issued_div', 
                                'status', 
                                'establishment_id',
                                'establishment_name', 
                                'establishment_address', 
                                'company_name',
                                'application_id', 
                                'application_type', 
                                'expiration_date',
                                'total_amount',
                                'balance',
                                'amount_paid',
                                'source',
                                'region_id', 
                                'created_by', 
                                'created_date',
                                'cancel_by',
                                'cancel_date',
                                'remarks']; // List your table fields here

    // Additional settings and methods specific to op_header_tbl
}
