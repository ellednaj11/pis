<?= $this->extend('layouts/mainLayout') ?>

<?= $this->section('content') ?>
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"></h3>
                <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#payment-modal">Draft Order of Payment</button>
              </div>
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="order-payment-table" class="table table-bordered table-striped" style="width:100% !important">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Trans No.</th>
                            <th>Order of Payment No.</th>
                            <!-- <th>Issued Date</th> -->
                            <th>Payment For</th>
                            <th>Proponent</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr>
                            <th>#</th>
                            <th>Trans No.</th>
                            <th>Order of Payment No.</th>
                            <!-- <th>Issued Date</th> -->
                            <th>Payment For</th>
                            <th>Proponent</th>
                            <th>Amount</th>
                            <th>Status</th>
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
    <?php include 'modals/addPaymentModal.php'; ?>
    <?php include 'modals/viewPaymentModal.php'; ?>
    <?php include 'modals/addClientPayment.php'; ?>
    <?php include 'modals/addReceipt.php'; ?>
    <!-- /.modal -->
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            get_all_order_of_payment();
        });

        function get_all_order_of_payment() {
            
            var user_id = <?= session()->get('id'); ?>;
            
            $.ajax({
                type: 'get'
                ,url: '<?= base_url(); ?>payment/get-all-order-payment'
                , beforeSend : function() {
                }        
                , success: function(result) {
                    $('#order-payment-table tbody').empty();
                    var count = 0;
                    if ($.fn.DataTable.isDataTable('#order-payment-table')) {
                        console.log('destroy table')
                        $('#order-payment-table').DataTable().clear().destroy();
                       
                    }
                    
                    for(var i = 0; i< result.length; i++)
                    {
                        var cancelButton = '';
                        if(user_id == result[i]['created_by']){
                            var cancelButton = '<button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="Cancel" class="btn btn-danger btn-sm" onclick="confirm_cancel('+result[i]['id']+',this)"><i class="fa fa-ban"></i></button>';
                        }
                        count = count + 1;
                        $('#order-payment-table tbody').append(
                        '<tr>'
                            +'<td>'+count+'</td>'
                            +'<td>'+result[i]['trans_no']+'</td>'
                            +'<td>'+result[i]['order_payment_no']+'</td>'
                            // +'<td>'+result[i]['issued_date']+'</td>'
                            +'<td>'+result[i]['application_type']+'</td>'
                            +'<td>'+result[i]['establishment_name']+'</td>'
                            +'<td>'+numberWithCommas(result[i]['total_amount'])+'</td>'
                            +'<td style="color:'+ status_color(result[i]['status']) +'">'+result[i]['status_name']+'</td>'
                                                                        
                            +'<td>' 
                                +'<button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="View" class="btn btn-info btn-sm"  onclick="view_order_payment(' + result[i]['id'] + ')"><i class="fa fa-eye"></i></button>'
                                // +`<button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="Payment" class="btn btn-success btn-sm"  onclick="add_client_payment('${result[i]['trans_no']}',${result[i]['total_amount']},${result[i]['id']})"><i class="fa fa-money-bill"></i></button>` 
                                // + cancelButton
                                // +'<button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="Payment" class="btn btn-success btn-sm" onclick="accept_payment(this)"><i class="fa fa-money-bill"></i></button>'
                            +'</td>'
                        +'</tr>'
                    );
                    }
                    
                    $('#order-payment-table').DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": true, "ordering": false,
                         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo('#order-payment-table_wrapper .col-md-6:eq(0)');
                    
                }
                , failure: function(msg) {
                    console.log("Failure to connect to server!");
                }
                , error: function(status) {
                    
                }
            });
        }

        function view_order_payment(id){
            
            //get_order_payment_dtl(id); // Inside modals/viewPaymentModal.php
            // get_client_payment(id);
            $.ajax({
                url: '<?= base_url(); ?>payment/get-all-order-payment-details',
                type: 'GET',
                data: { id: id },
                dataType: "json",
                success: function(result) {
                    var data = result['header_data']
                    $('#paymentModalLabel').text('Order of Payment No. ' + data[0]['order_payment_no']);
                    $('#source').text( data[0]['source']);
                    $('#paymentFor').text( data[0]['application_type']);
                    $('#establishment').text( data[0]['establishment_name']);
                    $('#location').text( data[0]['establishment_address']);
                    $('#proponent').text( data[0]['company_name']);
                    $('#status').text( data[0]['status_name']).css('color',status_color(data[0]['status']));
                    $('#expirationDate').text( format_date(data[0]['expiration_date']));
                    $('#transactionID').text( data[0]['trans_no']);
                    $('#transactionID').attr('data-id', id);
                    $('#orderPaymentNo').text( data[0]['order_payment_no']);
                    $('#orderPaymentDate').text( format_date(data[0]['issued_date']));
                    $('#issuingOffice').text( data[0]['region_name']);
                    $('#issuedBy').text( data[0]['issued_by']);
                    $('#issuedBy').attr('data-user_id', data[0]['created_by']);

                    var data_detail = result['detail_data']
                    let particularsHtml = '';
                    
                    var amount_due = 0;
                    data_detail.forEach(particular => {
                        amount_due += parseInt(particular.item_sub_total);
                        particularsHtml += `<tr>
                            <td style="padding: 5px .75rem;">${particular.item_name}</td>
                            <td style="padding: 5px .75rem;">${particular.item_fund_code}</td>
                            <td style="padding: 5px .75rem;">${particular.item_bank_name}</td>
                            <td style="padding: 5px .75rem;">${particular.item_bank_account}</td>
                            <td style="padding: 5px .75rem;">${numberWithCommas(particular.item_cost)}</td>
                            <td style="padding: 5px .75rem;">${particular.item_qty}</td>
                            <td style="padding: 5px .75rem;">${numberWithCommas(particular.item_sub_total)}</td>
                        </tr>`;
                    });
                    $('#particularsTableBody').html(particularsHtml);

                    var amount_paid = 0;
                    var balance = amount_due;
                    let paymentHtml = '';
                    let receiptHtml = '';
                    
                    
                    if(result['payment_data'].length !== 0){
                        
                        var payment_data = result['payment_data']
                        payment_data.forEach(data => {
                            var cancelButton = '';
                            var receiptButton = '';

                            var unused_credited_amount = data.total_amount_credited - data.amount_used_receipt
                            if(data.status == 1){
                                amount_paid += parseInt(data.total_amount_credited);
                                var receiptButton = `<button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="Receipt" class="btn btn-success btn-sm" onclick="receipt_payment(${data.id},${parseFloat(unused_credited_amount)})"><i class="fa fa-receipt"></i></button>`;
                            }

                            if(data.status == 2){
                                to_verify++;
                            }
                            
                            if(data.status != 0){
                                var cancelButton = `<button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="Cancel" class="btn btn-danger btn-sm" onclick="confirm_cancel_payment(${data.id})"><i class="fa fa-ban"></i></button>`;
                                active_payment++;
                            }
                            paymentHtml += `<tr>
                                <td style="padding: 5px .75rem;">${data.method_name}</td>
                                <td style="padding: 5px .75rem;">${data.payment_receipt_no}</td>
                                <td style="padding: 5px .75rem;">${format_date(data.payment_date)}</td>
                                <td style="padding: 5px .75rem;">${numberWithCommas(data.total_amount_paid)}</td>
                                <td style="padding: 5px .75rem;">${numberWithCommas(data.total_amount_credited)}</td>
                                <td style="padding: 5px .75rem;" class="status" data-status="${data.status}">${data.payment_status_name}</td>
                                <td style="padding: 5px .75rem;">${data.responsible_person}</td>
                                <td>
                                    ${cancelButton}
                                    ${receiptButton}
                                </td>
                            </tr>`;
                        });
                        balance = amount_due - amount_paid
                        
                    }else{
                        paymentHtml += '<td colspan="8" class="text-center">No payments recorded.</td>';
                    }
                    $('#payment_table_body').html(paymentHtml);
                    apply_text_color('payment_table');

                    $('#amount_due').text( numberWithCommas(amount_due));
                    $('#amount_paid').text( numberWithCommas(amount_paid));
                    $('#balance').text( numberWithCommas(balance));

                    if(result['receipt_data'].length !== 0){
                        
                        var receipt_data = result['receipt_data']
                        receipt_data.forEach(data => {
                            var cancelButton = '';
                            var statName = 'Cancelled';
                            if(data.status != 0){
                                var cancelButton = `<button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="Cancel" class="btn btn-danger btn-sm" onclick="confirm_cancel_or(${data.id},${data.payment_id},${data.op_id})"><i class="fa fa-ban"></i></button>`;
                                statName = 'Created';
                                active_receipt++;
                            }
                            receiptHtml += `<tr>
                                <td style="padding: 5px .75rem;">${data.official_receipt_no}</td>
                                <td style="padding: 5px .75rem;">${format_date(data.official_receipt_date)}</td>
                                <td style="padding: 5px .75rem;">${data.fund_code}</td>
                                <td style="padding: 5px .75rem;">${data.bank_name}</td>
                                <td style="padding: 5px .75rem;">${data.bank_account}</td>
                                <td style="padding: 5px .75rem;">${numberWithCommas(data.amount)}</td>
                                <td style="padding: 5px .75rem;" class="status" data-status="${data.status}">${statName}</td>
                                <td style="padding: 5px .75rem;">${data.responsible_person}</td>
                                
                                <td>
                                    ${cancelButton}
                                </td>
                            </tr>`;
                        });
                    }else{
                        receiptHtml += '<td colspan="8" class="text-center">No receipts recorded.</td>';
                    }
                    $('#receipt_table_body').html(receiptHtml);
                    apply_text_color('receipt_table');
                    
                    $('#view-payment-modal').modal('show');
                },
            });
        }

        function accept_payment(element){
            var row = element.closest('tr'); // Get the closest parent row
            var columns = row.getElementsByTagName('td'); // Get all td elements in the row
            var trans_no = columns[1].textContent;
            console.log(trans_no)
            window.location.href = "<?= base_url(); ?>accept-payment/"+trans_no;
        }

        function status_color(id){
            switch(id) {
                case '3':
                    return 'green';
                case '1':
                    return 'red';
                case '2':
                    return 'orange';
            }
        }

        function format_date(dateStr) {
            // Convert to Date object
            var date = new Date(dateStr);
            
            // Array of month names
            var monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            
            // Format the date to "Month Day, Year"
            var formattedDate = monthNames[date.getMonth()] + " " + date.getDate() + ", " + date.getFullYear();
            
            return formattedDate;
        }
    </script>
    
<?= $this->endSection() ?>