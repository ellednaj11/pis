<?php

namespace App\Controllers;

use App\Models\References\ScheduleFeesModel; //Reference
use App\Models\References\PaymentTypeModel; //Reference
use App\Models\References\BankAccountsModel; //Reference
use App\Controllers\BaseController;

class ScheduleFeesController extends BaseController {

    

    

    public function get_all_schedule_fees() {
        $model = new PaymentTypeModel();
        $data = $model->where('status !=', 0)->findAll();
        return $this->response->setJSON($data);
    }

    public function save_schedule_fees() {
        $header_data =  $this->request->getVar('hdr');
        $detail_data =  $this->request->getVar('dtl');
        $model1 = new PaymentTypeModel();
        $model2 = new ScheduleFeesModel();
        
        $session = session();
        $header_data['region_id'] = $session->get('reg_id');

        $db = $model1->db;

        try {
            // Start transaction
            $db->transBegin();

            // Insert data into OpHeaderModel and retrieve the inserted ID
            $model1->insert($header_data);
            $hdr_id = $model1->getInsertID(); // Get the last inserted ID

            foreach ($detail_data as &$item) {
                $item['schedule_fees_id'] = $hdr_id; // Assign the hdr_id to each item
                $model2->insert($item);
            }
            // Commit transaction
            $db->transCommit();

            // Handle success scenario
            echo json_encode(array('status' => 'success'));
        } catch (\Exception $e) {
            // Rollback transaction if an error occurs
            $db->transRollback();

            // Handle error scenario
            echo json_encode(array('status' => 'failed','data' => $detail_data,'error' => $e->getMessage()));
        }
    }

    public function remove_schedule_fees() {
        $id =  $this->request->getVar('id');
        $model = new ScheduleFeesModel();
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

    public function get_schedule_fees_details() {
        $id =  $this->request->getVar('id');

        $model1 = new PaymentTypeModel();
        $model2 = new ScheduleFeesModel();
        
        $header_data = $model1->where('id', $id)->findAll();
        
        $model = new ScheduleFeesModel();
        $detail_data = $model2->where('schedule_fees_id', $id)->findAll();
        echo json_encode(array('status' => 'success','header_data' => $header_data,'detail_data' => $detail_data));
    }

    public function get_banks() {
        $model = new BankAccountsModel();

        $session = session();
        $reg = $session->get('id');

        $reg_id =  $this->request->getVar('reg_id');

        $db = \Config\Database::connect();
        $builder = $db->table('ref_bank_account as bank_account');
        $builder->select('bank.bank_name as value');
        $builder->join('ref_bank as bank', 'bank_account.bank_name = bank.id','left');
        $builder->where('bank_account.region_id', $reg_id);
        $builder->groupBy('bank.bank_name', $reg_id);
        $query = $builder->get()->getResultArray();

        $model = new BankAccountsModel();
        $account_number = $model->select('account_number as value')->where('region_id =', $reg_id)->findAll();

        echo json_encode(array('status' => 'success','bank_name' => $query,'account_number' => $account_number));

    }

}
