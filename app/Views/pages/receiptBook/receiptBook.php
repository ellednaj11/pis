<?= $this->extend('layouts/mainLayout') ?>

<?= $this->section('content') ?>
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"></h3>
                <button class="btn btn-primary ml-auto" onclick="add_receipt_book()">Add receipt Book</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="receipt-book-table" class="table table-bordered table-striped" style="width:100% !important">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fund code</th>
                            <th>OR Number Start</th>
                            <th>OR Number End</th>
                            <th>Original Quantity</th>
                            <th>Used Quantity</th>
                            <th>Unused Quantity</th>
                            <th>Book Value</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Fund code</th>
                            <th>OR Number Start</th>
                            <th>OR Number End</th>
                            <th>Original Quantity</th>
                            <th>Used Quantity</th>
                            <th>Unused Quantity</th>
                            <th>Book Value</th>
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
    <?php include 'modals/addReceiptBookModal.php'; ?>
    <?php include 'modals/viewReceiptRegModal.php'; ?>
    <!-- /.modal -->
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            get_all_receipt_book();
        });

        function add_receipt_book(){
            get_ref_fund_code();
            $('#add-receipt-modal').modal('show');
        }

        function get_all_receipt_book() {
            $.ajax({
                type: 'get'
                ,url: '<?= base_url(); ?>receipt/get-all-receipt-book'
                , beforeSend : function() {
                }        
                , success: function(result) {
                    $('#receipt-book-table tbody').empty();
                    var count = 0;
                    for(var i = 0; i< result.length; i++)
                    {
                        count = count + 1;
                        var remaining_qty = result[i]['orig_qty'] - result[i]['used_qty'];
                        $('#receipt-book-table tbody').append(
                        '<tr>'
                            +'<td>'+count+'</td>'
                            +'<td>'+result[i]['fund_code']+'</td>'
                            +'<td>'+result[i]['or_number_start']+'</td>'
                            +'<td>'+result[i]['or_number_end']+'</td>'
                            +'<td>'+result[i]['orig_qty']+'</td>'
                            +'<td>'+result[i]['used_qty']+'</td>'
                            +'<td>'+remaining_qty+'</td>'
                            +'<td>'+result[i]['book_value']+'</td>'
                                                                        
                            +'<td>' 
                                +'<button id="delete_par-'+result[i]['id']+'" style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="View" class="btn btn-info btn-sm delete_par"  onclick="view_receipt_registry(' + result[i]['id'] + ')"><i class="fa fa-eye"></i></button>'
                                +'<button id="delete_par-'+result[i]['id']+'" style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="Cancel" class="btn btn-danger btn-sm delete_par" onclick="confirm_cancel('+result[i]['id']+',this)"><i class="fa fa-ban"></i></button>'
                            +'</td>'
                        +'</tr>'
                    );
                    }
                    
                    $('#receipt-book-table').DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": true, "ordering": false,
                         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo('#receipt-book-table_wrapper .col-md-6:eq(0)');
                    
                }
                , failure: function(msg) {
                    console.log("Failure to connect to server!");
                }
                , error: function(status) {
                    
                }
            });
        }

        function view_receipt_registry(id){
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
            var start_num = columns[2].textContent;
            var end_num = columns[3].textContent;
            var used_qty = columns[5].textContent;
            if(used_qty > 0){
                Swal.fire({
                    icon: 'error',
                    title: 'Not Allowed',
                    text: "You can only remove unused Receipt book",
                });
            }else{
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you really want to remove OR: "+start_num+" - "+end_num+"?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                    cancel_receipt_book(id,start_num,end_num)
                    } else {
                    }
                });
            }
            
        }

        function cancel_receipt_book(id,start_num,end_num){
            $.ajax({
                data : {id:id}
                ,type: "POST"
                ,url: '<?= base_url(); ?>receipt/remove-receipt-book'
                , dataType: 'json'
                , crossOrigin: false
                , beforeSend : function() {
                }
                , success: function(result) {
                    if(result.status == 'success'){
                        Swal.fire(
                        'Removed!',
                        'OR: '+start_num+'-'+end_num+' has been removed.',
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