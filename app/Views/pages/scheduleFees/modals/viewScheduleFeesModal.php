<div class="modal fade" id="view-details">
    <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="paymentModalLabel">Schedule of Fees</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="card-body">
                <div class=" row">
                    <label for="payment_for_view"  class="col-sm-2 col-form-label">Payment Type</label>
                    <div class=" form-group col-sm-10">
                    <input type="text" class="form-control" id="payment_for_view" name="payment_for_view" disabled>
                    </div>
                </div>
                
                <table class="table table-striped" id="particular_table_view" style="width:100% !important">
                    <thead>
                        <tr>
                        <th>Particular</th>
                        <th style="width: 100px">Fund Code</th>
                        <th>Amount</th>
                        <th style="width: 320px">Bank Name</th>
                        <th style="width: 180px">Bank Account</th>
                        </tr>
                    </thead>
                    <tbody id="particularsTableBody">
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
