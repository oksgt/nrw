<!DOCTYPE html>
<html style="height: auto;" lang="en">

<head>
    <meta charset="utf-8">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/treemaker/tree_maker.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url() ?>assets/fontawesome-free/css/all.min.css">
    <style type="text/css">
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

        <?php } ?>
        
        .card-body {
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
    </style>


</head>

<body >
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container">
                    <div class="row mt-3">
                        <div class="col-sm-8 col-12">
                            <h3 class="text-light"><?= $spam_name ?></h3>
                        </div>
                        <div class="col-sm-4 col-12 text-right">
                            <a type="button" class="btn btn-success pull-right" href="<?= base_url('index.php/flowkomponen/' . $root) ?>"> <i class="fa fa-edit"></i> Kelola Komponen</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid py-2">
                    <div class="d-flex flex-row flex-nowrap justify-content-center">
                        <div id="my_tree"></div>
                    </div>
                </div>
            </section>
        </div>

        <!-- jQuery -->
        <script src="<?= base_url() ?>assets/jquery/jquery.min.js"></script>
        <script src="<?= base_url() ?>assets/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script>
            var base_url = '<?php echo base_url() ?>';
        </script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/underscore/underscore.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/treemaker/tree_maker.js"></script>
        <script type="text/javascript">
            var $ = jQuery;
            var root = <?= $root ?>;

            $(document).ready(function() {
                generateTreeDiagram();

                //simulasi sync data log air
                var seconds = 0;
                var el = document.getElementById('seconds-counter');
                function incrementSeconds() {
                    seconds += 1;
                    $('.badge').text(seconds)
                }
                var cancel = setInterval(incrementSeconds, 1000);
            });

            function generateTreeDiagram() {

                //generate tree node structure
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
                            tmp = data;
                        }
                    });
                    return tmp;
                }();

                console.log(result_structure);

                var input = convertIntObj(result_structure);
                input = Object.values(input);

                let register = {};
                let output = {};

                for (let el of input) {
                    register[el.id] = el;
                    if (!el.pid) {
                        output[el.id] = el;
                    } else {
                        register[el.pid][el.id] = el;
                    }
                    delete el.pid;
                    delete el.id
                }
                let t = JSON.stringify(output);
                t = t.replace(/{}/g, '""');
                tree_structure = JSON.parse(t);

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

                console.log(result_node_detail);

                let node_style = {
                    'box-shadow': '0 0 5px 1px blue'
                };

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

            function convertIntObj(obj) {
                const res = {}
                for (const key in obj) {
                    res[key] = {};
                    for (const prop in obj[key]) {
                        const parsed = parseInt(obj[key][prop], 10);
                        res[key][prop] = isNaN(parsed) ? obj[key][prop] : parsed;
                    }
                }
                return res;
            }

            function getTableDetail(id) {
                $.ajax({
                    url: "<?php echo site_url('admin/spam/getNodeName/') ?>" + id,
                    dataType: 'json',
                    success: function(data) {
                        console.log(data)
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
                                console.log(data_lagi);
                                if(data_lagi == ""){
                                    $('.img-fluid').attr('src', '<?= base_url('assets/no-image.png') ?>');
                                } else {
                                    $('.img-fluid').attr('src', '<?= base_url('assets/gambar/') ?>'+data_lagi);
                                }
                            }
                        });
                    }
                });
                $('#modal-info').modal('show');
            }
            
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