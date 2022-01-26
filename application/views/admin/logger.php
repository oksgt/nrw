<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>NRW|Data Logger</title>
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<?php include 'css.php';?>
</head>
<body class="hold-transition sidebar-mini">
	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Data Input Logger</h3>
						</div>
						<!-- /.card-header -->
						<div class="card-body">
							<table id="example1" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>No</th>
										<th>Kode</th>
										<th>Tanggal</th>
										<th>Lokasi</th>
										<th>Cabang</th>
										<th>Diameter</th>
										<th>Debit</th>
										<th>Tekanan</th>
										<th>Kubikasi</th>
										<th>Level</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$no = 1;
									foreach ($logger as $logger): ?>
										<tr>
											<td><?php echo $no++ ?></td>
											<td><?php echo $logger->id_mendoan ?></td>
											<td><?php echo $logger->tanggal ?></td>
											<td><?php echo $logger->lokasi ?></td>
											<td><?php echo $logger->cabang ?></td>
											<td><?php echo $logger->diameter_pipa ?></td>
											<td><?php echo $logger->debit ?></td>
											<td><?php echo $logger->tekanan ?></td>
											<td><?php echo $logger->kubikasi ?></td>
											<td><?php echo $logger->level ?></td>
											<td width="250">
												<a href="<?php echo site_url('admin/logger/edit/'.$logger->id_mendoan) ?>"
													class="btn btn-small"><i class="fas fa-edit"></i> Edit</a>
													<a onclick="deleteConfirm('<?php echo site_url('admin/logger/delete/'.$logger->id_mendoan) ?>')"
														href="#!" class="btn btn-small text-danger"><i class="fas fa-trash"></i> Hapus</a>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
								<!-- /.card-body -->
							</div>
							<!-- /.card -->
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
				</div>
				<!-- /.container-fluid -->
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->
	
		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
		</aside>
		<!-- /.control-sidebar -->
	</div>
	<!-- ./wrapper -->
	<?php include 'js.php';?>
	<script>
		$(function () {
			$("#example1").DataTable({
				"responsive": true, "lengthChange": false, "autoWidth": false,
				"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
			}).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
			$('#example2').DataTable({
				"paging": true,
				"lengthChange": false,
				"searching": false,
				"ordering": true,
				"info": true,
				"autoWidth": false,
				"responsive": true,
			});
		});
	</script>
</body>
</html>
