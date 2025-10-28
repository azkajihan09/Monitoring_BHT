<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-heart mr-2"></i> Dispensasi Kawin</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item">Permohonan</li>
								<li class="breadcrumb-item active">Dispensasi Kawin</li>
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
							<form action="<?php echo base_url() ?>index.php/Diska" method="POST" class="form-horizontal">
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Jenis Laporan:</label>
									<div class="col-sm-10">
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="laporan_bulanan" name="jenis_laporan" value="bulanan" class="custom-control-input" <?= (!isset($_POST['jenis_laporan']) || (isset($_POST['jenis_laporan']) && $_POST['jenis_laporan'] === 'bulanan')) ? 'checked' : '' ?>>
											<label class="custom-control-label" for="laporan_bulanan">Laporan Bulanan</label>
										</div>
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="laporan_tahunan" name="jenis_laporan" value="tahunan" class="custom-control-input" <?= (isset($_POST['jenis_laporan']) && $_POST['jenis_laporan'] === 'tahunan') ? 'checked' : '' ?>>
											<label class="custom-control-label" for="laporan_tahunan">Laporan Tahunan</label>
										</div>
									</div>
								</div>
								<div class="form-group row" id="bulan_container">
									<label class="col-sm-2 col-form-label">Bulan:</label>
									<div class="col-sm-4">
										<select name="lap_bulan" class="form-control select2" id="lap_bulan">
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
												$selected = (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === $value) ? 'selected' : ((!isset($_POST['lap_bulan']) && isset($current_month) && $current_month == $value) ? 'selected' : '');
												echo "<option value=\"$value\" $selected>$label</option>";
											}
											?>
										</select>
									</div>

									<label class="col-sm-2 col-form-label">Tahun:</label>
									<div class="col-sm-4">
										<select name="lap_tahun" class="form-control select2" required="">
											<?php
											$currentYear = date('Y');
											for ($year = 2016; $year <= $currentYear + 1; $year++) {
												$selected = (isset($_POST['lap_tahun']) && $_POST['lap_tahun'] == $year) ? 'selected' : ((!isset($_POST['lap_tahun']) && isset($current_year) && $current_year == $year) ? 'selected' : '');
												echo "<option value=\"$year\" $selected>$year</option>";
											}
											?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-4 offset-sm-8">
										<button type="submit" name="btn" class="btn btn-primary btn-block">
											<i class="fas fa-search mr-2"></i> Tampilkan Data
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>

					<?php if (isset($_POST['btn'])): ?>

						<!-- Stats Row -->
						<?php if (isset($statistics) && !empty($datafilter)): ?>
							<div class="row">
								<div class="col-lg-3 col-6">
									<div class="small-box bg-info">
										<div class="inner">
											<h3><?= count($datafilter) ?></h3>
											<p>Total Perkara</p>
										</div>
										<div class="icon">
											<i class="fas fa-file-alt"></i>
										</div>
									</div>
								</div>

								<div class="col-lg-3 col-6">
									<div class="small-box bg-success">
										<div class="inner">
											<h3><?= !empty($statistics->total_kabul) ? $statistics->total_kabul : 0 ?></h3>
											<p>Dikabulkan</p>
										</div>
										<div class="icon">
											<i class="fas fa-check-circle"></i>
										</div>
									</div>
								</div>

								<div class="col-lg-3 col-6">
									<div class="small-box bg-warning">
										<div class="inner">
											<h3><?= round(!empty($statistics->avg_umur_laki) ? $statistics->avg_umur_laki : 0, 1) ?></h3>
											<p>Rata-rata Usia Laki-laki</p>
										</div>
										<div class="icon">
											<i class="fas fa-male"></i>
										</div>
									</div>
								</div>

								<div class="col-lg-3 col-6">
									<div class="small-box bg-danger">
										<div class="inner">
											<h3><?= round(!empty($statistics->avg_umur_perempuan) ? $statistics->avg_umur_perempuan : 0, 1) ?></h3>
											<p>Rata-rata Usia Perempuan</p>
										</div>
										<div class="icon">
											<i class="fas fa-female"></i>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<!-- Main Data Card -->
						<div class="card">
							<div class="card-header bg-gradient-success">
								<h3 class="card-title">
									<i class="fas fa-list-alt mr-1"></i>
									Data Dispensasi Kawin -
									<?php if (isset($_POST['jenis_laporan']) && $_POST['jenis_laporan'] === 'tahunan'): ?>
										Tahun <?= isset($_POST['lap_tahun']) ? $_POST['lap_tahun'] : '' ?>
									<?php else: ?>
										<?= isset($months[$_POST['lap_bulan']]) ? $months[$_POST['lap_bulan']] : '' ?> <?= isset($_POST['lap_tahun']) ? $_POST['lap_tahun'] : '' ?>
									<?php endif; ?>
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
											<a href="#" class="dropdown-item">
												<i class="fas fa-file-excel mr-2"></i> Excel
											</a>
											<a href="#" class="dropdown-item">
												<i class="fas fa-file-pdf mr-2"></i> PDF
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body p-0">
								<?php if (!empty($datafilter)): ?>
									<div class="table-responsive">
										<table id="example1" class="table table-bordered table-striped table-hover">
											<thead class="bg-light">
												<tr>
													<th class="text-center" style="width: 3%">No</th>
													<th style="width: 12%">Nomor Perkara</th>
													<th style="width: 12%">Tanggal<br>Pendaftaran</th>
													<th style="width: 25%">Pihak Laki-laki</th>
													<th style="width: 25%">Pihak Perempuan</th>
													<th style="width: 13%">Alasan</th>
													<th style="width: 10%">Status</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1;
												foreach ($datafilter as $row): ?>
													<tr>
														<td class="text-center"><?= $no++ ?></td>
														<td>
															<span class="badge badge-primary d-block"><?= $row->nomor_perkara ?></span>
															<small class="text-muted"><?= $row->pemohon ?></small>
														</td>
														<td>
															<?= date('d-m-Y', strtotime($row->tanggal_pendaftaran)) ?>
															<?php if (!empty($row->tanggal_putusan)): ?>
																<div class="small text-muted mt-1">
																	<span class="badge badge-light">Putus: <?= date('d-m-Y', strtotime($row->tanggal_putusan)) ?></span>
																</div>
															<?php endif; ?>
														</td>
														<td>
															<?php if (!empty($row->nama_laki)): ?>
																<div class="d-flex">
																	<div class="mr-2">
																		<span class="avatar-initial rounded-circle bg-gradient-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
																			<i class="fas fa-male text-white"></i>
																		</span>
																	</div>
																	<div>
																		<strong><?= $row->nama_laki ?></strong>
																		<div class="small">
																			<?php if (!empty($row->tanggal_lahir_laki)): ?>
																				<div class="text-muted">
																					Lahir: <?= date('d-m-Y', strtotime($row->tanggal_lahir_laki)) ?>
																				</div>
																			<?php endif; ?>
																			<?php if (!empty($row->umur_laki)): ?>
																				<span class="badge badge-info"><?= $row->umur_laki ?> tahun</span>
																			<?php endif; ?>
																		</div>
																	</div>
																</div>
															<?php else: ?>
																<span class="text-muted"><i>Data tidak tersedia</i></span>
															<?php endif; ?>
														</td>
														<td>
															<?php if (!empty($row->nama_perempuan)): ?>
																<div class="d-flex">
																	<div class="mr-2">
																		<span class="avatar-initial rounded-circle bg-gradient-danger d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
																			<i class="fas fa-female text-white"></i>
																		</span>
																	</div>
																	<div>
																		<strong><?= $row->nama_perempuan ?></strong>
																		<div class="small">
																			<?php if (!empty($row->tanggal_lahir_perempuan)): ?>
																				<div class="text-muted">
																					Lahir: <?= date('d-m-Y', strtotime($row->tanggal_lahir_perempuan)) ?>
																				</div>
																			<?php endif; ?>
																			<?php if (!empty($row->umur_perempuan)): ?>
																				<span class="badge badge-danger"><?= $row->umur_perempuan ?> tahun</span>
																			<?php endif; ?>
																		</div>
																	</div>
																</div>
															<?php else: ?>
																<span class="text-muted"><i>Data tidak tersedia</i></span>
															<?php endif; ?>
														</td>
														<td>
															<?php if (!empty($row->alasan_nikah)): ?>
																<span class="badge badge-warning"><?= $row->alasan_nikah ?></span>
															<?php else: ?>
																<span class="text-muted"><i>Tidak tercatat</i></span>
															<?php endif; ?>
														</td>
														<td>
															<?php if (!empty($row->jenis_putusan)): ?>
																<?php
																$statusClass = '';
																$statusIcon = '';
																$statusText = $row->jenis_putusan;

																if (stripos($row->jenis_putusan, 'KABUL') !== false) {
																	$statusClass = 'success';
																	$statusIcon = 'check-circle';
																} elseif (stripos($row->jenis_putusan, 'TOLAK') !== false) {
																	$statusClass = 'danger';
																	$statusIcon = 'times-circle';
																} elseif (stripos($row->jenis_putusan, 'GUGUR') !== false) {
																	$statusClass = 'warning';
																	$statusIcon = 'exclamation-circle';
																} elseif (stripos($row->jenis_putusan, 'CABUT') !== false) {
																	$statusClass = 'secondary';
																	$statusIcon = 'undo';
																} else {
																	$statusClass = 'primary';
																	$statusIcon = 'info-circle';
																}
																?>
																<div class="badge badge-<?= $statusClass ?> d-block">
																	<i class="fas fa-<?= $statusIcon ?> mr-1"></i> <?= $statusText ?>
																</div>
															<?php else: ?>
																<span class="badge badge-secondary">Dalam Proses</span>
																<?php if (!empty($row->tahapan_terakhir_text)): ?>
																	<div class="small text-muted mt-1"><?= $row->tahapan_terakhir_text ?></div>
																<?php endif; ?>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								<?php else: ?>
									<div class="alert alert-info m-3">
										<h5><i class="icon fas fa-info"></i> Informasi</h5>
										Tidak ada data Dispensasi Kawin pada periode yang dipilih.
									</div>
								<?php endif; ?>
							</div>
						</div>

						<?php if (!empty($datafilter) && count($datafilter) > 0): ?>
							<!-- Age Distribution Card -->
							<div class="card card-info">
								<div class="card-header">
									<h3 class="card-title">
										<i class="fas fa-chart-bar mr-1"></i>
										Statistik Usia Pada Dispensasi Kawin
									</h3>
									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse">
											<i class="fas fa-minus"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-md-6">
											<div class="chart-title text-center mb-2">Usia Laki-laki</div>
											<table class="table table-bordered">
												<tr class="bg-light">
													<th>Statistik</th>
													<th>Nilai (Tahun)</th>
												</tr>
												<tr>
													<td>Rata-rata Usia</td>
													<td><?= round(!empty($statistics->avg_umur_laki) ? $statistics->avg_umur_laki : 0, 1) ?></td>
												</tr>
												<tr>
													<td>Usia Terendah</td>
													<td><?= !empty($statistics->min_umur_laki) ? $statistics->min_umur_laki : '-' ?></td>
												</tr>
												<tr>
													<td>Usia Tertinggi</td>
													<td><?= !empty($statistics->max_umur_laki) ? $statistics->max_umur_laki : '-' ?></td>
												</tr>
											</table>
										</div>
										<div class="col-md-6">
											<div class="chart-title text-center mb-2">Usia Perempuan</div>
											<table class="table table-bordered">
												<tr class="bg-light">
													<th>Statistik</th>
													<th>Nilai (Tahun)</th>
												</tr>
												<tr>
													<td>Rata-rata Usia</td>
													<td><?= round(!empty($statistics->avg_umur_perempuan) ? $statistics->avg_umur_perempuan : 0, 1) ?></td>
												</tr>
												<tr>
													<td>Usia Terendah</td>
													<td><?= !empty($statistics->min_umur_perempuan) ? $statistics->min_umur_perempuan : '-' ?></td>
												</tr>
												<tr>
													<td>Usia Tertinggi</td>
													<td><?= !empty($statistics->max_umur_perempuan) ? $statistics->max_umur_perempuan : '-' ?></td>
												</tr>
											</table>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>

					<?php endif; ?>
				</div>
			</section>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			// Initialize DataTable with export buttons
			$("#example1").DataTable({
				"responsive": true,
				"lengthChange": true,
				"autoWidth": false,
				"dom": '<"top d-flex justify-content-between"Bf>rt<"bottom d-flex justify-content-between"lip>',
				"buttons": [{
						extend: "copy",
						className: "btn-sm btn-secondary",
						text: '<i class="fas fa-copy"></i> Salin'
					},
					{
						extend: "csv",
						className: "btn-sm btn-secondary",
						text: '<i class="fas fa-file-csv"></i> CSV'
					},
					{
						extend: "excel",
						className: "btn-sm btn-secondary",
						text: '<i class="fas fa-file-excel"></i> Excel'
					},
					{
						extend: "pdf",
						className: "btn-sm btn-secondary",
						text: '<i class="fas fa-file-pdf"></i> PDF'
					},
					{
						extend: "print",
						className: "btn-sm btn-secondary",
						text: '<i class="fas fa-print"></i> Cetak'
					},
					{
						extend: "colvis",
						className: "btn-sm btn-secondary",
						text: '<i class="fas fa-columns"></i> Kolom'
					}
				],
				"language": {
					"info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
					"infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
					"infoFiltered": "(disaring dari _MAX_ total data)",
					"search": "Cari:",
					"lengthMenu": "Tampilkan _MENU_ data",
					"zeroRecords": "Tidak ada data yang cocok",
					"paginate": {
						"first": "Pertama",
						"last": "Terakhir",
						"next": "Selanjutnya",
						"previous": "Sebelumnya"
					}
				}
			});

			// Enable tooltips
			$('[data-toggle="tooltip"]').tooltip();

			// Handle report type toggle
			function toggleBulanField() {
				if ($("#laporan_tahunan").is(":checked")) {
					$("#bulan_container").hide();
					$("#lap_bulan").prop("required", false);
					$("#lap_bulan").prop("disabled", true);
					// Force select2 to update its state
					if ($.fn.select2) {
						$("#lap_bulan").select2("enable", false);
					}
				} else {
					$("#bulan_container").show();
					$("#lap_bulan").prop("required", true);
					$("#lap_bulan").prop("disabled", false);
					// Force select2 to update its state
					if ($.fn.select2) {
						$("#lap_bulan").select2("enable", true);
					}
				}
			}

			// Initial state - make sure this runs immediately
			toggleBulanField();

			// Listen for changes
			$("input[name='jenis_laporan']").change(function() {
				toggleBulanField();
			});

			// Ensure the function is called after page load
			setTimeout(function() {
				toggleBulanField();
			}, 100);
		});
	</script>