<?= $this->extend('layouts/mainLayout') ?>

<?= $this->section('content') ?>
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex" >
                <h3 class="card-title"></h3>
                <!-- <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#payment-modal">Draft Order of Payment</button> -->
                <div class="row">
                    <label for="status-select" class="col-sm-4 col-form-label">Status</i></button></label>
                    <div class="form-group col-sm-8">
                        <select class="form-control" id="status-select" onchange="filter_status()">
                            <option value="" selected>All</option>
                            <option value="2">For Verification</option>
                            <option value="1">Accepted</option>
                            <option value="0">Canceled</option>
                        </select>
                    </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="payment_table" class="table table-bordered table-striped" style="width:100% !important">
                    <thead>
                        <tr>
                            <th>Transaction No.</th>
                            <th>Order of Payment No.</th>
                            <th>Payment Method</th>
                            <th>Payment Receipt No.</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Amount Accepted</th>
                            <th>Status</th>
                            <th>Responsible Person</th>
                            <th style="width: 120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="payment_table_body">
                    </tbody>
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
    <?php include 'modals/viewPayment.php'; ?>
    <?php include 'modals/viewOPModal.php'; ?>
    <!-- /.modal -->
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            get_all_payment('');
            
        });

        function get_all_payment(stat) {
            var user_id = <?= session()->get('id'); ?>;
            var status = stat;
            $.ajax({
                type: 'get'
                ,url: '<?= base_url(); ?>payment/get-all-client-payment'
                ,data: { status: status }
                ,dataType: "json"
                , beforeSend : function() {
                }        
                , success: function(result) {
                    $('#payment_table_body').empty();
                    var count = 0;
                    if ($.fn.DataTable.isDataTable('#payment_table')) {
                        $('#payment_table').DataTable().clear().destroy();
                    }
                    let paymentHtml = '';
                    if(result.length !== 0){
                        // var payment_data = result['payment_data']
                        result.forEach(data => {
                            count++;
                            var cancelButton = '';
                            if(data.status != 0){
                                var cancelButton = `<td style="padding: 5px .75rem;"><button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="Cancel" class="btn btn-danger btn-sm" onclick="confirm_cancel_payment(${data.id},this)"><i class="fa fa-ban"></i></button></td>`;
                                // active_payment++;
                            }
                            paymentHtml += `<tr>
                                
                                <td style="padding: 5px .75rem;">${data.trans_no}</td>
                                <td style="padding: 5px .75rem;">${data.order_payment_no}</td>
                                <td style="padding: 5px .75rem;">${data.method_name}</td>
                                <td style="padding: 5px .75rem;">${data.payment_receipt_no}</td>
                                <td style="padding: 5px .75rem;">${format_date(data.payment_date)}</td>
                                <td style="padding: 5px .75rem;">${numberWithCommas(data.total_amount_paid)}</td>
                                <td style="padding: 5px .75rem;">${numberWithCommas(data.total_amount_credited)}</td>
                                <td style="padding: 5px .75rem;"  class="status" data-status="${data.status}">${data.payment_status_name}</td>
                                <td style="padding: 5px .75rem;">${data.responsible_person}</td>
                                <td style="padding: 5px .75rem;">
                                    <button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="View client's Payment" class="btn btn-info btn-sm"  onclick="view_payment(${data.id})"><i class="fa fa-eye"></i></button>
                                    <button style="width:29px; padding:5px; margin:5px" data-toggle="tooltip" data-placement="top" title="View Order of Payment Detail" class="btn btn-success btn-sm"  onclick="view_op_detail(${data.op_id})"><i class="fa fa-eye"></i></button>
                                </td>
                                
                            </tr>`;
                        });
                    }else{
                        paymentHtml += '<td colspan="8" class="text-center">No payments recorded.</td>';
                    }
                    $('#payment_table_body').html(paymentHtml);

                    
                    
                    var table = $('#payment_table').DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": true, "ordering": false,
                         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    })
                    table.buttons().container().appendTo('#payment_table_wrapper .col-md-6:eq(0)');
                    table.on('draw', function () {
                        applyTextColorToTable('payment_table');
                    });
                    
                    applyTextColorToTable('payment_table');
                }
                , failure: function(msg) {
                    console.log("Failure to connect to server!");
                }
                , error: function(status) {
                    
                }
            });
        }

        function view_payment(id){
            $('#acceptButton').hide();
            $('#rejectButton').hide();
            $('#cancelButton').hide();
            $('.client_detail').hide();
            $.ajax({
                url: '<?= base_url(); ?>payment/get-spec-payment',
                type: 'GET',
                data: { id: id },
                dataType: "json",
                success: function(result) {
                    var data = result['header_data']
                    $('#pis_trans_no').text(data[0]['trans_no']);
                    $('#pis_trans_no').attr('data-trans_id', data[0]['op_trans_id']);
                    $('#payment_method').text( data[0]['method_name']);
                    $('#payment_method').attr('data-payment_id', data[0]['id']);
                    $('#payment_date').text( format_date(data[0]['payment_date']));
                    // $('#payment_attach').text( data[0]['payment_date']);
                    $('#status').text( data[0]['payment_status_name']);
                    $('#status').attr('data-status_id', data[0]['status']);
                    $('#amount_paid').text( numberWithCommas(data[0]['total_amount_paid']));
                    $('#amount_credited').text( numberWithCommas(data[0]['total_amount_credited']));
                    $('#email').text( data[0]['email']);
                    $('#cp_num').text(  data[0]['contact_num']);
                    $('#payment-modal').modal('show');

                    var attachments = result['attachments']
                    $('#file-buttons').empty();
                    if(attachments.length > 0){
                        attachments.forEach(function(file) {
                        $('#file-buttons').append(
                            `<button type="button" class="btn text-primary view-file-btn" data-file-path="${file.file_path}">
                                ${file.file_path}
                            </button>`
                        );
                    });
                    }else{
                        $('#file-buttons').append(
                            `<p>No file Attached.</p>`
                        );
                    }
                    

                    if (data[0]['status'] == 2) {
                        $('#acceptButton').show();
                        $('#rejectButton').show();
                    } else if (data[0]['status'] == 1) {
                        $('#cancelButton').show();
                    }

                    if(data[0]['embInput'] != 1){
                        $('.client_detail').show();
                    }
                },
            });
        }

        function view_op_detail(op_id){
            $.ajax({
                url: '<?= base_url(); ?>payment/get-all-order-payment-details',
                type: 'GET',
                data: { id: op_id },
                dataType: "json",
                success: function(result) {
                    var data = result['header_data']
                    $('#paymentModalLabel').text('Order of Payment No. ' + data[0]['order_payment_no']);
                    $('#source').text( data[0]['source']);
                    $('#paymentFor').text( data[0]['application_type']);
                    $('#establishment').text( data[0]['establishment_name']);
                    $('#location').text( data[0]['establishment_address']);
                    $('#proponent').text( data[0]['company_name']);
                    $('#status_view').text( data[0]['status_name']).css('color',status_color(data[0]['status']));
                    $('#expirationDate').text( format_date(data[0]['expiration_date']));
                    $('#transactionID').text( data[0]['trans_no']);
                    $('#transactionID').attr('data-id', op_id);
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
                    let paymentHtml = '';
                    let receiptHtml = '';
                    
                    
                    if(result['payment_data'].length !== 0){
                        
                        var payment_data = result['payment_data']
                        payment_data.forEach(data => {

                            var unused_credited_amount = data.total_amount_credited - data.amount_used_receipt
                            if(data.status == 1){
                                amount_paid += parseInt(data.total_amount_credited);
                            }
                            paymentHtml += `<tr>
                                <td style="padding: 5px .75rem;">${data.method_name}</td>
                                <td style="padding: 5px .75rem;">${data.payment_receipt_no}</td>
                                <td style="padding: 5px .75rem;">${format_date(data.payment_date)}</td>
                                <td style="padding: 5px .75rem;">${numberWithCommas(data.total_amount_paid)}</td>
                                <td style="padding: 5px .75rem;">${numberWithCommas(data.total_amount_credited)}</td>
                                <td style="padding: 5px .75rem;" class="status" data-status="${data.status}">${data.payment_status_name}</td>
                                <td style="padding: 5px .75rem;">${data.responsible_person}</td>
                            </tr>`;
                        });
                        var balance = amount_due - amount_paid
                        $('#amount_due').text( numberWithCommas(amount_due));
                        $('#amount_paid_view').text( numberWithCommas(amount_paid));
                        $('#balance').text( numberWithCommas(balance));
                    }else{
                        paymentHtml += '<td colspan="8" class="text-center">No payments recorded.</td>';
                    }
                    $('#payment_modal_table_body').html(paymentHtml);
                    apply_text_color('payment_modal_table_body');
                    

                    if(result['receipt_data'].length !== 0){
                        
                        var receipt_data = result['receipt_data']
                        receipt_data.forEach(data => {
                            var statName = 'Cancelled';
                            if(data.status != 0){
                                
                                statName = 'Created';
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

        function filter_status(){
            var statusValue = $('#status-select').val();
            get_all_payment(statusValue);
        }

        function applyTextColorToTable(tableId) {
            $('#' + tableId + ' td.status').each(function() {
                var status = $(this).data('status'); // Use data attribute to get status
                // Apply classes based on the status value
                switch(status) {
                    case 1:
                        $(this).addClass('status-approved');
                        break;
                    case 2:
                        $(this).addClass('status-pending');
                        break;
                    case 0:
                        $(this).addClass('status-rejected');
                        break;
                    // Add more cases as needed for other statuses
                    default:
                        // Optionally handle unknown statuses
                        break;
                }
            });
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

        function numberWithCommas(x) {
            var parts = x.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
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
    </script>
    
<?= $this->endSection() ?>