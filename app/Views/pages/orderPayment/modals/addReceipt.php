<div class="modal fade" id="add-official-receipt">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Create Official Receipt</h4>
        <button type="button" class="close" data-dismiss="modal" onClick="clear_receipt_draft()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body" style="max-height: 70vh;    overflow-y: auto;">
        <form class="form-horizontal" id="receipt_form">
            <div class="card-body">
                <div class=" row">
                    <label for="rec_fund_code"  class="col-sm-4 col-form-label">Fund Code</label>
                    <div class=" form-group col-sm-8">
                        <select class="form-control" id="rec_fund_code" name="rec_fund_code">
                            <option disabled selected>Select an option</option>
                        </select>
                        <input  type="hidden" id="receipt_op_id" name="receipt_op_id">
                        <input  type="hidden" id="receipt_op_number" name="receipt_op_number">
                        <input  type="hidden" id="receipt_payment_id" name="receipt_payment_id">
                    </div>
                </div>
                <div class=" row">
                    <label for="rec_bank" class="col-sm-4 col-form-label">Bank</label>
                    <div class="form-group col-sm-8">
                    <input  type="text" class="form-control" id="rec_bank" name="rec_bank" placeholder="" readonly>
                    </div>
                </div>
                <div class=" row">
                    <label for="rec_bank_acc" class="col-sm-4 col-form-label">Bank Account</label>
                    <div class="form-group col-sm-8">
                    <input  type="text" class="form-control" id="rec_bank_acc" name="rec_bank_acc" placeholder="" readonly>
                    </div>
                </div>
                <div class=" row">
                    <label for="rec_book"  class="col-sm-4 col-form-label">Receipt Book</label>
                    <div class=" form-group col-sm-8">
                        <select class="form-control" id="rec_book" name="rec_book">
                            <option disabled selected>Select Receipt Book</option>
                        </select>
                    </div>
                </div>
                <div class=" row">
                    <label for="or_num"  class="col-sm-4 col-form-label">Official Receipt No.</label>
                    <div class=" form-group col-sm-8">
                        <select class="form-control" id="or_num" name="or_num">
                            <option disabled selected>Select Receipt Number</option>
                        </select>
                    </div>
                </div>
                <div class=" row">
                    <label for="or_date"  class="col-sm-4 col-form-label">Official Receipt No. Date</label>
                    <div class="form-group col-sm-8">
                        <input type="date" class="form-control" id="or_date" name="or_date">
                    </div>
                </div>
                <div class=" row">
                    <label for="unused_pay_credit" class="col-sm-4 col-form-label">Unused Payment Credit</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control numeric" id="unused_pay_credit" name="unused_pay_credit" disabled>
                    </div>
                </div>
                <!-- <div class=" row">
                    <label for="rec_total_paid" class="col-sm-4 col-form-label">Total Amount Paid</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control numeric" id="rec_total_paid" name="rec_total_paid" >
                    </div>
                </div> -->
                <div class=" row">
                    <label for="rec_amount_to_paid" class="col-sm-4 col-form-label">Amount to be paid</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control numeric" id="rec_amount_to_paid" name="rec_amount_to_paid" readonly>
                    </div>
                </div>
                <div class=" row">
                    <label for="or_attach" class="col-sm-4 col-form-label">Upload Official Receipt</label>
                    <div class="form-group col-sm-8">
                    <input type="file" class="form-control" id="or_attach" name="or_attach[]" placeholder="" multiple>
                    </div>
                </div>
                
            </div>
            
        </form>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal" onClick="clear_receipt_draft()">Close</button>
            <button class="btn btn-primary" id="save_receipt">Save</button>
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
        var op_id = $("#modal_title_payment").attr('data-id');
        // numeric_control();
        
        var inputNames = ['rec_fund_code', 'rec_bank', 'rec_bank_acc', 'rec_book','or_num','or_date','rec_amount_to_paid','or_attach'];

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

        $.validator.addMethod("checkAmount", function(value, element) {
            var payableAmount = parseFloat(value.replace(/,/g, '')) || 0; // Remove commas and parse as float
            var unusedCredit = parseFloat($('#unused_pay_credit').val().replace(/,/g, '')) || 0;
            return payableAmount <= unusedCredit;
        }, "Amount paid cannot be greater than the Unused Payment Credit.");

        $.validator.setDefaults({
            submitHandler: function (form) {
                confirm_submit_receipt(); // Uncomment this line if you want to submit the form
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

        rules['rec_amount_to_paid'] = {
            checkAmount: true
        };

        rules['or_attach[]'] = {
            required: true,
            checkFiles: true // Use custom file validation rule
        };
        
        

        $('#receipt_form').validate({
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

        $('#save_receipt').click(function() {
            $('#receipt_form').submit();
        });

        // When an option is selected
        $('#rec_fund_code').on('change', function() {
            
            // Get the selected fund code
            var selectedCode = $(this).val();
            
            // Find the matching data from the retrieved options
            var selectedData = data.find(item => item.item_fund_code === selectedCode);
            
            if (selectedData) {
                // Fill the other input fields with the selected data
                $('#rec_bank').val(selectedData.item_bank_name);
                $('#rec_bank_acc').val(selectedData.item_bank_account);
                $('#rec_amount_to_paid').val(numberWithCommas(selectedData.total_sub_total));
                $('#rec_book').empty().append('<option disabled selected>Select Receipt Book</option>');
                $('#or_num').empty().append('<option disabled selected>Select Receipt Number</option>');
                get_receipt_book(selectedCode)
            }
        });

        $('#rec_book').on('change', function() {
            var selectedValue = $(this).val();  // Get the receipt book ID
            var selectedText = $("#rec_book option:selected").text();  // Get the receipt book text (e.g. "93030000 - 93030500")
            
            // Split the value to extract or_start and or_end
            var orRange = selectedText.split(' - ');
            var orStart = parseInt(orRange[0]);
            var orEnd = parseInt(orRange[1]);
            $('#or_num').empty().append('<option disabled selected>Select Receipt Number</option>');
            getUsedReceipts(selectedValue, orStart, orEnd)
        });
    });

    function get_op_fund_code(op_id,pay_id){
        $.ajax({
            type: 'get'
            ,data: { op_id: op_id, pay_id:pay_id }
            ,url: '<?= base_url(); ?>payment/get-op-fund-code'
            , beforeSend : function() {
            }        
            , success: function(result) {
                data = result;
                var select = $('#rec_fund_code');
                select.empty().append('<option disabled selected>Select Fund Code</option>');
                $.each(data, function(index, item) {
                    var optionText = item.item_fund_code;
                    var isDisabled = false;

                    if (item.or_trans_num) {
                        optionText += ' (already have receipt)';
                        isDisabled = true; // Disable the option if or_trans_num has a value
                    }
                    select.append(
                        $('<option>', {
                            value: item.item_fund_code,
                            text: optionText,
                            disabled: isDisabled // Apply the disabled attribute if needed
                        })
                    );
                });
            }
            , failure: function(msg) {
                console.log("Failure to connect to server!");
            }
            , error: function(status) {
                
            }
        });
    }

    function get_receipt_book(fund_code){
        $.ajax({
            type: 'get'
            ,data: { fund_code: fund_code }
            ,url: '<?= base_url(); ?>payment/get-spec-receipt-book'
            , beforeSend : function() {
            }        
            , success: function(result) {
                var select = $('#rec_book');
                select.empty().append('<option disabled selected>Select Receipt Book</option>');
                $.each(result, function(index, item) {
                    select.append(
                        $('<option>', {
                            value: item.id,
                            text: item.book_option
                        })
                    );
                });
            }
            , failure: function(msg) {
                console.log("Failure to connect to server!");
            }
            , error: function(status) {
                
            }
        });
    }

    // Function to get used receipts from the server and populate the third select
    function getUsedReceipts(receiptBookId, orStart, orEnd) {
        $.ajax({
            url: '<?= base_url(); ?>payment/get-used-receipt',
            method: 'GET',
            data: { receipt_book_id: receiptBookId }, // Send the receipt book ID to the server
            success: function(response) {
                // Assume response is an array of used receipt numbers like [93030001, 93030002, ...]
                var usedReceipts = response.map(function(item) {
                    return parseInt(item.official_receipt_no); // Extract only the or_num and convert to integer
                });
                console.log(orStart)
                // Populate the third select with numbers between orStart and orEnd
                var select = $('#or_num');
                select.empty().append('<option disabled selected>Select Receipt Number</option>');

                for (var i = orStart; i <= orEnd; i++) {
                    var option = $('<option>', { value: i, text: i });

                    // Disable the option if the receipt number is in the usedReceipts array
                    if (usedReceipts.includes(i)) {
                        option.attr('disabled', true).text(i + ' (Used)');
                    }

                    select.append(option);
                }
            }
        });
    }

    function confirm_submit_receipt(){
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
                submit_receipt_data();
            }
        });
    }

    function submit_receipt_data(){
        var formData = new FormData(document.getElementById('receipt_form'));
        var op_id = $("#modal_title_payment").attr('data-id');
        
        $.ajax({
            data: formData
            , type: "POST"
            , url: "<?php echo base_url('payment/save-official-receipt'); ?>"
            , contentType: false // Prevent jQuery from setting the content type
            , processData: false // Prevent jQuery from automatically transforming the data into a query string
            , beforeSend: function () {
                $("#save_receipt").prop("disabled", true);
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
                    $("#save_receipt").prop("disabled", false);
                    Swal.fire({
                        title: 'Successfully Saved!',
                        icon: 'success',
                        timer: 2000,
                        timerProgressBar: true,
                    }).then((result2) => {
                        // location.reload();
                        clear_receipt_draft()
                        $('#add-official-receipt').modal('hide');
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

    function clear_receipt_draft(){
        $('#receipt_form').find('input, textarea, select').val('');
        // Remove validation error classes and messages
        $('#receipt_form').find('.is-invalid').removeClass('is-invalid');
        $('#receipt_form').find('.invalid-feedback').remove();
    }
</script>
