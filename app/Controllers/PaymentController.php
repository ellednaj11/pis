<?php

namespace App\Controllers;

use App\Models\References\PaymentTypeModel; //Reference
use App\Models\References\ReceiptBooksModel; //Reference
use App\Models\References\FundCodeModel; //Reference

use App\Models\OrderPayment\OpHeaderModel;
use App\Models\OrderPayment\OpDetailModel;

use App\Models\OrderPayment\PaymentModel;
use App\Models\OrderPayment\PaymentAttachModel;

use App\Models\OrderPayment\ORModel;
use App\Models\OrderPayment\ORAttachModel;

use App\Controllers\BaseController;

class PaymentController extends BaseController {
    public function __construct()
    {
        helper('encryption');
    }

    //For Order of Payments
    public function get_payment_for_ref() {
        $payments = [];
        $model = new PaymentTypeModel();
        $payments = $model->findAll();

        return $this->response->setJSON($payments);
    }

    public function get_particular_ref() {
        $id_number = $this->request->getVar('payment_for_id');

        $db = \Config\Database::connect();
        $builder = $db->table('ref_schedule_fees as payment');
        $builder->select('par.*');
        $builder->join('ref_schedules_fees_particular as par', 'payment.id = par.schedule_fees_id','left');
        $builder->where('payment.id',  $id_number);
        $query = $builder->get()->getResultArray();
        return $this->response->setJSON($query);
    }

    public function get_payment_method_ref() {
        $id_number = $this->request->getVar('payment_for_id');

        $db = \Config\Database::connect();
        $builder = $db->table('ref_payment_method');
        $builder->select('*');
        $query = $builder->get()->getResultArray();
        return $this->response->setJSON($query);
    }

    public function get_all_order_payment() {
        $session = session();
        $db = \Config\Database::connect();
        $builder = $db->table('op_hdr_tbl as hdr');
        $builder->select('hdr.*,stat.status_name');
        // $builder->join('ref_schedule_fees as paytype', 'hdr.application_type = paytype.id','left');
        $builder->join('status_tbl as stat', 'hdr.status = stat.id','left');
        $builder->where('hdr.status !=', 0);
        if($session->get('isAdmin') != 1){
            $builder->where('hdr.region_id',$session->get('reg_id'));
        }
        $builder->orderBy('hdr.id', 'DESC');
        $query = $builder->get()->getResultArray();

        return $this->response->setJSON($query);
    }

    public function save_order_payment() {
        $header_data =  $this->request->getVar('op_hdr');
        $detail_data =  $this->request->getVar('op_dtl');
        $opHeaderModel = new OpHeaderModel();
        $opDetailModel = new OpDetailModel();
        $db = $opHeaderModel->db;

        $session = session();
        $rgnId = (int) $session->get('reg_id');
        $header_data['trans_no'] = $this->generateTransactionNumber($rgnId);
        $header_data['created_by'] = $session->get('id');
        $header_data['source'] = 'Manual';
        date_default_timezone_set('Asia/Manila');
        $now = new \DateTime();
        $header_data['created_date'] =$now->format('Y-m-d');
        $header_data['region_id'] = (int) $session->get('reg_id');
        try {
            // Start transaction
            $db->transBegin();

            // Insert data into OpHeaderModel and retrieve the inserted ID
            $opHeaderModel->insert($header_data);
            $op_hdr_id = $opHeaderModel->getInsertID(); // Get the last inserted ID

            // Loop through data for OpDetailModel and associate each item with op_hdr_id
            foreach ($detail_data as &$item) {
                $item['op_hdr_id'] = $op_hdr_id; // Assign the op_hdr_id to each item
                $opDetailModel->insert($item);
            }

            // Commit transaction
            $db->transCommit();

            // Handle success scenario
            echo json_encode(array('status' => 'success','data' => $header_data));
        } catch (\Exception $e) {
            // Rollback transaction if an error occurs
            $db->transRollback();

            // Handle error scenario
            echo json_encode(array('status' => 'failed','error' => $e->getMessage()));
        }
    }

    public function cancel_order_payment() {
        $id =  $this->request->getVar('id');
        $opHeaderModel = new OpHeaderModel();
        $db = $opHeaderModel->db;
        $session = session();
        $now = new \DateTime();
        $data = [
            'status' => 0,
            'cancel_by' => $session->get('id'),
            'cancel_date' => $now->format('Y-m-d'),
        ];

        if ($opHeaderModel->update($id, $data)) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failed'));
        }
    }

    public function get_all_order_payment_details() {
        $id =  $this->request->getVar('id');
        $header_data = [];

        // Query for order of payment header details
        $db = \Config\Database::connect();
        $builder = $db->table('op_hdr_tbl as hdr');
        $builder->select('hdr.*,stat.status_name,reg.rgnnam2 as region_name, CONCAT(user.first_name," ",user.last_name) as issued_by');
        // $builder->join('ref_schedule_fees as paytype', 'hdr.application_type = paytype.id','left');
        $builder->join('status_tbl as stat', 'hdr.status = stat.id','left');
        $builder->join('md_region as reg', 'hdr.region_id = reg.rgnid','left');
        $builder->join('user_tbl as user', 'hdr.created_by = user.id','left');
        $builder->where('hdr.id', $id);
        $header_data = $builder->get()->getResultArray();
        // Query for order of payment header details
        
        // Query for order of payment details
        $model = new OpDetailModel();
        $detail_data = $model->where('op_hdr_id', $id)->findAll();
        // Query for order of payment details

        // Query for payments details
        $payment_data = [];
        $builder = $db->table('payment_tbl as hdr');
        $builder->select('hdr.*,stat.payment_status_name,paymeth.method_name');
        $builder->select("CASE 
                     WHEN status = 0 THEN CONCAT(canceller.first_name, ' ', canceller.last_name)
                     WHEN status = 1 AND embInput = 0 THEN CONCAT(verifier.first_name, ' ', verifier.last_name)
                     WHEN status = 1 AND embInput = 1 THEN CONCAT(creator.first_name, ' ', creator.last_name)
                     WHEN status = 2 THEN ''
                  END AS responsible_person", false);
        $builder->join('payment_status as stat', 'hdr.status = stat.id','left');
        $builder->join('ref_payment_method as paymeth', 'hdr.payment_method = paymeth.id','left');
        $builder->join('user_tbl AS creator', 'creator.id = hdr.created_by', 'left');// Join for created_by
        $builder->join('user_tbl AS canceller', 'canceller.id = hdr.cancel_by', 'left');// Join for cancel_by
        $builder->join('user_tbl AS verifier', 'verifier.id = hdr.verified_by', 'left');// Join for verified_by
        $builder->where('hdr.op_id', $id);
        $payment_data = $builder->get()->getResultArray();
        // Query for payments details

        // Query for receipt details
        $builder = $db->table('official_receipt as or');
        $builder->select('or.*');
        $builder->select("CASE 
                     WHEN status = 0 THEN CONCAT(canceller.first_name, ' ', canceller.last_name)
                     WHEN status = 1 THEN CONCAT(creator.first_name, ' ', creator.last_name)
                  END AS responsible_person", false);
        $builder->join('user_tbl AS creator', 'creator.id = or.created_by', 'left');// Join for created_by
        $builder->join('user_tbl AS canceller', 'canceller.id = or.cancel_by', 'left');// Join for cancel_by
        $builder->where('or.op_id', $id);
        $receipt_data = $builder->get()->getResultArray();
        // Query for receipt details

        echo json_encode(array('status' => 'success','header_data' => $header_data,'detail_data' => $detail_data,'payment_data' => $payment_data,'receipt_data' => $receipt_data));
    }

    public function generateTransactionNumber($rgnId)
    {
        $model = new OpHeaderModel(); // Replace with your actual model class
        
        $db = \Config\Database::connect();
        $builder = $db->table('md_region');
        $builder->select('rgnnum');
        $builder->where('rgnid', $rgnId);
        $query = $builder->get()->getResultArray();
        $region = $query[0]['rgnnum'];
        // Get current year
        $currentYear = date('Y');

        do {
            // Generate 6 random digits
            $randomDigits = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            
            // Concatenate with 'CO-' and current year
            $transNumber = 'PIS-'.$region.'-' . $currentYear .'-'. $randomDigits;
            
            // Check if the generated number exists in the database
            $existing = $model->where('trans_no', $transNumber)->first();
        } while ($existing !== null);
        
        return $transNumber;
    }

    // FOR Client's Payments
    public function get_all_client_payment() {
        $header_data = [];
        $status =  $this->request->getVar('status');

        $db = \Config\Database::connect();
        $builder = $db->table('payment_tbl as hdr');
        $builder->join('op_hdr_tbl as op_hdr', 'hdr.op_id = op_hdr.id','left');
        $builder->join('payment_status as stat', 'hdr.status = stat.id','left');
        $builder->join('ref_payment_method as paymeth', 'hdr.payment_method = paymeth.id','left');
        // Join for created_by
        $builder->join('user_tbl AS creator', 'creator.id = hdr.created_by', 'left');

        // Join for cancel_by
        $builder->join('user_tbl AS canceller', 'canceller.id = hdr.cancel_by', 'left');

        // Join for verified_by
        $builder->join('user_tbl AS verifier', 'verifier.id = hdr.verified_by', 'left');
        // $builder->join('user_tbl as user', 'hdr.created_by = user.id','left');
        $builder->select('hdr.*,stat.payment_status_name,paymeth.method_name,op_hdr.order_payment_no,op_hdr.trans_no,op_hdr.id as op_id');
        $builder->select("CASE 
                     WHEN hdr.status = 0 THEN CONCAT(canceller.first_name, ' ', canceller.last_name)
                     WHEN hdr.status = 1 AND embInput = 0 THEN CONCAT(verifier.first_name, ' ', verifier.last_name)
                     WHEN hdr.status = 1 AND embInput = 1 THEN CONCAT(creator.first_name, ' ', creator.last_name)
                     WHEN hdr.status = 2 THEN ''
                  END AS responsible_person", false);
        if($status != ''){
            $builder->where('hdr.status', $status);
        }
        $builder->orderBy('id', 'DESC');
        // $builder->where('hdr.op_id', $id);
        $query = $builder->get()->getResultArray();
        
        // echo json_encode(array('status' => 'success','payment_data' => $query));
        return $this->response->setJSON($query);
    }

    public function get_spec_client_payment() {
        $id =  $this->request->getVar('id');
        $header_data = [];

        $db = \Config\Database::connect();
        $builder = $db->table('payment_tbl as hdr');
        
        $builder->join('payment_status as stat', 'hdr.status = stat.id','left');
        $builder->join('ref_payment_method as paymeth', 'hdr.payment_method = paymeth.id','left');
        // Join for created_by
        $builder->join('user_tbl AS creator', 'creator.id = hdr.created_by', 'left');

        // Join for cancel_by
        $builder->join('user_tbl AS canceller', 'canceller.id = hdr.cancel_by', 'left');

        // Join for verified_by
        $builder->join('user_tbl AS verifier', 'verifier.id = hdr.verified_by', 'left');
        // $builder->join('user_tbl as user', 'hdr.created_by = user.id','left');
        $builder->select('hdr.*,stat.payment_status_name,paymeth.method_name');
        $builder->select("CASE 
                     WHEN status = 0 THEN CONCAT(canceller.first_name, ' ', canceller.last_name)
                     WHEN status = 1 AND embInput = 0 THEN CONCAT(verifier.first_name, ' ', verifier.last_name)
                     WHEN status = 1 AND embInput = 1 THEN CONCAT(creator.first_name, ' ', creator.last_name)
                     WHEN status = 2 THEN ''
                  END AS responsible_person", false);
        

        $builder->where('hdr.op_id', $id);
        $query = $builder->get()->getResultArray();
        
        echo json_encode(array('status' => 'success','header_data' => $query));
    }

    public function get_spec_payment() {
        $id =  $this->request->getVar('id');
        $header_data = [];

        $db = \Config\Database::connect();
        $builder = $db->table('payment_tbl as hdr');
        
        $builder->join('payment_status as stat', 'hdr.status = stat.id','left');
        $builder->join('ref_payment_method as paymeth', 'hdr.payment_method = paymeth.id','left');
        $builder->join('payment_client as client', 'hdr.id = client.payment_id','left');
        $builder->join('op_hdr_tbl as op_hdr', 'hdr.op_id = op_hdr.id','left');
        // Join for created_by
        $builder->join('user_tbl AS creator', 'creator.id = hdr.created_by', 'left');

        // Join for cancel_by
        $builder->join('user_tbl AS canceller', 'canceller.id = hdr.cancel_by', 'left');

        // Join for verified_by
        $builder->join('user_tbl AS verifier', 'verifier.id = hdr.verified_by', 'left');
        // $builder->join('user_tbl as user', 'hdr.created_by = user.id','left');
        $builder->select('hdr.*,op_hdr.trans_no,op_hdr.id as op_trans_id,stat.payment_status_name,paymeth.method_name, client.email,client.contact_num');
        $builder->select("CASE 
                     WHEN hdr.status = 0 THEN CONCAT(canceller.first_name, ' ', canceller.last_name)
                     WHEN hdr.status = 1 AND embInput = 0 THEN CONCAT(verifier.first_name, ' ', verifier.last_name)
                     WHEN hdr.status = 1 AND embInput = 1 THEN CONCAT(creator.first_name, ' ', creator.last_name)
                     WHEN hdr.status = 2 THEN ''
                  END AS responsible_person", false);
        

        $builder->where('hdr.id', $id);
        $query = $builder->get()->getResultArray();

        $attachment = [];
        $attachmentModel = new PaymentAttachModel();
        $attachment = $attachmentModel->where('payment_id', $id)->findAll();
        
        echo json_encode(array('status' => 'success','header_data' => $query, 'attachments' => $attachment));
    }

    public function save_client_payment() {
        $PaymentModel = new PaymentModel();
        $Headermodel = new OpHeaderModel();
        $db = $PaymentModel->db;

        
        try {
            $op_id = $this->request->getPost('op_id');
            $op_trans_num = $this->request->getPost('op_trans_num');
            $amount_to_paid = str_replace(',', '', $this->request->getPost('amount_to_paid'));
            $amount_paid_cash = str_replace(',', '', $this->request->getPost('paid_cash'));
            $amount_paid_check = str_replace(',', '', $this->request->getPost('paid_check'));
            $total_amount_paid = str_replace(',', '', $this->request->getPost('total_paid'));
            $payment_method = $this->request->getPost('payment_method');
            $header_data = [
                'payment_method' => $this->request->getPost('payment_method'),
                'payment_receipt_no' => $this->request->getPost('payment_receipt_no'),
                'payment_date' => $this->request->getPost('payment_date'),
                'op_id' => $this->request->getPost('op_id'),
                'payable_amount' => $amount_to_paid,
                'amount_paid_cash' => $amount_paid_cash,
                'amount_paid_check' => $amount_paid_check,
                'check_info' => $this->request->getPost('check_info'),
                'total_amount_paid' => $total_amount_paid,
                'total_amount_credited' => $total_amount_paid
            ];

            if($payment_method != 1){
                $header_data['payment_receipt_no'] = $this->request->getPost('payment_receipt_no');
            }

            $session = session();
            $header_data['p_trans_no'] = $this->generate_payment_trans_no();
            $header_data['created_by'] = $session->get('id');
            date_default_timezone_set('Asia/Manila');
            $now = new \DateTime();
            $header_data['created_date'] =$now->format('Y-m-d H:i:s');

            // Start transaction
            $db->transBegin();

            // Insert data into PaymentModel and retrieve the inserted ID
            $PaymentModel->insert($header_data);
            $paymentID = $PaymentModel->insertID();
            // Update of Status for Order of Payment Header
            $statusData = $this->check_OP_status($op_id);
            $data = [
                'status' => $statusData['status'],
                'amount_paid' => $statusData['amount_paid'],
                'balance' => $statusData['balance'],
            ];
            $Headermodel->update($op_id, $data);
            // -----------------------------------------------

            if($payment_method != 1){
                

                // Insert into attach_tbl
                $attachmentModel = new PaymentAttachModel();
                $trans_array = explode('-',$op_trans_num);
                $reg_name = $trans_array[1];
                $files = $this->request->getFiles();
                $currentYear = date('Y');
                $baseUploadPath = 'public/uploads/'.$currentYear.'/'.$reg_name.'/'.$op_trans_num.'/payment';

                if (!is_dir($baseUploadPath)) {
                    mkdir($baseUploadPath, 0775, true); // Create the directory with permissions 0775
                    chmod($baseUploadPath, 0775); // Ensure the directory has the correct permissions
                }

                

                foreach ($files['payment_attach'] as $file) {
                    // Get file extension
                    $fileExtension = $file->getExtension();

                    // Generate a new random name for the file
                    $randname = 'file_' . rand(1000, 1000000) . '.' . $fileExtension;

                    // Move the file to the year-specific folder
                    $file->move($baseUploadPath, $randname);

                    // Collect file names for database insertion if needed

                    $attachmentData = [
                        'payment_id' => $paymentID,
                        'file_path' => $randname,
                    ];
        
                    if (!$attachmentModel->insert($attachmentData)) {
                        throw new \Exception('Attachment data not saved.');
                    }
                }
            }

            // Commit transaction
            $db->transCommit();

            // Handle success scenario
            return $this->response->setJSON(['status' => 'success', 'op_id' => $op_id]);
        } catch (\Exception $e) {
            // Rollback transaction if an error occurs
            $db->transRollback();

            // Handle error scenario
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function cancel_client_payment() {
        $id =  $this->request->getVar('id');
        $op_id =  $this->request->getVar('op_id');
        $remarks =  $this->request->getVar('remarks');

        $model = new PaymentModel();
        $db = $model->db;
        $session = session();
        $now = new \DateTime();
        $data = [
            'status' => 0,
            'total_amount_credited' => 0,
            'cancel_by' => $session->get('id'),
            'cancel_date' => $now->format('Y-m-d'),
            'remarks'=> $remarks
        ];

        if ($model->update($id, $data)) {
            // Update of Status for Order of Payment Header
            $Headermodel = new OpHeaderModel();
            $statusData = $this->check_OP_status($op_id);
            $statdata = [
                'status' => $statusData['status'],
                'amount_paid' => $statusData['amount_paid'],
                'balance' => $statusData['balance'],
            ];
            $Headermodel->update($op_id, $statdata);
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failed'));
        }
    }

    public function check_active_payment_receipt() {
        $id =  $this->request->getVar('id');
        $attachment = [];
        $model = new ORModel();
        $receipts = $model->where('payment_id', $id)->where('status', 1)->findAll();
        return $this->response->setJSON($receipts);
    }

    public function accept_client_payment() {
        $id =  $this->request->getVar('id');
        $op_id =  $this->request->getVar('op_id');
        $amount_paid =  $this->request->getVar('amount_paid');
        $model = new PaymentModel();
        $db = $model->db;
        try {
            $session = session();
            $now = new \DateTime();
            $data = [
                'status' => 1,
                'total_amount_credited' => $amount_paid,
                'verified_by' => $session->get('id'),
                'verified_date' => $now->format('Y-m-d'),
            ];

            if ($model->update($id, $data)) {
                $statusData = $this->check_OP_status($op_id);
                $data = [
                    'status' => $statusData['status'],
                    'amount_paid' => $statusData['amount_paid'],
                    'balance' => $statusData['balance'],
                ];
                $Headermodel = new OpHeaderModel();
                $Headermodel->update($op_id, $data);
            }
            return $this->response->setJSON(['status' => 'success']);

        } catch (\Exception $e) {
            // Rollback transaction if an error occurs
            $db->transRollback();

            // Handle error scenario
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function reject_client_payment() {
        $id =  $this->request->getVar('id');
        $remarks =  $this->request->getVar('remarks');
        $model = new PaymentModel();
        $db = $model->db;
        $session = session();
        $now = new \DateTime();
        $data = [
            'status' => 0,
            'remarks' => $remarks,
            'cancel_by' => $session->get('id'),
            'cancel_date' => $now->format('Y-m-d'),
        ];

        if ($model->update($id, $data)) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failed'));
        }
    }

    public function get_client_payment_attach(){
        $id =  $this->request->getVar('id');
        $attachment = [];
        $attachmentModel = new PaymentAttachModel();
        $attachment = $attachmentModel->where('payment_id', $id)->findAll();
        return $this->response->setJSON($attachment);
    }

    public function generate_payment_trans_no()
    {
        $model = new PaymentModel();
        $currentYear = date('Y');

        do {
            // Generate 6 random digits
            $randomDigits = str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT);
            
            // Concatenate with 'CO-' and current year
            $transNumber = $currentYear .'-'. $randomDigits;
            
            // Check if the generated number exists in the database
            $existing = $model->where('p_trans_no', $transNumber)->first();
        } while ($existing !== null);
        
        return $transNumber;
    }

    public function check_trans_number() {
        $transNumber =  $this->request->getVar('trans_no');
        $opHeaderModel = new OpHeaderModel();
        $existing = $opHeaderModel->where('trans_no', $transNumber)->first();
        $encodedId = encrypt_id($transNumber);

        $response['encryptText'] =  encrypt_id($transNumber);

        $response['msg'] = 'false';
        if($existing){
            $response['msg'] = 'true';
        }
        return $this->response->setJSON($response);
        // echo json_encode(array('status' => 'true','header_data' => $encodedId,'decoded' => $decodeID));
    }

    public function check_OP_status($op_id) {
        $PaymentModel = new PaymentModel();
        $Particularmodel = new OpDetailModel();
        // Get sum of item_sub_total
        $sumPar = $Particularmodel->selectSum('item_sub_total')->where('op_hdr_id', $op_id)->first();
        // Get sum of total_amount_credited where status is 1
        $sumPay = $PaymentModel->selectSum('total_amount_credited')->where('op_id', $op_id)->where('status', 1)->first();
    
        // Calculate totals
        $totalParSubTotal = $sumPar['item_sub_total'] ?? 0; // Handle case where no result is returned
        $totalPaySubTotal = $sumPay['total_amount_credited'] ?? 0; // Handle case where no result is returned
        $bal = $totalParSubTotal - $totalPaySubTotal;
    
        // Determine status: 1 = Unpaid, 2 = Partial, 3 = Paid
        $status = 0;
        if ($totalPaySubTotal == 0) {
            $status = 1; // Unpaid
        } elseif ($totalPaySubTotal >= $totalParSubTotal) {
            $status = 3; // Paid
        } elseif ($totalPaySubTotal > 0 && $totalPaySubTotal < $totalParSubTotal) {
            $status = 2; // Partial
        }
    
        // Return status, total payment, and balance
        return [
            'status' => $status,
            'amount_paid' => $totalPaySubTotal,
            'balance' => $bal,
        ];
    }

    // For Generate Official Receipt
    public function check_payment_used_receipt() {
        $pay_id = $this->request->getVar('id');

        $model = new PaymentModel();
        $unused_amount = $model->select('(total_amount_credited - amount_used_receipt) AS unused_amount')
                            ->where('id', $pay_id)
                            ->findAll();
        return $this->response->setJSON($unused_amount);
    }

    public function get_op_fund_code() {
        $op_id = $this->request->getVar('op_id');
        $pay_id = $this->request->getVar('pay_id');

        $model = new OpDetailModel();
        $fund_codes = $model->select('item_fund_code,item_bank_name,item_bank_account, SUM(item_sub_total) as total_sub_total, official_receipt.or_trans_num')
                            ->join('official_receipt', 'op_dtl_tbl.item_fund_code = official_receipt.fund_code AND official_receipt.status = 1 AND official_receipt.op_id = ' . $op_id, 'left')
                            ->where('op_dtl_tbl.op_hdr_id', $op_id)
                            ->groupBy('item_fund_code')
                            ->findAll();
        return $this->response->setJSON($fund_codes);
    }

    public function get_spec_receipt_book() {
        $fund_code = $this->request->getVar('fund_code');

        $model = new ReceiptBooksModel();
        $receipt_books = $model->select('*, CONCAT(or_number_start," - ",or_number_end) as book_option')
                            ->where('fund_code', $fund_code)
                            ->where('status', 1)
                            ->findAll();
        return $this->response->setJSON($receipt_books);
    }

    public function get_used_receipt() {
        $receipt_book_id = $this->request->getVar('receipt_book_id');

        $model = new ORModel();
        $receipt_books = $model->select('official_receipt_no')
                            ->where('receipt_book_id', $receipt_book_id)
                            ->findAll();
        return $this->response->setJSON($receipt_books);
    }

    public function save_official_receipt() {
        $PaymentModel = new PaymentModel();
        $Headermodel = new OpHeaderModel();
        $ORModel = new ORModel();
        $db = $ORModel->db;
        
        try {
            $op_id = $this->request->getPost('receipt_op_id');
            $op_trans_num = $this->request->getPost('receipt_op_number');
            $payment_id = $this->request->getPost('receipt_payment_id');
            $rec_fund_code = $this->request->getPost('rec_fund_code');
            $rec_fund_code = $this->request->getPost('rec_fund_code');
            $rec_bank = $this->request->getPost('rec_bank');
            $rec_bank_acc = $this->request->getPost('rec_bank_acc');
            $rec_book = $this->request->getPost('rec_book');
            $or_num = $this->request->getPost('or_num');
            $or_date = $this->request->getPost('or_date');
            $rec_amount_to_paid = str_replace(',', '', $this->request->getPost('rec_amount_to_paid'));

            
            $receipt_data = [
                'payment_id' => $payment_id,
                'op_id' => $op_id,
                'receipt_book_id' => $rec_book,
                'official_receipt_no' => $or_num,
                'official_receipt_date' => $or_date,
                'fund_code' => $rec_fund_code,
                'bank_name' => $rec_bank,
                'bank_account' => $rec_bank_acc,
                'amount' => $rec_amount_to_paid,
                'amount_due' => $rec_amount_to_paid,
            ];

            $session = session();
            $receipt_data['or_trans_num'] = $this->generate_OR_trans_no();
            $receipt_data['created_by'] = $session->get('id');
            date_default_timezone_set('Asia/Manila');
            $now = new \DateTime();
            $receipt_data['created_date'] =$now->format('Y-m-d H:i:s');

            // Start transaction
            $db->transBegin();

            // Insert data into ORModel and retrieve the inserted ID
            $ORModel->insert($receipt_data);

            $data = [
                'amount_used_receipt' => $rec_amount_to_paid
            ];
            $PaymentModel->update($payment_id, $data);
            // -----------------------------------------------

            
            $or_id = $ORModel->insertID();

            // Insert into attach_tbl
            $attachmentModel = new ORAttachModel();
            $files = $this->request->getFiles();
            $trans_array = explode('-',$op_trans_num);
            $reg_name = $trans_array[1];
            $currentYear = date('Y');
            $baseUploadPath = 'public/uploads/'.$currentYear.'/'.$reg_name.'/'.$op_trans_num.'/receipt';

            if (!is_dir($baseUploadPath)) {
                mkdir($baseUploadPath, 0775, true); // Create the directory with permissions 0775
                chmod($baseUploadPath, 0775); // Ensure the directory has the correct permissions
            }


            foreach ($files['or_attach'] as $file) {
                // Get file extension
                $fileExtension = $file->getExtension();

                // Generate a new random name for the file
                $randname = 'file_' . rand(1000, 1000000) . '.' . $fileExtension;

                // Move the file to the year-specific folder
                $file->move($baseUploadPath, $randname);

                // Collect file names for database insertion if needed

                $attachmentData = [
                    'or_id' => $or_id,
                    'file_name' => $randname,
                ];
    
                if (!$attachmentModel->insert($attachmentData)) {
                    throw new \Exception('Attachment data not saved.');
                }
            }

            // Commit transaction
            $db->transCommit();

            // Handle success scenario
            return $this->response->setJSON(['status' => 'success', 'op_id' => $op_id]);
        } catch (\Exception $e) {
            // Rollback transaction if an error occurs
            $db->transRollback();

            // Handle error scenario
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function cancel_official_receipt() {
        $id =  $this->request->getVar('id');
        $payment_id =  $this->request->getVar('payment_id');
        $model = new ORModel();
        $or_data = $model->where('id', $id)->first();
        $session = session();
        $now = new \DateTime();
        $data = [
            'status' => 0,
            'total_amount_credited' => 0,
            'cancel_by' => $session->get('id'),
            'cancel_date' => $now->format('Y-m-d'),
        ];

        if ($model->update($id, $data)) {
            // Update of Status for Order of Payment Header
            $PaymentModel = new PaymentModel();
            $payment_data = $PaymentModel->where('id', $payment_id)->first();
            $new_pay_data = [
                'amount_used_receipt' => $payment_data['amount_used_receipt'] - $or_data['amount']
            ];
            $PaymentModel->update($payment_id, $new_pay_data);
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failed'));
        }
    }

    public function generate_OR_trans_no()
    {
        $model = new ORModel();
        $currentYear = date('Y');

        do {
            // Generate 6 random digits
            $randomDigits = str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT);
            
            // Concatenate with 'CO-' and current year
            $transNumber = 'OR-'. $currentYear .'-'. $randomDigits;
            
            // Check if the generated number exists in the database
            $existing = $model->where('or_trans_num', $transNumber)->first();
        } while ($existing !== null);
        
        return $transNumber;
    }

    //For multiple use reference

    public function get_ref_fund_code() {

        $model = new FundCodeModel();
        $fund_codes = $model->select('*')
                            ->findAll();
        return $this->response->setJSON($fund_codes);
    }
}
