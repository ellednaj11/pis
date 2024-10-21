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
                            <p><span id="status_view"></span></p>
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
                            <td style="padding: 5px .75rem;" id="amount_paid_view"></td>
                        </tr>
                        <tr>
                            <td style="padding: 5px .75rem;" colspan="6"><b>Balance</b></td>
                            <td style="padding: 5px .75rem;" id="balance"></td>
                        </tr>
                    </tfoot>
                </table>

                <h5>Payments</h5>
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
                        </tr>
                    </thead>
                    <tbody id="payment_modal_table_body">
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

</script>
