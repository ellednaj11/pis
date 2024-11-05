<?php

use CodeIgniter\Router\RouteCollection;

namespace Config;
// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Home::index');
});

$routes->group('', ['filter' => 'needLogged'], function($routes) {
    $routes->get('order-payment', 'Home::orderPayment');
    $routes->get('dashboard', 'Home::dashboard');
    $routes->get('payment-history', 'Home::paymentHistory');
});


$routes->group('', ['filter' => 'cashier'], function($routes) {
    
    $routes->get('fees-schedule', 'Home::FeesSchedule');
    $routes->get('receipt-books', 'Home::receiptBooks');
    $routes->get('bank-accounts', 'Home::bankAccounts');
    $routes->get('reports', 'Home::reports');

    $routes->get('accept-payment/(:any)', 'Home::acceptPayment/$1');
});

// $routes->get('login', 'Home::login');
$routes->get('client', 'Home::client');
$routes->get('accept-payment', 'Home::clientHome');
$routes->post('client/save-client-payment', 'ClientController::save_client_payment', ['filter' => 'csrf']);

// $routes->get('dashboard', 'Home::orderPayment');
// $routes->get('order-payment', 'Home::orderPayment');


$routes->get('auth/login', 'AuthController::login', ['as' => 'login']);
$routes->post('auth/login', 'AuthController::login');
$routes->get('auth/logout', 'AuthController::logout');

//For Order of Payments
$routes->post('payment/get-payment-for-ref', 'PaymentController::get_payment_for_ref');
$routes->post('payment/get-particular-ref', 'PaymentController::get_particular_ref');
$routes->get('payment/get-payment-method-ref', 'PaymentController::get_payment_method_ref');

$routes->get('payment/get-all-order-payment', 'PaymentController::get_all_order_payment');
$routes->post('payment/save-order-payment', 'PaymentController::save_order_payment');
$routes->post('payment/cancel-order-payment', 'PaymentController::cancel_order_payment');
$routes->get('payment/get-all-order-payment-details', 'PaymentController::get_all_order_payment_details');


// FOR Print PDF
$routes->get('pdf/generate-pdf-op', 'PdfController::generate_pdf_order_of_payment');

$routes->get('payment/check-trans-number', 'PaymentController::check_trans_number');

// FOR Client's Payments
$routes->get('payment/get-all-client-payment', 'PaymentController::get_all_client_payment');
$routes->get('payment/get-spec-client-payment', 'PaymentController::get_spec_client_payment');
$routes->get('payment/get-spec-payment', 'PaymentController::get_spec_payment');
$routes->get('payment/get-client-payment-attach', 'PaymentController::get_client_payment_attach');
$routes->post('payment/save-client-payment', 'PaymentController::save_client_payment');
$routes->post('payment/accept-client-payment', 'PaymentController::accept_client_payment');
$routes->post('payment/reject-client-payment', 'PaymentController::reject_client_payment');
$routes->post('payment/cancel-client-payment', 'PaymentController::cancel_client_payment');
$routes->get('payment/check-active-payment-receipt', 'PaymentController::check_active_payment_receipt');

// For Generate Official Receipt
$routes->get('payment/check-payment-used-receipt', 'PaymentController::check_payment_used_receipt');
$routes->get('payment/get-op-fund-code', 'PaymentController::get_op_fund_code');
$routes->get('payment/get-spec-receipt-book', 'PaymentController::get_spec_receipt_book');
$routes->get('payment/get-used-receipt', 'PaymentController::get_used_receipt');
$routes->post('payment/save-official-receipt', 'PaymentController::save_official_receipt');

// FOR Receipts Details
$routes->get('receipt/get-all-receipt-book', 'ReceiptBookController::get_all_receipt_book');
$routes->post('receipt/save-receipt-book', 'ReceiptBookController::save_receipt_book');
$routes->post('receipt/remove-receipt-book', 'ReceiptBookController::remove_receipt_book');
$routes->post('payment/cancel-official-receipt', 'PaymentController::cancel_official_receipt');

// FOR Bank Accounts Details
$routes->get('bankAcc/get-all-bank-account', 'BankAccountController::get_all_bank_account');
$routes->post('bankAcc/save-bank-account', 'BankAccountController::save_bank_account');
$routes->post('bankAcc/remove-bank-account', 'BankAccountController::remove_bank_account');

// FOR Schedule of Fees Details
$routes->get('schedfees/get-all-schedule-fees', 'ScheduleFeesController::get_all_schedule_fees');
$routes->post('schedfees/save-schedule-fees', 'ScheduleFeesController::save_schedule_fees');
$routes->post('schedfees/remove-schedule-fees', 'ScheduleFeesController::remove_schedule_fees');
$routes->get('schedfees/get-banks', 'ScheduleFeesController::get_banks');
$routes->get('schedfees/get-schedule-fees-details', 'ScheduleFeesController::get_schedule_fees_details');


// FOR SIDEBAR
$routes->get('layout/get-for-verify-count', 'Home::get_for_verify_count');


//FOR MULTIPLE USE RFERENCE
$routes->get('payment/get-ref-fund-code', 'PaymentController::get_ref_fund_code');