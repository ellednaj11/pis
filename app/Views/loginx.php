<section>
    <div class="card">
        <div class="card-body">
            <div class="text-center">
            <img class="text-center" id="image-logo-denr" src="https://iis.emb.gov.ph/embis/assets/images/logo-denr.png" alt="logo-denr" style="width:100px;height:100px;margin-bottom: 15px;"><br>
            </div>
            
            <h3 class="card-title text-center" style="color:green;">ENVIRONMENTAL MANAGEMENT BUREAU</h3><br>
            
            <form id="login-form">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" id="username" placeholder="User Name">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                </div>
                <br>
                <button  id="loginsubmit" class="btn btn-primary btn-block">LOGIN</button>
            </form>
        </div>
    </div>
</section>
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
                $.ajax({
                    url: 'https://iis.emb.gov.ph/embis/api/swm_api/login_api',
                    type: 'POST',
                    body: data,
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function(response) {
                        // Handle success response
                        console.log(response);
                        alert("Login successful!");
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.log(xhr, status, error);
                        alert("Login failed!");
                    }
                });

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
            email: {
                required: "Please enter a User Name",
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

    $("#loginsubmit").click(function() {
        $username = $('#username').val();
        $password = $('#password').val();
        if (!username || !password) {
            var data = new FormData();
            data.append("username", username);
            data.append("password", password);
            fetch("https://iis.emb.gov.ph/embis/api/swm_api/login_api", {
                method: "POST",
                body: data
            }).then((result) => {
                if (result.status != 200) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed!',
                        text: 'Invalid Username or Password'
                    });
                    throw new Error("Bad Server Response");
                }
                return result.text();
            }).then((response) => {
                console.log(response)
                // user = JSON.parse(response)[0];
                // $.ajax({
                //     type: 'post',
                //     data: {
                //         'id_number': user['id_number'],
                //         'fname': user['fname'],
                //         'sname': user['sname'],
                //         'username': user['username'],
                //         'designation': user['designation'],
                //         'email': user['email'],
                //         'section': user['section'],
                //         'region_id': user['rgnid'],
                //         'region': user['region'],
                //         'wm_admin': user['wm_admin']
                //     },
                //     url: '<?= base_url(); ?>/auth/iislogin',
                //     success: function(result) {
                //         if (result.msg == 'admit') {
                //             window.location.href = "<?= base_url(); ?>/iis/homepage";
                //         } else if (result.msg == 'refuse') {
                //             Swal.fire({
                //                 icon: 'error',
                //                 title: 'Login Failed!',
                //                 text: 'Unauthorize Account'
                //             });
                //         } else if (result.msg == 'unknown') {
                //             Swal.fire({
                //                 icon: 'error',
                //                 title: 'Login Failed!',
                //                 text: 'Invalid Username or Password'
                //             });
                //         }
                //     }
                // })
            })
        }
    })
</script>
