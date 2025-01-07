<section>
<div class="login-box">
    <div class="card card-outline">
        <div class="card-body">
            <div class="text-center">
            <img class="text-center" id="image-logo-denr" src="https://iis.emb.gov.ph/embis/assets/images/logo-denr.png" alt="logo-denr" style="width:100px;height:100px;margin-bottom: 15px;"><br>
            </div>

            <h3 class="text-center" style="color:green;">ENVIRONMENTAL MANAGEMENT BUREAU</h3><br>
            <form id="login-form">
                <div class="form-group">
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                </div>
                <div class="row">
                    <div class="col-12 text-right">
                        <a href="#">Forgot password?</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button  id="loginsubmit" class="btn btn-primary btn-block">LOGIN</button>
                        <?= base_url() ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</section>
<!-- jQuery -->
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
                var username = $('#username').val();
                var password = $('#password').val();
                var data = new FormData();
                data.append("username", username);
                data.append("password", password);
                fetch("https://iis.emb.gov.ph/embis/api/pis_api/login_api", {
                    method: "POST",
                    body: data
                }).then((response) => {
                    if (response.status != 200) {
                        alert('Wrong username or password')
                    }else {
                        return response.json();
                    }
                })
                .then((data) => {
                    console.log(data[0])
                    $.ajax({
                        type: 'post',
                        data: {
                            id_number: data[0]['userid'],
                            rgnid: data[0]['rgnid'],
                            designation: data[0]['designation'],
                            section: data[0]['section'],
                        },
                        url: '<?= base_url(); ?>/auth/login',
                        success: function(result) {
                            if (result.msg == 'admit') {
                                window.location.href = "<?= base_url(); ?>order-payment";
                            } else if (result.msg == 'refuse') {
                                alert('not registered to PIS')
                            } else if (result.msg == 'unknown') {
                                alert('not registered to PIS')
                            }
                        }
                    })
                })
            }
        });
        $('#login-form').validate({
            rules: {
            username: {
                required: true,
            },
            password: {
                required: true,
                minlength: 5
            },
            },
            messages: {
                username: {
                required: "Please enter a Username",
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
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
