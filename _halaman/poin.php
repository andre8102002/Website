<?php  
$title="Poin";
  $nama_judul=$title;
  $url='poin';
  $fileJs='poinJs';

  $kategori=(isset($_GET['kategori']))?$_GET['kategori']:'';
  $tahun=(isset($_GET['tahun']))?$_GET['tahun']:'semua';
  ?>
<?=isi_buka($title)?>
<form>
		<div class="row">
			<?=input_hidden('halaman',$url)?>
			<div class="col-md-3">
				<?=input_text('kategori',$kategori)?>
			</div>
			<div class="col-md-3">
				<?php
					$op=null;
					$op['semua']='Semua';
					for ($i=2018; $i <= date('Y') ; $i++) { 
						$op[$i]=$i;
					}
					echo select('tahun',$op,$tahun);
				?>
			</div>	
			<div class="col-md-3">
				<button type="submit" class="btn btn-info">Tampilkan</button>
			</div>
		</div>
	</form>
	<hr>
 	<div id="mapid"></div>
<?=isi_tutup()?>