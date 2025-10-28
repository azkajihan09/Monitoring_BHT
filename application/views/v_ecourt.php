<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-laptop-code mr-2"></i> Laporan Perkara E-Court</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item">E-Court</li>
								<li class="breadcrumb-item active">Laporan Perkara</li>
							</ol>
						</div>
					</div>
				</div>
			</section>

			<section class="content">
				<div class="container-fluid">
					<!-- Filter Card -->
					<div class="card card-primary card-outline">
						<div class="card-header">
							<h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Data</h3>
							<div class="card-tools">
								<button type="button" class="btn btn-tool" data-card-widget="collapse">
									<i class="fas fa-minus"></i>
								</button>
							</div>
						</div>
						<div class="card-body">
							<form action="<?= base_url() ?>index.php/Ecourt" method="POST" class="form-horizontal">
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Jenis Perkara:</label>
									<div class="col-sm-4">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
											</div>
											<select name="jenis_perkara" class="form-control select2" required>
												<option value="Pdt.G" <?= (isset($_POST['jenis_perkara']) && $_POST['jenis_perkara'] === 'Pdt.G') ? 'selected' : ''; ?>>Gugatan (Pdt.G)</option>
												<option value="Pdt.P" <?= (isset($_POST['jenis_perkara']) && $_POST['jenis_perkara'] === 'Pdt.P') ? 'selected' : ''; ?>>Permohonan (Pdt.P)</option>
												<option value="all" <?= (isset($_POST['jenis_perkara']) && $_POST['jenis_perkara'] === 'all') ? 'selected' : ''; ?>>Semua Jenis</option>
											</select>
										</div>
									</div>

									<label class="col-sm-2 col-form-label">Periode:</label>
									<div class="col-sm-4">
										<div class="row">
											<div class="col-sm-6">
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
													<select name="lap_bulan" class="form-control select2" required>
														<?php
														$months = [
															'01' => 'Januari',
															'02' => 'Februari',
															'03' => 'Maret',
															'04' => 'April',
															'05' => 'Mei',
															'06' => 'Juni',
															'07' => 'Juli',
															'08' => 'Agustus',
															'09' => 'September',
															'10' => 'Oktober',
															'11' => 'November',
															'12' => 'Desember'
														];

														foreach ($months as $value => $label) {
															$selected = (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === $value) ? 'selected' : '';
															echo "<option value=\"$value\" $selected>$label</option>";
														}
														?>
													</select>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-check"></i></span>
													</div>
													<select name="lap_tahun" class="form-control select2" required>
														<?php
														$currentYear = date('Y');
														for ($year = 2016; $year <= $currentYear + 1; $year++) {
															$selected = (isset($_POST['lap_tahun']) && $_POST['lap_tahun'] == $year) ? 'selected' : '';
															echo "<option value=\"$year\" $selected>$year</option>";
														}
														?>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<div class="col-sm-4 offset-sm-8">
										<button type="submit" name="btn" value="search" class="btn btn-primary btn-block">
											<i class="fas fa-search mr-2"></i> Tampilkan Data
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>

					<?php if (isset($datafilter) && !empty($datafilter)): ?>
						<!-- Statistics Cards -->
						<div class="row">
							<div class="col-lg-3 col-6">
								<div class="small-box bg-info">
									<div class="inner">
										<h3><?= count($datafilter) ?></h3>
										<p>Total Perkara E-Court</p>
									</div>
									<div class="icon">
										<i class="fas fa-laptop-code"></i>
									</div>
									<a href="#" class="small-box-footer">
										Periode: <?= isset($nama_bulan[$_POST['lap_bulan']]) ? $nama_bulan[$_POST['lap_bulan']] : '' ?> <?= $_POST['lap_tahun'] ?>
										<i class="fas fa-calendar-alt mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-success">
									<div class="inner">
										<h3><?= isset($stats->registered_count) ? $stats->registered_count : '0' ?></h3>
										<p>Teregistrasi</p>
									</div>
									<div class="icon">
										<i class="fas fa-check-circle"></i>
									</div>
									<a href="#" class="small-box-footer">
										Sudah mendapat nomor perkara
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-warning">
									<div class="inner">
										<h3><?= isset($stats->gugatan_count) ? $stats->gugatan_count : '0' ?></h3>
										<p>Gugatan (Pdt.G)</p>
									</div>
									<div class="icon">
										<i class="fas fa-gavel"></i>
									</div>
									<a href="#" class="small-box-footer">
										Perkara gugatan
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-danger">
									<div class="inner">
										<h3><?= isset($stats->permohonan_count) ? $stats->permohonan_count : '0' ?></h3>
										<p>Permohonan (Pdt.P)</p>
									</div>
									<div class="icon">
										<i class="fas fa-file-signature"></i>
									</div>
									<a href="#" class="small-box-footer">
										Perkara permohonan
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>
						</div>

						<!-- Main Data Card -->
						<div class="card card-outline card-primary">
							<div class="card-header bg-light">
								<h3 class="card-title">
									<i class="fas fa-table mr-1"></i>
									Data Perkara E-Court
								</h3>
								<div class="card-tools">
									<button type="button" class="btn btn-tool" data-card-widget="collapse">
										<i class="fas fa-minus"></i>
									</button>
									<button type="button" class="btn btn-tool" data-card-widget="maximize">
										<i class="fas fa-expand"></i>
									</button>
									<div class="btn-group ml-2">
										<button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown">
											<i class="fas fa-download"></i> Export
										</button>
										<div class="dropdown-menu dropdown-menu-right">
											<a href="#" class="dropdown-item export-excel">
												<i class="fas fa-file-excel mr-2"></i> Excel
											</a>
											<a href="#" class="dropdown-item export-pdf">
												<i class="fas fa-file-pdf mr-2"></i> PDF
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body p-0">
								<div class="table-responsive">
									<table id="dataTable" class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th class="text-center" width="5%">No</th>
												<th width="15%">Nama Penggugat/Pemohon</th>
												<th width="15%">Email</th>
												<th width="10%">Jenis Perkara</th>
												<th width="15%">Nomor Perkara</th>
												<th width="10%">Tanggal Daftar</th>
												<th width="15%">Status</th>
												<th width="10%">Aksi</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$no = 1;
											foreach ($datafilter as $row): ?>
												<tr>
													<td class="text-center"><?= $no++ ?></td>
													<td><?= $row->nama_pihak ?></td>
													<td><a href="mailto:<?= $row->email ?>"><?= $row->email ?></a></td>
													<td><?= $row->jenis_perkara_nama ?></td>
													<td>
														<a href="#" class="text-primary view-details" data-id="<?= $row->perkara_id ?>" data-toggle="tooltip" title="Lihat detail perkara">
															<?= $row->nomor_perkara ?>
														</a>
													</td>
													<td><?= date('d-m-Y', strtotime($row->tanggal_pendaftaran)) ?></td>
													<td>
														<?php if ($row->status === 'Teregistrasi'): ?>
															<span class="badge badge-success">Teregistrasi</span>
														<?php else: ?>
															<span class="badge badge-warning">Pendaftaran</span>
														<?php endif; ?>
													</td>
													<td>
														<a href="<?= site_url('Ecourt/detail/' . $row->perkara_id) ?>" class="btn btn-xs btn-info" data-toggle="tooltip" title="Detail">
															<i class="fas fa-eye"></i>
														</a>
														<a href="<?= site_url('Ecourt_monitoring/timeline/' . $row->perkara_id) ?>" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Timeline">
															<i class="fas fa-history"></i>
														</a>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="card-footer bg-light">
								<div class="text-right">
									<small class="text-muted">Total data: <?= count($datafilter) ?> | Diperbarui: <?= date('d-m-Y H:i:s') ?></small>
								</div>
							</div>
						</div>
					<?php else: ?>
						<!-- No Data Message -->
						<?php if (isset($_POST['btn'])): ?>
							<div class="alert alert-warning alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h5><i class="icon fas fa-exclamation-triangle"></i> Tidak Ada Data</h5>
								<p>Tidak ditemukan data perkara E-Court pada periode yang dipilih. Silahkan pilih periode lainnya.</p>
							</div>
						<?php else: ?>
							<div class="jumbotron bg-light">
								<h1 class="display-5"><i class="fas fa-laptop-code mr-2"></i> Data Perkara E-Court</h1>
								<p class="lead">Silahkan pilih periode untuk menampilkan data perkara yang didaftarkan melalui sistem E-Court.</p>
								<hr class="my-4">
								<p>Sistem E-Court memungkinkan pendaftaran perkara secara elektronik untuk meningkatkan efisiensi layanan hukum.</p>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</section>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			// Initialize Select2
			$('.select2').select2({
				theme: 'bootstrap4'
			});

			// Initialize DataTables
			$("#dataTable").DataTable({
				"responsive": true,
				"lengthChange": true,
				"autoWidth": false,
				"language": {
					"lengthMenu": "Tampilkan _MENU_ data per halaman",
					"zeroRecords": "Data tidak ditemukan",
					"info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
					"infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
					"infoFiltered": "(difilter dari _MAX_ total data)",
					"search": "Cari:",
					"paginate": {
						"first": "Pertama",
						"last": "Terakhir",
						"next": "Selanjutnya",
						"previous": "Sebelumnya"
					}
				},
				"buttons": [{
						extend: 'excel',
						text: 'Excel',
						title: 'Laporan Perkara E-Court - <?= isset($_POST["lap_tahun"]) ? $_POST["lap_tahun"] : date("Y") ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6]
						}
					},
					{
						extend: 'pdf',
						text: 'PDF',
						title: 'Laporan Perkara E-Court - <?= isset($_POST["lap_tahun"]) ? $_POST["lap_tahun"] : date("Y") ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6]
						},
						orientation: 'landscape'
					}
				]
			});

			// Export buttons binding
			$('.export-excel').click(function(e) {
				e.preventDefault();
				$('.buttons-excel').click();
			});

			$('.export-pdf').click(function(e) {
				e.preventDefault();
				$('.buttons-pdf').click();
			});

			// Initialize tooltips
			$('[data-toggle="tooltip"]').tooltip();
		});
	</script>
</body>

</html>