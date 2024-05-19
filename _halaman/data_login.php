<?php
  $title="Data Login";
  $nama_judul=$title;
  $url='data_login';

  if(isset($_POST['simpan'])){
	if($_POST['id']==""){
		$data['username']=$_POST['username'];
		$data['password']=$_POST['password'];
        $data['email']=$_POST['email'];
		$exec=$db->insert("administrator",$data);
       $info='<div class="alert alert-success alert-dismissible">
	   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	   <h4><i class="icon fa fa-ban"></i> Sukses!</h4> Data Sukses Ditambah </div>';
    }else{
        $data['username']=$_POST['username'];
		$data['password']=$_POST['password'];
        $data['email']=$_POST['email'];
		$db->where('id',$_POST['id']);
		$exec=$db->update("administrator",$data);
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
	$exec=$db->delete("administrator");
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
  $username="";
  $password="";
  $email="";
  if(isset($_GET['ubah']) AND isset($_GET['id'])){
    $id=$_GET['id'];
    $db->where('id',$id);
  $row=$db->ObjectBuilder()->getOne('administrator');
  if($db->count>0){
    $id=$row->id;
    $username=$row->username;
    $password=$row->password;
    $email=$row->email;
}
  }

?>

<?=isi_buka('Form Kelurahan')?>
<form method="post" enctype="multipart/form-data">
    	<?=input_hidden('id',$id)?>
    	<div class="form-group">
    		<label>Username</label>
    		<?=input_text('username',$username)?>
    	</div>
    	<div class="form-group">
    		<label>Password</label>
    		<?=input_text('password',$password)?>
    	</div>
        <div class="form-group">
    		<label>Email</label>
    		<?=input_text('email',$email)?>
    	</div>
    	<div class="form-group">
    		<button type="submit" name="simpan" class="btn btn-info"><i class="fa fa-save"></i> Simpan</button>
			<a href="<?=url($url)?>" class="btn btn-danger" ><i class="fa fa-reply"></i> Kembali</a>
    	</div>
    </form>
     
        <?=isi_tutup()?>


        <?php  }else { ?>

        <?=isi_buka('Data Login')?>
        <a href="<?=url($url.'&tambah')?>" class="btn btn-primary" ><i class="fa fa-plus"></i> Tambah</a>
<hr>
<?=$session->pull("info")?>
        <table class="table table-bordered-stripped-primary">
	<thead>
		<tr>
			<th>No</th>
			<th>Username</th>
			<th>Passwword</th>
            <th>Email</th>
			<th>Opsi</th>
		</tr>
</thead>
<tbody>
<?php
			$no=1;
			$ambil_data=$db->ObjectBuilder()->get('administrator');
			foreach ($ambil_data as $row) {
				?>
                <tr>
						<td><?=$no?></td>
						<td><?=$row->username?></td>
						<td><?=$row->password?></td>
                        <td><?=$row->email?></td>
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