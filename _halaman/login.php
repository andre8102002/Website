<?php
	$pasang_template=false;
    if(isset($_POST['login'])){
        $username=$_POST['username'];
        $password=$_POST['password'];
        $db->where("username",$username);
        $db->where("password",$password);
        $data=$db->ObjectBuilder()->getOne("administrator");
        if($db->count>0){
        $session->set("sudah_login",true);
        $session->set("username",$data->username);
        $session->set("id",$data->id);
        $session->set("info",'<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Sukses!</h4> Selamat Datang <b>'.$data->username.'</b> di Halaman Utama Aplikasi
              </div>');

              ?>
              <script>window.location.href="<?=url('beranda')?>";
            </script>
            <?php
              
      
        }
        else{
            $session->set("sudah_login",false);
            $session->set("info", '<div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-ban"></i> Error!</h4> Username atau Password salah
          </div>');

          ?>
          <script>window.location.href="<?=url('login')?>";
        </script>
        <?php

        }
    }
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Form Login</title>
	<?php include '_layouts/head.php'?>
	<link rel="stylesheet" href="<?=templates()?>plugins/iCheck/square/blue.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Login</b>WEBGIS</a>
  </div>
  <!-- /.login-logo -->
  
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>
    <?=$session->get("info")?>
    <form  method="post">
      <label>Username</label>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name=username  placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <label>Password</label>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password"  placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <a href="lupa_pswd/forget_pass.php">Lupa Password ?</a><br>
      <br>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" name="login" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>


  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
</body>
<?php
  include '_layouts/javascript.php';
?>
<script src="<?=templates()?>plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</html>