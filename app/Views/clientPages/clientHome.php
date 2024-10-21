<?= $this->extend('layouts/clientLayout') ?>

<?= $this->section('content') ?>
      <div class="container-fluid">
        <div class="row" >
          <div class="col-12">
            <div class="card" >
              <!-- /.card-header -->
              <div class="card-body">
                <div class="d-flex">
                    <div class="ml-auto">
                    <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#payment-modal">Add Payment</button> -->
                    <button class="btn btn-primary" onClick="add_payment_confirm()">Add Payment</button>
                    </div>
                </div>
                
                <div class="row mx-2 my-2">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong>Source:</strong></p>
                            </div>
                            <div class="col-md-9">
                                <p><span id="source"><?= esc($header_data['source']) ?></span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong>Payment For:</strong></p>
                            </div>
                            <div class="col-md-9">
                                <p><span id="paymentFor"><?= esc($header_data['application_type']) ?></span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong>Establishment:</strong></p>
                            </div>
                            <div class="col-md-9">
                                <p><span id="establishment"><?= esc($header_data['establishment_name']) ?></span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong>Location:</strong></p>
                            </div>
                            <div class="col-md-9">
                                <p><span id="location"><?= esc($header_data['establishment_address']) ?></span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong>Proponent:</strong></p>
                            </div>
                            <div class="col-md-9">
                                <p><span id="proponent"><?= esc($header_data['company_name']) ?></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 ">
                        <div class="row">
                            <div class="col-md-5">
                                <p><strong>Status:</strong></p>
                            </div>
                            <div class="col-md-7">
                                <p><span id="status" data-status="<?= esc($header_data['status']) ?>"><?= esc($header_data['status_name']) ?></span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <p><strong>Expiration Date:</strong></p>
                            </div>
                            <div class="col-md-7">
                                <p><span id="expirationDate"><?= esc($header_data['expiration_date']) ?></span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <p><strong>Transaction ID:</strong></p>
                            </div>
                            <div class="col-md-7">
                                <p><span id="transactionID"><?= esc($header_data['trans_no']) ?></span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <p><strong>Order of Payment No:</strong></p>
                            </div>
                            <div class="col-md-7">
                                <p><span id="orderPaymentNo"><?= esc($header_data['order_payment_no']) ?></span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <p><strong>OP Date:</strong></p>
                            </div>
                            <div class="col-md-7">
                                <p><span id="orderPaymentDate"><?= esc($header_data['issued_date']) ?></span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <p><strong>Issuing Office:</strong></p>
                            </div>
                            <div class="col-md-7">
                                <p><span id="issuingOffice"><?= esc($header_data['region_name']) ?></span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <p><strong>Issued by:</strong></p>
                            </div>
                            <div class="col-md-7">
                                <p><span id="issuedBy"><?= esc($header_data['issued_by']) ?></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-2 my-2">
                    <h5>Particulars</h5>
                    <table class="table table-bordered" id="particular_table_view" style="width:100% !important">
                        <thead>
                            <tr>
                            <th>Particular</th>
                            <th>Fund Code</th>
                            <th>Bank</th>
                            <th>Bank Account</th>
                            <th>Cost per item</th>
                            <th style="width: 120px">Quantity</th>
                            <th style="width: 120px">Sub-Total</th>
                            </tr>
                        </thead>
                        <tbody id="particularsTableBody">
                        <?php foreach ($detail_data as $row): ?>
                            <tr>
                                <td style="padding: 5px .75rem;"><?php echo htmlspecialchars($row['item_name']); ?></td>
                                <td style="padding: 5px .75rem;"><?php echo htmlspecialchars($row['item_fund_code']); ?></td>
                                <td style="padding: 5px .75rem;"><?php echo htmlspecialchars($row['item_bank_name']); ?></td>
                                <td style="padding: 5px .75rem;"><?php echo htmlspecialchars($row['item_bank_account']); ?></td>
                                <td style="padding: 5px .75rem;"><?php echo number_format($row['item_cost'], 2); ?></td>
                                <td style="padding: 5px .75rem;"><?php echo htmlspecialchars($row['item_qty']); ?></td>
                                <td style="padding: 5px .75rem;"><?php echo number_format($row['item_sub_total'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" style="padding: 5px .75rem;">Amount Due</td>
                                <td id="amount_due"style="padding: 5px .75rem;"><?php echo number_format($amount_due, 2); ?></td>
                            </tr>
                            <tr>
                                <td colspan="6" style="padding: 5px .75rem;">Amount Paid</td>
                                <td id="footer_amount_paid" style="padding: 5px .75rem;"><?php echo number_format($amount_paid, 2); ?></td>
                            </tr>
                            <tr>
                                <td colspan="6" style="padding: 5px .75rem;">Balance</td>
                                <td id="balance" style="padding: 5px .75rem;"><?php echo number_format($balance, 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>

                    <h5>Payments</h5>
                    <table class="table table-bordered" id="payment_table">
                        <thead>
                            <tr>
                                <th>Payment Method</th>
                                <th>Payment Receipt No</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Amount Accepted</th>
                                <th>Status</th>
                                <th>Responsible Person</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($payment_data) { foreach ($payment_data as $index => $row): ?>
                                <tr>
                                    <td style="padding: 5px .75rem;"><?php echo htmlspecialchars($row['method_name']); ?></td>
                                    <td style="padding: 5px .75rem;"><?php echo htmlspecialchars($row['payment_receipt_no']); ?></td>
                                    <td style="padding: 5px .75rem;"><?php echo htmlspecialchars($row['payment_date']); ?></td>
                                    <td style="padding: 5px .75rem;"><?php echo number_format($row['total_amount_paid'], 2); ?></td>
                                    <td style="padding: 5px .75rem;"><?php echo number_format($row['total_amount_credited'], 2); ?></td>
                                    <td style="padding: 5px .75rem;" class="status" data-status="<?php echo htmlspecialchars($row['status']); ?>"><?php echo htmlspecialchars($row['payment_status_name']); if($row['status'] == 0){echo '<br>('.$row['remarks'].')';} ?></td>
                                    <td style="padding: 5px .75rem;"><?php echo htmlspecialchars($row['responsible_person']); ?></td>
                                </tr>
                            <?php endforeach; }else{?>
                            <tr>
                                <td colspan="8" class="text-center">No payments recorded.</td>
                            </tr>
                            <?php }; ?>
                        </tbody>
                    </table>
                </div>
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
    <?php include 'modals/add_payment.php'; ?>
    <!-- /.modal -->
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            var status = $('#status').data('status');

            var color = 'black'; // Default color
            switch (status) {
                case 3:
                    color = 'green';
                    break;
                case 1:
                    color = 'red';
                    break;
                case 2:
                    color = 'orange';
                    break;
            }

            $('#status').css('color', color);
            applyTextColorToTable('payment_table');
        });
        
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

        function add_payment_confirm(){
            var verify = <?php echo $to_verify; ?>;
            var balance = <?php echo $balance; ?>;
            console.log(verify)

            if(verify > 0 || balance == 0){
                Swal.fire({
                    position: "top-end",
                    text: "Your payment is under verification, or your selected order of payment has no remaining balance.",
                    showConfirmButton: false,
                    timer: 10000
                    });
            }else{
                $('#payable_amount').val($('#balance').text());
                $('#payment-modal').modal('show');
            }
        }
    </script>
    
<?= $this->endSection() ?>

