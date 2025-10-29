<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-green elevation-4">
	<!-- Brand Logo -->
	<a href="<?php echo site_url('home') ?>" class="brand-link navbar-green">
		<img src="<?php echo base_url() ?>assets/dist/img/logo-mahkamah-agung.png" alt="Logo PA Amuntai" class="brand-image img-circle elevation-2" style="opacity: .8">
		<span class="brand-text font-weight-light">SIPP PA Amuntai</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="<?php echo base_url() ?>assets/dist/img/Logo PA Amuntai - Trans.png" class="img-circle elevation-2" alt="User Image">
			</div>
			<div class="info">
				<a href="#" class="d-block">Administrator</a>
				<span class="badge badge-success">Online</span>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Dashboard -->
				<li class="nav-item">
					<a href="<?= site_url('home') ?>" class="nav-link <?= $this->uri->segment(1) == '' || $this->uri->segment(1) == 'home' ? 'active' : '' ?>">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>Dashboard</p>
					</a>
				</li>

				<li class="nav-header">MANAJEMEN PERKARA</li>

				<!-- Laporan Kegiatan Hakim -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-gavel"></i>
						<p>
							Laporan Monitoring BHT
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">

						<li class="nav-item">
							<a href="<?php echo site_url('Bht_putus') ?>" class="nav-link">
								<i class="fas fa-file-signature nav-icon"></i>
								<p>BHT Perkara Putus</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Bht_putus_2') ?>" class="nav-link">
								<i class="fas fa-file-contract nav-icon"></i>
								<p>BHT Perkara Putus 2</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Bht_putus_3') ?>" class="nav-link">
								<i class="fas fa-file-invoice nav-icon"></i>
								<p>BHT Perkara Putus 3</p>
							</a>
						</li>

					</ul>
				</li>

				<!-- BHT Reminder System -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-bell text-warning"></i>
						<p>
							Sistem Pengingat BHT
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?php echo site_url('bht_reminder') ?>" class="nav-link <?= $this->uri->segment(1) == 'bht_reminder' ? 'active' : '' ?>">
								<i class="fas fa-clock nav-icon text-danger"></i>
								<p>Dashboard Pengingat</p>
								<span class="badge badge-danger right" id="reminder-count">0</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('api/bht/reminders') ?>" class="nav-link" target="_blank">
								<i class="fas fa-list nav-icon"></i>
								<p>Data Pengingat (API)</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('bht_reminder/export_report?format=excel') ?>" class="nav-link">
								<i class="fas fa-file-excel nav-icon text-success"></i>
								<p>Export Laporan Excel</p>
							</a>
						</li>
					</ul>
				</li>

				<!-- Data & Pengembangan -->

				<li class="nav-header">DATA &amp; PENGEMBANGAN</li>

				<!-- Dashboard BHT Original -->
				<li class="nav-item">
					<a href="<?php echo site_url('dashboard_bht') ?>" class="nav-link <?= $this->uri->segment(1) == 'dashboard_bht' ? 'active' : '' ?>">
						<i class="nav-icon fas fa-chart-line text-info"></i>
						<p>Dashboard BHT Visual</p>
					</a>
				</li>

				<!-- Testing & Development (Show only in development) -->
				<?php if (ENVIRONMENT === 'development'): ?>
					<li class="nav-header">TESTING & DEBUG</li>
					<li class="nav-item">
						<a href="#" class="nav-link">
							<i class="nav-icon fas fa-bug text-warning"></i>
							<p>
								Development Tools
								<i class="fas fa-angle-left right"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item">
								<a href="<?php echo site_url('test/bht') ?>" class="nav-link" target="_blank">
									<i class="fas fa-vial nav-icon"></i>
									<p>Test BHT System</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo site_url('test/bht/template') ?>" class="nav-link" target="_blank">
									<i class="fas fa-code nav-icon"></i>
									<p>Test Template System</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo base_url('README_BHT_SYSTEM.md') ?>" class="nav-link" target="_blank">
									<i class="fas fa-book nav-icon text-primary"></i>
									<p>Dokumentasi System</p>
								</a>
							</li>
						</ul>
					</li>
				<?php endif; ?>

			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>

<!-- JavaScript untuk update badge counter pengingat -->
<script>
	$(document).ready(function() {
		// Update reminder counter saat halaman dimuat
		updateReminderCounter();

		// Update setiap 2 menit
		setInterval(updateReminderCounter, 2 * 60 * 1000);
	});

	function updateReminderCounter() {
		$.ajax({
			url: '<?= base_url("api/bht/reminders") ?>',
			method: 'GET',
			data: {
				status: 'URGENT'
			}, // Hanya hitung yang urgent
			dataType: 'json',
			success: function(response) {
				if (response.success && response.count !== undefined) {
					const badge = $('#reminder-count');
					const count = parseInt(response.count);

					if (count > 0) {
						badge.text(count);
						badge.removeClass('badge-secondary').addClass('badge-danger');

						// Animate badge jika ada urgent reminders
						badge.addClass('badge-pulse');
					} else {
						badge.text('0');
						badge.removeClass('badge-danger badge-pulse').addClass('badge-secondary');
					}
				}
			},
			error: function() {
				// Jika error, set badge ke tanda tanya
				$('#reminder-count').text('?').removeClass('badge-danger').addClass('badge-warning');
			}
		});
	}
</script>

<!-- CSS untuk animasi badge -->
<style>
	.badge-pulse {
		animation: pulse-badge 2s infinite;
	}

	@keyframes pulse-badge {
		0% {
			transform: scale(1);
			opacity: 1;
		}

		50% {
			transform: scale(1.1);
			opacity: 0.7;
		}

		100% {
			transform: scale(1);
			opacity: 1;
		}
	}

	/* Styling khusus untuk menu BHT */
	.nav-sidebar .nav-item>.nav-link.active .nav-icon {
		color: #ffc107 !important;
	}

	/* Highlight untuk menu pengingat */
	.nav-sidebar .nav-item>.nav-link:hover .text-warning {
		color: #fff !important;
	}
</style>
