<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <!-- drawflow -->
    <link rel="stylesheet" href="http://localhost/nrw/node_modules/drawflow/dist/drawflow.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/nrw/assets/drawflow/beautiful.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <title>Diagram</title>
</head>

<body>
    <div class="container h-100 ml-0">
        <div class="col-12" style="max-height: 50px !important; width:100%">
            <h1><?= $spam_name ?></h1>
        </div>
        <div class="row h-100 ml-100">

            <div class="col-sm-3 " style="height:100%; overflow-y: scroll;">
                <div class="tetx-center">
                    <span> Daftar Komponen</span>
                </div>
                <div class="drag-drawflow" style="height: 10px;">
                    &nbsp;
                </div>


                <?php
                foreach ($node as $r) { ?>
                    <div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node=<?= $r['id'] ?> style="margin-top: 10px">
                        <div class="card" style="border: none !important">
                            <div class="row no-gutters">
                                <div class="col-auto">
                                    <img src="<?= base_url('assets/gambar/') . $r['img'] ?>" class="img-fluid" width="70px" height="100" onerror="this.onerror=null;this.src='<?= base_url('assets/gambar/no-image.png') ?>';">
                                </div>
                                <div class="">
                                    <div class="card-block px-2 p-0">
                                        <h4 style="font-size: 12px;" class="card-title "><?= $r['step_name'] ?></h4>
                                        <h4 style="font-size: 11px;" class="card-title "><?= $r['kode'] . $r['name'] ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>
            <div class="col-sm-9 ">
                <div class="menu">
                    <ul>
                        <li onclick="editor.changeModule('Home'); changeModule(event);" class="selected">Diagram Editor</li>
                        <li>Display</li>
                        <!-- <li onclick="editor.changeModule('Other'); changeModule(event);" class="selected">Other Module</li> -->
                    </ul>
                </div>
                <div id="drawflow" ondrop="drop(event)" ondragover="allowDrop(event)" style="min-width: 100%;">
                    <div class="btn-clear" onclick="editor.clearModuleSelected()">Clear</div>
                    <div class="btn-export" onclick="saveTemplate()">Save</div>
                    <div class="btn-lock">
                        <i id="lock" class="fas fa-lock" onclick="editor.editor_mode='fixed'; changeMode('lock');"></i>
                        <i id="unlock" class="fas fa-lock-open" onclick="editor.editor_mode='edit'; changeMode('unlock');" style="display:none;"></i>
                    </div>
                    <div class="bar-zoom">
                        <i class="fas fa-search-minus" onclick="editor.zoom_out()"></i>
                        <i class="fas fa-search" onclick="editor.zoom_reset()"></i>
                        <i class="fas fa-search-plus" onclick="editor.zoom_in()"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

    <!-- drawflow -->
    <script src="https://cdn.jsdelivr.net/gh/jerosoler/Drawflow/dist/drawflow.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        // var $ = jQuery;
        var id = document.getElementById("drawflow");
        const editor = new Drawflow(id);
        editor.reroute = true;
        editor.start();
        const dataToImport = <?= $template_db ?>;
        editor.import(dataToImport);

        //editor.addNode(name, inputs, outputs, posx, posy, class, data, html);
        // editor.addNode('welcome', 0, 0, 50, 50, 'welcome', {}, welcome);
        // editor.addModule('Other');


        // Events!
        editor.on('nodeCreated', function(id) {
            console.log("Node created " + id);
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
            console.log(connection);
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
                                    var multiple = `
                                        <div>
                                            <div class="card text-center " >
                                                <div class="card-body p-0"
                                                    <h5 class="card-title">` + data[key].step_name + `</h5>
                                                    <h6 class="card-subtitle mt-1 mb-2 text-muted">` + data[key].kode + data[key].name + `</h6>
                                                </div>
                                                <div card-footer>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
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
                                                    <h5 class="card-title">` + data[key].step_name + `</h5>
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
            var string_template_ = JSON.stringify(template_);
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
    </script>
</body>

</html>