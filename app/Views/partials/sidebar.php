<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #08507E;">
    <!-- Brand Logo -->
    <a href="<?= base_url('/') ?>" class="brand-link">
        <img src="<?php echo base_url(); ?>assets/images/logo-denr.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">EMB - PIS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url('public/dist/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= session()->get('name') ?></a>
            </div>
        </div> -->

        

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="<?= base_url('/dashboard') ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/order-payment') ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Order of Payment</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/payment-history') ?>" class="nav-link">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Payment History<span class="badge badge-info right" id='for_verfiy_badge'></span></p>
                    </a>
                </li>
                <?php
                    $userType = session()->get('role');
                    $modules = [
                        '1' => [
                            (object)['value' => 'fees-schedule', 'title' => 'Schedule of Fees', 'icon' => 'fas fa-calendar-week'],
                            (object)['value' => 'receipt-books', 'title' => 'Receipt Book', 'icon' => 'fas fa-book'],
                            (object)['value' => 'bank-accounts', 'title' => 'Bank Accounts', 'icon' => 'fas fa-university'],
                            (object)['value' => 'reports', 'title' => 'Reports', 'icon' => 'fas fa-flag'],
                        ]
                    ];

                    if (array_key_exists($userType, $modules)) {
                        foreach ($modules[$userType] as $module) {
                            echo "<li class='nav-item'>
                                    <a href='" . base_url($module->value) . "' class='nav-link'>
                                        <i class='nav-icon {$module->icon}'></i>
                                        <p>{$module->title}
                                        
                                        </p>
                                    </a>
                                </li>";
                        }
                    }
                ?>
                <!-- <li class="nav-item">
                    <a href="<?= base_url('/fees-schedule') ?>" class="nav-link">
                        <i class="nav-icon fas fa-calendar-week"></i>
                        <p>Schedule of Fees</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/receipt-books') ?>" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Receipt Book</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/bank-accounts') ?>" class="nav-link">
                        <i class="nav-icon fas fa-university"></i>
                        <p>Bank Accounts</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/reports') ?>" class="nav-link">
                        <i class="nav-icon fas fa-flag"></i>
                        <p>Reports</p>
                    </a>
                </li> -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<script>
    $(function () {
        get_for_verify()
    });

    function get_for_verify(){
        var id = $('#payment_method').data('payment_id');
        var amount_paid = parseFloat($('#amount_paid').text().replace(/,/g, ''));
        var op_id = $('#pis_trans_no').data('trans_id');
        $.ajax({
            data : {id:id , amount_paid:amount_paid,op_id:op_id}
            ,type: "GET"
            ,url: '<?= base_url(); ?>layout/get-for-verify-count'
            , dataType: 'json'
            , crossOrigin: false
            , beforeSend : function() {
            }
            , success: function(result) {
                $('#for_verfiy_badge').text(result[0]['total_to_verify'])
            }
        });
    }
</script>
