<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<!-- Content Header -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-history mr-2"></i> Timeline Perkara</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item"><a href="javascript:history.back()">Kembali</a></li>
								<li class="breadcrumb-item active">Timeline Perkara</li>
							</ol>
						</div>
					</div>
				</div>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<!-- Action buttons -->
					<div class="row mb-3">
						<div class="col-md-12">
							<a href="javascript:history.back()" class="btn btn-secondary">
								<i class="fas fa-arrow-left mr-1"></i> Kembali
							</a>
							<?php if (isset($perkara->perkara_id)): ?>
								<a href="<?= site_url('Odp/detail/' . $perkara->perkara_id) ?>" class="btn btn-info ml-2">
									<i class="fas fa-info-circle mr-1"></i> Detail Perkara
								</a>
								<button class="btn btn-success ml-2" id="printTimeline">
									<i class="fas fa-print mr-1"></i> Cetak Timeline
								</button>
								<button class="btn btn-primary ml-2" id="exportExcel">
									<i class="fas fa-file-excel mr-1"></i> Export Excel
								</button>
							<?php endif; ?>
						</div>
					</div>

					<!-- Case Info Card -->
					<div class="card card-primary card-outline">
						<div class="card-header">
							<h3 class="card-title">
								<i class="fas fa-info-circle mr-1"></i>
								Informasi Perkara
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
									<dl class="row">
										<dt class="col-sm-4">Nomor Perkara</dt>
										<dd class="col-sm-8"><?= isset($perkara->nomor_perkara) ? $perkara->nomor_perkara : '-' ?></dd>

										<dt class="col-sm-4">Tanggal Daftar</dt>
										<dd class="col-sm-8"><?= isset($perkara->tanggal_pendaftaran) ? date('d-m-Y', strtotime($perkara->tanggal_pendaftaran)) : '-' ?></dd>

										<dt class="col-sm-4">Jenis Perkara</dt>
										<dd class="col-sm-8"><?= isset($perkara->jenis_perkara_nama) ? $perkara->jenis_perkara_nama : '-' ?></dd>
									</dl>
								</div>
								<div class="col-md-6">
									<dl class="row">
										<dt class="col-sm-4">Status Perkara</dt>
										<dd class="col-sm-8">
											<?php if (isset($perkara->status_perkara)): ?>
												<?php if ($perkara->status_perkara == 'Putus'): ?>
													<span class="badge badge-success">Putus</span>
												<?php elseif ($perkara->status_perkara == 'Proses'): ?>
													<span class="badge badge-info">Proses</span>
												<?php else: ?>
													<span class="badge badge-secondary"><?= $perkara->status_perkara ?></span>
												<?php endif; ?>
											<?php else: ?>
												<span class="badge badge-secondary">Tidak diketahui</span>
											<?php endif; ?>
										</dd>

										<dt class="col-sm-4">Pihak</dt>
										<dd class="col-sm-8">
											<span class="text-primary"><?= isset($perkara->nama_p) ? $perkara->nama_p : '-' ?></span>
											<i class="fas fa-exchange-alt mx-2"></i>
											<span class="text-warning"><?= isset($perkara->nama_t) ? $perkara->nama_t : '-' ?></span>
										</dd>

										<dt class="col-sm-4">Durasi Perkara</dt>
										<dd class="col-sm-8">
											<?php
											if (isset($perkara->tanggal_pendaftaran) && isset($perkara->tanggal_putusan)):
												$date1 = new DateTime($perkara->tanggal_pendaftaran);
												$date2 = new DateTime($perkara->tanggal_putusan);
												$interval = $date1->diff($date2);
												echo $interval->days . ' hari';
											else:
												echo '-';
											endif;
											?>
										</dd>
									</dl>
								</div>
							</div>
						</div>
					</div>

					<!-- Timeline Card -->
					<div class="card card-success card-outline">
						<div class="card-header">
							<h3 class="card-title">
								<i class="fas fa-history mr-1"></i>
								Timeline Proses Perkara
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
							<?php if (!empty($timeline)): ?>
								<div class="timeline">
									<?php
									$currentDate = null;
									foreach ($timeline as $item):
										$itemDate = date('d M Y', strtotime($item->tanggal));

										// Add date label when date changes
										if ($currentDate !== $itemDate):
											$currentDate = $itemDate;
									?>
											<div class="time-label">
												<span class="bg-primary"><?= $itemDate ?></span>
											</div>
										<?php endif; ?>

										<div>
											<?php
											// Choose icon based on event type
											switch ($item->jenis_event) {
												case 'pendaftaran':
													echo '<i class="fas fa-file-alt bg-blue"></i>';
													break;
												case 'penetapan_hakim':
													echo '<i class="fas fa-gavel bg-purple"></i>';
													break;
												case 'sidang':
													echo '<i class="fas fa-users bg-yellow"></i>';
													break;
												case 'putusan':
													echo '<i class="fas fa-balance-scale bg-green"></i>';
													break;
												case 'minutasi':
													echo '<i class="fas fa-file-signature bg-teal"></i>';
													break;
												case 'publikasi':
													echo '<i class="fas fa-cloud-upload-alt bg-info"></i>';
													break;
												case 'akta_cerai':
													echo '<i class="fas fa-file-contract bg-danger"></i>';
													break;
												default:
													echo '<i class="fas fa-circle bg-gray"></i>';
													break;
											}
											?>

											<div class="timeline-item">
												<span class="time"><i class="far fa-clock"></i> <?= date('H:i', strtotime($item->tanggal)) ?></span>
												<h3 class="timeline-header"><strong><?= $item->judul ?></strong></h3>

												<div class="timeline-body">
													<?= $item->deskripsi ?>

													<?php if (isset($item->keterangan) && !empty($item->keterangan)): ?>
														<div class="mt-2 text-muted small">
															<i class="fas fa-info-circle"></i> <?= $item->keterangan ?>
														</div>
													<?php endif; ?>
												</div>

												<?php if (isset($item->link) && !empty($item->link)): ?>
													<div class="timeline-footer">
														<a href="<?= $item->link ?>" class="btn btn-sm btn-info">
															<i class="fas fa-eye mr-1"></i> Lihat Detail
														</a>
													</div>
												<?php endif; ?>
											</div>
										</div>
									<?php endforeach; ?>

									<!-- End marker -->
									<div>
										<i class="far fa-clock bg-gray"></i>
									</div>
								</div>
							<?php else: ?>
								<div class="alert alert-info">
									<i class="fas fa-info-circle mr-1"></i> Tidak ada data timeline untuk perkara ini.
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			// Print timeline
			$('#printTimeline').click(function() {
				window.print();
			});

			// Initialize tooltips
			$('[data-toggle="tooltip"]').tooltip();
		});
	</script>
</body>