<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
    protected $table = 'user_tbl';
    protected $primaryKey = 'id';
    protected $allowedFields = ['first_name', 'last_name', 'mid_name','role','office','region_id','isAdmin'];

}
