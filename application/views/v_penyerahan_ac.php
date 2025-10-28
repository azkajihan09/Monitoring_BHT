<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-file-alt mr-2"></i> Penyerahan Akta Cerai</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item active">Penyerahan Akta Cerai</li>
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
							<form action="<?php echo base_url() ?>index.php/Penyerahan_ac" method="POST" class="form-horizontal">
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
												$selected = (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === $value) ? 'selected' : ((!isset($_POST['lap_bulan']) && isset($selected_bulan) && $selected_bulan == $value) ? 'selected' : '');
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
												$selected = (isset($_POST['lap_tahun']) && $_POST['lap_tahun'] == $year) ? 'selected' : ((!isset($_POST['lap_tahun']) && isset($selected_tahun) && $selected_tahun == $year) ? 'selected' : '');
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
						<!-- Statistik Card -->
						<?php if (isset($statistics) && !empty($datafilter)): ?>
							<div class="row">
								<div class="col-lg-4 col-6">
									<div class="small-box bg-gradient-primary">
										<div class="inner">
											<h3><?= count($datafilter) ?></h3>
											<p>Total Akta Cerai</p>
										</div>
										<div class="icon">
											<i class="fas fa-file-signature"></i>
										</div>
										<div class="small-box-footer bg-primary">
											Jumlah akta cerai dalam periode <i class="fas fa-calendar-alt mx-1"></i>
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-6">
									<div class="small-box bg-gradient-indigo">
										<div class="inner">
											<h3><?= !empty($statistics->total_suami) ? $statistics->total_suami : 0 ?></h3>
											<p>Diserahkan kepada Mantan Suami</p>
										</div>
										<div class="icon">
											<i class="fas fa-male"></i>
										</div>
										<div class="small-box-footer bg-indigo">
											<div class="d-flex justify-content-center align-items-center">
												<?php if (!empty($statistics->total) && !empty($statistics->total_suami)): ?>
													<div class="progress progress-xs mt-1 mb-0 w-50 mx-2" style="height: 5px">
														<div class="progress-bar bg-white" style="width: <?= ($statistics->total_suami / $statistics->total) * 100 ?>%"></div>
													</div>
													<span><?= round(($statistics->total_suami / $statistics->total) * 100) ?>% dari total</span>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-6">
									<div class="small-box bg-gradient-pink">
										<div class="inner">
											<h3><?= !empty($statistics->total_istri) ? $statistics->total_istri : 0 ?></h3>
											<p>Diserahkan kepada Mantan Istri</p>
										</div>
										<div class="icon">
											<i class="fas fa-female"></i>
										</div>
										<div class="small-box-footer bg-pink">
											<div class="d-flex justify-content-center align-items-center">
												<?php if (!empty($statistics->total) && !empty($statistics->total_istri)): ?>
													<div class="progress progress-xs mt-1 mb-0 w-50 mx-2" style="height: 5px">
														<div class="progress-bar bg-white" style="width: <?= ($statistics->total_istri / $statistics->total) * 100 ?>%"></div>
													</div>
													<span><?= round(($statistics->total_istri / $statistics->total) * 100) ?>% dari total</span>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<!-- Data Card -->
						<div class="card card-outline card-success shadow-sm">
							<div class="card-header bg-gradient-success">
								<h3 class="card-title">
									<i class="fas fa-list-alt mr-1"></i>
									Data Penyerahan Akta Cerai -
									<?= isset($months[$selected_bulan]) ? $months[$selected_bulan] : '' ?> <?= $selected_tahun ?>
								</h3>
								<div class="card-tools">
									<button type="button" class="btn btn-tool" data-card-widget="collapse">
										<i class="fas fa-minus"></i>
									</button>
									<button type="button" class="btn btn-tool" data-card-widget="maximize">
										<i class="fas fa-expand"></i>
									</button>
									<div class="btn-group ml-2">
										<button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">
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
								<?php if (!empty($datafilter)): ?>
									<div class="table-responsive">
										<table id="example1" class="table table-bordered table-striped table-hover">
											<thead class="bg-light">
												<tr>
													<th class="text-center" style="width:3%">No</th>
													<th style="width:10%">Nomor Perkara</th>
													<th style="width:10%">Nomor Akta Cerai</th>
													<th style="width:10%">Tanggal Putus</th>
													<th style="width:12%">Tanggal BHT</th>
													<th style="width:25%">Mantan Suami</th>
													<th style="width:25%">Mantan Istri</th>
													<th style="width:5%">Detail</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1;
												foreach ($datafilter as $row): ?>
													<tr>
														<td class="text-center"><?= $no++ ?></td>
														<td>
															<span class="badge badge-primary d-block mb-1"><?= $row->nomor_perkara ?></span>
															<small class="text-muted"><?= $row->jenis_perkara_nama ?></small>
														</td>
														<td><span class="badge badge-dark"><?= $row->nomor_akta_cerai ?></span></td>
														<td>
															<?php if (!empty($row->tanggal_putusan)): ?>
																<span class="badge badge-light">
																	<i class="fas fa-gavel mr-1"></i>
																	<?= date('d-m-Y', strtotime($row->tanggal_putusan)) ?>
																</span>
															<?php else: ?>
																<span class="badge badge-secondary">Belum ada</span>
															<?php endif; ?>

															<?php if (!empty($row->tgl_ikrar_talak)): ?>
																<div class="small mt-1">
																	<span class="badge badge-info">
																		<i class="fas fa-calendar-check mr-1"></i>
																		Ikrar: <?= date('d-m-Y', strtotime($row->tgl_ikrar_talak)) ?>
																	</span>
																</div>
															<?php endif; ?>
														</td>
														<td>
															<?php if (!empty($row->tanggal_bht)): ?>
																<span class="badge badge-success">
																	<i class="fas fa-calendar-check mr-1"></i>
																	<?= date('d-m-Y', strtotime($row->tanggal_bht)) ?>
																</span>
															<?php else: ?>
																<span class="badge badge-warning">Belum BHT</span>
															<?php endif; ?>
														</td>

														<!-- Mantan Suami -->
														<td>
															<div class="d-flex">
																<div class="mr-2">
																	<span class="avatar-initial rounded-circle bg-indigo d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
																		<i class="fas fa-male text-white"></i>
																	</span>
																</div>
																<div>
																	<strong>
																		<?php if ($row->jenis_perkara_nama == 'Cerai Talak'): ?>
																			<?= $row->nama_p ?>
																		<?php elseif ($row->jenis_perkara_nama == 'Cerai Gugat'): ?>
																			<?= $row->nama_t ?>
																		<?php endif; ?>
																	</strong>

																	<div class="mt-1">
																		<?php
																		// Tanggal penyerahan ke suami
																		$tanggal_ke_suami = null;

																		if ($row->jenis_perkara_nama == 'Cerai Talak' && !empty($row->tgl_AC_P)) {
																			$tanggal_ke_suami = $row->tgl_AC_P;
																		} elseif ($row->jenis_perkara_nama == 'Cerai Gugat' && !empty($row->tgl_AC_T)) {
																			$tanggal_ke_suami = $row->tgl_AC_T;
																		}

																		if (!empty($tanggal_ke_suami)):
																		?>
																			<div class="badge badge-success">
																				<i class="fas fa-check-circle mr-1"></i>
																				Telah Menerima Akta Cerai
																			</div>
																			<div class="small text-muted mt-1">
																				<i class="far fa-calendar-alt mr-1"></i>
																				<?= date('d-m-Y', strtotime($tanggal_ke_suami)) ?>
																			</div>
																		<?php else: ?>
																			<span class="badge badge-danger">
																				<i class="fas fa-times-circle mr-1"></i>
																				Belum Menerima Akta Cerai
																			</span>
																		<?php endif; ?>
																	</div>
																</div>
															</div>
														</td>

														<!-- Mantan Istri -->
														<td>
															<div class="d-flex">
																<div class="mr-2">
																	<span class="avatar-initial rounded-circle bg-pink d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
																		<i class="fas fa-female text-white"></i>
																	</span>
																</div>
																<div>
																	<strong>
																		<?php if ($row->jenis_perkara_nama == 'Cerai Talak'): ?>
																			<?= $row->nama_t ?>
																		<?php elseif ($row->jenis_perkara_nama == 'Cerai Gugat'): ?>
																			<?= $row->nama_p ?>
																		<?php endif; ?>
																	</strong>

																	<div class="mt-1">
																		<?php
																		// Tanggal penyerahan ke istri
																		$tanggal_ke_istri = null;

																		if ($row->jenis_perkara_nama == 'Cerai Talak' && !empty($row->tgl_AC_T)) {
																			$tanggal_ke_istri = $row->tgl_AC_T;
																		} elseif ($row->jenis_perkara_nama == 'Cerai Gugat' && !empty($row->tgl_AC_P)) {
																			$tanggal_ke_istri = $row->tgl_AC_P;
																		}

																		if (!empty($tanggal_ke_istri)):
																		?>
																			<div class="badge badge-success">
																				<i class="fas fa-check-circle mr-1"></i>
																				Telah Menerima Akta Cerai
																			</div>
																			<div class="small text-muted mt-1">
																				<i class="far fa-calendar-alt mr-1"></i>
																				<?= date('d-m-Y', strtotime($tanggal_ke_istri)) ?>
																			</div>
																		<?php else: ?>
																			<span class="badge badge-danger">
																				<i class="fas fa-times-circle mr-1"></i>
																				Belum Menerima Akta Cerai
																			</span>
																		<?php endif; ?>
																	</div>
																</div>
															</div>
														</td>
														<td class="text-center">
															<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-detail-<?= $no ?>">
																<i class="fas fa-eye"></i>
															</button>

															<!-- Modal Detail -->
															<div class="modal fade" id="modal-detail-<?= $no ?>">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-header bg-info">
																			<h5 class="modal-title">Detail Akta Cerai <?= $row->nomor_perkara ?></h5>
																			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																			</button>
																		</div>
																		<div class="modal-body">
																			<div class="timeline timeline-inverse">
																				<!-- Pendaftaran -->
																				<div>
																					<i class="fas fa-envelope bg-primary"></i>
																					<div class="timeline-item">
																						<h3 class="timeline-header"><strong>Pendaftaran Perkara</strong></h3>
																						<div class="timeline-body">
																							Nomor Akta Cerai: <strong><?= $row->nomor_akta_cerai ?></strong>
																						</div>
																					</div>
																				</div>

																				<!-- Putusan -->
																				<?php if (!empty($row->tanggal_putusan)): ?>
																					<div>
																						<i class="fas fa-gavel bg-success"></i>
																						<div class="timeline-item">
																							<span class="time"><i class="far fa-calendar"></i> <?= date('d-m-Y', strtotime($row->tanggal_putusan)) ?></span>
																							<h3 class="timeline-header"><strong>Putusan</strong></h3>
																						</div>
																					</div>
																				<?php endif; ?>

																				<!-- Ikrar Talak (jika ada) -->
																				<?php if (!empty($row->tgl_ikrar_talak)): ?>
																					<div>
																						<i class="fas fa-microphone bg-warning"></i>
																						<div class="timeline-item">
																							<span class="time"><i class="far fa-calendar"></i> <?= date('d-m-Y', strtotime($row->tgl_ikrar_talak)) ?></span>
																							<h3 class="timeline-header"><strong>Ikrar Talak</strong></h3>
																						</div>
																					</div>
																				<?php endif; ?>

																				<!-- BHT -->
																				<?php if (!empty($row->tanggal_bht)): ?>
																					<div>
																						<i class="fas fa-balance-scale bg-info"></i>
																						<div class="timeline-item">
																							<span class="time"><i class="far fa-calendar"></i> <?= date('d-m-Y', strtotime($row->tanggal_bht)) ?></span>
																							<h3 class="timeline-header"><strong>Berkekuatan Hukum Tetap</strong></h3>
																						</div>
																					</div>
																				<?php endif; ?>

																				<!-- Penyerahan kepada Suami -->
																				<?php
																				$tanggal_ke_suami = null;
																				if ($row->jenis_perkara_nama == 'Cerai Talak' && !empty($row->tgl_AC_P)) {
																					$tanggal_ke_suami = $row->tgl_AC_P;
																				} elseif ($row->jenis_perkara_nama == 'Cerai Gugat' && !empty($row->tgl_AC_T)) {
																					$tanggal_ke_suami = $row->tgl_AC_T;
																				}

																				if (!empty($tanggal_ke_suami)):
																				?>
																					<div>
																						<i class="fas fa-male bg-indigo"></i>
																						<div class="timeline-item">
																							<span class="time"><i class="far fa-calendar"></i> <?= date('d-m-Y', strtotime($tanggal_ke_suami)) ?></span>
																							<h3 class="timeline-header"><strong>Penyerahan kepada Mantan Suami</strong></h3>
																							<div class="timeline-body">
																								Akta cerai telah diserahkan kepada
																								<strong>
																									<?php if ($row->jenis_perkara_nama == 'Cerai Talak'): ?>
																										<?= $row->nama_p ?>
																									<?php else: ?>
																										<?= $row->nama_t ?>
																									<?php endif; ?>
																								</strong>
																							</div>
																						</div>
																					</div>
																				<?php endif; ?>

																				<!-- Penyerahan kepada Istri -->
																				<?php
																				$tanggal_ke_istri = null;
																				if ($row->jenis_perkara_nama == 'Cerai Talak' && !empty($row->tgl_AC_T)) {
																					$tanggal_ke_istri = $row->tgl_AC_T;
																				} elseif ($row->jenis_perkara_nama == 'Cerai Gugat' && !empty($row->tgl_AC_P)) {
																					$tanggal_ke_istri = $row->tgl_AC_P;
																				}

																				if (!empty($tanggal_ke_istri)):
																				?>
																					<div>
																						<i class="fas fa-female bg-pink"></i>
																						<div class="timeline-item">
																							<span class="time"><i class="far fa-calendar"></i> <?= date('d-m-Y', strtotime($tanggal_ke_istri)) ?></span>
																							<h3 class="timeline-header"><strong>Penyerahan kepada Mantan Istri</strong></h3>
																							<div class="timeline-body">
																								Akta cerai telah diserahkan kepada
																								<strong>
																									<?php if ($row->jenis_perkara_nama == 'Cerai Talak'): ?>
																										<?= $row->nama_t ?>
																									<?php else: ?>
																										<?= $row->nama_p ?>
																									<?php endif; ?>
																								</strong>
																							</div>
																						</div>
																					</div>
																				<?php endif; ?>

																				<div>
																					<i class="far fa-clock bg-gray"></i>
																				</div>
																			</div>
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
																		</div>
																	</div>
																</div>
															</div>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								<?php else: ?>
									<div class="alert alert-info m-3">
										<h5><i class="icon fas fa-info"></i> Informasi</h5>
										Tidak ada data Penyerahan Akta Cerai pada periode yang dipilih.
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</section>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			// Handle report type toggle
			function toggleBulanField() {
				if ($("#laporan_tahunan").is(":checked")) {
					$("#bulan_container").hide();
					$("#lap_bulan").prop("required", false);
				} else {
					$("#bulan_container").show();
					$("#lap_bulan").prop("required", true);
				}
			}

			// Initial state
			toggleBulanField();

			// Listen for changes
			$("input[name='jenis_laporan']").change(function() {
				toggleBulanField();
			});

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

			// Manually bind export buttons
			$(".export-excel").click(function(e) {
				e.preventDefault();
				$(".buttons-excel").click();
			});

			$(".export-pdf").click(function(e) {
				e.preventDefault();
				$(".buttons-pdf").click();
			});

			// Enable tooltips
			$('[data-toggle="tooltip"]').tooltip();
		});
	</script>
</body>