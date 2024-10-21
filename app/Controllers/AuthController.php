<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\BaseController;

class AuthController extends BaseController {
    public function __construct()
    {
        helper('encryption');
    }
    
    public function login() {
        $response = [];
        $model = new UserModel();
        if ($this->request->isAJAX()) {
            $id_number = $this->request->getVar('id_number');
            $rgnid = $this->request->getVar('rgnid');
            $designation = $this->request->getVar('designation');
            $section = $this->request->getVar('section');
            
            // Check user credentials
            $user = $model->where('iis_id', $id_number)->first();
            

            if ($user) {
                // Store user data in session
                $user['rgnid'] = $rgnid;
                $user['designation'] = $designation;
                $user['section'] = $section;
                $this->setUserSession($user);
                $response['msg'] = 'admit';
                $response['data'] = $user;
            } else {
                $response['msg'] = 'refuse';
            }

            return $this->response->setJSON($response);
        }
    }

    private function setUserSession($user) {
        $data = [
            'id' => $user['id'],
            'name' => $user['first_name'] .' '. $user['last_name'],
            'role' => $user['role'],
            'reg_id' => $user['rgnid'],
            'designation' => $user['designation'],
            'section' => $user['section'],
            'isAdmin' => $user['isAdmin'],
            'isLoggedIn' => true
        ];

        session()->set($data);
        return true;
    }

    public function logout() {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }

    public function unauthorized()
    {
        return view('unAuth');
    }
}
