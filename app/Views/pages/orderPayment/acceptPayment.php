<?= $this->extend('layouts/mainLayout') ?>

<?= $this->section('content') ?>
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
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
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5"></td>
                                <td>Amount Due</td>
                                <td id="amount_due"></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td>Amount Paid</td>
                                <td id="amount_paid"></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td>Balance</td>
                                <td id="balance"></td>
                            </tr>
                        </tfoot>
                    </table>

                    <h5>Payments</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Payment Method</th>
                                <th>Payment Receipt No</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Amount Credited</th>
                                <th>Status</th>
                                <th>Accepted by</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="text-center">No payments recorded.</td>
                            </tr>
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
    <?php include 'modals/addPaymentModal.php'; ?>
    <?php include 'modals/viewPaymentModal.php'; ?>
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
        });
        function view_order_payment(id){
            $('#view-payment-modal').modal('show');
            //get_order_payment_dtl(id); // Inside modals/viewPaymentModal.php

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
                    $('#expirationDate').text( data[0]['expiration_date']);
                    $('#transactionID').text( data[0]['trans_no']);
                    $('#orderPaymentNo').text( data[0]['order_payment_no']);
                    $('#orderPaymentDate').text( data[0]['issued_date']);
                    $('#issuingOffice').text( data[0]['region_name']);
                    $('#issuedBy').text( data[0]['issued_by']);

                    var data_detail = result['detail_data']
                    let particularsHtml = '';
                    data_detail.forEach(particular => {
                        particularsHtml += `<tr>
                            <td>${particular.item_name}</td>
                            <td>${particular.item_fund_code}</td>
                            <td>${particular.item_bank_name}</td>
                            <td>${particular.item_bank_account}</td>
                            <td>${numberWithCommas(particular.item_cost)}</td>
                            <td>${particular.item_qty}</td>
                            <td>${numberWithCommas(particular.item_sub_total)}</td>
                        </tr>`;
                    });
                    $('#particularsTableBody').html(particularsHtml);
                },
            });
        }
    </script>
    
<?= $this->endSection() ?>