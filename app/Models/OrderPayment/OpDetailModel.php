<?php
namespace App\Models\OrderPayment;

use CodeIgniter\Model;

class OpDetailModel extends Model
{
    protected $table = 'op_dtl_tbl';
    protected $primaryKey = 'id'; // Adjust according to your table's primary key
    protected $allowedFields = ['op_hdr_id', 
                                'item_id', 
                                'item_name',
                                'item_fund_code', 
                                'item_bank_name', 
                                'item_bank_account', 
                                'item_cost', 
                                'item_qty',
                                'item_sub_total']; // List your table fields here

    // Additional settings and methods specific to op_detail_tbl
}
