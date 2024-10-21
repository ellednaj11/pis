<div class="modal fade" id="payment-modal">
    <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Draft Order of Payment</h4>
        <button type="button" class="close" data-dismiss="modal" onClick="clear_op_draft()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body" id="add_modal_body">
        <form class="form-horizontal" id="particular_form">
            <div class="card-body">
                <div class=" row">
                    <label for="establishment_name"  class="col-sm-2 col-form-label">Establishment Name</label>
                    <div class=" form-group col-sm-10">
                    <input type="text" class="form-control" id="establishment_name" name="establishment_name" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="establishment_add" class="col-sm-2 col-form-label">Establishment Address</label>
                    <div class="form-group col-sm-10">
                    <input type="text" class="form-control" id="establishment_add" name="establishment_add" placeholder="">
                    </div>
                </div>
                <div class=" row">
                    <label for="company_name" class="col-sm-2 col-form-label">Proponent/Company Name</label>
                    <div class="form-group col-sm-10">
                    <input type="text" class="form-control" id="company_name" name="company_name" placeholder="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <label for="op_num" class="col-sm-4 col-form-label">OP Number</label>
                            <div class="form-group col-sm-8">
                                <input type="text" class="form-control" id="op_num" name="op_num" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <label for="issued_date" class="col-sm-4 col-form-label">Issued Date</label>
                            <div class="form-group col-sm-8">
                                <input type="date" class="form-control" id="issued_date" name="issued_date" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <label for="payment_for" class="col-sm-4 col-form-label">Payment For <button id="payment_tips" class="btn btn-info btn-sm" style="display: none;"><i class="fa fa-question"></i></button></label>
                            <div class="form-group col-sm-8">
                                <select class="form-control" id="payment_for" name="payment_for" onchange="get_particular()">
                                    <option disabled selected>Select an option</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <label for="expiration_date" class="col-sm-4 col-form-label">Expiration Date</label>
                            <div class="form-group col-sm-8">
                                <input type="date" class="form-control" id="expiration_date" name="expiration_date" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <button type="button" class="btn btn-primary" id="add_particular">Add Particular</button>
                </div>
                <table class="table table-striped" id="particular_table" style="width:100% !important">
                    <thead>
                        <tr>
                        <th>Item</th>
                        <th style="width: 100px">Fund Code</th>
                        <th>Bank</th>
                        <th style="width: 160px">Bank Account</th>
                        <th style="width: 100px">Cost per item</th>
                        <th style="width: 100px">Quantity</th>
                        <th style="width: 100px">Sub-Total</th>
                        <th style="width: 40px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total Amount</td>
                            <td><input class="form-control input-sm td_inputs td_size_category" type="text" disabled="" id="sum_total"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                
            </div>
            
        </form>
        </div>
        <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal" onClick="clear_op_draft()">Close</button>
        <button type="submit" class="btn btn-primary" id="save_order_payment">Save</button>
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
    var banks;
    var accounts;
    $(function () {
        numeric_control();
        get_payment_for();

        get_banks().done(function(result) {
            banks = result['bank_name'];
            accounts = result['account_number'];
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Error getting banks: ' + textStatus, errorThrown);
        });

        $.validator.setDefaults({
            submitHandler: function (form) {
                // Perform custom actions here

                // Optionally submit the form if you want to proceed with the form submission
                if(validate_table() == 0){
                    op_confirm_submit(); // Uncomment this line if you want to submit the form
                }
                
            }
        });

        var inputNames = ['establishment_name', 'establishment_add', 'company_name', 'op_num', 'issued_date', 'payment_for', 'expiration_date'];
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

        $('#particular_form').validate({
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

        

        $('#save_order_payment').click(function() {
            $('#particular_form').submit();
        });
        
    });
    const numeric_control = () => {
        $(".numeric").numeric({ decimal : ".",  negative : false, scale: 2 });
        $(".numeric_per").numeric({ decimal : ".",  negative : false, precision: 4, scale: 2 });
        $(".numeric_no_comma").numeric({ decimal : ".",  negative : false, scale: 2 });
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

        $('.numeric_no_comma').keyup(function(event) {
            // skip for arrow keys
            if(event.which >= 37 && event.which <= 40){
            event.preventDefault();
            }
           
            $(this).val(function(index, value) {
                return value
            });
        });

        
    };

    function numberWithCommas(x) {
            var parts = x.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
    }

    function get_payment_for(){
        $.ajax({
            type: 'post'
            ,url: '<?= base_url(); ?>payment/get-payment-for-ref'
            , beforeSend : function() {
            }        
            , success: function(result) {
                for(var i = 0; i< result.length; i++) {
                    $('#payment_for').append('<option value="'+result[i]['id']+'">'+result[i]['name']+'</option>');                   
                }
            }
            , failure: function(msg) {
                console.log("Failure to connect to server!");
            }
            , error: function(status) {
                
            }
        });
    }

    function get_particular(){
        const selectedValue = $('#payment_for').val();
        // $("#payment_tips").show();
        $.ajax({
            data : {
                'payment_for_id': selectedValue,
                }
            ,type: 'post'
            ,url: '<?= base_url(); ?>payment/get-particular-ref'
            , beforeSend : function() {
            }        
            , success: function(result) {
                
                $('#particular_table tbody').empty();
                for(var i = 0; i< result.length; i++)
                {
                    var bankOptions = generateOptions(banks, result[i]['bank_name']);
                    var accountOptions = generateOptions(accounts, result[i]['bank_account']);
                    $('#particular_table tbody').append(
                        '<tr>'
                            +'<td><input class="form-control input-sm td_inputs " data-toggle="tooltip" data-placement="top" title="'+result[i]['particular_name']+'" type="text"  value = "'+result[i]['particular_name']+'" disabled></td>'

                            +'<td><input class="form-control input-sm td_inputs " type="text" value = "'+result[i]['fund_code']+'" disabled></td>'

                            // +'<td><input class="form-control input-sm td_inputs " data-toggle="tooltip" data-placement="top" title="'+result[i]['bank_name']+'" type="text" value = "'+result[i]['bank_name']+'" disabled></td>'
                            +'<td><select class="form-control td_inputs" data-toggle="tooltip" data-placement="top" title="'+result[i]['bank_name']+'" disabled>'
                                +bankOptions 
                            +'</select></td>'

                            +'<td><select class="form-control td_inputs" data-toggle="tooltip" data-placement="top" title="'+result[i]['bank_account']+'" disabled>'
                                +accountOptions 
                            +'</select></td>'

                            // +'<td><input class="form-control input-sm td_inputs " data-toggle="tooltip" data-placement="top" title="'+result[i]['bank_account']+'" type="text" value = "'+result[i]['bank_account']+'" disabled></td>'

                            +'<td><input class="form-control input-sm td_inputs numeric" type="text" value = "'+numberWithCommas(result[i]['particular_amount'])+'"></td>'

                            +'<td><input class="form-control input-sm td_inputs numeric" type="text" value = "1"></td>'

                            +'<td><input class="form-control input-sm td_inputs numeric" type="text" value = "'+first_cal(result[i]['particular_amount'],1)+'" disabled></td>'
                                                                        
                            +'<td>' 
                                +'<button id="delete_par-'+result[i]['id']+'" style="width:29px; padding:5px;" class="btn btn-danger btn-sm delete_par"><i class="fa fa-trash"></i></button>'
                            +'</td>'
                        +'</tr>'
                    );
                    
                }
                var len = $("#particular_table > tbody > tr").length;
                particular_controls(len);
                refresh_particular_index();
                numeric_control();
                particular_footer_total_calculation();
            }
            , failure: function(msg) {
                console.log("Failure to connect to server!");
            }
            , error: function(status) {
                
            }
        });
    }

    function op_confirm_submit(){
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
        var establishment_name = $("#establishment_name").val();
        var establishment_address = $("#establishment_add").val();
        var company_name = $("#company_name").val();
        var op_num = $("#op_num").val();
        var issued_date = $("#issued_date").val();
        var selectedOption = $('#payment_for option:selected');
        var payment_for_text = selectedOption.text();
        var payment_for = $("#payment_for").val();
        var expiration_date = $("#expiration_date").val();
        var sum_total = $("#sum_total").val().replace(/,/g, "");

        var op_dtl = [];
        $('#particular_table > tbody > tr').each(function (row, tr){
            op_dtl.push({
                // "mrr_no" : mrr_no,
                // "po_no" : po_no,
                "item_name" : $(tr).find('td:eq(0) input').val(),
                "item_fund_code" : $(tr).find('td:eq(1) input').val(),
                "item_bank_name" : $(tr).find('td:eq(2) select').val(),
                "item_bank_account" : $(tr).find('td:eq(3) select').val(),
                "item_cost" : $(tr).find('td:eq(4) input').val().replace(/,/g, ""),
                "item_qty" : $(tr).find('td:eq(5) input').val().replace(/,/g, ""),
                "item_sub_total" : $(tr).find('td:eq(6) input').val().replace(/,/g, ""),
            });
        });
        var op_hdr = { 
            establishment_name: establishment_name,
            establishment_address: establishment_address,
            company_name: company_name,
            order_payment_no: op_num,
            issued_date : issued_date ,
            application_id : payment_for,
            application_type : payment_for_text,
            expiration_date: expiration_date,
            total_amount : sum_total,
            balance : sum_total,
            status : 1,
        };

        var data = { op_hdr : op_hdr, op_dtl : op_dtl}
        // return false
        $.ajax({
            data: data
            , type: "POST"
            , url: "<?php echo base_url('payment/save-order-payment'); ?>"
            , dataType: "json"
            , crossOrigin: false 
            , beforeSend: function () {
                $("#save_order_payment").prop("disabled", true);
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
                console.log(result.data['trans_no'])
                if(result.status == 'success'){
                    check_trans(result.data['trans_no'])
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

    function check_trans(trans_no){ 
        $.ajax({
            type: 'get',
            data: {
                trans_no: trans_no
            },
            url: '<?= base_url(); ?>/payment/check-trans-number',
            success: function(result) {
                console.log(result);
                if (result.msg == "true") {
                    window.open("<?= base_url(); ?>pdf/generate-pdf-op/?token="+result.encryptText);
                }
            }
        })
    }

    
    // Start of particular tables functionality
    var refresh_particular_index = function() {
        var total_tr = $('#particular_table > tbody > tr').length;
        $('#particular_table > tbody > tr').each(function (i) {

            i++;
            var select = $(this).find('select');
            var text = $(this).find('input');
            var button = $(this).find('button');

            text.eq(0).attr('id', 'td_item-'+i);
            text.eq(1).attr('id', 'td_fund_code-'+i);
            select.eq(0).attr('id', 'td_bank-'+i);
            select.eq(1).attr('id', 'td_bank_account-'+i);
            text.eq(2).attr('id', 'td_amount-'+i);
            text.eq(3).attr('id', 'td_qty-'+i);
            text.eq(4).attr('id', 'td_sub_total-'+i);

            // select.eq(0).attr('id', 'td_comp-'+i);

            button.eq(0).attr('id', 'delete_par-'+i);

            if(total_tr > 1) {
                $('#delete_par-'+i).removeAttr('disabled');
            }else if(total_tr <= 1) {
                $('#delete_par-'+i).prop('disabled',true);
            }

            particular_controls(i);
        });
    }

    var particular_controls = function(srl_num) {
        $('#td_amount-'+srl_num).unbind('keyup').bind('keyup', function() {
            var td_amount = $(this).val().replace(/,/g, "");
            var td_qty = $('#td_qty-'+srl_num).val();
            particular_sub_total_calculation(srl_num,td_amount,td_qty);
        });

        $('#td_qty-'+srl_num).unbind('keyup').bind('keyup', function() {
            var td_amount = $('#td_amount-'+srl_num).val().replace(/,/g, "");
            var td_qty = $(this).val().replace(/,/g, "");
            particular_sub_total_calculation(srl_num,td_amount,td_qty);
        });

        $('.delete_par').unbind('click').bind('click', function() {
            $(this).closest('tr').remove();
            refresh_particular_index();
            particular_footer_total_calculation()
        });
    }

    particular_controls(1);

    const particular_sub_total_calculation = (srl_num,amount,quantity) => {
        console.log(amount)
        var sub_total = parseFloat(amount) * parseFloat(quantity);
        // $('#td_sub_total-'+srl_num).val($.number(sub_total));
        $('#td_sub_total-'+srl_num).val(numberWithCommas(sub_total));

        particular_footer_total_calculation ();
    }

    const particular_footer_total_calculation = () => {
        sum_sub_total = 0
        $('#particular_table tbody tr').each(function (row, tr){
            var num_sub_total = $(tr).find('td:eq(6) input').val().replace(/,/g, "");
            if ($(tr).find('td:eq(6) input').val().length != 0) {
                sum_sub_total += parseFloat(num_sub_total);
            }
        });

        $('#sum_total').val(numberWithCommas(sum_sub_total));

    }

    function first_cal(amount,qty){
        return numberWithCommas(amount * qty);
    }
    
    $('#add_particular').on('click', function() {
        var len = $("#particular_table > tbody > tr").length+1;
        srl_id = len;

        var bankOptions = '<option disabled selected>Select an option</option>';
        $.each(banks, function(key, bank) {
            bankOptions += '<option value="' + bank.value + '">' + bank.value + '</option>';
        });

        var accountOptions = '<option disabled selected>Select an option</option>';
        $.each(accounts, function(key, accounts) {
            accountOptions += '<option value="' + accounts.value + '">' + accounts.value + '</option>';
        });

        $('#particular_table > tbody:last').append(
            '<tr>'
            +'<td><input class="form-control input-sm td_inputs " type="text"  value = "" ></td>'

            +'<td><input class="form-control input-sm td_inputs " type="text" value = "" ></td>'

            +'<td><select class="form-control td_inputs">'
                +bankOptions 
            +'</select></td>'

            +'<td><select class="form-control td_inputs">'
                +accountOptions 
            +'</select></td>'

            +'<td><input class="form-control input-sm td_inputs  numeric" type="text" value = ""></td>'

            +'<td><input class="form-control input-sm td_inputs  numeric_per" type="text" value = "1"></td>'

            +'<td><input class="form-control input-sm td_inputs " type="text" value = "0" disabled></td>'
                                                        
            +'<td>' 
            +'<button style="width:29px; padding:5px;" class="btn btn-danger btn-sm delete_par"><i class="fa fa-trash"></i></button>'
            +'</td>'
            +'</tr>'
        );

        particular_controls(len);
        refresh_particular_index();
        numeric_control();
    });
    // End of particular tables functionality

    
    function clear_op_draft(){
        $('#particular_form').find('input, textarea, select').val('');
        $('#particular_table tbody').empty();
    }

    function validate_table(){
        var error = 0;
        // Loop through each row and add validation rules to inputs
        $('#particular_table > tbody > tr').each(function (row, tr) {
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

            if($(tr).find('td:eq(2) select').val()  == null){
	            error = 1;
	            $(tr).find('td:eq(2) select').addClass('is-invalid');
	        }else{
	            $(tr).find('td:eq(2) select').removeClass('is-invalid');
	        }

	        if($(tr).find('td:eq(3) select').val() == null){
	            error = 1;
	            $(tr).find('td:eq(3) select').addClass('is-invalid');
	        }else{
	            $(tr).find('td:eq(3) select').removeClass('is-invalid');
	        }

            if($(tr).find('td:eq(4) input').val() == ""){
	            error = 1;
	            $(tr).find('td:eq(4) input').addClass('is-invalid');
	        }else{
	            $(tr).find('td:eq(4) input').removeClass('is-invalid');
	        }

	        if($(tr).find('td:eq(5) input').val() == ""){
	            error = 1;
	            $(tr).find('td:eq(5) input').addClass('is-invalid');
	        }else{
	            $(tr).find('td:eq(5) input').removeClass('is-invalid');
	        }
        });

        return error
    }

    function generateOptions(items, selectedValue) {
        var options = '<option disabled' + (selectedValue === null ? ' selected' : '') + '>Select an option</option>';
        $.each(items, function(key, item) {
            var isSelected = item.value === selectedValue ? ' selected' : '';
            options += '<option value="' + item.value + '"' + isSelected + '>' + item.value + '</option>';
        });
        return options;
    }

    function get_banks(){
        var data = { reg_id : 18}
        return $.ajax({
            data: data
            ,type: 'get'
            ,url: '<?= base_url(); ?>schedfees/get-banks'
            ,dataType: 'json'
        });
    }
</script>
