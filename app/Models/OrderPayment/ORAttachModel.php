<?php

namespace App\Models\OrderPayment;

use CodeIgniter\Model;

class ORAttachModel extends Model
{
    protected $table = 'official_receipt_attachment';
    protected $primaryKey = 'id'; // Adjust according to your table's primary key
    protected $allowedFields = ['or_id', 
                                'file_name']; // List your table fields here

    // Additional settings and methods specific to official_receipt_attachment
}
