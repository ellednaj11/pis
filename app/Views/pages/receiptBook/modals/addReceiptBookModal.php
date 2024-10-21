<div class="modal fade" id="add-receipt-modal">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Add Receipt Book</h4>
        <button type="button" class="close" data-dismiss="modal" onClick="clear_draft()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        <form class="form-horizontal" id="receipt_form">
            <div class="card-body">
                <div class=" row">
                    <label for="fund_code"  class="col-sm-4 col-form-label">Fund Code</label>
                    <div class=" form-group col-sm-8">
                    <input type="text" class="form-control" id="fund_code" name="fund_code" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="start_num" class="col-sm-4 col-form-label">Starting No.</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control numeric_no_comma" id="start_num" name="start_num" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="end_num" class="col-sm-4 col-form-label">Ending No.</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control numeric_no_comma" id="end_num" name="end_num" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="qty" class="col-sm-4 col-form-label">Quantity</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control numeric_no_comma" id="qty" name="qty" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="book_val" class="col-sm-4 col-form-label">Book Value</label>
                    <div class="form-group col-sm-8">
                    <input type="text" class="form-control numeric" id="book_val" name="book_val" placeholder="">
                    </div>
                </div>
                
            </div>
            
        </form>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal" onClick="clear_draft()">Close</button>
            <button class="btn btn-primary" id="save_receipt_book">Save</button>
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

        $.validator.setDefaults({
            submitHandler: function (form) {
                confirm_submit(); // Uncomment this line if you want to submit the form
            }
        });

        var inputNames = ['fund_code', 'start_num', 'end_num', 'book_val','qty'];
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

        $('#save_receipt_book').click(function() {
            console.log("asd")
            $('#receipt_form').submit();
        });

        
    });
    const numeric_control = () => {
        $(".numeric").numeric({ decimal : ".",  negative : false, scale: 2 });
        $(".numeric_no_comma").numeric({ decimal : ".",  negative : false, scale: 2 });
        $(".numeric_per").numeric({ decimal : ".",  negative : false, precision: 4, scale: 2 });
        $(".numeric2").numeric({ decimal : ".",  negative : false, scale: 8 });
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

    function submit_op(){
        var fund_code = $("#fund_code").val();
        var or_number_start = $("#start_num").val();
        var or_number_end = $("#end_num").val();
        var qty = $("#qty").val();
        var book_val = $("#book_val").val();
        var used_qty = 0;
        var status = 1;

        var hdr = { fund_code : fund_code, 
                    or_number_start : or_number_start,
                    or_number_end : or_number_end, 
                    orig_qty : qty, 
                    used_qty : used_qty, 
                    book_value : book_val, 
                    status : status};

        var data = { hdr : hdr}
        $.ajax({
            data: data
            , type: "POST"
            , url: "<?php echo base_url('receipt/save-receipt-book'); ?>"
            , dataType: "json"
            , crossOrigin: false 
            , beforeSend: function () {
                $("#save_receipt_book").prop("disabled", true);
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
                    Swal.fire({
                        title: 'Successfully Saved!',
                        icon: 'success',
                        timer: 2000,
                        timerProgressBar: true,
                    }).then((result) => {
                        location.reload();
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
        $('#receipt_form').find('input, textarea, select').val('');
    }
</script>
