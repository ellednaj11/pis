<div class="modal fade" id="add-client-payment">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Add Payment for Transaction No. <span id="modal_title_payment"></span></h4>
        <button type="button" class="close" data-dismiss="modal" onClick="clear_draft()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body" style="max-height: 70vh;    overflow-y: auto;">
        <form class="form-horizontal" id="payment_form">
            <div class="card-body">
                <div class=" row">
                    <label for="payment_method"  class="col-sm-4 col-form-label">Payment Method </label>
                    <div class=" form-group col-sm-8">
                        <select class="form-control" id="payment_method" name="payment_method" onchange="check_method()">
                            <option disabled selected>Select an option</option>
                        </select>
                        <input  type="hidden" id="op_id" name="op_id">
                        <input  type="hidden" id="op_trans_num" name="op_trans_num">
                    </div>
                </div>
                <!-- if payment method is not in EMB Cashier -->
                <div id="outPayment" style="display: none;"> 
                    <div class="card card-primary card-outline">
                        <div class="card-body" style="padding: 10px;">
                            <div class=" row">
                                <label for="payment_receipt_no" class="col-sm-4 col-form-label">Payment Receipt No.</label>
                                <div class="form-group col-sm-8">
                                <input  type="text" class="form-control" id="payment_receipt_no" name="payment_receipt_no" placeholder="">
                                </div>
                            </div>
                            <div class=" row">
                                <label for="payment_attach" class="col-sm-4 col-form-label">Upload Payment Receipt</label>
                                <div class="form-group col-sm-8">
                                <input type="file" class="form-control numeric" id="payment_attach" name="payment_attach[]" placeholder="" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row">
                    <label for="payment_date" class="col-sm-4 col-form-label">Payment Date</label>
                    <div class="form-group col-sm-8">
                    <input  type="date" class="form-control" id="payment_date" name="payment_date" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="paid_cash" class="col-sm-4 col-form-label">Amount Paid (Cash)</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control numeric" id="paid_cash" name="paid_cash" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="paid_check" class="col-sm-4 col-form-label">Amount Paid (Check)</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control numeric" id="paid_check" name="paid_check" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="check_info" class="col-sm-4 col-form-label">Check Info</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control " id="check_info" name="check_info" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="total_paid" class="col-sm-4 col-form-label">Total Amount Paid</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control numeric" id="total_paid" name="total_paid" readonly>
                    </div>
                </div>
                <div class=" row">
                    <label for="amount_to_paid" class="col-sm-4 col-form-label">Amount to be paid</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control numeric" id="amount_to_paid" name="amount_to_paid" readonly>
                    </div>
                </div>
                
            </div>
            
        </form>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal" onClick="clear_draft()">Close</button>
            <button class="btn btn-primary" id="save_payment">Save</button>
        </div>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script src="<?php echo base_url(); ?>assets/js/numeric.js"></script>
<!-- Validation -->
<script src="<?php echo base_url(); ?>public/plugins/jquery-validation/jquery.validate.min.js"></script>
<script>
    var inputNames = ['payment_method', 'payment_date', 'paid_cash', 'paid_check','check_info'];

    $(function () {
        numeric_control();
        get_payment_method();
        
        $.validator.addMethod("checkAmountPaid", function(value, element) {
            var amountPaid = parseFloat(value.replace(/,/g, '')) || 0; // Remove commas and parse as float
            var payableAmount = parseFloat($('#amount_to_paid').val().replace(/,/g, '')) || 0;
            
            return amountPaid <= payableAmount; // Return true if amountPaid is less than or equal to payableAmount
        }, "Amount paid cannot be greater than the payable amount.");

        $.validator.addMethod("checkFiles", function(value, element, param) {
            var files = $(element)[0].files;
            if (files.length === 0) return false; // No file selected

            // Allowed file types
            var allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];

            // Validate each selected file
            for (var i = 0; i < files.length; i++) {
                var fileType = files[i].type;
                if (!allowedTypes.includes(fileType)) {
                    return false; // If a file type is not allowed
                }
            }
            return true; // All files are valid
        }, "Please attach a valid file (PDF, JPG, or PNG only).");

        $.validator.setDefaults({
            submitHandler: function (form) {
                confirm_submit(); // Uncomment this line if you want to submit the form
            }
        });

        
        var rules = {};
        var messages = {};
        // Loop through the input names array and set required rules and messages
        inputNames.forEach(function(name) {
            rules[name] = {
                required: true
            };
            messages[name] = {
                required: "This field is required"
            };
        });

        rules['total_paid'] = {
            checkAmountPaid: true // Use the custom validator for checking amount_paid
        }; 
        

        $('#payment_form').validate({
            rules: rules,
            messages: messages,
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#save_payment').click(function() {
            $('#payment_form').submit();
        });

        $('#paid_cash').on('keyup', function() {
            var paid_cash = $(this).val().replace(/,/g, "");
            var paid_check = $('#paid_check').val().replace(/,/g, "");
            total_calculation(paid_check, paid_cash);
        });

        $('#paid_check').on('keyup', function() {
            var paid_check = $(this).val().replace(/,/g, "");
            var paid_cash = $('#paid_cash').val().replace(/,/g, "");
            total_calculation(paid_check, paid_cash);
        });

        
    });
    const total_calculation = (paid_check,paid_cash) => {
        if(isNaN(paid_check) || paid_check == ""){
            paid_check = 0;
        }
        if(isNaN(paid_cash) || paid_cash == ""){
            paid_cash = 0;
        }
        var total = parseFloat(paid_check) + parseFloat(paid_cash);
        $('#total_paid').val(numberWithCommas(total));
    }

    function get_payment_method(){
        $.ajax({
            type: 'get'
            ,url: '<?= base_url(); ?>payment/get-payment-method-ref'
            , beforeSend : function() {
            }        
            , success: function(result) {
                for(var i = 0; i< result.length; i++) {
                    $('#payment_method').append('<option value="'+result[i]['id']+'">'+result[i]['method_name']+'</option>');                   
                }
            }
            , failure: function(msg) {
                console.log("Failure to connect to server!");
            }
            , error: function(status) {
                
            }
        });
    }

    

    function check_method(){
        const itemsToRemove = ['payment_receipt_no', 'payment_attach[]'];
        inputNames = inputNames.filter(item => !itemsToRemove.includes(item));
        const selectedValue = $('#payment_method').val();
        if(selectedValue != 1){
            $("#outPayment").show();
            inputNames.push('payment_receipt_no');
            inputNames.push('payment_attach[]');

            // Add rules for payment_receipt_no
            $(`[name="payment_receipt_no"]`).rules('add', {
                required: true,
                messages: {
                    required: "This field is required"
                }
            });

            // Add custom rules for payment_attach[] with the checkFiles validation method
            $(`[name="payment_attach[]"]`).rules('add', {
                required: true,
                checkFiles: true // Use custom file validation rule
            });
        }else{
            $("#outPayment").hide();

            // Remove validation rules for payment_receipt_no and payment_attach[]
            $(`[name="payment_receipt_no"]`).rules('remove');
            $(`[name="payment_attach[]"]`).rules('remove');
        }

        // inputNames.forEach(function(name) {
        //     $(`[name="${name}"]`).rules('add', {
        //         required: true,
        //         messages: {
        //             required: "This field is required"
        //         }
        //     });
        // });
    }

    function confirm_submit(){
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                submit_data();
            }
        });
    }

    function submit_data(){
        var formData = new FormData(document.getElementById('payment_form'));
        var op_id = $("#modal_title_payment").attr('data-id');
        // var payment_method = $("#payment_method").val();
        // var payment_receipt_no = $("#payment_receipt_no").val();
        // var payment_attach = $("#payment_attach").val();
        // var payment_date = $("#payment_date").val();
        // var op_id = $("#modal_title_payment").attr('data-id');
        // var amount_paid_cash = $("#paid_cash").val().replace(/,/g, "");
        // var amount_paid_check = $("#paid_check").val().replace(/,/g, "");
        // var check_info = $("#check_info").val();
        // var total_amount_paid = $("#total_paid").val().replace(/,/g, "");
        // var amount_to_paid = $("#amount_to_paid").val().replace(/,/g, "");

        // var hdr = { payment_method : payment_method, 
        //             payment_receipt_no : payment_receipt_no,
        //             payment_date : payment_date, 
        //             op_id : op_id, 
        //             payable_amount : amount_to_paid, 
        //             amount_paid_cash : amount_paid_cash, 
        //             amount_paid_check : amount_paid_check,
        //             check_info : check_info, 
        //             total_amount_paid : total_amount_paid};

        // var data = { hdr : hdr}
        $.ajax({
            data: formData
            , type: "POST"
            , url: "<?php echo base_url('payment/save-client-payment'); ?>"
            , contentType: false // Prevent jQuery from setting the content type
            , processData: false // Prevent jQuery from automatically transforming the data into a query string
            , beforeSend: function () {
                $("#save_payment").prop("disabled", true);
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we process your request.',
                    didOpen: () => {
                    Swal.showLoading();
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false
                });
            }
            , success: function (result) {
                if(result.status == 'success'){
                    $("#save_payment").prop("disabled", false);
                    Swal.fire({
                        title: 'Successfully Saved!',
                        icon: 'success',
                        timer: 2000,
                        timerProgressBar: true,
                    }).then((result2) => {
                        // location.reload();
                        clear_draft()
                        $('#add-client-payment').modal('hide');
                        get_all_order_of_payment() 
                        view_order_payment(result.op_id);
                    })
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.error
                    });
                }
            }, failure: function (msg) {
                console.log("Erorr connecting to server...");
            }, error: function (status) {

            }

        });
    }

    function clear_draft(){
        $('#payment_form').find('input, textarea, select').val('');
        $("#outPayment").hide();
        const itemsToRemove = ['payment_receipt_no', 'payment_attach'];
        inputNames = inputNames.filter(item => !itemsToRemove.includes(item));
    }
</script>
