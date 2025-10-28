<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-certificate mr-2"></i> Itsbat Nikah</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item">Permohonan</li>
								<li class="breadcrumb-item active">Itsbat Nikah</li>
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
						</div>
						<div class="card-body">
							<form action="<?php echo base_url() ?>index.php/Itsbat" method="POST" class="form-horizontal">
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Laporan Bulan:</label>
									<div class="col-sm-4">
										<select name="lap_bulan" class="form-control select2" required="">
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

									<label class="col-sm-2 col-form-label">Tahun:</label>
									<div class="col-sm-4">
										<select name="lap_tahun" class="form-control select2" required="">
											<?php
											$currentYear = date('Y');
											for ($year = 2016; $year <= $currentYear + 1; $year++) {
												$selected = (isset($_POST['lap_tahun']) && $_POST['lap_tahun'] == $year)
													? 'selected'
													: ($year == $currentYear && !isset($_POST['lap_tahun']) ? 'selected' : '');
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
						<!-- Data Card -->
						<div class="card">
							<div class="card-header bg-success">
								<h3 class="card-title">
									<i class="fas fa-certificate mr-1"></i>
									Data Itsbat Nikah -
									<?php
									echo isset($months[$_POST['lap_bulan']]) ? $months[$_POST['lap_bulan']] : '';
									echo " ";
									echo isset($_POST['lap_tahun']) ? $_POST['lap_tahun'] : '';
									?>
								</h3>
								<div class="card-tools">
									<button type="button" class="btn btn-tool" data-card-widget="collapse">
										<i class="fas fa-minus"></i>
									</button>
									<button type="button" class="btn btn-tool" data-card-widget="maximize">
										<i class="fas fa-expand"></i>
									</button>
								</div>
							</div>
							<div class="card-body">
								<?php if (!empty($datafilter)): ?>
									<div class="table-responsive">
										<table id="example1" class="table table-bordered table-striped table-hover">
											<thead class="bg-info">
												<tr>
													<th width="3%" class="text-center">No</th>
													<th width="12%">Nomor Perkara</th>
													<th width="10%">Tanggal Daftar</th>
													<th width="10%">Tanggal Putus</th>
													<th width="10%">Status Putusan</th>
													<th width="12%">Tanggal Nikah</th>
													<th width="15%">Pemohon I</th>
													<th width="15%">Pemohon II</th>
													<th width="8%">Durasi Nikah</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$no = 1;
												foreach ($datafilter as $row):
													// Extract complete marriage date
													$marriageDate = null;
													$marriageYear = null;
													$marriageMonth = null;
													$marriageDay = null;
													$marriageInfo = "";
													$duration = null;

													if (!empty($row->tahun_nikah)) {
														// Try to find a full date pattern like DD-MM-YYYY or DD/MM/YYYY
														preg_match('/\b\d{1,2}[\/-]\d{1,2}[\/-](19|20)\d{2}\b/', $row->tahun_nikah, $fullDateMatches);

														if (!empty($fullDateMatches[0])) {
															// Try to parse the full date
															$dateStr = str_replace('/', '-', $fullDateMatches[0]);
															$marriageDate = date_create_from_format('d-m-Y', $dateStr);

															if ($marriageDate) {
																$marriageYear = date_format($marriageDate, 'Y');
																$marriageMonth = date_format($marriageDate, 'm');
																$marriageDay = date_format($marriageDate, 'd');
																$marriageInfo = date_format($marriageDate, 'd-m-Y');
															}
														} else {
															// If no full date found, try to find just the year
															preg_match('/\b(19|20)\d{2}\b/', $row->tahun_nikah, $yearMatches);
															if (!empty($yearMatches[0])) {
																$marriageYear = (int)$yearMatches[0];
																$marriageInfo = "Tahun " . $marriageYear;

																// Also try to find month if available
																preg_match('/\b(?:januari|februari|maret|april|mei|juni|juli|agustus|september|oktober|november|desember)\b/i', $row->tahun_nikah, $monthMatches);
																if (!empty($monthMatches[0])) {
																	$monthNames = array(
																		'januari' => '01',
																		'februari' => '02',
																		'maret' => '03',
																		'april' => '04',
																		'mei' => '05',
																		'juni' => '06',
																		'juli' => '07',
																		'agustus' => '08',
																		'september' => '09',
																		'oktober' => '10',
																		'november' => '11',
																		'desember' => '12'
																	);
																	$monthKey = strtolower($monthMatches[0]);
																	if (array_key_exists($monthKey, $monthNames)) {
																		$marriageMonth = $monthNames[$monthKey];
																		$marriageInfo = "Bulan " . ucfirst($monthMatches[0]) . " " . $marriageYear;
																	}
																}
															}
														}

														// Calculate marriage duration
														if ($marriageYear) {
															$currentYear = date('Y');
															$duration = $currentYear - $marriageYear;
														}
													}
												?>
													<tr>
														<td class="text-center"><?= $no++ ?></td>
														<td>
															<span class="badge badge-primary"><?= $row->nomor_perkara ?></span>
														</td>
														<td><?= date('d-m-Y', strtotime($row->tanggal_pendaftaran)) ?></td>
														<td><?= !empty($row->tanggal_putusan) ? date('d-m-Y', strtotime($row->tanggal_putusan)) : '-' ?></td>
														<td>
															<?php if (!empty($row->jenis_putusan)): ?>
																<span class="badge badge-success"><?= $row->jenis_putusan ?></span>
															<?php else: ?>
																<span class="badge badge-warning">Belum Putus</span>
															<?php endif; ?>
														</td>
														<td>
															<?php if (!empty($marriageInfo)): ?>
																<span class="badge badge-info"><?= $marriageInfo ?></span>
																<?php if (strlen($row->tahun_nikah) > 50): ?>
																	<span class="d-block mt-1 small text-muted">
																		<a href="#" data-toggle="tooltip" title="<?= htmlspecialchars(substr($row->tahun_nikah, 0, 200)) ?>...">
																			<i class="fas fa-info-circle"></i> Detail Posita
																		</a>
																	</span>
																<?php endif; ?>
															<?php else: ?>
																-
															<?php endif; ?>
														</td>
														<td>
															<strong><?= $row->nama_p1 ?></strong>
															<?php if (!empty($row->tanggal_lahir_p1)): ?>
																<div class="d-block mt-1">
																	<span class="badge badge-light">
																		<i class="far fa-calendar-alt mr-1"></i>
																		Lahir: <?= date('d-m-Y', strtotime($row->tanggal_lahir_p1)) ?>
																	</span>
																	<span class="badge badge-secondary">
																		<?= $row->usia_p1 ?> tahun
																	</span>
																</div>
															<?php endif; ?>
														</td>
														<td>
															<strong><?= $row->nama_p2 ?></strong>
															<?php if (!empty($row->tanggal_lahir_p2)): ?>
																<div class="d-block mt-1">
																	<span class="badge badge-light">
																		<i class="far fa-calendar-alt mr-1"></i>
																		Lahir: <?= date('d-m-Y', strtotime($row->tanggal_lahir_p2)) ?>
																	</span>
																	<span class="badge badge-secondary">
																		<?= $row->usia_p2 ?> tahun
																	</span>
																</div>
															<?php endif; ?>
														</td>
														<td class="text-center">
															<?php if (!empty($duration)): ?>
																<span class="badge badge-dark">
																	<?= $duration ?> tahun
																</span>
															<?php else: ?>
																-
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								<?php else: ?>
									<div class="alert alert-info">
										<h5><i class="icon fas fa-info"></i> Informasi</h5>
										Tidak ada data Itsbat Nikah pada periode yang dipilih.
									</div>
								<?php endif; ?>
							</div>

							<?php if (!empty($datafilter)): ?>
								<div class="card-footer">
									<div class="row">
										<div class="col-md-4">
											<div class="info-box bg-light">
												<div class="info-box-content">
													<span class="info-box-text text-center text-muted">Total Perkara</span>
													<span class="info-box-number text-center text-muted mb-0"><?= count($datafilter) ?></span>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="info-box bg-light">
												<div class="info-box-content">
													<span class="info-box-text text-center text-muted">Rata-rata Usia Pemohon I</span>
													<span class="info-box-number text-center text-muted mb-0">
														<?php
														$totalUsia = 0;
														foreach ($datafilter as $row) {
															$totalUsia += $row->usia_p1;
														}
														echo round($totalUsia / count($datafilter), 1) . ' tahun';
														?>
													</span>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="info-box bg-light">
												<div class="info-box-content">
													<span class="info-box-text text-center text-muted">Rata-rata Usia Pemohon II</span>
													<span class="info-box-number text-center text-muted mb-0">
														<?php
														$totalUsia = 0;
														foreach ($datafilter as $row) {
															$totalUsia += $row->usia_p2;
														}
														echo round($totalUsia / count($datafilter), 1) . ' tahun';
														?>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</section>
		</div>
	</div>
	<!-- ./wrapper -->




	<!-- Page specific script -->
	<!-- <script>
  $(function () {
    $("#DataTable").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#DataTable').DataTable({
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
 -->
</body>

</html>
