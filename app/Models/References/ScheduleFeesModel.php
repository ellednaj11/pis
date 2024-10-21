<?php

namespace App\Models\References;

use CodeIgniter\Model;

class ScheduleFeesModel extends Model {
    protected $table = 'ref_schedules_fees_particular';
    protected $primaryKey = 'id';
    protected $allowedFields = ['schedule_fees_id', 
                                'particular_code',
                                'particular_name', 
                                'fund_code', 
                                'particular_amount',
                                'bank_account_id', 
                                'bank_name', 
                                'bank_account'];
}
