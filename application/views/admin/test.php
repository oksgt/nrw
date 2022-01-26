<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        // var $ = new jQuery();
    </script>
    <meta charset="UTF-8">
    <title>Process Flow</title>
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/treemaker/tree_maker.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

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

        /* #my_tree {
            transform: rotate(180deg);
        }

        .tree__container__step__card__p {
            transform: rotate(180deg);
            -webkit-transform: rotate(180deg);
            -moz-transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            -o-transform: rotate(180deg);
        } */
    </style>
</head>

<body>

    <div id="my_tree"></div>
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
            var root = 1; 

            $(document).ready(function() {
                generateTreeDiagram();
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
                    url: "<?php echo site_url('admin/spam/getNodeName') ?>" + id,
                    dataType: 'json',
                    success: function(data) {
                        $('.modal-title').html(data.name);
                    }
                });

                // $.ajax({
                //     url: '<?= base_url('index.php/flow/table/') ?>' + id,
                //     dataType: 'html',
                //     success: function(html) {
                //         $('.modal-body').html(html)
                //     }
                // });

                $('#modal-info').modal('show');
            }
    </script>
</body>

<div class="modal" tabindex="-1" id="modal-info">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</html>