<?php
  $title="Kelurahan";
  $nama_judul=$title;
  $url='kelurahan';

  if(isset($_POST['simpan'])){

	// cek validasi
	$validation=null;
	// cek kode apakah sudah ada
	
	//tidak boleh kosong
	if($_POST['kelurahan']==''){
		$validation[]='Kelurahan Harus Diisi';
	}
	if($_POST['luas']==''){
		$validation[]='Luas Harus Diisi';
	}
	if($_POST['jml_pddk']==''){
		$validation[]='Penduduk Harus Diisi';
	}
	if($_POST['rt']==''){
		$validation[]='RT Harus Diisi';
	}
	if($_POST['rw']==''){
		$validation[]='RW Harus Diisi';
	}
	


	if(count($validation)>0){
		$pasang_template=false;
		$session->set('error_validation',$validation);
		$session->set('error_value',$_POST);
		redirect($_SERVER['HTTP_REFERER']);
		return false;
	}
	// cek validasi
	if($_POST['id']==""){
		$data['kelurahan']=$_POST['kelurahan'];
		$data['tanggal']=$_POST['tanggal'];
		$data['luas']=$_POST['luas'];
		$data['jml_pddk']=$_POST['jml_pddk'];
		$data['rt']=$_POST['rt'];
		$data['rw']=$_POST['rw'];
		$exec=$db->insert("data_kelurahan",$data);
       $info='<div class="alert alert-success alert-dismissible">
	   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	   <h4><i class="icon fa fa-ban"></i> Sukses!</h4> Data Sukses Ditambah </div>';
    }else{
		$data['kelurahan']=$_POST['kelurahan'];
		$data['tanggal']=$_POST['tanggal'];
		$data['luas']=$_POST['luas'];
		$data['jml_pddk']=$_POST['jml_pddk'];
		$data['rt']=$_POST['rt'];
		$data['rw']=$_POST['rw'];
		$db->where('id',$_POST['id']);
		$exec=$db->update("data_kelurahan",$data);
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
	$db->where("id",$_GET['id']);
	$exec=$db->delete("data_kelurahan");
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
	


  if(isset($_GET['tambah']) OR isset($_GET['ubah'])){
  $id="";
  $kelurahan="";
  $tanggal=date('Y-m-d');
  $luas="";
  $jml_pddk="";
  $rt="";
  $rw="";
  if(isset($_GET['ubah']) AND isset($_GET['id'])){
    $id=$_GET['id'];
    $db->where('id',$id);
  $row=$db->ObjectBuilder()->getOne('data_kelurahan');
  if($db->count>0){
    $id=$row->id;
    $kelurahan=$row->kelurahan;
    $tanggal=$row->tanggal;
	$luas=$row->luas;
	$jml_pddk=$row->jml_pddk;
	$rt=$row->rt;
	$rw=$row->rw;
}
  }
// value ketika validasi
if($session->get('error_value')){
	extract($session->pull('error_value'));
}

?>

<?=isi_buka('Formulir Kelurahan')?>
<form method="post" enctype="multipart/form-data">
<?php
    		// menampilkan error validasi
  			if($session->get('error_validation')){
  				foreach ($session->pull('error_validation') as $key => $value) {
  					echo '<div class="alert alert-danger">'.$value.'</div>';
  				}
  			}
    	?>
    	<?=input_hidden('id',$id)?>
    	<div class="form-group">
    		<label>Kelurahan</label>
    		<?=input_text('kelurahan',$kelurahan)?>
    	</div>
    	<div class="form-group">
    		<label>tanggal</label>
    		<?=input_date('tanggal',$tanggal)?>
    	</div>
		<div class="form-group">
    		<label>Luas</label>
    		<?=input_text('luas',$luas)?>
    	</div>
		<div class="form-group">
    		<label>Penduduk</label>
    		<?=input_text('jml_pddk',$jml_pddk)?>
    	</div>
		<div class="form-group">
    		<label>rt</label>
    		<?=input_text('rt',$rt)?>
    	</div>
		<div class="form-group">
    		<label>rw</label>
    		<?=input_text('rw',$rw)?>
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
			<th>Kelurahan</th>
			<th>Tanggal</th>
			<th>Luas</th>
			<th>Penduduk</th>
			<th>RT</th>
			<th>RW</th>
			<th>Opsi</th>
		</tr>
</thead>
<tbody>
<?php
			$no=1;
			$ambil_data=$db->ObjectBuilder()->get('data_kelurahan');
			foreach ($ambil_data as $row) {
				?>
                <tr>
						<td><?=$no?></td>
						<td><?=$row->kelurahan?></td>
						<td><?=$row->tanggal?></td>
						<td><?=$row->luas?></td>
						<td><?=$row->jml_pddk?></td>
						<td><?=$row->rt?></td>
						<td><?=$row->rw?></td>
						<td>
							<a href="<?=url($url.'&ubah&id='.$row->id)?>" class="btn btn-success"><i class="fa fa-edit"></i> Ubah</a>
							<a href="<?=url($url.'&hapus&id='.$row->id)?>" class="btn btn-danger" onclick="return confirm('Hapus data?')"><i class="fa fa-trash"></i> Hapus</a>
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