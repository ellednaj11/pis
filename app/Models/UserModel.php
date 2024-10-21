<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
    protected $table = 'user_tbl';
    protected $primaryKey = 'id';
    protected $allowedFields = ['first_name', 'last_name', 'mid_name','role'];

    public function findUserByiisNumber(string $iis_number) // Use user id number for filtering
    {
        $user = $this
            ->asArray()
            ->select('user.*')
            ->select('user.region as region_id')
            ->select('user.user_access_level as access')
            ->select('reg.rgnnum as cregion')
            ->join('md_region as reg', ' region = reg.rgnid ', 'left')
            ->where(['id' => $iis_number])
            ->first();

        if (!$user)
            throw new Exception('iis number not register, please contact administrator');

        return $user;
    }
}
