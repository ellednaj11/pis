<?= $this->extend('layouts/mainLayout') ?>

<?= $this->section('content') ?>
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"></h3>
                <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#add-schedule-fees">Add Schedule of Fees</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="schedule-fees-table" class="table table-bordered table-striped" style="width:100% !important">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Payment for</th>
                            <!-- <th>Fund Code</th>
                            <th>Particular</th>
                            <th>Amount</th>
                            <th>Settlement Bank</th>
                            <th>Bank Account</th> -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Payment for</th>
                            <!-- <th>Fund Code</th>
                            <th>Particular</th>
                            <th>Amount</th>
                            <th>Settlement Bank</th>
                            <th>Bank Account</th> -->
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    <!-- /.content -->
    <?php include 'modals/addScheduleFeesModal.php'; ?>
    <?php include 'modals/viewScheduleFeesModal.php'; ?>
    <?php include 'modals/editScheduleFeesModal.php'; ?>
    <!-- /.modal -->
    <script>
        var banks;
        var accounts;
        var fundCodes;
        $(function () {
            numeric_control();
            $('[data-toggle="tooltip"]').tooltip();
            get_schedule_fees();

            get_banks().done(function(result) {
                banks = result['bank_name'];
                accounts = result['account_number'];
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Error getting banks: ' + textStatus, errorThrown);
            });

            get_fund_code().done(function(result) {
                fundCodes = result;
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Error getting fund codes: ' + textStatus, errorThrown);
            });
        });

        const numeric_control = () => {
            $(".numeric").numeric({ decimal : ".",  negative : false, scale: 2 });
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

        function get_schedule_fees() {
            $.ajax({
                type: 'get'
                ,url: '<?= base_url(); ?>schedfees/get-all-schedule-fees'
                , beforeSend : function() {
                }        
                , success: function(result) {
                    $('#schedule-fees-table tbody').empty();
                    var count = 0;
                    for(var i = 0; i< result.length; i++)
                    {
                        count = count + 1;
                        var remaining_qty = result[i]['orig_qty'] - result[i]['used_qty'];
                        $('#schedule-fees-table tbody').append(
                        '<tr>'
                            +'<td>'+count+'</td>'
                            +'<td>'+result[i]['name']+'</td>'
                            // +'<td>'+result[i]['account_type']+'</td>'
                            // +'<td>'+result[i]['account_type']+'</td>'
                            // +'<td>'+result[i]['status']+'</td>'
                            // +'<td>'+result[i]['bank_name']+'</td>'
                            // +'<td>'+result[i]['location']+'</td>'
                                                                        
                            +'<td>' 
                                +'<button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="View" class="btn btn-info btn-sm"  onclick="view_details(' + result[i]['id'] + ')"><i class="fa fa-eye"></i></button>'
                                // +'<button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-success btn-sm" onclick="edit_details('+result[i]['id']+')"><i class="fa fa-pen"></i></button>'
                            +'</td>'
                        +'</tr>'
                    );
                    }
                    
                    $('#schedule-fees-table').DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": true, "ordering": false,
                         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo('#schedule-fees-table_wrapper .col-md-6:eq(0)');
                    
                }
                , failure: function(msg) {
                    console.log("Failure to connect to server!");
                }
                , error: function(status) {
                    
                }
            });
        }


        function confirm_cancel(id,element){
            var row = element.closest('tr'); // Get the closest parent row
            var columns = row.getElementsByTagName('td'); // Get all td elements in the row
            var account_num = columns[1].textContent;
            var account_name = columns[2].textContent;
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to remove Bank Account: "+account_num+" ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    cancel_bank_account(id,account_num)
                } else {
                }
            });
            
        }

        function cancel_bank_account(id,account_num){
            $.ajax({
                data : {id:id}
                ,type: "POST"
                ,url: '<?= base_url(); ?>bankAcc/remove-bank-account'
                , dataType: 'json'
                , crossOrigin: false
                , beforeSend : function() {
                }
                , success: function(result) {
                    if(result.status == 'success'){
                        Swal.fire(
                        'Removed!',
                        'Bank Account with account number : '+account_num+' has been removed.',
                        'success'
                        ).then((result) => {
                            location.reload();
                        })
                    }
                }
            });
        }

        function view_details(id){
            $('#view-details').modal('show');
            $.ajax({
                url: '<?= base_url(); ?>schedfees/get-schedule-fees-details',
                type: 'GET',
                data: { id: id },
                dataType: "json",
                success: function(result) {
                    console.log(result);
                    var data = result['header_data']
                    $('#payment_for_view').val(data[0]['name']);

                    var data_detail = result['detail_data']
                    let particularsHtml = '';
                    data_detail.forEach(particular => {
                        particularsHtml += `<tr>
                            <td>${particular.particular_name}</td>
                            <td>${particular.fund_code}</td>
                            <td>${particular.particular_amount}</td>
                            <td>${particular.bank_name}</td>
                            <td>${particular.bank_account}</td>
                        </tr>`;
                    });
                    $('#particularsTableBody').html(particularsHtml);
                },
            });
        }

        function generateOptions(items, selectedValue) {
            var options = '<option disabled' + (selectedValue === null ? ' selected' : '') + '>Select an option</option>';
            $.each(items, function(key, item) {
                var isSelected = item.value === selectedValue ? ' selected' : '';
                options += '<option value="' + item.value + '"' + isSelected + '>' + item.value + '</option>';
            });
            return options;
        }

        function edit_details(id){
            $('#edit-details').modal('show');
            $("#edit_id").val(id);
            $.ajax({
                url: '<?= base_url(); ?>schedfees/get-schedule-fees-details',
                type: 'GET',
                data: { id: id },
                dataType: "json",
                success: function(result) {
                    
                    console.log(result);
                    var data = result['header_data']
                    $('#payment_for_edit').val(data[0]['name']);
                    
                    var data_detail = result['detail_data']
                    let particularsHtml = '';
                    data_detail.forEach(particular => {
                        var bankOptions = generateOptions(banks, particular.bank_name);
                        var accountOptions = generateOptions(accounts, particular.bank_account);
                        
                        $('#particular_table_edit > tbody:last').append(
                            '<tr>'
                            +'<td><input class="form-control input-sm td_inputs " type="text"  value = "' + particular.particular_name + '" ></td>'

                            +'<td><input class="form-control input-sm td_inputs " type="text" value = "' + particular.fund_code + '" ></td>'

                            +'<td><input class="form-control input-sm td_inputs  numeric" type="text" value = "' + particular.particular_amount + '"></td>'

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
                    });
                    refresh_particular_edit_index();
                    numeric_control();
                },
            });
            
        }

        function get_fund_code(){
            return $.ajax({
                type: 'get'
                ,url: '<?= base_url(); ?>payment/get-ref-fund-code'
                ,dataType: 'json'
            });
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
    
<?= $this->endSection() ?>