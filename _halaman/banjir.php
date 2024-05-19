<?php
  $title="Data Rawan Banjir";
  $nama_judul=$title;
  $url='banjir';

  if(isset($_POST['simpan'])){
	$file=upload('marker','poin_marker');
	if($file!=false){
		$data['marker']=$file;
		if($_POST['id_peta']!=''){
			// hapus file di dalam folder
			$db->where('id_peta',$_GET['id']);
			$get=$db->ObjectBuilder()->getOne('rawan_banjir');
			$marker=$get->marker;
			unlink('assets/unggah/poin_marker/'.$marker);
			// end hapus file di dalam folder
	}
}
	$data['id_kel']=$_POST['id_kel'];
    $data['tanggal']=$_POST['tanggal'];
	$data['ketinggian']=$_POST['ketinggian'];
    $data['kategori']=$_POST['kategori'];
	$data['lokasi']=$_POST['lokasi'];
	$data['latitude']=$_POST['latitude'];
	$data['longtitude']=$_POST['longtitude'];
	if($_POST['id_peta']==""){
		$exec=$db->insert("rawan_banjir",$data);
		$info='<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Sukses!</h4> Data Sukses Ditambah </div>';
		
	}
	else{
		$db->where('id_peta',$_POST['id_peta']);
		$exec=$db->update("rawan_banjir",$data);
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
	$db->where("id_peta",$_GET['id']);
	$exec=$db->delete("rawan_banjir");
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
  $id_peta="";
  $id_kel="";
  $tanggal=date('Y-m-d');
  $ketinggian="";
  $kategori="";
  $lokasi="";
  $latitude="";
  $longtitude="";
  
  if(isset($_GET['ubah']) AND isset($_GET['id'])){
    $id=$_GET['id'];
    $db->where('id_peta',$id);
  $row=$db->ObjectBuilder()->getOne('rawan_banjir');
  if($db->count>0){
    $id_peta=$row->id_peta;
    $id_kel=$row->id_kel;
    $tanggal=$row->tanggal;
	$ketinggian=$row->ketinggian;
	$kategori=$row->kategori;
    $lokasi=$row->lokasi;
    $latitude=$row->latitude;
    $longtitude=$row->longtitude;
}
  }

?>

<?=isi_buka('Form Data Banjir')?>
<form method="post" enctype="multipart/form-data">
    	<?=input_hidden('id_peta',$id_peta)?>
    	<div class="form-group">
    		<label>Kelurahan</label>
    		<div class="row">
	    		<div class="col-md-6">
	    			<?php
	    				$op['']='Pilih Kelurahan';
	    				foreach ($db->ObjectBuilder()->get('k_kelurahan') as $row) {
	    					$op[$row->id_kel]=$row->nama_kel;
	    				}
	    			?>
	    			<?=select('id_kel',$op,$id_kel)?>
	    		</div>
    		</div>
    	</div>
        <div class="form-group">
    		<label>Tanggal</label>
    		<div class="row">
	    		<div class="col-md-4">
    				<?=input_date('tanggal',$tanggal)?>
    			</div>
    		</div>
            <div class="form-group">
    		<label>Ketinggian (cm)</label>
    		<div class="row">
	    		<div class="col-md-4">
	    			<?=input_text('ketinggian',$ketinggian)?>
		    	</div>
	    	</div>
    	</div>
        <div class="form-group">
    		<label>Kategori (cm)</label>
    		<div class="row">
	    		<div class="col-md-4">
	    			<?=input_text('kategori',$kategori)?>
		    	</div>
	    	</div>
    	</div>
        <div class="form-group">
    		<label>Lokasi</label>
    		<div class="row">
	    		<div class="col-md-4">
    				<?=textarea('lokasi',$lokasi)?>
    			</div>
    		</div>
    	</div>
    
    	<div class="form-group">
    		<label>Titik Koordinat</label> 
    		<div class="row">
	    		<div class="col-md-3">
	    			<?=input_text('latitude',$latitude)?>
	    		</div>
	    		<div class="col-md-3">
	    			<?=input_text('longtitude',$longtitude)?>
	    		</div>
    		</div>
    	</div>
		<div class="form-group">
    		<label>Marker</label>
    		<?=input_file('marker','')?>
    	</div>
    	<div class="form-group">
    		<button type="submit" name="simpan" class="btn btn-info"><i class="fa fa-save"></i> Simpan</button>
			<a href="<?=url($url)?>" class="btn btn-danger" ><i class="fa fa-reply"></i> Kembali</a>
    	</div>
    </form>
     
        <?=isi_tutup()?>


        <?php  }else { ?>

        <?=isi_buka('Data Banjir')?>
        <a href="<?=url($url.'&tambah')?>" class="btn btn-primary" ><i class="fa fa-plus"></i> Tambah</a>
<hr>
<?=$session->pull("info")?>
        <table class="table table-bordered-stripped-primary">
	<thead>
		<tr>
			<th>No</th>
			<th>Kelurahan</th>
			<th>Tanggal</th>
			<th>Ketinggian</th>
            <th>Kategori</th>
			<th>Lokasi</th>
            <th>Latitude</th>
			<th>Longtitude</th>
			<th>Marker</th>
			<th>Opsi</th>
		</tr>
</thead>
<tbody>
<?php
			$no=1;
            $db->join('k_kelurahan b','a.id_kel=b.id_kel','LEFT');
			$ambil_data=$db->ObjectBuilder()->get('rawan_banjir a');
			foreach ($ambil_data as $row) {
				?>
                <tr>
						<td><?=$no?></td>
						<td><?=$row->nama_kel?></td>
                        <td><?=$row->tanggal?></td>
						<td><?=$row->ketinggian?></td>
                        <td><?=$row->kategori?></td>
						<td><?=$row->lokasi?></td>
                        <td><?=$row->latitude?></td>
						<td><?=$row->longtitude?></td>
						<td class="text-center"><?=($row->marker==''?'-':'<img src="'.assets('unggah/poin_marker/'.$row->marker).'" width="40px">')?></td>
						<td>
						<td>
							<a href="<?=url($url.'&ubah&id='.$row->id_peta)?>" class="btn btn-success"><i class="fa fa-edit"></i> Ubah</a>
							<a href="<?=url($url.'&hapus&id='.$row->id_peta)?>" class="btn btn-danger" onclick="return confirm('Hapus data?')"><i class="fa fa-trash"></i> Hapus</a>
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