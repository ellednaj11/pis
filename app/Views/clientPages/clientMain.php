
<div class="login-container">
    <div class="login-image">
        <h3>EMB Payment Information System Client Payment Page</h3>
        <p>Instructions: <br>

        1. Please enter the Order of Payment <br>
        Transaction ID and Click the Submit button.<br><br>

        2. Select your preferred payment method. <br><br>

        3. For payment made through linkbiz.portal, landbank over-the-counter channels, you have to revisit this page to upload the proof of payment.
        </p>
    </div>
    <div class="login-form my-2">
        <div class="text-center">
            <img src="https://iis.emb.gov.ph/embis/assets/images/logo-denr.png" alt="Logo" style="max-width: 100px;">
        </div>
        <br>
        <p class="text-center">Please provide Transaction Number</p>
        <form  id="trans-form">
            <!-- <div class="form-group">
                <label for="username">Payment for:</label>
                <input type="text" class="form-control" id="username" placeholder="Payment for">
            </div> -->
            <div class="form-group">
                <label for="trans_no">Transaction ID / Application ID</label>
                <input type="text" class="form-control" id="trans_no" name="trans_no" placeholder="Transaction Number">
            </div>
            <!-- <div class="form-group">
                <label for="password">Order of Payment No</label>
                <input type="password" class="form-control" id="password" placeholder="Transaction ID / Application ID">
            </div>
            <div class="form-group">
                <label for="password">Order of Payment Date</label>
                <input type="password" class="form-control" id="password" placeholder="Transaction ID / Application ID">
            </div> -->
            <!-- <button class="btn btn-primary btn-block">Submit</button> -->
            <button  id="loginsubmit" class="btn btn-info btn-block">Submit</button>
        </form>
    </div>
    
</div>
<script src="<?= base_url('public/plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('public/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- AdminLTE App -->
<!-- <script src="<?= base_url('public/dist/js/adminlte.min.js') ?>"></script> -->
<script src="<?php echo base_url(); ?>public/plugins/jquery-validation/jquery.validate.min.js"></script>
<script>
    $(function () {
        $.validator.setDefaults({
            submitHandler: function (form,event) {
                event.preventDefault(); 
                var trans_no = $('#trans_no').val();
                // fetch("https://iis.emb.gov.ph/embis/api/pis_api/login_api", {
                //     method: "POST",
                //     body: data
                // }).then((response) => {
                //     if (response.status != 200) {
                //         alert('Wrong username or password')
                //     }else {
                //         return response.json();
                //     }
                // })
                // .then((data) => {
                    
                // })

                $.ajax({
                    type: 'get',
                    data: {
                        trans_no: trans_no
                    },
                    url: '<?= base_url(); ?>/payment/check-trans-number',
                    success: function(result) {
                        console.log(result);
                        if (result.msg == "true") {
                            window.location.href = "<?= base_url(); ?>accept-payment?token="+result.encryptText;
                        } else{
                            alert('Please enter valid Transaction Number')
                        }
                    }
                })
            }
        });
        $('#trans-form').validate({
            rules: {
            trans_no: {
                required: true,
            },
            },
            messages: {
                trans_no: {
                required: "Please enter Transaction Number",
            },
            },
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
    });

</script>
