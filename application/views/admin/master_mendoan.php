<?php include 'css.php';?>
</head> 
<body class="hold-transition sidebar-mini">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Master Mendoan</h3>
                        </div>
                        <div class="col-md-9">
                            <button class="btn btn-success" onclick="add_person()"><i class="glyphicon glyphicon-plus"></i> Add Data</button>
                            <button class="btn btn-warning" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
                        </div>
                        <div class="card-body">
                            <table id="table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
										<th>Kode</th>
										<th>Lokasi</th>
                                        <th>Debit Normal</th>
                                        <th>Tekanan Normal</th>
                                        <th>Diameter</th>
										<th>SPAM</th>
										<th>Cabang</th>
										<th>Jenis Layanan</th>
                                        <th style="width:125px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php include 'js.php';?>
        <script type="text/javascript">

var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 
        "dom": 'Bfrtip',
        "buttons": [
        'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('admin/mendoan/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],

    });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });

});


function add_person()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
}

function edit_person(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('admin/mendoan/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="NO"]').val(data.NO);
            $('[name="KODE"]').val(data.KODE);
            $('[name="LOKASI"]').val(data.LOKASI);
            $('[name="DEBIT_NORMAL"]').val(data.DEBIT_NORMAL);
            $('[name="TEKANAN_NORMAL"]').val(data.TEKANAN_NORMAL);
            $('[name="DIAMETER_PIPA"]').val(data.DIAMETER_PIPA);
            $('[name="SPAM"]').val(data.SPAM);
            $('[name="CABANG_LAYANAN"]').val(data.CABANG_LAYANAN);
            $('[name="JENIS_LAYANAN"]').val(data.JENIS_LAYANAN);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Person'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('admin/mendoan/ajax_add')?>";
    } else {
        url = "<?php echo site_url('admin/mendoan/ajax_update')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
            }

            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_person(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('admin/mendoan/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });
    }
}

</script>
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="text" value="" name="NO"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">KODE</label>
                            <div class="col-md-9">
                                <input name="KODE" placeholder="KODE" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">LOKASI</label>
                            <div class="col-md-9">
                                <input name="LOKASI" placeholder="LOKASI" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">DEBIT NORMAL</label>
                            <div class="col-md-9">
                                <input name="DEBIT_NORMAL" placeholder="DEBIT_NORMAL" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">TEKANAN NORMAL</label>
                            <div class="col-md-9">
                                <input name="TEKANAN_NORMAL" placeholder="TEKANAN_NORMAL" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="control-label col-md-3">SPAM</label>
                            <div class="col-md-9">
                                <input name="SPAM" placeholder="SPAM" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="control-label col-md-4">CABANG LAYANAN</label>
                            <div class="col-md-9">
                                <select name="CABANG_LAYANAN" class="form-control">
                                    <option value="">--Select Cabang--</option>
                                    <option value="Purwokerto 1">Purwokerto 1</option>
                                    <option value="Purwokerto 2">Purwokerto 2</option>
                                    <option value="Banyumas">Banyumas</option>
                                    <option value="Ajibarang">Ajibarang</option>
                                    <option value="Wangon">Wangon</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">JENIS LAYANAN</label>
                            <div class="col-md-9">
                                <select name="JENIS_LAYANAN" class="form-control">
                                    <option value="">--Select Jenis--</option>
                                    <option value="SUPER DMA">SUPER DMA</option>
                                    <option value="DMA">DMA</option>
                                    <option value="SUMBER">SUMBER</option>
                                    <option value="TRANSMISI">TRANSMISI</option>
                                    <option value="DISTRIBUSI">DISTRIBUSI</option>
                                    <option value="LAYANAN KHUSUS">LAYANAN KHUSUS</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>