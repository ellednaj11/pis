<div class="modal fade" id="payment-modal">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Payment Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        <form class="form-horizontal" id="payment_form">
            <div class="card-body">
                <div class=" row">
                    <div class="col-md-5">
                        <p><strong>PIS transaction No:</strong></p>
                    </div>
                    <div class="col-md-7">
                        <p><span id="pis_trans_no"></span></p>
                    </div>
                </div>
                <div class=" row">
                    <div class="col-md-5">
                        <p><strong>Payment Method:</strong></p>
                    </div>
                    <div class="col-md-7">
                        <p><span id="payment_method"></span></p>
                    </div>
                </div>
                <div class=" row">
                    <div class="col-md-5">
                        <p><strong>Payment Date:</strong></p>
                    </div>
                    <div class="col-md-7">
                        <p><span id="payment_date"></span></p>
                    </div>
                </div>
                <div class=" row">
                    <div class="col-md-5">
                        <p><strong>Payment Attachment:</strong></p>
                    </div>
                    <div class="col-md-7" id="file-buttons">
                    </div>
                </div>
                <div class=" row">
                    <div class="col-md-5">
                        <p><strong>Status:</strong></p>
                    </div>
                    <div class="col-md-7">
                        <p><span id="status"></span></p>
                    </div>
                </div>
                <div class=" row">
                    <div class="col-md-5">
                        <p><strong>Amount Paid:</strong></p>
                    </div>
                    <div class="col-md-7">
                        <p><span id="amount_paid"></span></p>
                    </div>
                </div>
                <div class=" row">
                    <div class="col-md-5">
                        <p><strong>Amount Credited:</strong></p>
                    </div>
                    <div class="col-md-7">
                        <p><span id="amount_credited"></span></p>
                    </div>
                </div>
                <div class="row client_detail" style="display: none;">
                    <div class="col-md-5">
                        <p><strong>Client's Email:</strong></p>
                    </div>
                    <div class="col-md-7">
                        <p><span id="email"></span></p>
                    </div>
                </div>
                <div class="row client_detail" style="display: none;">
                    <div class="col-md-5">
                        <p><strong>Client's Cellphone No:</strong></p>
                    </div>
                    <div class="col-md-7">
                        <p><span id="cp_num"></span></p>
                    </div>
                </div>
                
            </div>
            
        </form>
        </div>
        <div class="modal-footer justify-content-end">
            <button class="btn btn-success" id="acceptButton" onclick="accept_payment()" style="display: none;">Accept</button>
            <button class="btn btn-danger" id="rejectButton" onclick="confirm_reject_payment()" style="display: none;">Reject</button>
            <!-- <button class="btn btn-danger" id="cancelButton" onclick="confirm_cancel_payment()" style="display: none;">Cancel</button> -->
        </div>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->

</div>
<!-- Modal -->
<div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-body">
                <!-- Content will be loaded here dynamically -->
                <div id="file-content" style="text-align: center;">
                    <!-- Content goes here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
<script src="<?php echo base_url(); ?>assets/js/numeric.js"></script>
<!-- Validation -->
<script src="<?php echo base_url(); ?>public/plugins/jquery-validation/jquery.validate.min.js"></script>
<script>
    $(function () {
        $(document).on('click', '.view-file-btn', function(event) {
            event.preventDefault();  // Prevent form submission and page reload

            var filePath = $(this).data('file-path');
            var trans_num = $('#pis_trans_no').text();
            var trans_num_array = trans_num.split('-');
            // $baseUploadPath = 'public/uploads/'.$currentYear.'/'.$op_trans_num.'/payment';
            var fileUrl = 'public/uploads/'+trans_num_array[2]+'/'+trans_num_array[1]+'/'+trans_num+'/payment/' + filePath;  // Assuming files are in 'public/uploads/'
            console.log(fileUrl)
            // Load file content in the modal
            $.ajax({
                url: fileUrl,
                success: function(data, status, xhr) {
                    var contentType = xhr.getResponseHeader('Content-Type');
                    var fileContent;

                    if (contentType.startsWith('image')) {
                        fileContent = `<img src="` + fileUrl + `" alt="File" class="img-fluid">`;
                    } else if (contentType === 'application/pdf') {
                        fileContent = `<iframe src="` + fileUrl + `" style="width: 100%; height: 500px;" frameborder="0"></iframe>`;
                    }

                    $('#file-content').html(fileContent);
                    $('#fileModal').modal('show');
                },
                error: function() {
                    $('#file-content').html('<p>File not found or unsupported format.</p>');
                    $('#fileModal').modal('show');
                }
            });
        });
    });
    function confirm_cancel_payment(){
        // var op_num = columns[1].textContent;
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to cancel this Payment?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No',
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if (result.isConfirmed) {
                cancel_payment()
            }
        });
    }

    function confirm_reject_payment(){
        Swal.fire({
        title: 'Select a remarks',
        html: `
            <select id="swal-select" class="swal2-input">
            <option>Please provide the correct information</option>
            <option>Please provide a clear copy of the Proof of Payment</option>
            </select>
        `,
        focusConfirm: false,
        width: '650px',
        showCancelButton: true,
        preConfirm: () => {
            const selectedValue = document.getElementById('swal-select').value;
            if (!selectedValue) {
            Swal.showValidationMessage('Please select a remarks');
            }
            return selectedValue;
        }
        }).then((result) => {
        if (result.isConfirmed) {
            reject_payment(result.value)
        }
        });
    }

    function accept_payment(){
        var id = $('#payment_method').data('payment_id');
        var amount_paid = parseFloat($('#amount_paid').text().replace(/,/g, ''));
        var op_id = $('#pis_trans_no').data('trans_id');
        $.ajax({
            data : {id:id , amount_paid:amount_paid,op_id:op_id}
            ,type: "POST"
            ,url: '<?= base_url(); ?>payment/accept-client-payment'
            , dataType: 'json'
            , crossOrigin: false
            , beforeSend : function() {
            }
            , success: function(result) {
                if(result.status == 'success'){
                    Swal.fire(
                    'Accepted!',
                    // 'Order of Payment No: '+op_num+' has been cancelled.',
                    ).then((result) => {
                        location.reload();
                    })
                }
            }
        });
    }

    function reject_payment(remarks){
        var id = $('#payment_method').data('payment_id');
        $.ajax({
            data : {id:id,remarks:remarks}
            ,type: "POST"
            ,url: '<?= base_url(); ?>payment/reject-client-payment'
            , dataType: 'json'
            , crossOrigin: false
            , beforeSend : function() {
            }
            , success: function(result) {
                if(result.status == 'success'){
                    Swal.fire(
                    'Canceled!',
                    // 'Order of Payment No: '+op_num+' has been cancelled.',
                    ).then((result) => {
                        location.reload();
                    })
                }
            }
        });
    }

    function cancel_payment(){
        var id = $('#payment_method').data('payment_id');
        var op_id = $('#pis_trans_no').data('trans_id');
        console.log(id)
        $.ajax({
            data : {id:id, op_id:op_id}
            ,type: "POST"
            ,url: '<?= base_url(); ?>payment/cancel-client-payment'
            , dataType: 'json'
            , crossOrigin: false
            , beforeSend : function() {
            }
            , success: function(result) {
                if(result.status == 'success'){
                    Swal.fire(
                    'Canceled!',
                    // 'Order of Payment No: '+op_num+' has been cancelled.',
                    ).then((result) => {
                        location.reload();
                    })
                }
            }
        });
    }

    function cancel_client_payment(id){
        var op_id = $("#transactionID").attr('data-id');
        $.ajax({
            data : {id:id}
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
                    // 'Order of Payment No: '+op_num+' has been cancelled.',
                    'success'
                    ).then((result) => {
                        view_order_payment(op_id);
                        // location.reload();
                    })
                }
            }
        });
    }

    
</script>
