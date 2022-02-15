<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NRW</title>
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
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">

</head>

<body class="">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <?php if(!empty($data_spam)){ ?>
                    <?php foreach ($data_spam as $spam) { ?> 
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner" >
                                    <h3 style="font-size: 30px"><?= $spam->name ?></h3>
                                    <p><?= getJumlahPelangganSpam($spam->id) ?> SR</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-project-diagram"></i>
                                </div>
                                <a href="<?= site_url('flow/'.$spam->id) ?>" class="small-box-footer nav-link">Lihat Diagram <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                

            </div>

        </div>
    </section>
</body>
<!-- jQuery -->
<script src="<?= base_url() ?>assets/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap.bundle.min.js"></script>

</html>