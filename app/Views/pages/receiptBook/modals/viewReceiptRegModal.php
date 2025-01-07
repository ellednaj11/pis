<div class="modal fade" id="view-receipt-registry">
    <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="paymentModalLabel">Order of Payment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="row mx-5 my-3">
                <h5>Particulars</h5>
                <table class="table table-bordered" id="particular_table" style="width:100% !important">
                    <thead>
                        <tr>
                            <th>OR number</th>
                            <th>Status</th>
                            <th>Proponent Name/ Company</th>
                            <th>Amount</th>
                            <th>Amount Credited</th>
                            <th>Date Used</th>
                            <th>Used By</th>
                        </tr>
                    </thead>
                    <tbody id="">
                    </tbody>
                </table>
            </div>
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
        // get_order_payment_dtl();
    });

</script>
