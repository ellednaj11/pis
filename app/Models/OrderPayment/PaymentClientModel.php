<?php

namespace App\Models\OrderPayment;

use CodeIgniter\Model;

class PaymentClientModel extends Model
{
    protected $table = 'payment_client';
    protected $primaryKey = 'id'; // Adjust according to your table's primary key
    protected $allowedFields = ['payment_id', 
                                'email',
                                'contact_num']; // List your table fields here

    // Additional settings and methods specific to op_header_tbl
}
