<?php
include('../session.php');
require_once("../class-user.php");

$auth_user = new USER();
$page_level = 3;
$auth_user->check_accesslevel($page_level);
$pageTitle = "Manage Account";
?>

<!doctype html>
<html lang="en">

<head>
    <?php
    include('x-meta.php');
    include('x-css.php');
    ?>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
        
        ul#myTab.nav.nav-tabs a {
            color: black !important;
        }

        ul#myTab.nav.nav-tabs .nav-link:hover {
            color: white !important;
        }

        ul#myTab.nav.nav-tabs .nav-link.active:hover {

            color: black !important;
        }

        ul#myTab.nav.nav-tabs .nav-link.active {
            background-color: #e9ecef !important;
            /*color:white!important;*/
        }
        .pagination>li>a {
            background-color: white;
            color: #5A4181;
        }

        .pagination>li>a:focus,
        .pagination>li>a:hover,
        .pagination>li>span:focus,
        .pagination>li>span:hover {
            color: #5a5a5a;
            background-color: #eee;
            border-color: #ddd;
        }

        .pagination>.active>a {
            color: white;
            background-color: darkslategrey !Important;
            border: solid 1px darkslategrey !Important;
        }

        .pagination>.active>a:hover {
            background-color: darkslategray !Important;
            border: solid 1px darkslategray !Important;
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="../assets/css/dashboard.css" rel="stylesheet">
</head>

<body>
    <?php
    include('x-nav.php');
    ?>

    <div class="container-fluid">
        <div class="row">
            <?php
            include('x-sidenav.php');
            ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manage Account</h1>

                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bcrum bg-dark">
                        <li class="breadcrumb-item "><a href="index" class="bcrum_i_a">Dashboard</a></li>
                        <li class="breadcrumb-item  active bcrum_i_ac" aria-current="page">Account Management</li>
                    </ol>
                </nav>
                <div class="table-responsive">
                    <table class="table table-striped table-sm" id="admin_data">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>User Level</th>
                                <th>Username</th>
                                <th>Register Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <!-- Modal -->
                    <div class="modal fade" id="changepass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Change Password</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="change_password_form" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="update_password_new">New Password<span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" id="update_password_new" name="update_password_new" placeholder="" value="" required="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="update_password_newconfirm">Confirm Password<span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" id="update_password_newconfirm" name="update_password_newconfirm" placeholder="" value="" required="">
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" id="account_ID" name="account_ID" value="">
                                    <input type="hidden" name="operation" value="change_password">
                                    <div class="">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-primary" id="btn_change_password" value="Save changes">
                                    </div>
                                </div>
                                    </form>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <?php
    include('x-script.php');
    ?>
    <script type="text/javascript">
        $(document).ready(function() {

            var admin_dataTable = $('#admin_data').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "datatable/account/fetch.php",
                    type: "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],

            });
            admin_dataTable.columns([1]).visible(false);

            $(document).on('submit', '#change_password_form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: "datatable/account/insert.php",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alertify.alert(data).setHeader('Account Record');
                        $('#change_password_form')[0].reset();
                        $('#changepass').modal('hide');
                        admin_dataTable.ajax.reload();
                    }
                });
            });

            $(document).on('click', '.change', function() {
                var acc_ID = $(this).attr("id");
                $('#account_ID').val(acc_ID);
                $('#changepass').modal('show');
            });

            $(document).on('click', '.delete_account', function() {
                var user_ID = $(this).attr("id");

                alertify.confirm('Are you sure you want to delete this account?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/account/insert.php",
                            data: {
                                m_operation: "user_delete",
                                user_ID: user_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                alertify.alert(data.responseText).setHeader('Delete Account');
                                admin_dataTable.ajax.reload();
                            }
                        })
                        alertify.success('Ok')
                    },
                    function() {
                        alertify.error('Cancel')
                    }
                ).setHeader('Delete Account');
            });
        });
    </script>
</body>

</html>