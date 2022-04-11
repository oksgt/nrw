<!DOCTYPE html>
<html style="height: auto;" lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Modals &amp; Alerts</title>

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
              <h1>Data Master SPAM
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
                  <button type="button" class="btn btn-success" id="btn-add-spam" onclick="add_spam()">
                    <i class="fa fa-plus"></i>&nbsp; Tambah SPAM
                  </button>
                  <button class="btn btn-default" onclick="reload_table()"><i class="fas fa-refresh"></i>&nbsp; Reload</button>
                </div>
                <div class="card-body">
                  <div class="col-md-12 table-responsive">
                    <table id="table" class="table table-striped table-bordered table-sm small" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th width="10%">No</th>
                          <th width="50%">SPAM</th>
                          <th style="width:125px;" width="40%">Action</th>
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


        <div class="modal" tabindex="-1" id="modal-add-spam">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="form-spam" method="post" onsubmit="return false;">
                  <input type="hidden" name="id">
                  <div class="form-group">
                    <label for="input_spam">Nama SPAM</label>
                    <input type="text" class="form-control" id="input_spam" name="input_spam">
                    <small id="input_spam_error_detail" class="form-text text-danger"></small>
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

      $(document).ready(function() {
        form_validation();

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
            "url": "<?php echo site_url('admin/spam/ajax_list') ?>",
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
        $('#form-spam')[0].reset();
        $('#modal-add-spam').modal('show');
        $('.modal-title').text('Tambah SPAM');
      }

      function form_validation() {
        $('#form-spam').on('submit', function(event) {
          event.preventDefault();
          event.stopPropagation();

          var input_list = ['input_spam'];
          var input_list_error = ['input_spam_error_detail'];

          $.ajax({
            url: "<?php echo site_url('admin/spam/validation') ?>",
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
          'input_spam',
        ];

        var input_list_error = [
          'input_spam_error_detail',
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
          url = "<?php echo site_url('admin/spam/') ?>" + "insert/";
        } else {
          url = "<?php echo site_url('admin/spam/') ?>" + "update/";
        }
        var form = $('#form-spam')[0];
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
              $('#modal-add-spam').modal('hide');
              $('#form-spam')[0].reset();
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
          url: "<?php echo site_url('admin/spam/') ?>" + "detail/" + id,
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
            $('[name=input_spam]').val(data.name);
            $("input[name=input_flow][value=" + data.diagram_flow_direction + "]").prop('checked', true);

            save_method = "edit";
            $('.modal-title').text('Edit SPAM');

            $('#btnSave').html('<b class="fa fa-edit"></b> Edit');
            $('#btnSave').removeClass('bg-gradient-primary');
            $('#btnSave').addClass('bg-gradient-warning');

            $('#modal-add-spam').modal('show');
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
              url: "<?php echo site_url('admin/spam/') ?>" + "delete/" + id,
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
    </script>



</body>

</html>