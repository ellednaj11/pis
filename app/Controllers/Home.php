<?php

namespace App\Controllers;

use App\Models\OrderPayment\OpHeaderModel;
use App\Models\OrderPayment\OpDetailModel;
use App\Models\OrderPayment\PaymentModel;


class Home extends BaseController
{
    public function __construct()
    {
        helper('encryption');
    }

    public function index()
    {
        $view = "login";
        $data["test"] = '';
        $this->LoginLayout($view, $data);
    }

    public function dashboard(): string
    {
        return view('pages/dashboard/dashboardPage', ['title' => 'Dashboard']);
    }
    public function orderPayment(): string
    {
        return view('pages/orderPayment/payment', ['title' => 'Order of Payment']);
    }
    public function acceptPayment($trans_no): string
    {

        $db = \Config\Database::connect();
        $builder = $db->table('op_hdr_tbl as hdr');
        $builder->select('hdr.*,stat.status_name,reg.rgnnam2 as region_name, CONCAT(user.first_name," ",user.last_name) as issued_by');
        // $builder->join('ref_schedule_fees as paytype', 'hdr.application_type = paytype.id','left');
        $builder->join('status_tbl as stat', 'hdr.status = stat.id','left');
        $builder->join('md_region as reg', 'hdr.region_id = reg.rgnid','left');
        $builder->join('user_tbl as user', 'hdr.created_by = user.id','left');
        $builder->where('hdr.trans_no', $trans_no);
        $query = $builder->get()->getResultArray();

        // Check if data is found
        if (!empty($query)) {
            $header_data = $query[0]; // Assuming you're only expecting one result
        } else {
            $header_data = []; // Handle the case where no data is found
        }

        $data = [
            'title' => 'Accept Payment',
            'header_data' => $header_data
        ];

        return view('pages/orderPayment/acceptPayment', $data);
    }
    public function paymentHistory(): string
    {
        return view('pages/paymentHistory/paymentHistory', ['title' => 'Payment History']);
    }
    public function FeesSchedule(): string
    {
        return view('pages/scheduleFees/scheduleFees', ['title' => 'Schedule of Fees']);
    }
    public function receiptBooks(): string
    {
        return view('pages/receiptBook/receiptBook', ['title' => 'Receipt books']);
    }
    public function bankAccounts(): string
    {
        return view('pages/bankAccounts/bankAccounts', ['title' => 'Bank Accounts']);
    }
    public function reports(): string
    {
        return view('pages/cashier/reports', ['title' => 'Reports']);
    }

    public function login()
    {
        $view = "login";
        $data["test"] = '';
        $this->LoginLayout($view, $data);
    }

    public function client()
    {
        $view = "clientPages/clientMain";
        $data["test"] = '';
        $this->ClientLayout($view, $data);
    }

    public function clientHome()
    {
        $token = $this->request->getGet('token');
        $trans_no = decrypt_id($token);

        $db = \Config\Database::connect();
        $builder = $db->table('op_hdr_tbl as hdr');
        $builder->select('hdr.*,stat.status_name,reg.rgnnam2 as region_name, CONCAT(user.first_name," ",user.last_name) as issued_by');
        $builder->join('status_tbl as stat', 'hdr.status = stat.id','left');
        $builder->join('md_region as reg', 'hdr.region_id = reg.rgnid','left');
        $builder->join('user_tbl as user', 'hdr.created_by = user.id','left');
        $builder->where('hdr.trans_no', $trans_no);
        $query = $builder->get()->getResultArray();

        // Check if data is found
        if (!empty($query)) {
            $header_data = $query[0];
        }
        $header_data['expiration_date'] = \DateTime::createFromFormat('Y-m-d', $header_data['expiration_date'])->format('F j, Y');
        $header_data['issued_date'] = \DateTime::createFromFormat('Y-m-d', $header_data['issued_date'])->format('F j, Y');
        $model = new OpDetailModel();
        $detail_data = $model->where('op_hdr_id', $header_data['id'])->findAll();
        $amount_due = 0; // Initialize the total amount due variable
        $amount_paid = 0; // Initialize the total amount paid variable
        $to_verify = 0;
        // Loop through each row in the detail_data array
        foreach ($detail_data as $row) {
            // Add the amount from the current row to the total amount
            $amount_due += $row['item_sub_total'];
        }


        $db2 = \Config\Database::connect();
        $builder2 = $db2->table('payment_tbl as pay');
        $builder2->select('pay.*,stat.payment_status_name,meth.method_name');
        $builder2->select("CASE 
                     WHEN status = 0 THEN CONCAT(canceller.first_name, ' ', canceller.last_name)
                     WHEN status = 1 AND embInput = 0 THEN CONCAT(verifier.first_name, ' ', verifier.last_name)
                     WHEN status = 1 AND embInput = 1 THEN CONCAT(creator.first_name, ' ', creator.last_name)
                     WHEN status = 2 THEN ''
                  END AS responsible_person", false);
        $builder2->join('payment_status as stat', 'pay.status = stat.id','left');
        $builder2->join('ref_payment_method as meth', 'pay.payment_method = meth.id','left');
        // Join for created_by
        $builder2->join('user_tbl AS creator', 'creator.id = pay.created_by', 'left');

        // Join for cancel_by
        $builder2->join('user_tbl AS canceller', 'canceller.id = pay.cancel_by', 'left');

        // Join for verified_by
        $builder2->join('user_tbl AS verifier', 'verifier.id = pay.verified_by', 'left');
        $builder2->where('pay.op_id', $header_data['id']);
        $payment_data = $builder2->get()->getResultArray();

        foreach ($payment_data as $row) {
            // Check if the status column value is 1
            if ($row['status'] == 1) {
                // Add the amount from the current row to the total amount
                $amount_paid += $row['total_amount_credited'];
            }

            if($row['status'] == 2){
                $to_verify++;
            }
        }

        $data = [
            'title' => 'Accept Payment',
            'header_data' => $header_data,
            'detail_data' => $detail_data,
            'payment_data' => $payment_data,
            'amount_due' => $amount_due,
            'amount_paid' => $amount_paid,
            'balance' => $amount_due - $amount_paid,
            'to_verify' => $to_verify
        ];

        return view('clientPages/clientHome', $data);
        // return view('clientPages/clientHome', ['title' => 'Reports'], $data);
        // $view = "clientPages/clientHome";
        // $data["test"] = '';
        // $this->ClientLayout($view, $data);
    }

    public function get_for_verify_count() {
        $session = session();
        $reg_id = $session->get('reg_id');
        $model = new PaymentModel();
        $data = $model->select('COUNT(payment_tbl.id) as total_to_verify')
                            ->join('op_hdr_tbl AS op_hdr', 'op_hdr.id = payment_tbl.op_id', 'left')
                            ->where('payment_tbl.status', 2)
                            ->where('op_hdr.region_id', $reg_id)
                            ->findAll();
        return $this->response->setJSON($data);
    }
}
