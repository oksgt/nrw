<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('node_modules/drawflow/dist/bootstrap.min.css?no-cache=') ?>" crossorigin="anonymous">

    <!-- drawflow -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('node_modules/drawflow/dist/drawflow.css') ?>?no-cache=">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/drawflow/beautiful.css') ?>?no-cache=" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/fontawesome-free/css/all.min.css') ?>?no-cache=" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap?no-cache=" rel="stylesheet">

    <title>Diagram</title>

    <style>
        .sidebar_komponen {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #f7f7f7;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidebar_komponen a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidebar_komponen a:hover {
            color: #f1f1f1;
        }

        .sidebar_komponen .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        .openbtn {
            font-size: 20px;
            cursor: pointer;
            background-color: #111;
            color: white;
            padding: 10px 15px;
            border: none;
        }

        .openbtn:hover {
            background-color: #444;
        }

        #main {
            transition: margin-left .5s;
            padding: 16px;
        }

        /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */

        @media screen and (max-height: 450px) {
            .sidebar_komponen {
                padding-top: 15px;
            }

            .sidebar_komponen a {
                font-size: 18px;
            }
        }

        .input {
            background-color: orangered !important;
        }

        .output {
            background-color: green !important;
        }

        .drawflow-node {
            background: none !important;
            border: 2px solid #f7f7f7 !important;
            color: #f7f7f7 !important;
        }



        .drawflow_content_node .card {
            background: none !important;
        }

        .main-path {
            stroke: white !important;
            stroke-width: 5px !important;
            stroke-dasharray: 15px !important;
            animation: dash 2.5s linear infinite;
        }

        .main-path-bg {
            fill: none !important;
            stroke: #2199e8 !important;
            stroke-width: 10px !important;
        }
    </style>
</head>

<body>
    <div id="mySidebar" class="sidebar_komponen">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <a href="#">Daftar Komponen</a>
        <?php
        foreach ($node as $r) { ?>
            <div class="drag-drawflow pl-2 pr-2" draggable="true" ondragstart="drag(event)" data-node=<?= $r['id'] ?> style="margin-top: 10px; border: none !important">
                <div class="card">
                    <div class="row no-gutters">
                        <div class="col-auto">
                            <img src="<?= base_url('assets/gambar/') . $r['img'] ?>" class="img-fluid" width="70" height="100" onerror="this.onerror=null;this.src='<?= base_url('assets/gambar/no-image.png') ?>';">
                        </div>
                        <div class="">
                            <div class="card-block pl-2" style="padding: 10px 0; text-align: left">
                                <h4 style="font-size: 12px; color: #818181;" class="card-title "><?= $r['step_name'] . " " . $r['kode'] ?></h4>
                                <h4 style="font-size: 11px; color: #818181;" class="card-title "><?= $r['name'] ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="h-100 ml-0 w-100" id="main">
        <div class="col-12 pl-0" style="max-height: 50px !important; width:100%">
            <h1 class="display-4" style="font-size: 30px;"><?= $spam_name ?></h1>
        </div>
        <div class="row h-100 ">
            <div class="col-12">
                <div class="menu">
                    <ul>
                        <?php
                        if ($this->session->userdata('status') == 'loggedin') { ?>
                            <li onclick="openNav()"><i class="fa fa-bars"></i></li>
                            <li onclick="editor.clearModuleSelected()" title="Clear Editor">
                                <i class="fas fa-eraser"></i>
                            </li>
                            <li onclick="saveTemplate()" title="Save Diagram">
                                <i class="far fa-save"></i>
                            </li>
                        <?php } ?>
                        <li><i class="fas fa-search-minus" onclick="editor.zoom_out()"></i></li>
                        <li><i class="fas fa-search" onclick="editor.zoom_reset()"></i></li>
                        <li><i class="fas fa-search-plus" onclick="editor.zoom_in()"></i></li>
                        <li><i class="fas fa-tachometer-alt"></i>
                            <b class="timelabel"></b>
                        </li>
                        <!-- <li id="lock"><i class="fas fa-lock" onclick="editor.editor_mode='fixed'; changeMode('lock');"></i></li>
                        <li id="unlock"><i class="fas fa-lock-open" onclick="editor.editor_mode='edit'; changeMode('unlock');"></i></li> -->
                    </ul>
                </div>
                <div class="" id="drawflow" ondrop="drop(event)" ondragover="allowDrop(event)" style="width: 100%; height: 100%">

                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('node_modules/drawflow/dist/jquery.js') ?>" crossorigin="anonymous"></script>
    <script src="<?= base_url('node_modules/drawflow/dist/bootstrap.bundle.min.js') ?>" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

    <!-- drawflow -->
    <script src="<?= base_url('node_modules/drawflow/dist/drawflow.js') ?>"></script>
    <script src="<?= base_url('node_modules/drawflow/dist/font-awesome_5.13.0_all.min.js') ?>" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
    <script src="<?= base_url('node_modules/drawflow/dist/micromodal.min.js') ?>"></script>

    <script>
        const id_logger = [];

        <?php
        foreach ($node as $r) {
            if ($r['kode'] !== "") { ?>
                id_logger.push('<?= $r['kode'] ?>');
        <?php }
        } ?>

        $(document).ready(function() {

            setInterval(function() {
                getDataLogger();
            }, 5 * 60 * 1000);

        });


        var id = document.getElementById("drawflow");
        const editor = new Drawflow(id);
        editor.reroute = true;

        <?php
        if ($this->session->userdata('status') !== 'loggedin') { ?>
            editor.editor_mode = 'fixed';
        <?php } ?>

        editor.start();

        var dataToImport = "";
        dataToImport = <?= $template_db; ?>;
        if (dataToImport !== 0) {
            editor.import(dataToImport);
            getDataLogger();
        }




        //editor.addNode(name, inputs, outputs, posx, posy, class, data, html);
        // editor.addNode('welcome', 0, 0, 50, 50, 'welcome', {}, welcome);
        // editor.addModule('Other');


        // Events!
        editor.on('nodeCreated', function(id) {
            console.log("Node created " + id);
            getDataLogger();
        })

        editor.on('nodeRemoved', function(id) {
            console.log("Node removed " + id);
        })

        editor.on('nodeSelected', function(id) {
            console.log("Node selected " + id);
        })

        editor.on('moduleCreated', function(name) {
            console.log("Module Created " + name);
        })

        editor.on('moduleChanged', function(name) {
            console.log("Module Changed " + name);
        })

        editor.on('connectionCreated', function(connection) {
            console.log('Connection created');
            // console.log(connection);
        })

        editor.on('connectionRemoved', function(connection) {
            console.log('Connection removed');
            console.log(connection);
        })

        editor.on('mouseMove', function(position) {
            // console.log('Position mouse x:' + position.x + ' y:' + position.y);
        })

        editor.on('nodeMoved', function(id) {
            console.log("Node moved " + id);
        })

        editor.on('zoom', function(zoom) {
            console.log('Zoom level ' + zoom);
        })

        editor.on('translate', function(position) {
            console.log('Translate x:' + position.x + ' y:' + position.y);
        })

        editor.on('addReroute', function(id) {
            console.log("Reroute added " + id);
        })

        editor.on('removeReroute', function(id) {
            console.log("Reroute removed " + id);
        })

        /* DRAG EVENT */

        /* Mouse and Touch Actions */

        var elements = document.getElementsByClassName('drag-drawflow');
        for (var i = 0; i < elements.length; i++) {
            elements[i].addEventListener('touchend', drop, false);
            elements[i].addEventListener('touchmove', positionMobile, false);
            elements[i].addEventListener('touchstart', drag, false);
        }

        var mobile_item_selec = '';
        var mobile_last_move = null;

        function positionMobile(ev) {
            mobile_last_move = ev;
        }

        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev) {
            if (ev.type === "touchstart") {
                mobile_item_selec = ev.target.closest(".drag-drawflow").getAttribute('data-node');
            } else {
                ev.dataTransfer.setData("node", ev.target.getAttribute('data-node'));
            }
        }

        function drop(ev) {
            if (ev.type === "touchend") {
                var parentdrawflow = document.elementFromPoint(mobile_last_move.touches[0].clientX, mobile_last_move.touches[0].clientY).closest("#drawflow");
                if (parentdrawflow != null) {
                    addNodeToDrawFlow(mobile_item_selec, mobile_last_move.touches[0].clientX, mobile_last_move.touches[0].clientY);
                }
                mobile_item_selec = '';
            } else {
                ev.preventDefault();
                var data = ev.dataTransfer.getData("node");
                addNodeToDrawFlow(data, ev.clientX, ev.clientY);
            }

        }

        function addNodeToDrawFlow(name, pos_x, pos_y) {
            if (editor.editor_mode === 'fixed') {
                return false;
            }
            pos_x = pos_x * (editor.precanvas.clientWidth / (editor.precanvas.clientWidth * editor.zoom)) - (editor.precanvas.getBoundingClientRect().x * (editor.precanvas.clientWidth / (editor.precanvas.clientWidth * editor.zoom)));
            pos_y = pos_y * (editor.precanvas.clientHeight / (editor.precanvas.clientHeight * editor.zoom)) - (editor.precanvas.getBoundingClientRect().y * (editor.precanvas.clientHeight / (editor.precanvas.clientHeight * editor.zoom)));

            console.log(name)
            $.ajax({
                url: "<?php echo site_url('admin/flow/getnode/') ?>/" + <?= $root ?>,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    for (var key in data) {
                        if (data.hasOwnProperty(key)) {
                            console.log(key + " -> " + data[key].id);
                            // case data[key].id:
                            if (name == data[key].id) {
                                var image = "<?= base_url('assets/gambar/') ?>" + data[key].img;
                                if (data[key].step == 5) {
                                    id_logger.push(data[key].kode);
                                    var multiple = `
                                        <div>
                                            <div class="card text-center card-logger" >
                                                <div class="card-body p-0"
                                                    <h5 class="card-title">` + data[key].step_name + " " + data[key].kode + `</h5>
                                                    <h6 class="card-subtitle mt-1 mb-2 text-muted">` + data[key].name + `</h6>
                                                    
                                                </div>
                                                <div class="card-footer p-0">
                                                    <div class="btn-group d-flex" role="group" aria-label="Basic example">
                                                        <button id="P_` + data[key].kode + `" type="button" class="btn btn-sm">P_` + data[key].kode + `</button>
                                                        <button id="Q_` + data[key].kode + `" type="button" class="btn btn-sm">Q_` + data[key].kode + `</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                } else {
                                    var multiple = `
                                        <div>
                                            <div class="card text-center " >
                                                <img src="` + image + `" class="card-img-top " 
                                                onerror="this.onerror=null;this.src='<?= base_url('assets/gambar/no-image.png') ?>';"
                                                style="width: 150px; margin-left:23px; margin-top: 10px; margin-bottom: -10px
                                                -khtml-user-select: none; -o-user-select: none; -moz-user-select: none; -webkit-user-select: none; user-select: none;">
                                                <div class="card-body p-0"
                                                    <h5 class="card-title" >` + data[key].step_name + `</h5>
                                                    <h6 class="card-subtitle mt-2 mb-2 text-muted">` + data[key].kode + data[key].name + `</h6>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                }

                                editor.addNode('multiple', data[key].input_port, data[key].output_port, pos_x, pos_y, 'multiple', {}, multiple);
                            }
                            // break;
                        }
                    }
                    getDataLogger();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

        }

        var transform = '';

        function showpopup(e) {
            e.target.closest(".drawflow-node").style.zIndex = "9999";
            e.target.children[0].style.display = "block";
            //document.getElementById("modalfix").style.display = "block";

            //e.target.children[0].style.transform = 'translate('+translate.x+'px, '+translate.y+'px)';
            transform = editor.precanvas.style.transform;
            editor.precanvas.style.transform = '';
            editor.precanvas.style.left = editor.canvas_x + 'px';
            editor.precanvas.style.top = editor.canvas_y + 'px';
            console.log(transform);

            //e.target.children[0].style.top  =  -editor.canvas_y - editor.container.offsetTop +'px';
            //e.target.children[0].style.left  =  -editor.canvas_x  - editor.container.offsetLeft +'px';
            editor.editor_mode = "fixed";

        }

        function closemodal(e) {
            e.target.closest(".drawflow-node").style.zIndex = "2";
            e.target.parentElement.parentElement.style.display = "none";
            //document.getElementById("modalfix").style.display = "none";
            editor.precanvas.style.transform = transform;
            editor.precanvas.style.left = '0px';
            editor.precanvas.style.top = '0px';
            editor.editor_mode = "edit";
        }

        function changeModule(event) {
            var all = document.querySelectorAll(".menu ul li");
            for (var i = 0; i < all.length; i++) {
                all[i].classList.remove('selected');
            }
            event.target.classList.add('selected');
        }

        function changeMode(option) {

            //console.log(lock.id);
            if (option == 'lock') {
                lock.style.display = 'none';
                unlock.style.display = 'block';
            } else {
                lock.style.display = 'block';
                unlock.style.display = 'none';
            }

        }

        function saveTemplate() {
            var template_ = editor.export();
            var string_template_ = JSON.stringify(template_, null, 4);
            $.ajax({
                url: '<?= base_url('spam/saveTemplate') ?>',
                method: "POST",
                data: {
                    template: string_template_,
                    root: <?= $root ?>
                },
                success: function(response) {
                    var myLoadedObj = JSON.parse(string_template_);
                    alert('Diagram Berhasil Disimpan');
                },
                error: function() {
                    alert("error");
                }

            });
        }

        function openNav() {
            document.getElementById("mySidebar").style.width = "300px";
            document.getElementById("main").style.marginLeft = "300px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }

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
                        console.log(data);
                        if (data != null) {
                            console.log('data.new_kode ' + data.new_kode);
                            $('#P_' + data.new_kode.replace(".", "\\.")).text((data.tekanan == 0) ? 'P: 0.00' : 'P: ' + data.tekanan);
                            if (data.tekanan < data.TEKANAN_NORMAL) {
                                console.log('danger' + '#P_' + data.new_kode);
                                $('#P_' + data.new_kode.replace(".", "\\.")).removeClass('btn-success');
                                $('#P_' + data.new_kode.replace(".", "\\.")).addClass('btn-danger');
                            } else {
                                console.log('normal')
                                $('#P_' + data.new_kode.replace(".", "\\.")).removeClass('btn-danger');
                                $('#P_' + data.new_kode.replace(".", "\\.")).addClass('btn-success');
                            }

                            $('#Q_' + data.new_kode.replace(".", "\\.")).text((data.debit == 0) ? 'Q: 0.00' : 'Q: ' + data.debit);
                            if (data.debit < data.DEBIT_NORMAL) {
                                $('#Q_' + data.new_kode.replace(".", "\\.")).removeClass('btn-success');
                                $('#Q_' + data.new_kode.replace(".", "\\.")).addClass('btn-danger');
                            } else {
                                $('#Q_' + data.new_kode.replace(".", "\\.")).removeClass('btn-danger');
                                $('#Q_' + data.new_kode.replace(".", "\\.")).addClass('btn-success');
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
    </script>
</body>

</html>