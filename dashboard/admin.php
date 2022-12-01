<?php
include('../session.php');
require_once("../class-user.php");
$auth_user = new USER();
$page_level = 3;
$auth_user->check_accesslevel($page_level);
$pageTitle = "Manage Admin";
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
                    <h1 class="h2">Manage Admin</h1>

                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bcrum bg-dark">
                        <li class="breadcrumb-item "><a href="index" class="bcrum_i_a">Dashboard</a></li>
                        <li class="breadcrumb-item  active bcrum_i_ac" aria-current="page">Admin Management</li>
                    </ol>
                </nav>
                <div class="table-responsive">
                    <button type="button" class="btn btn-sm btn-success add">Add Admin</button>
                    <br><br>
                    <table class="table table-striped table-sm" id="admin_data">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Admin ID</th>
                                <th>Name</th>
                                <th>Sex</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Account Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>


                    <!--modal student -->
                    <div class="modal fade" id="admin_modal" tabindex="-1" role="dialog" aria-labelledby="admin_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="admin_modal_title">Add Admin</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="post" id="admin_form" enctype="multipart/form-data">
                                    <div class="modal-body" id="product_modal_content">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <img id="s_img" src="../assets/img/users/default.jpg" alt="Student Image" runat="server" height="125" width="125" class="img-thumbnail" style="border:1px solid; border-color: #4caf50; min-width:125px; min-height:125px; max-width:125px; max-height:125px; background-size:cover;" />
                                                <br><br>
                                                <input type="file" class="form-control" id="admin_img" name="admin_img" placeholder="" value="">
                                            </div>
                                            <div class="form-group col-md-4">

                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="admin_EmpID">Admin ID<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="admin_EmpID" name="admin_EmpID" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="admin_fname">First Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="admin_fname" name="admin_fname" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="admin_mname">Middle Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="admin_mname" name="admin_mname" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="admin_lname">Last Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="admin_lname" name="admin_lname" placeholder="" value="" required="">
                                            </div>
                                            <!-- <div class="form-group col-md-3">
                                                <label for="admin_suffix">Suffix<span class="text-danger">*</span></label>
                                                <select class="form-control" id="admin_suffix" name="admin_suffix">
                                                    <?php
                                                    // $auth_user->user_suffix_option();
                                                    ?>
                                                </select>
                                            </div> -->
                                            <div class="form-group col-md-6">
                                                <label for="admin_bday">Birthday<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="admin_bday" name="admin_bday" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="admin_sex">Sex<span class="text-danger">*</span></label>
                                                <select class="form-control" id="admin_sex" name="admin_sex" required="">
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Orther">Other</option>
                                                    <?php
                                                    // $auth_user->user_sex_option();
                                                    ?>
                                                </select>
                                            </div>
                                            <!-- <div class="form-group col-md-4">
                                                <label for="admin_marital">Marital<span class="text-danger">*</span></label>
                                                <select class="form-control" id="admin_marital" name="admin_marital" required="">
                                                    <?php
                                                    // $auth_user->user_marital_option();
                                                    ?>
                                                </select>
                                            </div> -->
                                            <div class="form-group col-md-12">
                                                <label for="admin_email">Email<span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="admin_email" name="admin_email" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="admin_address">Address<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="admin_address" name="admin_address" placeholder="" value="" required="">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="admin_ID" id="admin_ID" />
                                        <input type="hidden" name="operation" id="operation" />
                                        <div class="btn-group" id='sbtng'>
                                            <button type="button" class="btn btn-secondary rounded mr-1" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary submit rounded" id="submit_input" value="submit_admin">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--/modal student -->

                    <!--delete modal -->
                    <div class="modal fade" id="deladmin_modal" tabindex="-1" role="dialog" aria-labelledby="admin_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="admin_modal_title">Delete this Admin</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-danger" id="admin_delform">Delete</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/delete modal -->


                </div>
            </main>
        </div>
    </div>

    <?php
    include('x-script.php');
    ?>
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#s_img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#admin_img").change(function() {
            readURL(this);
        });

        $(document).ready(function() {
            var admin_dataTable = $('#admin_data').DataTable({
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "order": [],
                "ajax": {
                    url: "datatable/admin/fetch.php",
                    type: "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],

            });
            admin_dataTable.columns([1]).visible(false);
            admin_dataTable.columns([5]).visible(false);
            admin_dataTable.columns([6]).visible(false);
            admin_dataTable.columns([7]).visible(false);

            $(document).on('submit', '#admin_form', function(event) {
                event.preventDefault();

                $.ajax({
                    url: "datatable/admin/insert.php",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alertify.alert(data).setHeader('Account Record');
                        $('#admin_form')[0].reset();
                        $('#admin_modal').modal('hide');
                        admin_dataTable.ajax.reload();
                    }
                });

            });

            $(document).on('click', '.add', function() {
                $('#admin_modal_title').text('Add New Admin Account');
                $('#admin_modal').modal('show');
                $('#admin_form')[0].reset();

                var btng = document.getElementById("sbtng");
                btng.className = btng.className.replace(/\btng_null\b/g, "");
                btng.classList.add("btn-group");

                $('#s_img').attr('src', "../assets/img/users/default.jpg");
                $("#admin_EmpID").prop("disabled", false);
                $("#admin_fname").prop("disabled", false);
                $("#admin_mname").prop("disabled", false);
                $("#admin_lname").prop("disabled", false);
                $("#admin_suffix").prop("disabled", false);
                $("#admin_bday").prop("disabled", false);
                $("#admin_sex").prop("disabled", false);
                $("#admin_marital").prop("disabled", false);
                $("#admin_email").prop("disabled", false);
                $("#admin_address").prop("disabled", false);


                $("#admin_img").show();
                $('#submit_input').show();

                $('#submit_input').text('Submit');
                $('#submit_input').val('submit_admin');
                $('#operation').val("submit_admin");
            });

            $(document).on('click', '.view', function() {
                var admin_ID = $(this).attr("id");
                $('#admin_modal_title').text('View Admin Account');
                $('#admin_modal').modal('show');


                $('#submit_input').hide();
                var btng = document.getElementById("sbtng");
                btng.className = btng.className.replace(/\bbtn-group\b/g, "");
                btng.classList.add("btng_null");


                $("#admin_img").hide();

                $.ajax({
                    url: "datatable/admin/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "admin_view",
                        admin_ID: admin_ID
                    },
                    dataType: 'json',
                    success: function(data) {
                        $("#admin_EmpID").prop("disabled", true);
                        $("#admin_fname").prop("disabled", true);
                        $("#admin_mname").prop("disabled", true);
                        $("#admin_lname").prop("disabled", true);
                        $("#admin_suffix").prop("disabled", true);
                        $("#admin_bday").prop("disabled", true);
                        $("#admin_sex").prop("disabled", true);
                        $("#admin_marital").prop("disabled", true);
                        $("#admin_email").prop("disabled", true);
                        $("#admin_address").prop("disabled", true);


                        $('#s_img').attr('src', data.admin_img);
                        $('#admin_EmpID').val(data.admin_EmpID);
                        $('#admin_fname').val(data.admin_fname);
                        $('#admin_mname').val(data.admin_mname);
                        $('#admin_lname').val(data.admin_lname);
                        $('#admin_suffix').val(data.admin_suffix).change();
                        $('#admin_bday').val(data.admin_bday);
                        $('#admin_sex').val(data.admin_sex).change();
                        $('#admin_marital').val(data.admin_marital).change();
                        $('#admin_email').val(data.admin_email);
                        $('#admin_address').val(data.admin_address);

                        $('#submit_input').hide();
                        $('#admin_ID').val(admin_ID);
                        $('#submit_input').text('Update');
                        $('#submit_input').val('admin_view');
                        $('#operation').val("admin_view");
                    }
                });
            });


            $(document).on('click', '.edit', function() {
                var admin_ID = $(this).attr("id");
                var acreg = $(this).attr("acreg");
                $('#admin_modal_title').text('View Student');
                $('#admin_modal').modal('show');


                var btng = document.getElementById("sbtng");
                btng.className = btng.className.replace(/\btng_null\b/g, "");
                btng.classList.add("btn-group");


                $("#admin_img").show();

                $.ajax({
                    url: "datatable/admin/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "admin_update",
                        admin_ID: admin_ID
                    },
                    dataType: 'json',
                    success: function(data) {
                        // if (acreg == "UN") {
                        //     $("#admin_EmpID").prop("disabled", false);
                        // } else {
                        //     $("#admin_EmpID").prop("disabled", true);
                        // }
                        $("#admin_EmpID").prop("disabled", false);
                        $("#admin_fname").prop("disabled", false);
                        $("#admin_mname").prop("disabled", false);
                        $("#admin_lname").prop("disabled", false);
                        $("#admin_suffix").prop("disabled", false);
                        $("#admin_bday").prop("disabled", false);
                        $("#admin_sex").prop("disabled", false);
                        $("#admin_marital").prop("disabled", false);
                        $("#admin_email").prop("disabled", false);
                        $("#admin_address").prop("disabled", false);


                        $('#s_img').attr('src', data.admin_img);
                        $('#admin_EmpID').val(data.admin_EmpID);
                        $('#admin_fname').val(data.admin_fname);
                        $('#admin_mname').val(data.admin_mname);
                        $('#admin_lname').val(data.admin_lname);
                        $('#admin_suffix').val(data.admin_suffix).change();
                        $('#admin_bday').val(data.admin_bday);
                        $('#admin_sex').val(data.admin_sex).change();
                        $('#admin_marital').val(data.admin_marital).change();
                        $('#admin_email').val(data.admin_email);
                        $('#admin_address').val(data.admin_address);

                        $('#submit_input').show();
                        $('#admin_ID').val(admin_ID);
                        $('#submit_input').text('Update');
                        $('#submit_input').val('admin_update');
                        $('#operation').val("admin_update");
                    }
                });
            });

            $(document).on('click', '.delete', function() {
                var admin_id = $(this).attr("id");
                alertify.confirm('Are you sure you want to delete this admin?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/admin/insert.php",
                            data: {
                                operation: "delete_admin",
                                admin_ID: admin_id
                            },
                            dataType: 'json',
                            complete: function(data) {
                                alertify.alert(data.responseText).setHeader('Delete Admin');
                                admin_dataTable.ajax.reload();
                            }
                        })
                    },
                    function() {
                        alertify.error('Cancel')
                    }
                ).setHeader('Delete Admin');
            });

            // $(document).on('click', '.delete', function() {
            //     var admin_ID = $(this).attr("id");
            //     $('#deladmin_modal').modal('show');
            //     // $('.submit').hide();

            //     $('#admin_ID').val(admin_ID);
            // });

            // $(document).on('click', '#admin_delform', function(event) {
            //     var admin_ID = $('#admin_ID').val();
            //     $.ajax({
            //         type: 'POST',
            //         url: "datatable/admin/insert.php",
            //         data: {
            //             operation: "delete_admin",
            //             admin_ID: admin_ID
            //         },
            //         dataType: 'json',
            //         complete: function(data) {
            //             $('#deladmin_modal').modal('hide');
            //             alertify.alert(data.responseText).setHeader('Delete this Student');
            //             admin_dataTable.ajax.reload();

            //         }
            //     })

            // });

            $(document).on('click', '.gen_account', function(event) {
                var admin_ID = $(this).attr("id");
                alertify.confirm('Are you sure you want to create this person account?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/admin/insert.php",
                            data: {
                                operation: "gen_account",
                                admin_ID: admin_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                alertify.alert(data.responseText).setHeader('Generated Account');
                                admin_dataTable.ajax.reload();
                            }
                        })
                        admin_dataTable.ajax.reload();
                        // alertify.success('Ok')
                    },
                    function() {
                        alertify.error('Cancel')
                    }
                ).setHeader('Generate Account');
            });

        });
    </script>
</body>

</html>