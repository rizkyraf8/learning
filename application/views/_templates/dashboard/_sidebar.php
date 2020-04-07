<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">

		<!-- Sidebar user panel (optional) -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?=base_url()?>assets/dist/img/user1.png" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p><?=$user->username?></p>
				<small><?=$user->email?></small>
			</div>
		</div>
		
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">MAIN MENU</li>
			<!-- Optionally, you can add icons to the links -->
			<?php 
			$page = $this->uri->segment(1);
			$master = ["jenjangkelas", "kelas", "matpel", "guru", "siswa"];
			$relasi = ["kelasguru", "jenjangkelasmatpel"];
			$users = ["users"];
			?>
			<li class="<?= $page === 'dashboard' ? "active" : "" ?>"><a href="<?=base_url('dashboard')?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
			<?php if($this->ion_auth->is_admin()) : ?>
			<li class="treeview <?= in_array($page, $master)  ? "active menu-open" : ""  ?>">
				<a href="#"><i class="fa fa-folder"></i> <span>Data Master</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li class="<?=$page==='jenjangkelas'?"active":""?>">
						<a href="<?=base_url('jenjangkelas')?>">
							<i class="fa fa-circle-o"></i> 
							Master Jenjang Kelas
						</a>
					</li>
					<li class="<?=$page==='kelas'?"active":""?>">
						<a href="<?=base_url('kelas')?>">
							<i class="fa fa-circle-o"></i>
							Master Kelas
						</a>
					</li>
					<li class="<?=$page==='matpel'?"active":""?>">
						<a href="<?=base_url('matpel')?>">
							<i class="fa fa-circle-o"></i>
							Master Mata Pelajaran
						</a>
					</li>
					<li class="<?=$page==='guru'?"active":""?>">
						<a href="<?=base_url('guru')?>">
							<i class="fa fa-circle-o"></i>
							Master Guru
						</a>
					</li>
					<li class="<?=$page==='siswa'?"active":""?>">
						<a href="<?=base_url('siswa')?>">
							<i class="fa fa-circle-o"></i>
							Master Siswa
						</a>
					</li>
				</ul>
			</li>
			<li class="treeview <?= in_array($page, $relasi)  ? "active menu-open" : ""  ?>">
				<a href="#"><i class="fa fa-link"></i> <span>Relasi</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li class="<?=$page==='kelasguru'?"active":""?>">
						<a href="<?=base_url('kelasguru')?>">
							<i class="fa fa-circle-o"></i>
							Kelas - Guru
						</a>
					</li>
					<li class="<?=$page==='jenjangkelasmatpel'?"active":""?>">
						<a href="<?=base_url('jenjangkelasmatpel')?>">
							<i class="fa fa-circle-o"></i>
							Jenjang - Mata Pelajaran
						</a>
					</li>
				</ul>
			</li>
			<?php endif; ?>
			<?php if( $this->ion_auth->is_admin() || $this->ion_auth->in_group('guru') ) : ?>
			<li class="<?=$page==='soal'?"active":""?>">
				<a href="<?=base_url('soal')?>" rel="noopener noreferrer">
					<i class="fa fa-file-text-o"></i> <span>Bank Soal</span>
				</a>
			</li>
			<?php endif; ?>
			<?php if( $this->ion_auth->is_admin()) : ?>
			<li class="<?=$page==='banksoal'?"active":""?>">
				<a href="<?=base_url('banksoal')?>" rel="noopener noreferrer">
					<i class="fa fa-file-text-o"></i> <span>Print Soal</span>
				</a>
			</li>
			<?php endif; ?>
			<?php if( $this->ion_auth->in_group('guru') ) : ?>
			<li class="<?=$page==='ujian'?"active":""?>">
				<a href="<?=base_url('ujian/master')?>" rel="noopener noreferrer">
					<i class="fa fa-chrome"></i> <span>Ujian</span>
				</a>
			</li>
			<?php endif; ?>
			<?php if( $this->ion_auth->in_group('siswa') ) : ?>
			<li class="<?=$page==='ujian'?"active":""?>">
				<a href="<?=base_url('ujian/list')?>" rel="noopener noreferrer">
					<i class="fa fa-chrome"></i> <span>Ujian</span>
				</a>
			</li>
			<?php endif; ?>
			<?php if( !$this->ion_auth->in_group('siswa') ) : ?>
			<li class="header">REPORTS</li>
			<li class="<?=$page==='hasilujian'?"active":""?>">
				<a href="<?=base_url('hasilujian')?>" rel="noopener noreferrer">
					<i class="fa fa-file"></i> <span>Hasil Ujian</span>
				</a>
			</li>
			<?php endif; ?>
			<?php if($this->ion_auth->is_admin()) : ?>
			<li class="header">ADMINISTRATOR</li>
			<li class="<?=$page==='users'?"active":""?>">
				<a href="<?=base_url('users')?>" rel="noopener noreferrer">
					<i class="fa fa-users"></i> <span>User Management</span>
				</a>
			</li>
<!-- 			<li class="<?=$page==='settings'?"active":""?>">
				<a href="<?=base_url('settings')?>" rel="noopener noreferrer">
					<i class="fa fa-cog"></i> <span>Settings</span>
				</a>
			</li> -->
			<?php endif; ?>
		</ul>

	</section>
	<!-- /.sidebar -->
</aside>