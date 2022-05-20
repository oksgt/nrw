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
    <!-- <link rel="stylesheet" href="<?= base_url(); ?>/assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css">

</head>

<body class="">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="input_logger">Logger</label>
                            <select class="custom-select ml-2" id="selectlog" onchange="load_chart();">
                                <option value="x" selected>--Silahkan Pilih--</option>
                                <?php
                                foreach ($list_logger as $key => $value) {
                                    echo '<option value="' . $value->KODE . '">' . $value->KODE . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group ml-4">
                            <label for="input_logger">Periode</label>
                            <input class="form-control ml-2" id="date" name="date" placeholder="MM/DD/YYY" type="text" onchange="load_chart();" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mb-2 ">

            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid" id="idGraph">

        </div>
    </section>
</body>
<!-- jQuery -->
<script src="<?= base_url() ?>assets/jquery/jquery.min.js"></script>

<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js">
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var date_input = $('input[name="date"]'); //our date input has the name "date"
        // var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
        var options = {
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true,
        };
        date_input.datepicker(options);

        setInterval(function() {
            load_chart();
        }, 5 * 60 * 1000);
    });

    function load_chart() {
        var id = $('#selectlog').find(":selected").text();
        var date = $('input[name="date"]').val();

        var periode = [];
        var debit = [];
        var tekanan = [];
        $('#idGraph').html('');
        $.ajax({
            url: "<?php echo site_url('admin/chart/') ?>" + "get_chart/" + id + "/" + date,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#idGraph').html('<canvas id="myChart"></canvas>');
                var ctx = document.getElementById('myChart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: "line",
                    data: {},
                    options: {}
                });
                // console.log(data.result);
                for (var i in data.result) {
                    periode.push(data.result[i].updatedindb);
                    debit.push(data.result[i].debit);
                    tekanan.push(data.result[i].tekanan);
                }

                chart = new Chart(ctx, {
                    type: "line",
                    data: {},
                    options: {}
                });

                chart.data = {
                    labels: periode,
                    datasets: [{
                            label: "Debit",
                            backgroundColor: '#00FFFF',
                            borderColor: '#008B8B',
                            fill: false,
                            data: debit
                        },
                        {
                            label: "Tekanan",
                            backgroundColor: '#B22222',
                            borderColor: '#8B0000',
                            fill: false,
                            data: tekanan
                        },
                    ]
                };
                chart.options = {
                    plugins: {
                        title: {
                            display: true,
                            text: data.lokasi
                        }
                    }
                }

                chart.update();
            }
        });
    }
</script>
<script>

</script>

<script type="text/javascript">

</script>

</html>