<!DOCTYPE html>
<html style="height: auto;" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Node Komponen</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/fontawesome-free/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/toastr/toastr.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>dist/css/adminlte.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/datatables-buttons/css/buttons.bootstrap4.min.css">
</head>

<body class="" style="height: auto;">
    <div class="wrapper">
        <div class="content-wrapper" style="min-height: 344px;">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Nilai Parameter "<?= strtoupper($komponen['step_name'] . " - " . $komponen['name']) ?>"
                            </h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-default">
                                <div class="card-header">
                                    <button type="button" class="btn btn-success" id="btn-add-komponen" onclick="add_spam()">
                                        <i class="fa fa-plus"></i>&nbsp; Tambah Nilai
                                    </button>
                                    <button class="btn btn-default" onclick="reload_table()"><i class="fas fa-sync"></i>&nbsp; Reload</button>
                                    <button class="btn btn-default" onclick="add_image()"><i class="far fa-image"></i>&nbsp; Foto Lokasi</button>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-12 table-responsive">
                                        <table id="table" class="table table-striped table-bordered table-sm small" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="3%">No</th>
                                                    <th width="10%">Debit Produksi</th>
                                                    <th width="10%">Debit Distribusi</th>
                                                    <th width="10%">Air Terjual</th>
                                                    <th width="10%">Kehilangan Air</th>
                                                    <th width="5%">Jumlah Pelanggan</th>
                                                    <th width="10%">Kapasitas Pompa</th>
                                                    <th width="10%">Tanggal Input</th>
                                                    <th width="10%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                <form id="form" method="post" onsubmit="return false;">
                                    <input type="hidden" name="id">
                                    <input type="hidden" name="input_spam_node" value="<?= $node_id ?>">
                                    <div class="form-group">
                                        <label for="input_debit_produksi">Debit Produksi (l/dt)</label>
                                        <input type="text" class="form-control float" id="input_debit_produksi" name="input_debit_produksi">
                                        <small id="input_debit_produksi_error_detail" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="input_debit_distribusi">Debit Distribusi (l/dt)</label>
                                        <input type="text" class="form-control float" id="input_debit_distribusi" name="input_debit_distribusi">
                                        <small id="input_debit_distribusi_error_detail" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="input_air_terjual">Air Terjual (l/dt)</label>
                                        <input type="text" class="form-control float" id="input_air_terjual" name="input_air_terjual">
                                        <small id="input_air_terjual_error_detail" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="input_kehilangan_air">Kehilangan Air (l/dt)</label>
                                        <input type="text" class="form-control float" id="input_kehilangan_air" name="input_kehilangan_air">
                                        <small id="input_kehilangan_air_error_detail" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="input_jml_pelanggan">Jumlah Pelanggan (SR)</label>
                                        <input type="text" class="form-control float" id="input_jml_pelanggan" name="input_jml_pelanggan">
                                        <small id="input_jml_pelanggan_error_detail" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="input_kapasitas_pompa">Kapasitas Pompa (l/dt)</label>
                                        <input type="text" class="form-control float" id="input_kapasitas_pompa" name="input_kapasitas_pompa">
                                        <small id="input_kapasitas_pompa_error_detail" class="form-text text-danger"></small>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" id="btnSave"><i class="fa fa-save"></i> Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal" tabindex="-1" id="modal-image">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="post" id="form-image" onSubmit="return false;">
                                    <input type="hidden" name="id">
                                    <input type="hidden" name="input_spam_node" value="<?= $node_id ?>">
                                    <input type="hidden" id="old_img" name="old_img">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Upload Image</label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <span class="btn btn-default btn-file">
                                                        Browse… <input type="file" id="imgInp" name="imgInp">
                                                    </span>
                                                </span>
                                                <input type="text" class="form-control" readonly>
                                            </div>
                                            <br>
                                            <div class="input-group">
                                                <img id='img-upload' style="height: 300px"/>
                                            </div>

                                        </div>
                                    </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" id="btnUpload" onclick="do_upload()"><i class="fa fa-save"></i> Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </div>

        <!-- jQuery -->
        <script src="<?= base_url() ?>assets/jquery/jquery.min.js"></script>
        <script src="<?= base_url() ?>assets/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url() ?>assets/sweetalert2/sweetalert2.min.js"></script>
        <script src="<?= base_url() ?>assets/toastr/toastr.min.js"></script>
        <script src="<?= base_url() ?>dist/js/adminlte.min.js"></script>

        <!-- DataTables  & Plugins -->
        <script src="<?= base_url(); ?>/assets/datatables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url(); ?>/assets/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>/assets/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="<?= base_url(); ?>/assets/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>/assets/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="<?= base_url(); ?>/assets/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>/assets/jszip/jszip.min.js"></script>
        <script src="<?= base_url(); ?>/assets/pdfmake/pdfmake.min.js"></script>
        <script src="<?= base_url(); ?>/assets/pdfmake/vfs_fonts.js"></script>
        <script src="<?= base_url(); ?>/assets/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="<?= base_url(); ?>/assets/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="<?= base_url(); ?>/assets/datatables-buttons/js/buttons.colVis.min.js"></script>

        <script type="text/javascript">
            var save_method; //for save method string
            var table;
            $(document).ready(function() {
                form_validation();
                upload_handler();
                table = $('#table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "order": [],
                    "dom": "<'row'>" +
                                "<'row'<'col-sm-12 col-md-6 'B><'col-md-6 text-right 'f>>" +
                                "<'row'<'col-sm-12'tr>>" +
                                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"+
                                "<'row'<'col-sm-12 '>>",
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    "ajax": {
                        "url": "<?php echo site_url('admin/IpaDetail/ajax_list/') ?>" + <?= $node_id ?>,
                        "type": "POST"
                    },
                    "columnDefs": [{
                        "targets": [-1],
                        "orderable": false,
                    }]
                }).buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');

                setInputFilter(document.getElementById("input_debit_produksi"), function(value) {
                    return /^-?\d*[.,]?\d*$/.test(value);
                });
                setInputFilter(document.getElementById("input_debit_distribusi"), function(value) {
                    return /^-?\d*[.,]?\d*$/.test(value);
                });
                setInputFilter(document.getElementById("input_air_terjual"), function(value) {
                    return /^-?\d*[.,]?\d*$/.test(value);
                });
                setInputFilter(document.getElementById("input_kehilangan_air"), function(value) {
                    return /^-?\d*[.,]?\d*$/.test(value);
                });
                setInputFilter(document.getElementById("input_jml_pelanggan"), function(value) {
                    return /^-?\d*[.,]?\d*$/.test(value);
                });
                setInputFilter(document.getElementById("input_kapasitas_pompa"), function(value) {
                    return /^-?\d*[.,]?\d*$/.test(value);
                });

            });

            function reload_table() {
                table.ajax.reload(null, false);
            }

            function add_spam() {
                save_method = 'add';
                $('#form')[0].reset();
                $('#modal-add-komponen').modal('show');
                $('.modal-title').text('Tambah Nilai Parameter');
                $('#btnSave').html('<b class="fa fa-save"></b> Simpan');
                $('#btnSave').removeClass('bg-gradient-warning');
                $('#btnSave').addClass('bg-gradient-primary');
            }

            function form_validation() {
                $('#form').on('submit', function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    var input_list = [
                        'input_debit_produksi',
                        'input_debit_distribusi',
                        'input_air_terjual',
                        'input_kehilangan_air',
                        'input_jml_pelanggan',
                        'input_kapasitas_pompa'
                    ];
                    var input_list_error = [
                        'input_debit_produksi_error_detail',
                        'input_debit_distribusi_error_detail',
                        'input_air_terjual_error_detail',
                        'input_kehilangan_air_error_detail',
                        'input_jml_pelanggan_error_detail',
                        'input_kapasitas_pompa_error_detail'
                    ];

                    $.ajax({
                        url: "<?php echo site_url('admin/IpaDetail/validation') ?>",
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
                var input_list = [
                    'input_debit_produksi',
                    'input_debit_distribusi',
                    'input_air_terjual',
                    'input_kehilangan_air',
                    'input_jml_pelanggan',
                    'input_kapasitas_pompa'
                ];
                var input_list_error = [
                    'input_debit_produksi_error_detail',
                    'input_debit_distribusi_error_detail',
                    'input_air_terjual_error_detail',
                    'input_kehilangan_air_error_detail',
                    'input_jml_pelanggan_error_detail',
                    'input_kapasitas_pompa_error_detail'
                ];

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
                    url = "<?php echo site_url('admin/IpaDetail/') ?>" + "insert/";
                } else {
                    url = "<?php echo site_url('admin/IpaDetail/') ?>" + "update/";
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
                        if (data.status) {
                            Swal.fire(
                                'Good job!',
                                'Data saved successfully!',
                                'success'
                            );
                            reset_validation();
                            reload_table();
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

            function detail(id) {
                reset_validation();
                $.ajax({
                    url: "<?php echo site_url('admin/IpaDetail/') ?>" + "detail/" + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {

                        var format = function(num) {
                            var str = num.toString().replace("", ""),
                                parts = false,
                                output = [],
                                i = 1,
                                formatted = null;
                            if (str.indexOf(".") > 0) {
                                parts = str.split(".");
                                str = parts[0];
                            }
                            str = str.split("").reverse();
                            for (var j = 0, len = str.length; j < len; j++) {
                                if (str[j] != ",") {
                                    output.push(str[j]);
                                    if (i % 3 == 0 && j < (len - 1)) {
                                        output.push(",");
                                    }
                                    i++;
                                }
                            }
                            formatted = output.reverse().join("");
                            return (formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
                        };

                        $('[name=id]').val(data.id);
                        $('[name=input_spam_node]').val(data.id_spam_node);

                        $('[name=input_debit_produksi]').val(data.debit_produksi);
                        $('[name=input_debit_distribusi]').val(data.debit_distribusi);
                        $('[name=input_air_terjual]').val(data.air_terjual);
                        $('[name=input_kehilangan_air]').val(data.kehilangan_air);
                        $('[name=input_jml_pelanggan]').val(data.jml_pelanggan);
                        $('[name=input_kapasitas_pompa]').val(data.kapasitas_pompa);

                        save_method = "edit";
                        $('.modal-title').text('Edit Nilai Parameter');

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
                            url: "<?php echo site_url('admin/IpaDetail/') ?>" + "delete/" + id,
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
                                            reload_table();
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

            function isNumberKey(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
            }

            function setInputFilter(textbox, inputFilter) {
                ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
                    textbox.addEventListener(event, function() {
                        if (inputFilter(this.value)) {
                            this.oldValue = this.value;
                            this.oldSelectionStart = this.selectionStart;
                            this.oldSelectionEnd = this.selectionEnd;
                        } else if (this.hasOwnProperty("oldValue")) {
                            this.value = this.oldValue;
                            this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                        } else {
                            this.value = "";
                        }
                    });
                });
            }

            function add_image() {
                $('#form-image')[0].reset();

                $.ajax({
                    url: '<?= base_url('index.php/admin/spam/get_image/') ?>' + <?= $node_id ?>,
                    dataType: 'html',
                    success: function(data) {
                        console.log(data)
                        $('#old_img').val(data);
                        $('#img-upload').attr('src', '<?= base_url('assets/gambar/') ?>'+data);
                    }
                });

                $('#modal-image').modal('show');
                $('.modal-title').text('Update Foto Lokasi');
                $('#btnUpload').html('<b class="fa fa-save"></b> Upload');
                $('#btnUpload').removeClass('bg-gradient-warning');
                $('#btnUpload').addClass('bg-gradient-primary');
            }

            function upload_handler() {
                $(document).on('change', '.btn-file :file', function() {
                    var input = $(this),
                        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                    input.trigger('fileselect', [label]);
                });

                $('.btn-file :file').on('fileselect', function(event, label) {

                    var input = $(this).parents('.input-group').find(':text'),
                        log = label;

                    if (input.length) {
                        input.val(log);
                    } else {
                        if (log) alert(log);
                    }

                });

                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $('#img-upload').attr('src', e.target.result);
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $("#imgInp").change(function() {
                    readURL(this);
                });
            }

            function do_upload() {
                var form = $('#form-image')[0];
                var formData = new FormData(form);
                $.ajax({
                    url: "<?php echo site_url('admin/IntakeDetail/') ?>" + "upload/",
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data.status) {
                            Swal.fire(
                                'Good job!',
                                'Data saved successfully!',
                                'success'
                            );
                            $('#modal-image').modal('hide');
                            // reload_table();
                            // $('#modal_product').modal('hide');
                            $('#form-image')[0].reset();
                        } else {
                            Swal.fire(
                                'Oups!',
                                data.message,
                                'warning'
                            );
                        }
                        $('#form-image')[0].reset();
                    }
                });
            }
        </script>

</body>

</html>