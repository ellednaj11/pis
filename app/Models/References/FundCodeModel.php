<?php

namespace App\Models\References;

use CodeIgniter\Model;

class FundCodeModel extends Model {
    protected $table = 'ref_fund_code';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name','description','created_by','created_date'];
}
