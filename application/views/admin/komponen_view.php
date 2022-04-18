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
              <h1>Daftar Komponen "<?= strtoupper($spam->name) ?>"
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
                    <i class="fa fa-plus"></i>&nbsp; Tambah Komponen
                  </button>
                  <button class="btn btn-default" onclick="reload_table()"><i class="fas fa-sync"></i>&nbsp; Reload</button>
                  <a class="btn btn-success" href="<?= base_url('index.php/flow/' . $root) ?>"><i class="fas fa-project-diagram"></i>&nbsp; Diagram</a>
                </div>
                <div class="card-body">
                  <div class="col-md-12 table-responsive">
                    <table id="table" class="table table-striped table-bordered table-sm small" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th width="5%">No</th>
                          <th width="20%">Jenis Step</th>
                          <th width="10%">Kode</th>
                          <th width="20%">Nama</th>
                          <th width="5%">In</th>
                          <th width="5%">Out</th>
                          <th width="10%">URL</th>
                          <th width="20%">Action</th>
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
                  <input type="hidden" name="root" value="<?= $root ?>">

                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label for="input_step">Step</label>
                        <select class="form-control" id="input_step" name="input_step">
                          <option value="x">-- Silahkan Pilih--</option>'
                        </select>
                        <small id="input_step_error_icon" class="form-text text-danger"></small>
                      </div>
                      <div class="form-group ">
                        <label for="input_nama_komponen">Nama</label>
                        <input type="text" class="form-control" id="input_nama_komponen" name="input_nama_komponen">
                        <small id="input_nama_komponen_error_detail" class="form-text text-danger"></small>
                      </div>
                      <div class="form-group ">
                        <label for="input_kode">Kode</label>
                        <input type="text" class="form-control" id="input_kode" name="input_kode">
                        <small id="input_kode_error_detail" class="form-text text-danger"></small>
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="form-group ">
                        <label for="input_in">In</label>
                        <input type="text" class="form-control" id="input_in" name="input_in">
                        <small id="input_in_error_detail" class="form-text text-danger"></small>
                      </div>
                      <div class="form-group ">
                        <label for="input_out">Out</label>
                        <input type="text" class="form-control" id="input_out" name="input_out">
                        <small id="input_out_error_detail" class="form-text text-danger"></small>
                      </div>
                      <div class="form-group">
                        <label for="input_url">URL</label>
                        <input type="text" class="form-control" id="input_url" name="input_url">
                        <small id="input_url_error_detail" class="form-text text-danger"></small>
                      </div>
                    </div>
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
      var root = <?= $root ?>;
      $(document).ready(function() {
        form_validation();

        setInputFilter(document.getElementById("input_in"), function(value) {
          return /^-?\d*$/.test(value);
        }, "Harus Angka");

        setInputFilter(document.getElementById("input_out"), function(value) {
          return /^-?\d*$/.test(value);
        }, "Harus Angka");

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
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
            "<'row'<'col-sm-12 '>>",
          "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
          "ajax": {
            "url": "<?php echo site_url('admin/flowkomponen/ajax_list/') ?>" + root,
            "type": "POST"
          },
          "columnDefs": [{
            "targets": [-1],
            "orderable": false,
          }]
        }).buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');

      });

      function reload_table() {
        $('#table').DataTable().ajax.reload();
      }

      function add_spam() {
        save_method = 'add';
        $('#form')[0].reset();
        $('#modal-add-komponen').modal('show');
        $('.modal-title').text('Tambah Komponen SPAM');
        $('#btnSave').html('<b class="fa fa-save"></b> Simpan');
        $('#btnSave').removeClass('bg-gradient-warning');
        $('#btnSave').addClass('bg-gradient-primary');
        $('.form-group-name').hide();
        $('.form-group-kode').hide();
        getNextStep();
      }

      function fetch_existing_node() {
        $.ajax({
          url: "<?php echo site_url('admin/flowkomponen/') ?>" + "fetch_existing_node/" + <?= $root ?>,
          method: "GET",
          dataType: 'html',
          success: function(html) {
            $('#input_parent').html(html);
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
            $('#input_parent').html('<option>Oups! Something gone wrong!</option>');
          }
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

      function detail(id, step, pid) {
        reset_validation();
        getNextStep();
        $.when(
          $.ajax({
            url: "<?php echo site_url('admin/flowkomponen/') ?>" + "detail/" + id,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
              console.log(data);

              $('[name=id]').val(data.id);
              $('[name=input_step]').val(data.step);
              $('[name=input_nama_komponen]').val(data.name);
              $('[name=input_kode]').val(data.kode);
              $('[name=input_url]').val(data.url);
              $('[name=input_in]').val(data.input_port);
              $('[name=input_out]').val(data.output_port);

              save_method = "edit";
              $('.modal-title').text('Edit Komponen');

              $('#btnSave').html('<b class="fa fa-edit"></b> Edit');
              $('#btnSave').removeClass('bg-gradient-primary');
              $('#btnSave').addClass('bg-gradient-warning');
              input_name_handler();
              $('#modal-add-komponen').modal('show');
            }
          })
        );
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

      function getNextStep(id = "") {
        $.ajax({
          url: "<?php echo site_url('admin/flowkomponen/') ?>" + "fetchStep/",
          method: "GET",
          dataType: 'html',
          success: function(html) {
            $('#input_step').html(html);
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
            $('#input_step').html('<option>Oups! Something gone wrong!</option>');
          }
        });
      }

      // Restricts input for the given textbox to the given inputFilter function.
      function setInputFilter(textbox, inputFilter, errMsg) {
        ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop", "focusout"].forEach(function(event) {
          textbox.addEventListener(event, function(e) {
            if (inputFilter(this.value)) {
              // Accepted value
              if (["keydown", "mousedown", "focusout"].indexOf(e.type) >= 0) {
                this.classList.remove("input-error");
                this.setCustomValidity("");
              }
              this.oldValue = this.value;
              this.oldSelectionStart = this.selectionStart;
              this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
              // Rejected value - restore the previous one
              this.classList.add("input-error");
              this.setCustomValidity(errMsg);
              this.reportValidity();
              this.value = this.oldValue;
              this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
              // Rejected value - nothing to restore
              this.value = "";
            }
          });
        });
      }
    </script>



</body>

</html>