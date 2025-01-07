<div class="modal fade" id="payment-modal">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Add Payment</h4>
        <button type="button" class="close" data-dismiss="modal" onClick="clear_draft()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        <form class="form-horizontal" id="payment_form">
            <div class="card-body">
                <div class=" row">
                    <label for="payment_method"  class="col-sm-4 col-form-label">Payment Method </label>
                    <div class=" form-group col-sm-8">
                        <select class="form-control" id="payment_method" name="payment_method">
                            <option disabled selected>Select an option</option>
                        </select>
                    </div>
                </div>
                <div class=" row">
                    <label for="payment_receipt_no" class="col-sm-4 col-form-label">Payment Receipt No.</label>
                    <div class="form-group col-sm-8">
                    <input  type="text" class="form-control" id="payment_receipt_no" name="payment_receipt_no" placeholder="">
                    <input  type="hidden" id="trans_no" name="trans_no" placeholder="" value="<?= esc($header_data['trans_no']) ?>">
                    <input  type="hidden" id="op_id" name="op_id" placeholder="" value="<?= esc($header_data['id']) ?>">
                    </div>
                </div>
                <div class=" row">
                    <label for="payment_date" class="col-sm-4 col-form-label">Payment Date</label>
                    <div class="form-group col-sm-8">
                    <input  type="date" class="form-control" id="payment_date" name="payment_date" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="payment_attach" class="col-sm-4 col-form-label">Upload Payment Receipt</label>
                    <div class="form-group col-sm-8">
                    <input type="file" class="form-control" id="payment_attach" name="payment_attach[]" placeholder="" multiple>
                    </div>
                </div>
                <div class=" row">
                    <label for="amount_paid" class="col-sm-4 col-form-label">Payable Amount</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control numeric" id="payable_amount" disabled>
                    </div>
                </div>
                <div class=" row">
                    <label for="amount_paid" class="col-sm-4 col-form-label">Amount Paid</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control numeric" id="amount_paid" name="amount_paid" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="client_email" class="col-sm-4 col-form-label">Email</label>
                    <div class="form-group col-sm-8">
                    <input type="email" class="form-control" id="client_email" name="client_email" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="cell_number" class="col-sm-4 col-form-label">Cellphone Number</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control numeric_no_comma" id="cell_number" name="cell_number" minlength="11"  maxlength="11">
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
    $(function () {
        numeric_control();

        $.validator.addMethod("checkAmountPaid", function(value, element) {
            var amountPaid = parseFloat(value.replace(/,/g, '')) || 0; // Remove commas and parse as float
            var payableAmount = parseFloat($('#payable_amount').val().replace(/,/g, '')) || 0;

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

        var inputNames = ['payment_method','payment_receipt_no', 'payment_date', 'payment_attach', 'amount_paid','amount_to_paid','client_email','cell_number'];
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

        rules['amount_paid'] = {
            required: true,
            checkAmountPaid: true // Use the custom validator for checking amount_paid
        };

        rules['payment_attach[]'] = {
            required: true,
            checkFiles: true // Use custom file validation rule
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
            console.log("asd")
            $('#payment_form').submit();
        });

        // $('#amount_paid').on('keyup', function() {
        //     var amountPaid = parseFloat($(this).val().replace(/,/g, '')) || 0;
        //     var payableAmount = parseFloat($('#payable_amount').val().replace(/,/g, '')) || 0;
        //     console.log(payableAmount)
        //     if (amountPaid > payableAmount) {
        //         $(this).val('');
        //     } else {
        //         $(this).val(amountPaid);
        //     }
        // });

        get_payment_method();
    });


    const numeric_control = () => {
        $(".numeric").numeric({ decimal : ".",  negative : false, scale: 2 });
        $(".numeric_no_comma").numeric({ decimal : ".",  negative : false, scale: 2 });
        $(".numeric_per").numeric({ decimal : ".",  negative : false, precision: 4, scale: 2 });
        $(".numeric2").numeric({ decimal : ".",  negative : false, scale: 8 });
        $(".numeric_cp_num").numeric({ decimal : ".",  negative : false, scale: 8 });

        $('.numeric').keyup(function(event) {
            // skip for arrow keys
            if(event.which >= 37 && event.which <= 40){
            event.preventDefault();
            }

            $(this).val(function(index, value) {
                value = value.replace(/,/g,''); // remove commas from existing input
                return numberWithCommas(value); // add commas back in
            });
        });

        $(".numeric_cp_num").on('input', function() {
            if ($(this).val().length > 11) {
                $(this).val($(this).val().slice(0, 11));
            }
        });

        $('.numeric_no_comma').keyup(function(event) {
            // skip for arrow keys
            if(event.which >= 37 && event.which <= 40){
            event.preventDefault();
            }

            $(this).val(function(index, value) {
                return value
            });
        });

        $('.numeric2').keyup(function(event) {
            // skip for arrow keys
            if(event.which >= 37 && event.which <= 40){
            event.preventDefault();
            }

            $(this).val(function(index, value) {
                value = value.replace(/,/g,''); // remove commas from existing input
                return numberWithCommas(value); // add commas back in
            });
        });

        function numberWithCommas(x) {
                var parts = x.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return parts.join(".");
        }
    };

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
                submit_op();
            }
        });
    }

    function submit_op() {

        // Create a FormData object using the form element
        var formData = new FormData(document.getElementById('payment_form'));
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        // Send data to server using AJAX
        $.ajax({
            url: "<?php echo base_url('client/save-client-payment'); ?>", // Your PHP route here
            type: 'POST',
            data: formData,
            crossOrigin: false,
            contentType: false, // Prevent jQuery from setting the content type
            processData: false, // Prevent jQuery from automatically transforming the data into a query string
            beforeSend: function () {
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
            },
            success: function (result) {
            if (result.status == 'success') {
                Swal.fire({
                title: 'Successfully Saved!',
                icon: 'success',
                timer: 2000,
                timerProgressBar: true,
                }).then((result) => {
                location.reload();
                });
            } else {
                Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.error
                });
            }
            },
            error: function (xhr, status, error) {
            console.log("Error connecting to server...");
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'Something went wrong. Please try again later.'
            });
            },
            complete: function () {
            $("#save_payment").prop("disabled", false); // Re-enable the submit button
            }
        });
    }

    function get_payment_method(){
        $.ajax({
            type: 'get'
            ,url: '<?= base_url(); ?>payment/get-payment-method-ref'
            , beforeSend : function() {
            }
            , success: function(result) {
                var indexToRemove = 0; //Index of EMB Cashier
                result.splice(indexToRemove, 1);
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

    function clear_draft(){
        $('#payment_form').find('input, textarea, select').val('');
    }
    function numberWithCommas(x) {
                var parts = x.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return parts.join(".");
        }
</script>
