<?php
include('../session.php');
require_once("../class-user.php");
$auth_user = new USER();
$page_level = 3;
$auth_user->check_accesslevel($page_level);
$pageTitle = "Manage School Year";
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
                    <h1 class="h2">Manage School Year</h1>

                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bcrum bg-dark">
                        <li class="breadcrumb-item "><a href="index" class="bcrum_i_a">Dashboard</a></li>
                        <li class="breadcrumb-item  active bcrum_i_ac" aria-current="page">School Year Management</li>
                    </ol>
                </nav>

                <div class="table-responsive">
                    <button type="button" class="btn btn-sm btn-success add">
                        Add Academic Year
                    </button>
                    <br><br>
                    <table class="table table-striped table-sm" id="section_data">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>School Year</th>
                                <th>Status</th>
                                <th width="30%"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>


                    <div class="modal fade" id="semester_modal" tabindex="-1" role="dialog" aria-labelledby="product_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="semester_modal_title">Add School Year</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="product_modal_content">

                                    <form method="post" id="semester_form" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="semester_start">Start<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="semester_start" name="semester_start" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="semester_end">End<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="semester_end" name="semester_end" placeholder="" value="" required="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="semester_stat">Status<span class="text-danger">*</span></label>
                                                <select class="form-control" id="semester_stat" name="semester_stat">
                                                    <option value="1">Activate</option>
                                                    <option value="0">Deactivate</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="semester_ID" id="semester_ID" />
                                            <input type="hidden" name="operation" id="operation" />
                                            <div class="">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary submit" id="submit_input" value="submit_semester">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="delsemester_modal" tabindex="-1" role="dialog" aria-labelledby="product_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="semester_modal_title">Delete this School Year</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-danger" id="Section_delform">Delete</button>
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

            </main>
        </div>
    </div>

    <?php
    include('x-script.php');
    ?>
    <script type="text/javascript">
        $(document).ready(function() {

            var dataTable = $('#section_data').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "datatable/academicyear/fetch.php",
                    type: "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],
            });
            dataTable.columns([1]).visible(false);

            $(document).on('submit', '#semester_form', function(event) {
                event.preventDefault();

                $.ajax({
                    url: "datatable/academicyear/insert.php",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alertify.alert(data).setHeader('School Year');
                        $('#semester_form')[0].reset();
                        $('#semester_modal').modal('hide');
                        dataTable.ajax.reload();
                    }
                });

            });

            $(document).on('click', '.add', function() {
                $('#semester_modal_title').text('Add School Year');
                $("#semester_start").prop("disabled", false);
                $("#semester_end").prop("disabled", false);
                $("#semester_stat").prop("disabled", false);
                $('#semester_form')[0].reset();
                $('#semester_modal').modal('show');
                $('#submit_input').show();
                $('#submit_input').text('Submit');
                $('#submit_input').val('submit_semester');
                $('#operation').val("submit_semester");
            });

            $(document).on('click', '.view', function() {
                var semester_ID = $(this).attr("id");
                $('#semester_modal_title').text('View School Year');
                $('#semester_modal').modal('show');
                $("#submit_input").hide();

                $.ajax({
                    url: "datatable/academicyear/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "semester_view",
                        semester_ID: semester_ID
                    },
                    dataType: 'json',
                    success: function(data) {

                        $("#semester_start").prop("disabled", true);
                        $("#semester_end").prop("disabled", true);
                        $("#semester_stat").prop("disabled", true);

                        $('#semester_start').val(data.sem_start);
                        $('#semester_end').val(data.sem_end);
                        $('#semester_stat').val(data.stat_ID).change();

                        $('#submit_input').hide();
                        $('#semester_ID').val(semester_ID);
                        $('#submit_input').text('View');
                        $('#submit_input').val('semester_view');
                        $('#operation').val("semester_view");

                    }
                });


            });
            $(document).on('click', '.edit', function() {
                var semester_ID = $(this).attr("id");
                $('#semester_modal_title').text('Edit School Year');
                $('#semester_modal').modal('show');
                $("#submit_input").show();

                $.ajax({
                    url: "datatable/academicyear/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "semester_edit",
                        semester_ID: semester_ID
                    },
                    dataType: 'json',
                    success: function(data) {
                        $("#semester_start").prop("disabled", false);
                        $("#semester_end").prop("disabled", false);
                        $("#semester_stat").prop("disabled", false);


                        $('#semester_start').val(data.sem_start);
                        $('#semester_end').val(data.sem_end);
                        $('#semester_stat').val(data.stat_ID).change();

                        $('#submit_input').show();
                        $('#semester_ID').val(semester_ID);
                        $('#submit_input').text('Update');
                        $('#submit_input').val('semester_update');
                        $('#operation').val("semester_edit");

                    }
                });


            });

            $(document).on('click', '.delete', function() {
                var semester_ID = $(this).attr("id");
                alertify.confirm('Are you sure you want to delete this semester?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/academicyear/insert.php",
                            data: {
                                operation: "delete_semester",
                                semester_ID: semester_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                $('#delsemester_modal').modal('hide');
                                alertify.alert(data.responseText).setHeader('Delete School Year');
                                dataTable.ajax.reload();
                            }
                        })
                    },
                    function() {
                        alertify.error('Cancel')
                    }
                ).setHeader('Delete Semester');
            });

            // $(document).on('click', '.delete', function() {
            //     var semester_ID = $(this).attr("id");
            //     $('#delsemester_modal').modal('show');
            //     $('.submit').hide();

            //     $('#semester_ID').val(semester_ID);
            // });

            // $(document).on('click', '#Section_delform', function(event) {
            //     var semester_ID = $('#semester_ID').val();
            //     $.ajax({
            //         type: 'POST',
            //         url: "datatable/academicyear/insert.php",
            //         data: {
            //             operation: "delete_section",
            //             semester_ID: semester_ID
            //         },
            //         dataType: 'json',
            //         complete: function(data) {
            //             $('#delsemester_modal').modal('hide');
            //             alertify.alert(data.responseText).setHeader('Delete this School Year');
            //             dataTable.ajax.reload();
            //             dataTable_product_data.ajax.reload();

            //         }
            //     })

            // });

        });
    </script>
</body>

</html>