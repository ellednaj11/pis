<div class="modal fade" id="edit-details">
    <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Edit Schedule of Fees</h4>
        <input type="hidden" id="edit_id"></input>
        <button type="button" class="close" data-dismiss="modal" onClick="clear_draft_edit()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        <form class="form-horizontal" id="particular_form_edit">
            <div class="card-body">
                <div class=" row">
                    <label for="payment_for_edit"  class="col-sm-2 col-form-label">Payment Type</label>
                    <div class=" form-group col-sm-10">
                    <input type="text" class="form-control" id="payment_for_edit" name="payment_for_edit" placeholder="">
                    </div>
                </div>
                
                <div class="col-lg-3 mb-3">
                    <button type="button" class="btn btn-primary" id="add_particular_edit">Add Particular</button>
                </div>
                <table class="table table-striped" id="particular_table_edit" style="width:100% !important">
                    <thead>
                        <tr>
                        <th>Particular</th>
                        <th style="width: 100px">Fund Code</th>
                        <th>Amount</th>
                        <th style="width: 320px">Bank Name</th>
                        <th style="width: 180px">Bank Account</th>
                        <th style="width: 40px">Action</th>
                        </tr>
                    </thead>
                    <tbody  id="particularsTableBodyEdit">
                    </tbody>
                </table>
                
            </div>
            
        </form>
        </div>
        <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal" onClick="clear_draft_edit()">Close</button>
        <button type="submit" class="btn btn-primary" id="update_data">Save</button>
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

        $.validator.setDefaults({
            submitHandler: function (form) {
                // Optionally submit the form if you want to proceed with the form submission
                if(validate_table_edit() == 0){
                    confirm_submit_edit(); // Uncomment this line if you want to submit the form
                }
            }
        });

        var inputNames = ['payment_for_edit'];
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

        $('#particular_form_edit').validate({
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

        $('#update_data').click(function() {
            $('#particular_form_edit').submit();
        });
        
    });

    function confirm_submit_edit(){
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
                submit_edit();
            }
        });
    }

    function submit_edit(){
        var payment_for = $("#payment_for_edit").val();

        var dtl = [];
        $('#particular_table_edit > tbody > tr').each(function (row, tr){
            dtl.push({
                "particular_name" : $(tr).find('td:eq(0) input').val(),
                "fund_code" : $(tr).find('td:eq(1) input').val(),
                "particular_amount" : $(tr).find('td:eq(2) input').val(),
                "bank_name" : $(tr).find('td:eq(3) select').val(),
                "bank_account" : $(tr).find('td:eq(4) select').val(),
            });
        });

        var hdr = { 
            name: payment_for,
        };

        var data = { hdr : hdr, dtl : dtl}

        $.ajax({
            data: data
            , type: "POST"
            , url: "<?php echo base_url('schedfees/save-schedule-fees'); ?>"
            , dataType: "json"
            , crossOrigin: false 
            , beforeSend: function () {
                $("#update_data").prop("disabled", true);
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

    
    // Start of particular tables functionality
    var refresh_particular_edit_index = function() {
        var total_tr = $('#particular_table_edit > tbody > tr').length;
        $('#particular_table_edit > tbody > tr').each(function (i) {

            i++;
            var select = $(this).find('select');
            var text = $(this).find('input');
            var button = $(this).find('button');

            text.eq(0).attr('id', 'td_item-'+i);
            text.eq(1).attr('id', 'td_fund_code-'+i);
            text.eq(2).attr('id', 'td_amount-'+i);
            select.eq(0).attr('id', 'bank_name-'+i);
            select.eq(1).attr('id', 'bank_acc-'+i);

            // select.eq(0).attr('id', 'td_comp-'+i);

            button.eq(0).attr('id', 'delete_par-'+i);

            if(total_tr > 1) {
                $('#delete_par-'+i).removeAttr('disabled');
            }else if(total_tr <= 1) {
                $('#delete_par-'+i).prop('disabled',true);
            }
            particular_controls_edit(i);
        });
    }

    var particular_controls_edit = function(srl_num) {
        $('.delete_par').unbind('click').bind('click', function() {
            $(this).closest('tr').remove();
            refresh_particular_edit_index();
        });
    }

    particular_controls_edit(1);
    
    $('#add_particular_edit').on('click', function() {
        var len = $("#particular_table_edit > tbody > tr").length+1;
        var bankOptions = '<option disabled selected>Select an option</option>';
        $.each(banks, function(key, bank) {
            bankOptions += '<option value="' + bank.value + '">' + bank.value + '</option>';
        });

        var accountOptions = '<option disabled selected>Select an option</option>';
        $.each(accounts, function(key, accounts) {
            accountOptions += '<option value="' + accounts.value + '">' + accounts.value + '</option>';
        });


        $('#particular_table_edit > tbody:last').append(
            '<tr>'
            +'<td><input class="form-control input-sm td_inputs " type="text"  value = "" ></td>'

            +'<td><input class="form-control input-sm td_inputs " type="text" value = "" ></td>'

            +'<td><input class="form-control input-sm td_inputs  numeric" type="text" value = ""></td>'

            +'<td><select class="form-control td_inputs">'
                +bankOptions 
            +'</select></td>'

            +'<td><select class="form-control td_inputs">'
                +accountOptions 
            +'</select></td>'
                                                        
            +'<td>' 
            +'<button style="width:29px; padding:5px;" class="btn btn-danger btn-sm delete_par"><i class="fa fa-trash"></i></button>'
            +'</td>'
            +'</tr>'
        );
        particular_controls_edit(len);
        refresh_particular_edit_index();
        numeric_control();
    });

    function validate_table_edit(){
        var error = 0;
        // Loop through each row and add validation rules to inputs
        $('#particular_table_edit > tbody > tr').each(function (row, tr) {
            if($(tr).find('td:eq(0) input').val() == ""){
	            error = 1;
	            $(tr).find('td:eq(0) input').addClass('is-invalid');
	        }else{
	            $(tr).find('td:eq(0) input').removeClass('is-invalid');
	        }

            if($(tr).find('td:eq(1) input').val() == ""){
	            error = 1;
	            $(tr).find('td:eq(1) input').addClass('is-invalid');
	        }else{
	            $(tr).find('td:eq(1) input').removeClass('is-invalid');
	        }

            if($(tr).find('td:eq(2) input').val() == ""){
	            error = 1;
	            $(tr).find('td:eq(2) input').addClass('is-invalid');
	        }else{
	            $(tr).find('td:eq(2) input').removeClass('is-invalid');
	        }

	        if($(tr).find('td:eq(3) select').val() == null){
	            error = 1;
	            $(tr).find('td:eq(3) select').addClass('is-invalid');
	        }else{
	            $(tr).find('td:eq(3) select').removeClass('is-invalid');
	        }

            if($(tr).find('td:eq(4) select').val() == null){
	            error = 1;
	            $(tr).find('td:eq(4) select').addClass('is-invalid');
	        }else{
	            $(tr).find('td:eq(4) select').removeClass('is-invalid');
	        }
        });

        return error
    }
    // End of particular tables functionality
    function clear_draft_edit(){
        $('#particular_form_edit').find('input, textarea, select').val('');
        $('#particular_table_edit tbody').empty();
    }
    
</script>
