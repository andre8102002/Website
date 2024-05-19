<?php
  $title="Digitasi";
  $nama_judul=$title;
  $url='digitasi';

  if(isset($_POST['simpan'])){
	$file=upload('geojson_kel','geojson');
	if($file!=false){
		$data['geojson_kel']=$file;
		if($_POST['id_kel']!=''){
			// hapus file di dalam folder
			$db->where('id_kel',$_GET['id']);
			$get=$db->ObjectBuilder()->getOne('k_kelurahan');
			$geojson_kel=$get->geojson_kel;
			unlink('assets/unggah/geojson/'.$geojson_kel);
			// end hapus file di dalam folder
		}
	}

	// cek validasi
	$validation=null;
	// cek kode apakah sudah ada
	if($_POST['id_kel']!=""){
		$db->where('id_kel !='.$_POST['id_kel']);
	}
	$db->where('kd_kel',$_POST['kd_kel']);
	$db->get('k_kelurahan');
	if($db->count>0){
		$validation[]='Kode Kelurahan Yang Anda Isi Sudah Ada';
	}
	//tidak boleh kosong
	if($_POST['kd_kel']==''){
		$validation[]='Kode Kelurahan Harus Diisi';
	}
	if(!is_numeric($_POST['kd_kel'])){
		$validation[]='Kode Kelurahan Harus Berupa Angka';
	}
	if($_POST['nama_kel']==''){
		$validation[]='Nama Kelurahan Harus Diisi';
	}


	if(count($validation)>0){
		$pasang_template=false;
		$session->set('error_validation',$validation);
		$session->set('error_value',$_POST);
		redirect($_SERVER['HTTP_REFERER']);
		return false;
	}
	// cek validasi

	if($_POST['id_kel']==""){
		$data['kd_kel']=$_POST['kd_kel'];
		$data['nama_kel']=$_POST['nama_kel'];
		$exec=$db->insert("k_kelurahan",$data);
       $info='<div class="alert alert-success alert-dismissible">
	   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	   <h4><i class="icon fa fa-ban"></i> Sukses!</h4> Data Sukses Ditambah </div>';
    }else{
        $data['kd_kel']=$_POST['kd_kel'];
		$data['nama_kel']=$_POST['nama_kel'];
		$db->where('id_kel',$_POST['id_kel']);
		$exec=$db->update("k_kelurahan",$data);
		$info='<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Sukses!</h4> Data Sukses diubah </div>';
		
    
}
if($exec){
	$session->set('info',$info);
}
else{
	$session->set("info",'<div class="alert alert-danger alert-dismissible">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			  <h4><i class="icon fa fa-ban"></i> Error!</h4> Proses gagal dilakukan
			</div>');
  }
  redirect(url($url));
}

  if(isset($_GET['hapus'])){
	$pasang_template=false;
	// hapus file di dalam folder
	$db->where('id_kel',$_GET['id']);
	$get=$db->ObjectBuilder()->getOne('k_kelurahan');
	$geojson_kel=$get->geojson_kel;
	unlink('assets/unggah/geojson/'.$geojson_kel);
	// end hapus file di dalam folder
	$db->where("id_kel",$_GET['id']);
	$exec=$db->delete("k_kelurahan");
	$info='<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-ban"></i> Sukses!</h4> Data Sukses dihapus </div>';
	if($exec){
		$session->set('info',$info);
	}
	else{
      $session->set("info",'<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4> Proses gagal dilakukan
              </div>');
	}
	redirect(url($url));
}
	


  elseif(isset($_GET['tambah']) OR isset($_GET['ubah'])){
  $id_kel="";
  $kd_kel="";
  $nama_kel="";
  $geojson_kel="";
  if(isset($_GET['ubah']) AND isset($_GET['id'])){
    $id=$_GET['id'];
    $db->where('id_kel',$id);
  $row=$db->ObjectBuilder()->getOne('k_kelurahan');
  if($db->count>0){
    $id_kel=$row->id_kel;
    $kd_kel=$row->kd_kel;
    $nama_kel=$row->nama_kel;
	$geojson_kel=$row->geojson_kel;
}
  }
// value ketika validasi
if($session->get('error_value')){
	extract($session->pull('error_value'));
}
?>

<?=isi_buka('Form Digitasi')?>
<form method="post" enctype="multipart/form-data">
<?php
    		// menampilkan error validasi
  			if($session->get('error_validation')){
  				foreach ($session->pull('error_validation') as $key => $value) {
  					echo '<div class="alert alert-danger">'.$value.'</div>';
  				}
  			}
    	?>
    	<?=input_hidden('id_kel',$id_kel)?>
    	<div class="form-group">
    		<label>Kode Kelurahan</label>
    		<?=input_text('kd_kel',$kd_kel)?>
    	</div>
    	<div class="form-group">
    		<label>Nama Kelurahan</label>
    		<?=input_text('nama_kel',$nama_kel)?>
    	</div>
		<div class="form-group">
    		<label>GeoJSON</label>
    		<?=input_file('geojson_kel',$geojson_kel)?>
    	</div>
    	<div class="form-group">
    		<button type="submit" name="simpan" class="btn btn-info"><i class="fa fa-save"></i> Simpan</button>
			<a href="<?=url($url)?>" class="btn btn-danger" ><i class="fa fa-reply"></i> Kembali</a>
    	</div>
    </form>
     
        <?=isi_tutup()?>


        <?php  }else { ?>

        <?=isi_buka('Data Kelurahan')?>
        <a href="<?=url($url.'&tambah')?>" class="btn btn-primary" ><i class="fa fa-plus"></i> Tambah</a>
<hr>
<?=$session->pull("info")?>
        <table class="table table-bordered-stripped-primary">
	<thead>
		<tr>
			<th>No</th>
			<th>Kode</th>
			<th>Kelurahan</th>
			<th>GeoJSON</th>
			<th>Opsi</th>
		</tr>
</thead>
<tbody>
<?php
			$no=1;
			$ambil_data=$db->ObjectBuilder()->get('k_kelurahan');
			foreach ($ambil_data as $row) {
				?>
                <tr>
						<td><?=$no?></td>
						<td><?=$row->kd_kel?></td>
						<td><?=$row->nama_kel?></td>
						<td><a href="<?=assets('unggah/geojson/'.$row->geojson_kel)?>" target="_BLANK"><?=$row->geojson_kel?></a></td>
						<td>
							<a href="<?=url($url.'&ubah&id='.$row->id_kel)?>" class="btn btn-success"><i class="fa fa-edit"></i> Ubah</a>
							<a href="<?=url($url.'&hapus&id='.$row->id_kel)?>" class="btn btn-danger" onclick="return confirm('Hapus data?')"><i class="fa fa-trash"></i> Hapus</a>
						</td>
					</tr>
				<?php
				$no++;
			}

		?>
</tbody>
</table>
     
        <?=isi_tutup()?>
        <?php } ?>