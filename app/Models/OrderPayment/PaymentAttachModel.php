<?php

namespace App\Models\OrderPayment;

use CodeIgniter\Model;

class PaymentAttachModel extends Model
{
    protected $table = 'payment_attachment';
    protected $primaryKey = 'id'; // Adjust according to your table's primary key
    protected $allowedFields = ['payment_id', 
                                'file_path',
                                'original_name']; // List your table fields here

    // Additional settings and methods specific to op_header_tbl
}
