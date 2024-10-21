<?php

namespace App\Controllers;

use App\Models\OrderPayment\PaymentModel; //Reference
use App\Models\OrderPayment\PaymentClientModel; //Reference
use App\Models\OrderPayment\PaymentAttachModel; //Reference
use App\Controllers\BaseController;

class ClientController extends BaseController {

    

    

    public function save_client_payment()
    {
        // Database transaction
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Insert into payment_tbl
            $paymentModel = new PaymentModel();
            $amount_paid_cash = str_replace(',', '', $this->request->getPost('amount_paid'));
            $op_trans_num = $this->request->getPost('trans_no');
            $paymentData = [
                'op_id' => $this->request->getPost('op_id'),
                'payment_method' => $this->request->getPost('payment_method'),
                'payment_receipt_no' => $this->request->getPost('payment_receipt_no'),
                'payment_date' => $this->request->getPost('payment_date'),
                'payable_amount' => $this->request->getPost('amount_to_paid'),
                'total_amount_paid' => $amount_paid_cash,
                'status' => 2,
                'embInput' => 0,
            ];

            $paymentData['p_trans_no'] = $this->generate_payment_trans_no();
            date_default_timezone_set('Asia/Manila');
            $now = new \DateTime();
            $paymentData['created_date'] =$now->format('Y-m-d H:i:s');

            if (!$paymentModel->insert($paymentData)) {
                throw new \Exception('Payment data not saved.');
            }

            $paymentID = $paymentModel->insertID(); // Get the insert ID

            // Insert into client_tbl
            $clientModel = new PaymentClientModel();
            $clientData = [
                'payment_id' => $paymentID,
                'email' => $this->request->getPost('client_email'),
                'contact_num' => $this->request->getPost('cell_number'),
            ];

            if (!$clientModel->insert($clientData)) {
                throw new \Exception('Client data not saved.');
            }

            // Insert into attach_tbl
            $attachmentModel = new PaymentAttachModel();
            $files = $this->request->getFiles();
            $trans_array = explode('-',$op_trans_num);
            $reg_name = $trans_array[1];
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

            

            // Commit transaction
            $db->transCommit();

            return $this->response->setJSON(['status' => 'success']);
        } catch (\Exception $e) {
            // Rollback transaction on error
            $db->transRollback();

            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
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


}
