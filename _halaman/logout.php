<?php
$setTemplate=false;
$session->destroy('_gis-web', true);

$session->set("info",'<div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> Sukses Keluar!</h4> Masukan akun untuk masuk
      </div>');
      ?>
      <script>window.location.href="<?=url('login')?>";
    </script>
    <?php

?>