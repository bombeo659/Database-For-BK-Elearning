<?php
include('../session.php');
require_once("../class-user.php");

$auth_user = new USER();
$page_level = 1;
$auth_user->check_accesslevel($page_level);
$pageTitle = "Manage Classroom";
if (isset($_REQUEST["room_ID"])) {
    $this_room_ID = $_REQUEST["room_ID"];
}
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
            border: solid 1px darkslategray;
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
            $rtab_n = "active_room";
            include('x-sidenav.php');
            ?>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <?php if ($auth_user->admin_level() || $auth_user->instructor_level()) { ?>
                        <h1 class="h2">Modules Board</h1>
                    <?php } ?>
                    <?php if ($auth_user->student_level()) { ?>
                        <h1 class="h2">Modules</h1>
                    <?php } ?>
                </div>

                <div class="table-responsive">
                    <?php
                    $room_ID = $_GET["room_ID"];
                    $rtab = "room_module";
                    $rtab_c = "Modules";
                    include('x-roomtab.php');
                    ?>

                    <?php if ($auth_user->admin_level() || $auth_user->instructor_level()) { ?>
                        <button type="button" class="btn btn-sm btn-success add">
                            Add Module
                        </button>
                        <br><br>
                    <?php } ?>

                    <table class="table table-striped table-sm" id="modules_data">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th width="60%">Module Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <div class="modal fade" id="module_modal" tabindex="-1" role="dialog" aria-labelledby="product_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="module_modal_title">Add Module</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="module_modal_content">
                                    <form method="post" id="module_form" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="module_title">Title<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="module_title" name="module_title" placeholder="" value="" required="">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="module_ID" id="module_ID" />
                                            <input type="hidden" name="room_ID" id="room_ID" value="<?php echo $this_room_ID ?>" />
                                            <input type="hidden" name="operation" id="operation" />
                                            <div class="">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary submit" id="submit_input" value="submit_module">Submit</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal -->
                    <div class="modal fade" id="material_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Module Files</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php
                                    if ($auth_user->student_level()) {
                                    } else {
                                    ?>
                                    <button type="button" class="btn btn-sm btn-success add_materials">Upload Files</button>
                                    <?php
                                    }
                                    ?>
                                    <br><br>
                                    <table class="table table-striped table-sm" id="material_data">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th width="60%">Name</th>
                                                <th>Type</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="material_submit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Upload Files</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="post" id="material_form" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="material_name" class="col-sm-2 col-form-label">File Name:</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="material_name" name="material_name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="material_file" class="col-sm-2 col-form-label">File:</label>
                                            <div class="col-sm-10">
                                                <input type="file" class="form-control" id="material_file" name="material_file">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="room_ID" id="room_ID" value="<?php echo $this_room_ID ?>" />
                                        <input type="hidden" name="mod_ID" id="module_IDx" />
                                        <input type="hidden" name="m_operation" id="m_operation" value="material_submit"/>

                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary submit" id="submit_input_m" value="material_submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="delmodule_modal" tabindex="-1" role="dialog" aria-labelledby="product_modal_title" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="module_modal_title">Delete this Module</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-danger" id="module_delform">Delete</button>
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
             var dataTable = $('#modules_data').DataTable({
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "order": [],
                "ajax": {
                    url: "datatable/room_module/fetch.php?room_ID=" + <?php echo $this_room_ID ?>,
                    type: "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],

            });

            $(document).on('submit', '#module_form', function(event) {
                event.preventDefault();

                $.ajax({
                    url: "datatable/room_module/insert.php",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alertify.alert(data).setHeader('Module');
                        $('#module_form')[0].reset();
                        $('#module_modal').modal('hide');
                        dataTable.ajax.reload();
                    }
                });

            });

            $(document).on('click', '.add', function() {
                $('#module_modal_title').text('Add Module');
                $("#module_title").prop("disabled", false);
                $('#module_form')[0].reset();
                $('#module_modal').modal('show');
                $('#submit_input').show();
                $('#submit_input').text('Submit');
                $('#submit_input').val('submit_module');
                $('#operation').val("submit_module");
            });

            // $(document).on('click', '.view', function() {
            //     var module_ID = $(this).attr("id");

            //     $('#module_modal_title').text('View Module');
            //     $('#module_modal').modal('show');
            //     $("#submit_input").hide();

            //     $.ajax({
            //         url: "datatable/room_module/fetch_single.php",
            //         method: 'POST',
            //         data: {
            //             action: "module_view",
            //             module_ID: module_ID
            //         },
            //         dataType: 'json',
            //         success: function(data) {
            //             $("#module_title").prop("disabled", true);
            //             $('#module_title').val(data.mod_Title);
            //             $('#submit_input').hide();
            //             $('#module_ID').val(module_ID);
            //             $('#submit_input').text('View');
            //             $('#submit_input').val('module_view');
            //             $('#operation').val("module_view");
            //         }
            //     });
            // });

            $(document).on('click', '.edit', function() {
                var module_ID = $(this).attr("id");
                $('#module_modal_title').text('Edit Module');
                $('#module_modal').modal('show');
                $("#submit_input").show();

                $.ajax({
                    url: "datatable/room_module/fetch_single.php",
                    method: 'POST',
                    data: {
                        action: "module_edit",
                        module_ID: module_ID
                    },
                    dataType: 'json',
                    success: function(data) {
                        $("#module_title").prop("disabled", false);
                        $('#module_title').val(data.mod_Title);
                        $('#submit_input').show();
                        $('#module_ID').val(module_ID);
                        $('#submit_input').text('Update');
                        $('#submit_input').val('module_update');
                        $('#operation').val("module_edit");
                    }
                });
            });

            $(document).on('click', '.delete', function() {
                var module_ID = $(this).attr("id");
                alertify.confirm('Are you sure you want to delete this module?',
                    function() {
                        $.ajax({
                            type: 'POST',
                            url: "datatable/room_module/insert.php",
                            data: {
                                operation: "delete_module",
                                module_ID: module_ID
                            },
                            dataType: 'json',
                            complete: function(data) {
                                $('#delmodule_modal').modal('hide');
                                alertify.alert(data.responseText).setHeader('Delete Module');
                                dataTable.ajax.reload();
                                dataTable_product_data.ajax.reload();
                            }
                        })
                    },
                    function() {
                        alertify.error('Cancel')
                    }
                ).setHeader('Delete Module');
            });

            // $(document).on('click', '.view_file', function() {
            //     var module_ID = $(this).attr("id");
            //     // $('#module_modal_title').text('View Module');
            //     $('#material_modal').modal('show');
            //     // $("#submit_input").hide();

            //     $('#module_IDx').val(module_ID);
            //     material_data(module_ID);
            //     $('#material_data').DataTable().destroy();

            //     // $.ajax({
            //     //    url:"datatable/room_module/fetch_single.php",
            //     //    method:'POST',
            //     //    data:{action:"module_view",module_ID:module_ID},
            //     //    dataType    :   'json',
            //     //    success:function(data)
            //     //    {

            //     //    $("#module_title").prop("disabled", true);

            //     //      $('#module_title').val(data.mod_Title);

            //     //      $('#submit_input').hide();
            //     //      $('#module_ID').val(module_ID);
            //     //      $('#submit_input').text('View');
            //     //      $('#submit_input').val('module_view');
            //     //      $('#operation').val("module_view");

            //     //    }
            //     //  });
            // });


            //************MATERIAL SCRIPT************//
            function material_data(mod_ID) {
                var materials_dataTable = $('#material_data').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "bAutoWidth": false,
                    "order": [],
                    "ajax": {
                        url: "datatable/room_materials/fetch.php?room_ID=" + <?php echo $this_room_ID ?> + "&mod_ID=" + mod_ID,
                        type: "POST"
                    },
                    "columnDefs": [{
                        "targets": [0],
                        "orderable": false,
                    }, ],

                });

                $(document).on('submit', '#material_form', function(event) {
                    event.preventDefault();

                    $.ajax({
                        url: "datatable/room_materials/insert.php",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            alertify.alert(data).setHeader('Room Files');
                            $('#material_form')[0].reset();
                            $('#material_submit_modal').modal('hide');
                            materials_dataTable.ajax.reload();
                        }
                    });

                });

                $(document).on('click', '.add_materials', function() {

                    $('#material_submit_modal').modal('show');
                    materials_dataTable.ajax.reload();
                    // questionaire_dataTable.ajax.reload();

                });

                $(document).on('click', '.delete_material', function() {
                    var material_ID = $(this).attr("id");

                    alertify.confirm('Are you sure you want to delete this file?',
                        function() {
                            $.ajax({
                                type: 'POST',
                                url: "datatable/room_materials/insert.php",
                                data: {
                                    m_operation: "material_delete",
                                    material_ID: material_ID
                                },
                                dataType: 'json',
                                complete: function(data) {
                                    alertify.alert(data.responseText).setHeader('Room Files');
                                    materials_dataTable.ajax.reload();
                                }
                            })
                            alertify.success('Ok')
                        },
                        function() {
                            alertify.error('Cancel')
                        }
                    ).setHeader('Room Files');
                });
            }
        });
    </script>
</body>

</html>