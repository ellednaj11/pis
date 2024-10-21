<?php

namespace App\Controllers;

use App\Models\References\PaymentTypeModel; //Reference
use App\Models\OrderPayment\OpHeaderModel;
use App\Models\OrderPayment\OpDetailModel;
use App\Models\OrderPayment\PaymentModel;
use App\Models\OrderPayment\PaymentAttachModel; //Reference
use App\Controllers\BaseController;

use Mpdf\Mpdf;

class PdfController extends BaseController {
    public function __construct()
    {
        helper('encryption');
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
        $builder2->select('pay.*,stat.payment_status_name,meth.method_name, CONCAT(user.first_name," ",user.last_name) as verified_by');
        $builder2->join('payment_status as stat', 'pay.status = stat.id','left');
        $builder2->join('ref_payment_method as meth', 'pay.payment_method = meth.id','left');
        $builder2->join('user_tbl as user', 'pay.verified_by = user.id','left');
        $builder2->where('pay.op_id', $header_data['id']);
        $payment_data = $builder2->get()->getResultArray();

        foreach ($payment_data as $row) {
            // Check if the status column value is 1
            if ($row['status'] == 1) {
                // Add the amount from the current row to the total amount
                $amount_paid += $row['total_amount_paid'];
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
    }

    public function generate_pdf_order_of_payment(){
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
        $model = new OpDetailModel();
        $detail_data = $model->where('op_hdr_id', $header_data['id'])->findAll();

        $statusColor = $this->status_color($header_data['status']);

        // Initialize mPDF
        $mpdf = new Mpdf();

        // Sample HTML content for PDF
        $htmlContent = '
            <html>
        <head>
            <link rel="icon" href="' . base_url('assets/images/logo-denr.png') . '">
            <style>
                body { font-family: Arial, sans-serif; }
                .header { text-align: center; }
                .status { color: ' . $statusColor . '; font-weight: bold; }
                .table { border-collapse: collapse; width: 100%; margin-top: 20px; }
                .table th, .table td { border: 1px solid black; padding: 8px; text-align: left; }
                .table th { background-color: #f2f2f2; }
                .footer { margin-top: 20px; font-size: 10px; }
            </style>
        </head>
        <body>
            <div class="header">
                <img src="' . base_url('assets/images/pdf_header/CO-5674.png') . '" alt="Header Image" width="100%">
                <h2>ORDER OF PAYMENT</h2>
                <p>(No. '.$header_data['order_payment_no'].')</p>
                <p>Expiration Date: <b>'.$this->date_format($header_data['expiration_date']).'</b></p>
            </div>

            <p><b>Source:</b> '.$header_data['source'].'<br>
            <b>Transaction ID:</b> '.$header_data['order_payment_no'].'<br>
            <b>PIS Order of Payment No:</b> '.$header_data['trans_no'].'<br>
            <b>Order of Payment No:</b> '.$header_data['order_payment_no'].'<br>
            <b>Order of Payment Date:</b> '.$this->date_format($header_data['issued_date']).'<br>
            <b>Issuing Office:</b> '.$header_data['region_name'].'<br>
            <b>Prepared By:</b> '.$header_data['issued_by'].'</p>

            <p><span class="status">Status: '.$header_data['status_name'].'</span></p>

            <p><b>TO:</b> '.$header_data['company_name'].'</p>
            <p>Kindly settle the <b>'.$header_data['application_type'].'</b> of the Sample Project Only located at Somewhere out there in the amount of <b>Php ' . number_format($header_data['total_amount'], 2) . '</b> for the following particulars through the available payment channels indicated below:</p>

            <table class="table">
                <tr>
                    <th>Particular</th>
                    <th>Fund Code</th>
                    <th>Qty</th>
                    <th>Cost per Item</th>
                    <th>Sub-Total</th>
                </tr>';
                $total_amount = 0;
                foreach ($detail_data as $item) {
                    $total_amount +=$item['item_sub_total'];
                    $htmlContent .= '<tr>
                                <td>' . $item['item_name'] . '</td>
                                <td>' . $item['item_fund_code'] . '</td>
                                <td>' . $item['item_qty'] . '</td>
                                <td>Php ' . number_format($item['item_cost'], 2) . '</td>
                                <td>Php ' . number_format($item['item_sub_total'], 2) . '</td>
                            </tr>';
                }
        $htmlContent .= '<tr>
                    <td colspan="4" style="text-align: right;">Amount Due</td>
                    <td>Php ' . number_format($total_amount, 2) . '</td>
                </tr>
            </table>

            <p><b>Available Payment Channel/s:</b></p>
            <ol>
                <li><b>Over-EMB-Cashier</b>
                    <ul>
                        <li>Please visit EMB Central, Visayas Avenue Diliman, Quezon City</li>
                        <li>Present this Order of Payment to the Cashier</li>
                    </ul>
                </li>
                <li><b>Paymaya</b>
                    <ul>
                        <li>Open your internet browser and type the url: <a href="https://pay.emb.gov.ph/client">https://pay.emb.gov.ph/client</a></li>
                        <li>Make sure to have good internet signal to avoid interruption</li>
                        <li>Enter the required information related to the Order of Payment page.</li>
                        <li>Click the <b>Paymaya</b> icon and proceed as instructed</li>
                    </ul>
                </li>
            </ol>

            <div class="footer">
                <p>This is a system-generated document hence a signature is not required. However, the Order of Payment transaction can be validated by browsing to <a href="https://pay.emb.gov.ph/client">https://pay.emb.gov.ph/client</a> or scanning the QR Code.</p>
                <img src="' . base_url('path/to/qr-code-image.png') . '" alt="QR Code" width="100">
            </div>
        </body>
        </html>
        ';

        // Write HTML content to the PDF
        $mpdf->WriteHTML($htmlContent);

        // Set Content-Type header for PDF output
        $this->response->setHeader('Content-Type', 'application/pdf');

        // Output the generated PDF (inline view in browser)
        return $this->response->setBody($mpdf->Output('sample.pdf', 'S'));
    }

    public function generateOrderPaymentPDF($orderId)
    {
        // Load order details using the Order ID
        $orderModel = new OrderModel();
        $orderData = $orderModel->find($orderId);

        if (!$orderData) {
            throw new \Exception("Order not found");
        }

        // Initialize mPDF
        $mpdf = new Mpdf();

        // Generate HTML content dynamically based on $orderData
        $html = '
        <html>
        <head>
            <style>
                /* Your styles */
            </style>
        </head>
        <body>
            <!-- Your HTML content with dynamic order data -->
        </body>
        </html>
        ';

        // Write the HTML content to the PDF
        $mpdf->WriteHTML($html);

        // Output the PDF to the browser
        $mpdf->Output('OrderOfPayment.pdf', 'I');
    }

    public function status_color($id){
        switch($id) {
            case '3':
                return 'green';
            case '1':
                return 'red';
            case '2':
                return 'orange';
        }
    }

    public function date_format($date){
        $date = $date;
        $dateTime = new \DateTime($date);
        $formattedDate = $dateTime->format('F j, Y');
        return $formattedDate;
    }
}
