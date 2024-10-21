<?php

namespace App\Models\References;

use CodeIgniter\Model;

class PaymentTypeModel extends Model {
    protected $table = 'ref_schedule_fees';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name','fees_desc','region_id','status'];
}
