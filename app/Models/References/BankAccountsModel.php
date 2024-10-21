<?php

namespace App\Models\References;

use CodeIgniter\Model;

class BankAccountsModel extends Model {
    protected $table = 'ref_bank_account';
    protected $primaryKey = 'id';
    protected $allowedFields = ['region_id', 
                                'treasury_bank', 
                                'bank_name',
                                'location', 
                                'account_name', 
                                'account_number', 
                                'account_type',
                                'status',
                                'created_by',
                                'created_date',
                                'remove_by',
                                'remove_date'];
}
