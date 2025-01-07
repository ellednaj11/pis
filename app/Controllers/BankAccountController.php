<?php

namespace App\Controllers;

use App\Models\References\BankAccountsModel; //Reference
use App\Controllers\BaseController;

class BankAccountController extends BaseController {

    

    

    public function get_all_bank_account() {
        $model = new BankAccountsModel();
        $data = $model->where('status !=', 0)->findAll();
        return $this->response->setJSON($data);
    }

    public function save_bank_account() {
        $header_data =  $this->request->getVar('hdr');
        $model = new BankAccountsModel();
        $db = $model->db;

        $session = session();
        $header_data['created_by'] = $session->get('id');
        $now = new \DateTime();
        $header_data['created_date'] =$now->format('Y-m-d H:i:s');
        try {
            // Start transaction
            $db->transBegin();

            // Insert data into OpHeaderModel and retrieve the inserted ID
            $model->insert($header_data);

            // Commit transaction
            $db->transCommit();

            // Handle success scenario
            echo json_encode(array('status' => 'success'));
        } catch (\Exception $e) {
            // Rollback transaction if an error occurs
            $db->transRollback();

            // Handle error scenario
            echo json_encode(array('status' => 'failed','error' => $e->getMessage()));
        }
    }

    public function remove_bank_account() {
        $id =  $this->request->getVar('id');
        $model = new BankAccountsModel();
        $db = $model->db;

        $session = session();
        $now = new \DateTime();
        $data = [
            'status' => 0,
            'remove_by' => $session->get('id'),
            'remove_date' => $now->format('Y-m-d'),
        ];

        if ($model->update($id, $data)) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failed'));
        }
    }

    public function get_all_order_payment_details() {
        $id =  $this->request->getVar('id');
        $header_data = [];

        $db = \Config\Database::connect();
        $builder = $db->table('op_hdr_tbl as hdr');
        $builder->select('hdr.*,paytype.name as payment_type,stat.status_name,reg.rgnnam2 as region_name, CONCAT(user.first_name," ",user.last_name) as issued_by');
        $builder->join('ref_schedule_fees as paytype', 'hdr.application_type = paytype.id','left');
        $builder->join('status_tbl as stat', 'hdr.status = stat.id','left');
        $builder->join('md_region as reg', 'hdr.region_id = reg.rgnid','left');
        $builder->join('user_tbl as user', 'hdr.created_by = user.id','left');
        $builder->where('hdr.id', $id);
        $query = $builder->get()->getResultArray();
        
        $model = new OpDetailModel();
        $detail_data = $model->where('op_hdr_id', $id)->findAll();
        echo json_encode(array('status' => 'success','header_data' => $query,'detail_data' => $detail_data));
    }

}
