<?= $this->extend('layouts/mainLayout') ?>

<?= $this->section('content') ?>
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"></h3>
                <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#add-bank-modal">Add Bank Account</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="bank-account-table" class="table table-bordered table-striped" style="width:100% !important">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Account Number</th>
                            <th>Account Name</th>
                            <th>Account Type</th>
                            <th>Agency Code</th>
                            <th>Bank</th>
                            <th>Branch</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Account Number</th>
                            <th>Account Name</th>
                            <th>Account Type</th>
                            <th>Agency Code</th>
                            <th>Bank</th>
                            <th>Branch</th>
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
    <?php include 'modals/addBankAccountModal.php'; ?>
    <?php include 'modals/viewBankAccountModal.php'; ?>
    <!-- /.modal -->
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            get_bank_account();
        });

        function get_bank_account() {
            $.ajax({
                type: 'get'
                ,url: '<?= base_url(); ?>bankAcc/get-all-bank-account'
                , beforeSend : function() {
                }        
                , success: function(result) {
                    $('#bank-account-table tbody').empty();
                    var count = 0;
                    for(var i = 0; i< result.length; i++)
                    {
                        count = count + 1;
                        var remaining_qty = result[i]['orig_qty'] - result[i]['used_qty'];
                        $('#bank-account-table tbody').append(
                        '<tr>'
                            +'<td>'+count+'</td>'
                            +'<td>'+result[i]['account_number']+'</td>'
                            +'<td>'+result[i]['account_type']+'</td>'
                            +'<td>'+result[i]['account_type']+'</td>'
                            +'<td>'+result[i]['status']+'</td>'
                            +'<td>'+result[i]['bank_name']+'</td>'
                            +'<td>'+result[i]['location']+'</td>'
                                                                        
                            +'<td>' 
                                +'<button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="View" class="btn btn-info btn-sm"  onclick="view_details(' + result[i]['id'] + ')"><i class="fa fa-eye"></i></button>'
                                +'<button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="Cancel" class="btn btn-danger btn-sm" onclick="confirm_cancel('+result[i]['id']+',this)"><i class="fa fa-ban"></i></button>'
                            +'</td>'
                        +'</tr>'
                    );
                    }
                    
                    $('#bank-account-table').DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": true, "ordering": false,
                         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo('#bank-account-table_wrapper .col-md-6:eq(0)');
                    
                }
                , failure: function(msg) {
                    console.log("Failure to connect to server!");
                }
                , error: function(status) {
                    
                }
            });
        }

        function view_details(id){
            $('#view-receipt-registry').modal('show');
            // $.ajax({
            //     url: '<?= base_url(); ?>payment/get-all-order-payment-details',
            //     type: 'GET',
            //     data: { id: id },
            //     dataType: "json",
            //     success: function(result) {
            //         var data_detail = result['detail_data']
            //         let particularsHtml = '';
            //         data_detail.forEach(particular => {
            //             particularsHtml += `<tr>
            //                 <td>${particular.item_name}</td>
            //                 <td>${particular.item_fund_code}</td>
            //                 <td>${particular.item_cost}</td>
            //                 <td>${particular.item_qty}</td>
            //                 <td>${particular.item_sub_total}</td>
            //             </tr>`;
            //         });
            //         $('#particularsTableBody').html(particularsHtml);
            //     },
            // });
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

    </script>
    
<?= $this->endSection() ?>