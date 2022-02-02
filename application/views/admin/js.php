<!-- jQuery -->
<script src="<?= base_url(); ?>/assets/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= base_url(); ?>/assets/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?= base_url(); ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url(); ?>/assets/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url(); ?>dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="<?= base_url(); ?>dist/js/demo.js"></script> -->

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

<script src="<?= base_url(); ?>/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<script>
  function loaddaftarspam() {
        $.ajax({
          url: "<?php echo site_url('admin/spam/') ?>" + "load_active_spam/",
          method: 'GET',
          dataType: 'html',
          success: function(html) {
            $('#list-menu-spam').html(html);
          }
        });
      }
</script>