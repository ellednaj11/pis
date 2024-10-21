<div class="modal fade" id="add-bank-modal">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Add Bank Account</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        <form class="form-horizontal" id="bank_form">
            <div class="card-body">
                <div class=" row">
                    <label for="acc_num"  class="col-sm-3 col-form-label">Account Number</label>
                    <div class=" form-group col-sm-9">
                    <input type="text" class="form-control" id="acc_num" name="acc_num" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="acc_name" class="col-sm-3 col-form-label">Account name</label>
                    <div class="form-group col-sm-9">
                    <input type="text" class="form-control " id="acc_name" name="acc_name" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="acc_type" class="col-sm-3 col-form-label">Account Type</label>
                    <div class="form-group col-sm-9">
                    <input type="text" class="form-control " id="acc_type" name="acc_type" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="bank_name" class="col-sm-3 col-form-label">Bank Name</label>
                    <div class="form-group col-sm-9">
                    <input type="text" class="form-control " id="bank_name" name="bank_name" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="bank_branch" class="col-sm-3 col-form-label">Bank Branch</label>
                    <div class="form-group col-sm-9">
                    <!-- <input type="textarea" class="form-control" id="bank_branch" name="bank_branch" placeholder=""> -->
                    <textarea class="form-control" id="bank_branch" name="bank_branch" placeholder=""></textarea>
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

        var inputNames = ['acc_num', 'acc_name', 'acc_type', 'bank_name','bank_branch'];
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

        $('#bank_form').validate({
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
            $('#bank_form').submit();
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
        var acc_num = $("#acc_num").val();
        var acc_name = $("#acc_name").val();
        var acc_type = $("#acc_type").val();
        var bank_name = $("#bank_name").val();
        var bank_branch = $("#bank_branch").val();

        var hdr = { account_number : acc_num, 
                    account_name : acc_name,
                    account_type : acc_type, 
                    bank_name : bank_name, 
                    location : bank_branch,};

        var data = { hdr : hdr}
        $.ajax({
            data: data
            , type: "POST"
            , url: "<?php echo base_url('bankAcc/save-bank-account'); ?>"
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
        $('#bank_form').find('input, textarea, select').val('');
    }
</script>
