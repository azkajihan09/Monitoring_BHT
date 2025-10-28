<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-history mr-2"></i> Timeline Perkara E-Court</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item"><a href="<?= site_url('Ecourt_monitoring') ?>">Monitoring E-Court</a></li>
								<li class="breadcrumb-item active">Timeline</li>
							</ol>
						</div>
					</div>
				</div>
			</section>

			<section class="content">
				<div class="container-fluid">
					<!-- Case Info Card -->
					<div class="card card-primary card-outline">
						<div class="card-header">
							<h3 class="card-title">
								<i class="fas fa-info-circle mr-1"></i>
								Detail Perkara
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
									<table class="table table-bordered table-striped">
										<tr>
											<th style="width: 30%">Nomor Perkara</th>
											<td><?= isset($case->nomor_perkara) && !empty($case->nomor_perkara) ? $case->nomor_perkara : 'Belum Teregistrasi' ?></td>
										</tr>
										<tr>
											<th>Jenis Perkara</th>
											<td><?= isset($case->jenis_perkara_nama) && !empty($case->jenis_perkara_nama) ? $case->jenis_perkara_nama : '-' ?></td>
										</tr>
										<tr>
											<th>Tanggal Daftar E-Court</th>
											<td>
												<?= !empty($case->ecourt_reg_date) ? date('d-m-Y', strtotime($case->ecourt_reg_date)) : '-' ?>
											</td>
										</tr>
										<tr>
											<th>Status Pembayaran</th>
											<td>
												<?php if (isset($case->status_pembayaran) && $case->status_pembayaran == '1'): ?>
													<span class="badge badge-success">Lunas</span>
													<small class="ml-2">
														<?= !empty($case->tanggal_bayar) ? date('d-m-Y', strtotime($case->tanggal_bayar)) : '' ?>
													</small>
												<?php else: ?>
													<span class="badge badge-warning">Menunggu Pembayaran</span>
												<?php endif; ?>
											</td>
										</tr>
									</table>
								</div>
								<div class="col-md-6">
									<table class="table table-bordered table-striped">
										<tr>
											<th style="width: 30%">Status Proses</th>
											<td>
												<?php
												if (empty($case->nomor_perkara)) {
													echo '<span class="badge badge-warning">Belum Teregistrasi</span>';
												} elseif (empty($case->penetapan_majelis_hakim)) {
													echo '<span class="badge badge-info">Menunggu PMH</span>';
												} elseif (empty($case->tanggal_putusan)) {
													echo '<span class="badge badge-primary">Menunggu Putusan</span>';
												} elseif (empty($case->dokumen_upload_date)) {
													echo '<span class="badge badge-success">Menunggu Upload Dokumen</span>';
												} else {
													echo '<span class="badge badge-success">Selesai</span>';
												}
												?>
											</td>
										</tr>
										<tr>
											<th>Majelis Hakim</th>
											<td><?= isset($case->majelis_hakim_text) && !empty($case->majelis_hakim_text) ? strip_tags($case->majelis_hakim_text) : '-' ?></td>
										</tr>
										<tr>
											<th>Panitera Pengganti</th>
											<td><?= isset($case->panitera_pengganti_text) && !empty($case->panitera_pengganti_text) ? strip_tags($case->panitera_pengganti_text) : '-' ?></td>
										</tr>
										<tr>
											<th>Tanggal Putusan</th>
											<td><?= !empty($case->tanggal_putusan) ? date('d-m-Y', strtotime($case->tanggal_putusan)) : '-' ?></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>

					<!-- Timeline Card -->
					<div class="card card-outline card-info">
						<div class="card-header">
							<h3 class="card-title">
								<i class="fas fa-history mr-1"></i>
								Timeline Perkara
							</h3>
							<div class="card-tools">
								<a href="<?= site_url('Ecourt_monitoring') ?>" class="btn btn-sm btn-default">
									<i class="fas fa-arrow-left mr-1"></i> Kembali
								</a>
								<button type="button" class="btn btn-tool ml-2" data-card-widget="collapse">
									<i class="fas fa-minus"></i>
								</button>
							</div>
						</div>
						<div class="card-body">
							<!-- Timeline -->
							<div class="timeline">
								<?php if (!empty($timeline)): ?>
									<?php foreach ($timeline as $index => $item): ?>
										<!-- Timeline Item -->
										<div>
											<i class="fas <?= $item['icon'] ?> <?= isset($item['color']) && !empty($item['color']) ? $item['color'] : 'bg-blue' ?>"></i>
											<div class="timeline-item">
												<span class="time"><i class="fas fa-clock"></i> <?= date('d-m-Y', strtotime($item['date'])) ?></span>
												<h3 class="timeline-header"><strong><?= $item['event'] ?></strong></h3>
												<div class="timeline-body">
													<?= $item['description'] ?>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								<?php else: ?>
									<div>
										<i class="fas fa-exclamation-circle bg-warning"></i>
										<div class="timeline-item">
											<h3 class="timeline-header"><strong>Tidak ada data timeline</strong></h3>
											<div class="timeline-body">
												Belum ada tahapan perkara yang tercatat dalam sistem.
											</div>
										</div>
									</div>
								<?php endif; ?>
								<div>
									<i class="fas fa-clock bg-gray"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</body>