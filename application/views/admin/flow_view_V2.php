<!DOCTYPE html>
<html style="height: auto;" lang="en">

<head>
    <meta charset="utf-8">

    <!-- Google Font: Source Sans Pro -->
    <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/treemaker/tree_maker.css"> -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/treeMakerV2/style/tree_maker.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url() ?>assets/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <style type="text/css">
        .custom-loader {
            animation: none !important;
            border-width: 0 !important;
        }

        .path {
            animation: dash 2.5s linear infinite;
        }

        @keyframes dash {
            from {
                stroke-dashoffset: 100;
                opacity: 1;
            }

            to {
                stroke-dashoffset: 0;
                opacity: 1;
            }
        }

        <?php if ($direction == 'up') { ?>#my_tree {
            transform: rotate(180deg);
        }

        .tree__container__step__card__p {
            transform: rotate(180deg);
            -webkit-transform: rotate(180deg);
            -moz-transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            -o-transform: rotate(180deg);
        }

        <?php } ?>.card-body {
            min-height: 300px;
            min-width: 300px;
            margin-right: 5px;
        }

        body {
            background-color: #212124;
        }

        .tree__container__step__card__p {
            color: #f8f9fa;
        }

        /* Context menu */
        .context-menu {
            display: none;
            position: absolute;
            border: 1px solid black;
            border-radius: 3px;
            width: 200px;
            background: white;
            box-shadow: 10px 10px 5px #888888;
            padding-bottom: 0;
        }

        .context-menu ul {
            list-style: none;
            padding: 2px;
        }

        .context-menu ul li {
            padding: 5px 2px;
            margin-bottom: 3px;
            /* background-color: darkturquoise; */
        }

        .context-menu ul li:hover {
            cursor: pointer;
            background-color: #28a745;
        }

        .c {
            width: 100%;
            height: 100%;
            overflow: hidden;
            overflow-y: auto;
        }

        .card-home {
            position: absolute;
            top: 0;
            animation: scroll 70s linear 1s infinite;
        }

        /* @keyframes scroll {
            100% { top: -200%; }  
        } */

        /* #padre{
            overflow-x: visible;
            white-space: nowrap;
            
        }


        #hijo{
        left: 0;
            position: fixed;
            overflow: visible;
            -moz-transform-origin: top left;
            -ms-transform-origin: top left;
            -o-transform-origin: top left;
            -webkit-transform-origin: top left;
            transform-origin: top left;
            -moz-transition: all .2s ease-in-out;
            -o-transition: all .2s ease-in-out;
            -webkit-transition: all .2s ease-in-out;
            transition: all .2s ease-in-out;
        } */
    </style>


</head>

<body>
    <div class="wrapper ">
        <div class="content-wrapper c " id="padre">
            <div class="card-home" id="hijo">
                <section class="content-header" style="min-width: 100%;">
                    <div class="container">
                        <div class="row mt-3 ">
                            <div class="col-sm-7 col-12">
                                <h3 class="text-light"><?= $spam_name ?><br><small class="text-light timelabel"></small></h3>

                            </div>
                            <div class="col-sm-5 col-12 text-right">
                                <!-- <a type="button" class="btn btn-primary pull-right" onclick="UpdateLogger()"> <i class="fa fa-sync"></i> Sync Data Logger</a> -->

                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-primary" onclick="UpdateLogger()"><i class="fa fa-sync"></i></button>
                                    <!-- <button type="button" class="btn btn-secondary">Middle</button> -->
                                    <?php if ($this->session->userdata('status') == 'loggedin') { ?>
                                        <a type="button" class="btn btn-success" href="<?= base_url('index.php/flowkomponen/' . $root) ?>"> <i class="fa fa-edit"></i> </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="content ">
                    <div class="col-md-12 container-fluid py-2">
                        <div class="d-flex flex-row flex-nowrap justify-content-center ">
                            <div id="my_tree" class=""></div>
                        </div>
                    </div>
                </section>
            </div>



        </div>


        <div class="modal" tabindex="-1" id="modal-add-komponen">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="new-tab" data-toggle="tab" href="#new" role="tab" aria-controls="new" aria-selected="true">New Child</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="merge-tab" data-toggle="tab" href="#merge" role="tab" aria-controls="merge" aria-selected="false" onclick="data_to_merge(<?= $root ?>)">Existing Child</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="new" role="tabpanel" aria-labelledby="new-tab">
                                <form id="form" method="post" onsubmit="return false;">
                                    <input type="hidden" name="id">
                                    <input type="hidden" name="root" value="<?= $root ?>">
                                    <!-- <input type="hidden" name="input_parent" id="input_parent" > -->
                                    <div class="form-group input_parent_container">
                                        <label for="input_parent">Pilih Parent (Turunan Dari)</label>
                                        <select class="form-control" id="input_parent" name="input_parent">

                                        </select>
                                        <small id="input_parent_error_icon" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="input_step">Step</label>
                                        <select class="form-control" id="input_step" name="input_step" onchange="input_name_handler()">
                                            <option value="x">-- Silahkan Pilih Parent--</option>'
                                        </select>
                                        <small id="input_step_error_icon" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group form-group-name">
                                        <label for="input_nama_komponen">Nama</label>
                                        <input type="text" class="form-control" id="input_nama_komponen" name="input_nama_komponen">
                                        <small id="input_nama_komponen_error_detail" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group form-group-kode">
                                        <label for="input_kode">Kode</label>
                                        <input type="text" class="form-control" id="input_kode" name="input_kode">
                                        <small id="input_kode_error_detail" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="input_url">URL</label>
                                        <input type="text" class="form-control" id="input_url" name="input_url">
                                        <small id="input_url_error_detail" class="form-text text-danger"></small>
                                    </div>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary" id="btnSave"><i class="fa fa-save"></i> Simpan</button>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="merge" role="tabpanel" aria-labelledby="merge-tab">
                                <div class="row">
                                    <input type="hidden" id="idnya" name="idnya">
                                    <div class="col-12" id="mergecontainer">

                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>

        <!-- Context-menu -->
        <div class='context-menu'>
            <ul>
                <li><span class='Gainsboro'></span>&nbsp;<span><i class="fa fa-plus"></i> Tambah Child </span></li>
                <li><span class='Gainsboro2'></span>&nbsp;<span><i class="fa fa-edit"></i> Edit </span></li>
                <li><span class='Gainsboro3'></span>&nbsp;<span><i class="fa fa-trash"></i> Hapus </span></li>
            </ul>
        </div>
        <input type='hidden' value='' id='txt_id'>
        <input type='hidden' value='' id='txt_step'>

        <!-- jQuery -->
        <script src="<?= base_url() ?>assets/jquery/jquery.min.js"></script>
        <script src="<?= base_url() ?>assets/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url() ?>assets/sweetalert2/sweetalert2.min.js"></script>
        <script>
            var base_url = '<?php echo base_url() ?>';
        </script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/underscore/underscore.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/treeMakerV2/src/tree_maker_edit.js"></script>
        <!-- <script type="text/javascript" src="<?php echo base_url() ?>assets/treemaker/tree_maker.js"></script> -->
        <script type="text/javascript" src="<?php echo base_url() ?>assets/scroll-sneak.js"></script>

        <script type="text/javascript">
            var $ = jQuery;
            var root = <?= $root ?>;
            var save_method;
            var detail_clicked = false;
            const id_logger = [];
            $(document).ready(function() {

                generateTreeDiagram();
                // generateTreeDiagram2();
                rightClickNode();
                form_validation();
                getDataLogger();

                setInterval(function() {
                    getDataLogger();
                }, 5 * 60 * 1000);

            });

            function getDataLogger() {
                for (var key in id_logger) {
                    console.log('id_logger[key] ' + id_logger[key]);
                    $('#P_' + id_logger[key].replace(".", "\\.")).text('P: -');
                    $('#Q_' + id_logger[key].replace(".", "\\.")).text('Q: -');
                    $.ajax({
                        url: "<?php echo site_url('admin/spam/') ?>" + "getDataLogger/" + id_logger[key],
                        method: "GET",
                        dataType: 'json',
                        success: function(data) {
                            // console.log('P: ' + data.debit);


                            if (data != null) {
                                console.log('data.new_kode ' + data.new_kode);
                                $('#P_' + data.new_kode.replace(".", "\\.")).text('P: ' + data.tekanan);
                                if (data.tekanan < data.TEKANAN_NORMAL) {
                                    console.log('danger' + '#P_' + data.new_kode);
                                    $('#P_' + data.new_kode.replace(".", "\\.")).removeClass('badge badge-success');
                                    $('#P_' + data.new_kode.replace(".", "\\.")).addClass('badge badge-danger');
                                } else {
                                    console.log('normal')
                                    $('#P_' + data.new_kode.replace(".", "\\.")).removeClass('badge badge-danger');
                                    $('#P_' + data.new_kode.replace(".", "\\.")).addClass('badge badge-success');
                                }

                                $('#Q_' + data.new_kode.replace(".", "\\.")).text('Q: ' + data.debit);
                                if (data.debit < data.DEBIT_NORMAL) {
                                    $('#Q_' + data.new_kode.replace(".", "\\.")).removeClass('badge badge-success');
                                    $('#Q_' + data.new_kode.replace(".", "\\.")).addClass('badge badge-danger');
                                } else {
                                    $('#Q_' + data.new_kode.replace(".", "\\.")).removeClass('badge badge-danger');
                                    $('#Q_' + data.new_kode.replace(".", "\\.")).addClass('badge badge-success');
                                }
                            } else {

                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                            // $('#input_parent').html('<option>Oups! Something gone wrong!</option>');

                        }
                    });
                }
                var currentdate = new Date();
                var datetime = "Logger Last Sync : " +
                    currentdate.getDate() + "/" +
                    (currentdate.getMonth() + 1) + "/" +
                    currentdate.getFullYear() + " @ " +
                    currentdate.getHours() + ":" +
                    currentdate.getMinutes();
                $('.timelabel').text(datetime);
                console.log(datetime);
            }

            function setCenterScreen(id) {
                $('#58').css({
                    "position": "absolute",
                    "top": "50%",
                    "left": "50%",
                    "margin-top": "-50px",
                    "margin-left": "-50px",
                    "width": "100px",
                    "height": "100px"
                });
            }

            function input_name_handler() {
                var r = $('#input_step').val();
                if (r == 5) {
                    $('.form-group-name').hide();
                    $('.form-group-kode').show();
                } else {
                    $('.form-group-name').show();
                    $('.form-group-kode').hide();
                }
            }

            function getNextStep(id) {
                $.ajax({
                    url: "<?php echo site_url('admin/flowkomponen/') ?>" + "fetchNextStep/" + id,
                    method: "GET",
                    dataType: 'html',
                    success: function(html) {
                        $('#input_step').html(html);
                        $('[name=input_step]').val(id);
                        input_name_handler();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                        $('#input_step').html('<option>Oups! Something gone wrong!</option>');
                    }
                });
            }

            function UpdateLogger() {
                Swal.fire({
                    title: 'Update Data Logger',
                    // input: 'text',
                    // inputAttributes: {
                    //     autocapitalize: 'off'
                    // },
                    showCancelButton: true,
                    confirmButtonText: 'Sync',
                    showLoaderOnConfirm: true,
                    preConfirm: (login) => {
                        return fetch(`<?php echo site_url('Checklog/ajax_update') ?>`)
                            .then(response => {
                                if (!response.status) {
                                    throw new Error(response.statusText)
                                }
                                return response.json()
                            })
                            .catch(error => {
                                Swal.showValidationMessage(
                                    `Request failed: ${error}`
                                )
                            })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire(
                            'Good job!',
                            'Data logger berhasil update!',
                            'success'
                        );
                        var currentdate = new Date();
                        var datetime = "Logger Last Sync : " +
                            currentdate.getDate() + "/" +
                            (currentdate.getMonth() + 1) + "/" +
                            currentdate.getFullYear() + " @ " +
                            currentdate.getHours() + ":" +
                            currentdate.getMinutes();
                        $('.timelabel').text(datetime);
                    }
                })

            }

            function rightClickNode() {
                // disable right click and show custom context menu
                $(".node").bind('contextmenu', function(e) {
                    var id = this.id;
                    console.log($(this).attr("data-step"));
                    $("#txt_id").val(id);

                    if ($(this).attr("data-step") == 5) {
                        $("#txt_step").val($(this).attr("data-parent-step"));
                    } else {
                        $("#txt_step").val($(this).attr("data-step"));
                    }

                    var top = e.pageY + 5;
                    var left = e.pageX;

                    // Show contextmenu
                    $(".context-menu").toggle(100).css({
                        top: top + "px",
                        left: left + "px"
                    });

                    // disable default context menu
                    return false;
                });

                // Hide context menu
                $(document).bind('contextmenu click', function() {
                    $(".context-menu").hide();
                    $("#txt_id").val("");
                    $("#txt_step").val("");
                });

                // disable context-menu from custom menu
                $('.context-menu').bind('contextmenu', function() {
                    return false;
                });

                // Clicked context-menu item
                $('.context-menu li').click(function() {
                    var className = $(this).find("span:nth-child(1)").attr("class");
                    var titleid = $('#txt_id').val();
                    var nextStep = $('#txt_step').val();
                    titleid = titleid.split("_");

                    if (className == "Gainsboro") { //add child
                        fetch_existing_node(titleid[1]);
                        console.log(titleid[1]);
                        $('#idnya').val(titleid[1]);
                        // console.log('ss '+titleid[1]);
                        getNextStep(nextStep);
                        add_spam();
                    } else if (className == "Gainsboro2") { //edit
                        detail(titleid[1]);
                    } else {
                        hapus_data(titleid[1]);
                    }
                    $(".context-menu").hide();
                });
            }

            function form_validation() {
                $('#form').on('submit', function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    var input_list = ['input_parent', 'input_step'];
                    var input_list_error = ['input_parent_error_icon', 'input_step_error_icon'];

                    $.ajax({
                        url: "<?php echo site_url('admin/flowkomponen/validation') ?>",
                        method: 'post',
                        data: $(this).serialize(),
                        dataType: 'json',
                        beforeSend: function() {
                            $('#btnSave').attr('disabled', true);
                        },
                        success: function(data) {

                            if (data.error) {
                                for (let index = 0; index < input_list.length; index++) {
                                    const input_ = input_list[index];
                                    const input_error = input_list_error[index];
                                    if (data[input_error] !== "") {
                                        $('[id=' + input_error + ']').html(data[input_error]);
                                        $('[id=' + input_ + ']').addClass('is-invalid');
                                    } else {
                                        $('[id=' + input_error + ']').html('');
                                        $('[id=' + input_ + ']').removeClass('is-invalid');
                                        $('[id=' + input_ + ']').addClass('is-valid');
                                    }
                                }
                            }

                            if (data.success) {
                                for (let index = 0; index < input_list.length; index++) {
                                    const input_ = input_list[index];
                                    const input_error = input_list_error[index]

                                    $('[id=' + input_error + ']').html('');
                                    $('[id=' + input_ + ']').removeClass('is-invalid');
                                    $('[id=' + input_ + ']').addClass('is-valid');
                                }
                                save();
                            }

                            $('#btnSave').attr('disabled', false);

                        }
                    });
                });
            }

            function reset_validation() {
                var input_list = ['input_parent', 'input_step'];
                var input_list_error = ['input_parent_error_icon', 'input_step_error_icon'];

                for (let index = 0; index < input_list.length; index++) {
                    const input_ = input_list[index];
                    const input_error = input_list_error[index]

                    $('[id=' + input_error + ']').html('');
                    $('[id=' + input_ + ']').removeClass('is-invalid');
                    $('[id=' + input_ + ']').removeClass('is-valid');
                }
            }

            function save() {
                var url;
                if (save_method == "add") {
                    url = "<?php echo site_url('admin/flowkomponen/') ?>" + "insert/";
                } else {
                    url = "<?php echo site_url('admin/flowkomponen/') ?>" + "update/";
                }
                var form = $('#form')[0];
                var formData = new FormData(form);
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        var sneaky = new ScrollSneak(location.hostname);
                        document.getElementById('form').onsubmit = sneaky.sneak;
                        if (data.status) {
                            // console.log(data.id);
                            setCenterScreen(data.id);
                            Swal.fire(
                                'Good job!',
                                'Data saved successfully!',
                                'success'
                            );
                            reset_validation();
                            generateTreeDiagram();
                            // fetch_existing_node();
                            rightClickNode();
                            getDataLogger();
                            $('#modal-add-komponen').modal('hide');
                            $('#form')[0].reset();
                        } else {
                            Swal.fire(
                                'Oups!',
                                data.message,
                                'warning'
                            );
                        }
                    }
                });
            }

            function fetch_existing_node(id = "", action = "") {
                $.ajax({
                    url: "<?php echo site_url('admin/flowkomponen/') ?>" + "fetch_existing_node/" + <?= $root ?>,
                    method: "GET",
                    dataType: 'html',
                    success: function(html) {
                        console.log('fetched');
                        console.log('id ' + id);
                        $('#input_parent').html(html);
                        if (id !== "") {
                            $('#input_parent').val(id);
                            console.log(action);
                            if (action !== "edit") {
                                $('.input_parent_container').hide();
                                $('#input_parent').attr('disabled', false);
                            } else {
                                $('#input_parent').attr('disabled', false);
                            }
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                        $('#input_parent').html('<option>Oups! Something gone wrong!</option>');
                    }
                });
            }

            function detail(id) {
                reset_validation();
                $.ajax({
                    url: "<?php echo site_url('admin/flowkomponen/') ?>" + "detail/" + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $('.input_parent_container').show();
                        fetch_existing_node(data.pid, "edit");
                        getNextStep(data.step);
                        // console.log(data.step);
                        $('[name=id]').val(data.id);
                        // $('[name=input_parent]').val(data.pid);

                        $('[name=input_nama_komponen]').val(data.name);
                        $('[name=input_kode]').val(data.kode);
                        $('[name=input_url]').val(data.url);

                        save_method = "edit";
                        $('.modal-title').text('Edit Child');

                        $('#btnSave').html('<b class="fa fa-edit"></b> Edit');
                        $('#btnSave').removeClass('bg-gradient-primary');
                        $('#btnSave').addClass('bg-gradient-warning');

                        $('#modal-add-komponen').modal('show');
                    }
                });
            }

            function hapus_data(id) {
                Swal.fire({
                    title: 'Hapus Data',
                    text: 'Apakah Anda yakin?',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "<?php echo site_url('admin/flowkomponen/') ?>" + "delete/" + id,
                            method: "GET",
                            dataType: 'json',
                            success: function(data) {
                                if (data.status) {
                                    Swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                    ).then((result) => {
                                        if (result.isConfirmed) {
                                            generateTreeDiagram();
                                            rightClickNode();
                                            getDataLogger();
                                        }
                                    });
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(errorThrown);
                                alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
                            }
                        });
                    }
                })
            }

            function generateTreeDiagram() {
                $('#my_tree').html("");
                let tree_structure;
                var result_structure = function() {
                    var tmp = null;
                    $.ajax({
                        async: false,
                        type: 'GET',
                        url: "<?php echo site_url('admin/spam/') ?>" + 'getNodeStructure/' + root,
                        dataType: 'json',
                        global: false,
                        success: function(data) {
                            // console.log(data);
                            tmp = data;
                        }
                    });
                    return tmp;
                }();

                let output;
                output = result_structure.reduce(aggregateTree, {
                    result: {}
                }).result;

                let t = JSON.stringify(output);

                t = t.replace(/{}/g, '""');
                tree_structure = JSON.parse(t);
                console.log(result_structure);
                // exit;
                // document.body.innerHTML = "<pre>" + (JSON.stringify(tree_structure, null, " "))
                //generate node detail 
                var result_node_detail = function() {
                    var tmp = null;
                    $.ajax({
                        async: false,
                        type: 'GET',
                        url: "<?php echo site_url('admin/spam/') ?>" + 'getNodeDetail/' + root,
                        dataType: 'json',
                        global: false,
                        success: function(data) {
                            tmp = data;
                        }
                    });
                    return tmp;
                }();

                for (var key in result_node_detail) {
                    if (result_node_detail[key].kode !== "") {
                        id_logger.push(result_node_detail[key].kode);
                    }
                }

                let treeParams = result_node_detail;

                treeMaker(tree_structure, {
                    id: 'my_tree',
                    card_click: function(element) {

                    },
                    treeParams: treeParams,
                    'link_width': '4px',
                    'link_color': '#2199e8',
                });
            }

            function aggregateTree({
                index = {},
                result = {}
            }, {
                id,
                pid
            }, ) {
                const childItem = (index[id] ?? = {
                    [id]: {}
                });
                const parentItem = (pid !== 0) &&
                    (index[pid] ?? = {
                        [pid]: {}
                    }) ||
                    null;

                if (parentItem !== null) {
                    Object.assign(parentItem[pid], childItem);
                } else {
                    Object.assign(result, childItem);
                }
                return {
                    index,
                    result
                };
            }

            function aggregateTreeV2({
                index = {},
                result = {}
            }, {
                seq,
                pid
            }, ) {
                const childItem = (index[seq] ?? = {
                    [seq]: {}
                });
                const parentItem = (pid !== 0) &&
                    (index[pid] ?? = {
                        [pid]: {}
                    }) ||
                    null;

                if (parentItem !== null) {
                    Object.assign(parentItem[pid], childItem);
                } else {
                    Object.assign(result, childItem);
                }
                return {
                    index,
                    result
                };
            }

            function convertIntObj(obj) {
                const res = {}
                for (const key in obj) {
                    res[key] = {};
                    for (const prop in obj[key]) {
                        const parsed = obj[key][prop];
                        res[key][prop] = isNaN(parsed) ? obj[key][prop] : parsed;
                    }
                }
                return res;
            }

            function getTableDetail(id) {
                console.log('getTableDetail');
                $.ajax({
                    url: "<?php echo site_url('admin/spam/getNodeName/') ?>" + id,
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data)
                        $('#node_id').val(data.id);
                        $('.modal-title').html(data.step_name + " - " + data.name);
                        $.ajax({
                            url: '<?= base_url('index.php/admin/spam/get_last_value/') ?>' + data.step + "/" + data.id,
                            dataType: 'html',
                            success: function(data_lagi) {
                                $('.data_log').html(data_lagi);
                            }
                        });

                        $.ajax({
                            url: '<?= base_url('index.php/admin/spam/get_image/') ?>' + data.id,
                            dataType: 'html',
                            success: function(data_lagi) {
                                // console.log(data_lagi);
                                if (data_lagi == "") {
                                    $('.img-fluid').attr('src', '<?= base_url('assets/no-image.png') ?>');
                                } else {
                                    $('.img-fluid').attr('src', '<?= base_url('assets/gambar/') ?>' + data_lagi);
                                }
                            }
                        });
                        $('#modal-info').modal('show');
                    }
                });

            }

            function add_spam() {
                save_method = 'add';
                $('#form')[0].reset();
                $('#modal-add-komponen').modal('show');
                $('.modal-title').text('Tambah Child SPAM');
                $('#btnSave').html('<b class="fa fa-save"></b> Simpan');
                $('#btnSave').removeClass('bg-gradient-warning');
                $('#btnSave').addClass('bg-gradient-primary');
                $('.form-group-name').hide();
                $('.form-group-kode').hide();
            }

            function next(id) {
                alert(id);
            }

            function data_to_merge(id) {
                $.ajax({
                    async: false,
                    type: 'GET',
                    url: "<?php echo site_url('admin/spam/') ?>" + 'getOtherNode/' + id + '/' + $('#idnya').val(),
                    dataType: 'html',
                    global: false,
                    success: function(html) {
                        $('#mergecontainer').html(html);
                    }
                });
            }

            function childDuplicate(id) {
                var parent = $('#idnya').val();
                $.ajax({
                    async: false,
                    type: 'GET',
                    url: "<?php echo site_url('admin/spam/') ?>" + 'childDuplicate/' + id + "/" + parent,
                    dataType: 'json',
                    global: false,
                    success: function(data) {
                        // $('#mergecontainer').html(html);
                        if (data.status) {
                            // console.log(data.id);
                            Swal.fire(
                                'Good job!',
                                'Data saved successfully!',
                                'success'
                            );
                            reset_validation();
                            generateTreeDiagram();
                            rightClickNode();
                            getDataLogger();
                            $('#modal-add-komponen').modal('hide');
                            $('#form')[0].reset();
                        } else {
                            Swal.fire(
                                'Oups!',
                                data.message,
                                'warning'
                            );
                        }
                    }
                });
            }
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function(event) {
                var scrollpos = localStorage.getItem('scrollpos');
                if (scrollpos) window.scrollTo(0, scrollpos);
            });

            window.onbeforeunload = function(e) {
                localStorage.setItem('scrollpos', window.scrollY);
            };
        </script>

        <div class="modal" tabindex="-1" id="modal-info">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <input type="hidden" id="node_id">
                    <div class="modal-body">
                        <div class="row col-12">
                            <div class="col-6 data_log">

                            </div>
                            <div class="col-6 img_log">
                                <label for="">Gambar Terbaru</label>
                                <img src="" class="img-fluid" alt="...">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


</body>

</html>