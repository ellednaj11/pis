<div class="modal fade" id="view-payment-modal" style>
    <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="paymentModalLabel">Order of Payment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body" style="max-height: 75vh;    overflow-y: auto;">
            <div class="row mx-5 my-3">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>Source:</strong></p>
                        </div>
                        <div class="col-md-9">
                            <p><span id="source"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>Payment For:</strong></p>
                        </div>
                        <div class="col-md-9">
                            <p><span id="paymentFor"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>Establishment:</strong></p>
                        </div>
                        <div class="col-md-9">
                            <p><span id="establishment"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>Location:</strong></p>
                        </div>
                        <div class="col-md-9">
                            <p><span id="location"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>Proponent:</strong></p>
                        </div>
                        <div class="col-md-9">
                            <p><span id="proponent"></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 ">
                    <div class="row">
                        <div class="col-md-5">
                            <p><strong>Status:</strong></p>
                        </div>
                        <div class="col-md-7">
                            <p><span id="status"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <p><strong>Expiration Date:</strong></p>
                        </div>
                        <div class="col-md-7">
                            <p><span id="expirationDate"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <p><strong>Transaction ID:</strong></p>
                        </div>
                        <div class="col-md-7">
                            <p><span id="transactionID"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <p><strong>Order of Payment No:</strong></p>
                        </div>
                        <div class="col-md-7">
                            <p><span id="orderPaymentNo"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <p><strong>OP Date:</strong></p>
                        </div>
                        <div class="col-md-7">
                            <p><span id="orderPaymentDate"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <p><strong>Issuing Office:</strong></p>
                        </div>
                        <div class="col-md-7">
                            <p><span id="issuingOffice"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <p><strong>Issued by:</strong></p>
                        </div>
                        <div class="col-md-7">
                            <p><span id="issuedBy"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <button type="button" class="btn btn-success btn-custom mx-1" onclick="check_print()">Print</button>
                        <button type="button" class="btn btn-danger btn-custom mx-1" onclick="confirm_cancel_op()">Cancel</button>
                        <button type="button" class="btn btn-warning btn-custom mx-1">Email</button>
                    </div>
                </div>
            </div>
            <div class="row mx-5 my-3">
                <h5>Particulars</h5>
                <table class="table table-bordered" id="particular_table_view" style="width:100% !important">
                    <thead>
                        <tr>
                            <th>Particular</th>
                            <th>Fund Code</th>
                            <th>Bank</th>
                            <th>Bank Account</th>
                            <th style="width: 120px">Cost per item</th>
                            <th>Quantity</th>
                            <th style="width: 120px">Sub-Total</th>
                        </tr>
                    </thead>
                    <tbody id="particularsTableBody">
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="padding: 5px .75rem;" colspan="6"><b>Total Amount Due</b></td>
                            <td style="padding: 5px .75rem;" id="amount_due"></td>
                        </tr>
                        <tr>
                            <td style="padding: 5px .75rem;" colspan="6"><b>Amount Paid</b></td>
                            <td style="padding: 5px .75rem;" id="amount_paid"></td>
                        </tr>
                        <tr>
                            <td style="padding: 5px .75rem;" colspan="6"><b>Balance</b></td>
                            <td style="padding: 5px .75rem;" id="balance"></td>
                        </tr>
                    </tfoot>
                </table>

                <h5>Payments</h5>
                <div class="col-lg-3 mb-3">
                    <button type="button" class="btn btn-primary" style="padding: 2px .75rem;" onclick="add_client_payment()">Add Payment</button>
                </div>
                <table class="table table-bordered" id="payment_table">
                    <thead>
                        <tr>
                            <th>Payment Method</th>
                            <th>Payment Receipt No</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Amount Credited</th>
                            <th>Status</th>
                            <th>Responsible Person</th>
                            <th style="width: 120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="payment_table_body">
                        <tr>
                            <td colspan="8" class="text-center">No payments recorded.</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Official Receipt</h5>
                <table class="table table-bordered" id="receipt_table">
                    <thead>
                        <tr>
                            <th>OR Number</th>
                            <th>Date</th>
                            <th>Fund Code</th>
                            <th>Bank</th>
                            <th>Bank Account</th>
                            <th style="width: 120px">Item Amount</th>
                            <th>Status</th>
                            <th>Responsible Person</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="receipt_table_body">
                    </tbody>
                </table>
            </div>
        </div>
        <!-- <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-success btn-custom">Print</button>
            <button type="button" class="btn btn-danger btn-custom" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-warning btn-custom">Email</button>
        </div> -->
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    
</div>
<script src="<?php echo base_url(); ?>assets/js/numeric.js"></script>
<!-- Validation -->
<script src="<?php echo base_url(); ?>public/plugins/jquery-validation/jquery.validate.min.js"></script>
<script>
    $(function () {
        // get_order_payment_dtl();
        // apply_text_color('payment_table');
        
    });
    var active_payment = 0;
    var active_receipt = 0;
    var to_verify = 0;

    function add_client_payment(){
        var bal = $('#balance').text()
        if(parseInt(bal) == 0){
            Swal.fire({
            position: "top-end",
            text: "Your selected order of payment has no remaining balance.",
            showConfirmButton: false,
            timer: 10000
            });
        }else if(to_verify > 0){
            Swal.fire({
            position: "top-end",
            text: "There is a pending payment for this transaction. Please complete or cancel it before adding a new payment.",
            showConfirmButton: false,
            timer: 10000
            });
        }else{
            $('#modal_title_payment').text($('#transactionID').text());
            var id = $("#transactionID").attr('data-id');
            var trans_no = $("#transactionID").text();
            console.log(trans_no)
            $('#op_id').val(id);
            $('#op_trans_num').val(trans_no);
            $('#amount_to_paid').val($('#balance').text());
            $('#add-client-payment').modal('show');
        }
        
    }

    function get_client_payment(id){ //Currenty not used
        $.ajax({
            url: '<?= base_url(); ?>payment/get-spec-client-payment',
            type: 'GET',
            data: { id: id },
            dataType: "json",
            success: function(result) {
                let particularsHtml = '';
                if(result['header_data'].length !== 0){
                    var data_detail = result['header_data']
                    data_detail.forEach(data => {
                        particularsHtml += `<tr>
                            <td style="padding: 5px .75rem;">${data.method_name}</td>
                            <td style="padding: 5px .75rem;">${data.payment_receipt_no}</td>
                            <td style="padding: 5px .75rem;">${data.payment_date}</td>
                            <td style="padding: 5px .75rem;">${numberWithCommas(data.total_amount_paid)}</td>
                            <td style="padding: 5px .75rem;">${numberWithCommas(data.total_amount_credited)}</td>
                            <td style="padding: 5px .75rem;">${data.payment_status_name}</td>
                            <td style="padding: 5px .75rem;">${data.issued_by}</td>
                            <td style="padding: 5px .75rem;"><button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="Cancel" class="btn btn-danger btn-sm" onclick="confirm_cancel_payment(${data.id})"><i class="fa fa-ban"></i></button></td>
                        </tr>`;
                    });
                }else{
                    particularsHtml += '<td colspan="8" class="text-center">No payments recorded.</td>';
                }
                
                $('#payment_table_body').html(particularsHtml);
            },
        });
    }

    function confirm_cancel_op(){
        var id = $("#transactionID").attr('data-id');
        var creator_user_id = $("#issuedBy").attr('data-user_id');
        var current_user_id = <?= session()->get('id'); ?>;
        if(current_user_id != creator_user_id){
            Swal.fire({
            position: "top-end",
            icon: "warning",
            text: "You cannot cancel transactions you didn't create.",
            showConfirmButton: false,
            timer: 10000
            });
        }else{
            if(active_payment > 0){
                Swal.fire({
                position: "top-end",
                icon: "warning",
                text: "You cannot cancel transactions with active payments.",
                showConfirmButton: false,
                timer: 10000
                });
            }else{
                var op_num = $('#orderPaymentNo').text();
                var id = $("#transactionID").attr('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you really want to cancel Order of Payment: "+op_num+"?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'No',
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                    if (result.isConfirmed) {
                        cancel_order_payment(id,op_num)
                    } 
                });
            }
        }
    }

    function cancel_order_payment(id,op_num){
        $.ajax({
            data : {id:id}
            ,type: "POST"
            ,url: '<?= base_url(); ?>payment/cancel-order-payment'
            , dataType: 'json'
            , crossOrigin: false
            , beforeSend : function() {
            }
            , success: function(result) {
                if(result.status == 'success'){
                    Swal.fire(
                    'Cancelled!',
                    'Order of Payment No: '+op_num+' has been cancelled.',
                    'success'
                    ).then((result) => {
                        location.reload();
                    })
                }
            }
        });
    }

    function confirm_cancel_payment(id){
        $.ajax({
            data : {id:id}
            ,type: "GET"
            ,url: '<?= base_url(); ?>payment/check-active-payment-receipt'
            , dataType: 'json'
            , crossOrigin: false
            , beforeSend : function() {
            }
            , success: function(result) {
                if(result.length > 0) {
                    Swal.fire({
                        position: "top-end",
                        icon: "warning",
                        text: "You cannot cancel payment with active receipts.",
                        showConfirmButton: false,
                        timer: 10000
                    });
                }else{
                    // Swal.fire({
                    //     title: 'Are you sure?',
                    //     text: "Do you really want to cancel this Payment?",
                    //     icon: 'warning',
                    //     showCancelButton: true,
                    //     confirmButtonColor: '#3085d6',
                    //     cancelButtonColor: '#d33',
                    //     cancelButtonText: 'No',
                    //     confirmButtonText: 'Confirm'
                    // }).then((result) => {
                    //     if (result.isConfirmed) {
                    //     cancel_client_payment(id)
                        
                    //     }
                    // });
                    Swal.fire({
                        title: 'Are you sure you want to cancel?',
                        icon: 'warning',
                        html: `
                            <p>Please provide a reason for canceling the payment.</p>
                            <textarea id="swal-textarea" class="swal2-textarea" placeholder="Enter your remarks here" style="width: 360px;"></textarea>
                        `,
                        focusConfirm: false,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, cancel it',
                        cancelButtonText: 'No, keep it',
                        reverseButtons: true,
                        preConfirm: () => {
                            const textValue = document.getElementById('swal-textarea').value.trim();
                            if (!textValue) {
                                Swal.showValidationMessage('Please provide a reason for canceling');
                            }
                            return textValue;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            cancel_client_payment(id,result.value)
                        }
                    });
                }
            }
        });

        
    }

    function cancel_client_payment(id,remarks){
        var op_id = $("#transactionID").attr('data-id');
        $.ajax({
            data : {id:id,op_id:op_id,remarks:remarks}
            ,type: "POST"
            ,url: '<?= base_url(); ?>payment/cancel-client-payment'
            , dataType: 'json'
            , crossOrigin: false
            , beforeSend : function() {
            }
            , success: function(result) {
                if(result.status == 'success'){
                    Swal.fire(
                    'Cancelled!',
                    'The payment has been cancelled.',
                    'success'
                    ).then((result) => {
                        get_all_order_of_payment()
                        view_order_payment(op_id);
                        // location.reload();
                    })
                }
            }
        });
    }

    function check_print(){ 
        var trans_no = $('#transactionID').text();

        $.ajax({
            type: 'get',
            data: {
                trans_no: trans_no
            },
            url: '<?= base_url(); ?>/payment/check-trans-number',
            success: function(result) {
                if (result.msg == "true") {
                    window.open("<?= base_url(); ?>pdf/generate-pdf-op/?token="+result.encryptText);
                }
            }
        })
    }

    function apply_text_color(tableId) {
        $('#' + tableId + ' td.status').each(function() {
            var status = $(this).data('status'); // Use data attribute to get status
            // Apply classes based on the status value
            switch(status) {
                case 1:
                    $(this).addClass('status-approved');
                    break;
                case 2:
                    $(this).addClass('status-pending');
                    break;
                case 0:
                    $(this).addClass('status-rejected');
                    break;
                // Add more cases as needed for other statuses
                default:
                    // Optionally handle unknown statuses
                    break;
            }
        });
    }

    function format_date(dateStr) {
        // Convert to Date object
        var date = new Date(dateStr);
        
        // Array of month names
        var monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        
        // Format the date to "Month Day, Year"
        var formattedDate = monthNames[date.getMonth()] + " " + date.getDate() + ", " + date.getFullYear();
        
        return formattedDate;
    }

    

    function receipt_payment(pay_id,unused_payment_amount){
        $.ajax({
            url: '<?= base_url(); ?>payment/check-payment-used-receipt',
            type: 'GET',
            data: { id: pay_id },
            dataType: "json",
            success: function(result) {
                var unused_amount = result[0].unused_amount
                if(unused_amount > 0){
                    var op_id = $("#transactionID").attr('data-id');
                    var trans_no = $("#transactionID").text();
                    $('#receipt_op_number').val(trans_no);
                    $('#receipt_op_id').val(op_id);
                    $('#receipt_payment_id').val(pay_id);
                    $('#unused_pay_credit').val(numberWithCommas(unused_payment_amount));
                    get_op_fund_code(op_id,pay_id);
                    $('#add-official-receipt').modal('show');
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Available Credit',
                        text: 'The credited payment amount has been fully used. You cannot add a new receipt.',
                        confirmButtonText: 'OK',
                        timer: 10000
                    });
                }
            },
        });

        
    }
    function confirm_cancel_or(id,payment_id){
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to cancel this Receipt?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No',
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if (result.isConfirmed) {
            cancel_official_receipt(id,payment_id)
            
            }
        });
    }
    function cancel_official_receipt(id,payment_id){
        $.ajax({
            data : {id:id,payment_id:payment_id}
            ,type: "POST"
            ,url: '<?= base_url(); ?>payment/cancel-official-receipt'
            , dataType: 'json'
            , crossOrigin: false
            , beforeSend : function() {
            }
            , success: function(result) {
                if(result.status == 'success'){
                    Swal.fire(
                    'Cancelled!',
                    'success'
                    ).then((result) => {
                        location.reload();
                    })
                }
            }
        });
    }

</script>
