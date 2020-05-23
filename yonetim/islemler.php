<?php 
define('guvenlik', true);
require_once 'inc/ust.php'; ?>
    <!-- Sidebar menu-->
<?php require_once 'inc/sol.php'; ?>

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> İşlemlerler</h1>
          <p>İşlemler Listesi</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">İşlemlerler</li>
          <li class="breadcrumb-item active"><a href="#">İşlemler Listesi</a></li>
        </ul>
      </div>
      <div class="row">

        <div class="clearfix"></div>
        <div class="col-md-12">
        <div class="tile">
        <h3 class="tile-title">İşlemler</h3>
        <?php 
        if(@$_SESSION['oturum'] == sha1(md5(@$yid.IP()))){

        $islem = @get('islem');

        if(!$islem){
          header('Location:'.$yonetim);
        }


        
        switch ($islem) {
        
        case 'yaziduzenle':
          $id = get('id');
          if(!$id){
            header('Location:'.$yonetim."/konular.php");
          }

          $yazibul = $db->prepare("SELECT * FROM yazilar WHERE yazi_id=:id");
          $yazibul->execute([':id' => $id]);
          if($yazibul->rowCount()){
            $yazirow = $yazibul->fetch(PDO::FETCH_OBJ);  
            if(isset($_POST['yaziguncelle'])){

                  require_once 'inc/class.upload.php';

                  $baslik    = post('baslik');
                  $sefbaslik = sef_link($baslik);
                  $kategori  = post('kategori');
                  $icerik    = $_POST['icerik'];
                  $etiketler = post('etiketler');
                  $durum = post('durum');
              

                if(!$baslik || !$kategori || !$icerik || !$etiketler || !$durum){
                    echo '<div class="alert alert-danger">Boş alan bırakmayınız.</div>';
                  }else{
        
                    #etiketlieri seflinke çevirmek için yazıldı.
                    $sefyap = explode(',', $etiketler);
                    $dizi   = array();
                    foreach ($sefyap as $par) {
                      $dizi[] = sef_link($par);
                    }
                    $deger = implode(',', $dizi);


                    $image = new Upload($_FILES['resim']);

                    if($image->uploaded){

                      $rname = md5(uniqid());
                      $image->allowed = array("image/*");
                      $image->image_convert = "webp";
                      $image->file_new_name_body = $rname;
                      $image->process("../images");

                      if($image->processed){

                      $konu_guncelle = $db->prepare("UPDATE yazilar SET
                          yazi_baslik=:baslik,
                          yazi_sef=:sef,
                          yazi_kat_id=:yazikatid,
                          yazi_resim=:resim,
                          yazi_icerik=:icerik,
                          yazi_etiketler=:etiketler,
                          yazi_sef_etiketler=:sefetiketler,
                          yazi_durum=:durum WHERE yazi_id=:id
                            ");

                      $konu_guncelle->execute([
                        ':baslik' => $baslik,
                        ':sef' => $sefbaslik, 
                        ':yazikatid' => $kategori,
                        ':resim' => "images/".$rname.".webp",
                        ':icerik' => $icerik,
                        ':etiketler' => $etiketler,
                        ':sefetiketler' => $deger,
                        ':durum' => $durum,
                        ':id' => $id
                      ]);

                      if($konu_guncelle->rowCount()){

                        $sonid = $db->lastInsertId();
                        $konubul = $db->prepare("SELECT *FROM yazilar WHERE yazi_id=:id");
                        $konubul->execute([':id' => $sonid]);
                        $konurow = $konubul->fetch(PDO::FETCH_OBJ);
                        #abonelere mail gönderme kısmı start

                        #aboneler mail gönderme kısmı end
                        echo '<div class="alert alert-success">Konu başarıyla güncellendi.</div>';
                        header('refresh:2;url='.$_SERVER['HTTP_REFERER']);
                      }else{
                        echo '<div class="alert alert-danger">Konu güncellenirken hata oluştu.</div>';
                      }


                      }else{
                        echo '<div class="alert alert-danger">Resim yüklenemedi.</div>';

                      }
                    }else{

                      //echo '<div class="alert alert-danger">Resim seçmediniz.</div>';

                      $konu_guncelle2 = $db->prepare("UPDATE yazilar SET
                          yazi_baslik=:baslik,
                          yazi_sef=:sef,
                          yazi_kat_id=:yazikatid,
                          yazi_icerik=:icerik,
                          yazi_etiketler=:etiketler,
                          yazi_sef_etiketler=:sefetiketler,
                          yazi_durum=:durum WHERE yazi_id=:id
                            ");

                      $konu_guncelle2->execute([
                        ':baslik' => $baslik,
                        ':sef' => $sefbaslik, 
                        ':yazikatid' => $kategori,
                        ':icerik' => $icerik,
                        ':etiketler' => $etiketler,
                        ':sefetiketler' => $deger,
                        ':durum' => $durum,
                        ':id' => $id
                      ]);

                      if($konu_guncelle2->rowCount()){

                        $sonid = $db->lastInsertId();
                        $konubul = $db->prepare("SELECT *FROM yazilar WHERE yazi_id=:id");
                        $konubul->execute([':id' => $sonid]);
                        $konurow = $konubul->fetch(PDO::FETCH_OBJ);
                        #abonelere mail gönderme kısmı start

                        #aboneler mail gönderme kısmı end
                        echo '<div class="alert alert-success">Konu başarıyla güncellendi. Resim değiştirilmedi.</div>';
                        header('refresh:2;url='.$_SERVER['HTTP_REFERER']);
                      }else{
                        echo '<div class="alert alert-danger">Konu güncellenirken hata oluştu.</div>';
                      }
                    }

                    
                  }
                }
            ?>
                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                  <div class="tile-body">
                    
                      <div class="form-group row">
                        <label class="control-label col-md-3">Yazı Başlığı</label>
                        <div class="col-md-8">
                          <input value="<?php echo $yazirow->yazi_baslik;?>" class="form-control" type="text" name="baslik" placeholder="baslik">
                        </div>
                      </div>

                        <div class="form-group row">
                        <label class="control-label col-md-3">Yazı Kategorisi</label>
                        <div class="col-md-8">
                          <select name="kategori" class="form-control">
                            <?php 
                              $kategoriler = $db->prepare("SELECT * FROM kategoriler");
                              $kategoriler->execute();
                              if($kategoriler->rowCount()){
                                foreach ($kategoriler as $row) {
                                  echo '<option value="'.$row['id'].'"';
                                  echo $yazirow->yazi_kat_id == $row['id'] ? 'selected' : null;
                                  echo'>'.$row['kat_adi'].'</option>';
                                }
                              }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="control-label col-md-3">Yazı Resim</label>
                        <div class="col-md-8">
                          <img src="<?php echo $arow->site_url;?>/<?php echo $yazirow->yazi_resim;?>" width="100" height="100"/>
                          <input class="form-control" type="file" name="resim">
                        </div>
                      </div>

                       <div class="form-group row">
                        <label class="control-label col-md-3">Yazı İçerik</label>
                        <div class="col-md-8">
                          <textarea name="icerik" class="ckeditor"><?php echo $yazirow->yazi_icerik;?></textarea>
                        </div>
                      </div>

                       <div class="form-group row">
                        <label class="control-label col-md-3">Yazı Etiketler</label>
                        <div class="col-md-8">
                          <input class="form-control" value="<?php echo $yazirow->yazi_etiketler;?>" type="text" name="etiketler" placeholder="Vigüllü şeklide giriniz.">
                        </div>
                      </div>

                       <div class="form-group row">
                      <label class="control-label col-md-3">Yazı Durumu</label>
                      <div class="col-md-8">
                        <select name="durum" class="form-control">
                          <option value="1" <?php echo $yazirow->yazi_durum == 1 ? 'selected' : null;?>>Aktif</option>
                          <option value="2" <?php echo $yazirow->yazi_durum == 2 ? 'selected' : null;?>>Pasif</option>
                        </select>
                      </div>
                    </div>
                  
                  </div>
                  <div class="tile-footer">
                    <div class="row">
                      <div class="col-md-8 col-md-offset-3">
                        <button class="btn btn-primary" type="submit" name="yaziguncelle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Yazı Güncelle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim?>/konular.php"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                      </div>
                    </div>
                  </div>
                  </form>
            <?php
          }else{
             header('Location:'.$yonetim."/konular.php");
          }
        break;
        #profil işlemleri -start

        case 'cikis':
          session_destroy();
          header('Location:giris.php');
        break;
        case 'profil':

          if(isset($_POST['profilguncelle'])){

              $kadi = post('kadi');
              $posta = post('eposta');

              if(!$kadi || !$posta){
                echo '<div class="alert alert-danger">Boş alan bırakmayınız.</div>';
              }else{
                if(!filter_var($posta,FILTER_VALIDATE_EMAIL)){
                  echo '<div class="alert alert-danger">E-Posta formatı yanlış.</div>';
                }else{

                      $guncelle = $db->prepare("UPDATE yoneticiler SET kadi=:k, eposta=:e WHERE id=:id");
                      $guncelle->execute([':k' => $kadi, ':e' => $posta, ':id' => $yid]);
                      if($guncelle){
                              echo '<div class="alert alert-success">Profil bilgileri başarıyla güncellendi.</div>';
                              header('refresh=2;url='.$_SERVER['HTTP_REFERER']);
                      }else{
                          echo '<div class="alert alert-danger">Hata oluştu.</div>';
                      }
                }
              }

              
          }
          ?>
            <form class="form-horizontal" action="" method="post">
            <div class="tile-body">
              
                <div class="form-group row">
                  <label class="control-label col-md-3">Kullanıcı Adı</label>
                  <div class="col-md-8">
                    <input class="form-control" value="<?php echo $ykadi;?>" type="text" name="kadi" placeholder="Kullanıcı Adı">
                  </div>
                </div>
                  <div class="form-group row">
                  <label class="control-label col-md-3">E-Posta</label>
                  <div class="col-md-8">
                    <input class="form-control" value="<?php echo $yposta;?>" type="text" name="eposta" placeholder="E-Posta">
                  </div>
                </div>
                 
            </div>
            <div class="tile-footer">
              <div class="row">
                <div class="col-md-8 col-md-offset-3">
                  <button class="btn btn-primary" type="submit" name="profilguncelle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Profil Güncelle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim?>"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                </div>
              </div>
            </div>
            </form>
          <?php
        break;

        case 'sifredegistir':

          if(isset($_POST['sifreguncelle'])){

              $sifre1 = post('sifre1');
              $sifre2 = post('sifre2');
              $sifrele = sha1(md5($sifre1));


              if(!$sifre1 || !$sifre2){
                echo '<div class="alert alert-danger">Boş alan bırakmayınız.</div>';
              }else{
                if($sifre1 != $sifre2){
                  echo '<div class="alert alert-danger">Şifreler uyuşmadı.</div>';
                }else{

                      $guncelle = $db->prepare("UPDATE yoneticiler SET sifre=:s WHERE id=:id");
                      $guncelle->execute([':s' => $sifrele, ':id' => $yid]);
                      if($guncelle){
                              echo '<div class="alert alert-success">Şifreniz başarıyla güncellendi.</div>';
                              header('refresh=2;url='.$_SERVER['HTTP_REFERER']);
                      }else{
                          echo '<div class="alert alert-danger">Hata oluştu.</div>';
                      }
                }
              }

              
          }
          ?>
            <form class="form-horizontal" action="" method="post">
            <div class="tile-body">
              
                <div class="form-group row">
                  <label class="control-label col-md-3">Yeni Şifre</label>
                  <div class="col-md-8">
                    <input class="form-control" type="password" name="sifre1" placeholder="Yeni Şifre">
                  </div>
                </div>
                  <div class="form-group row">
                  <label class="control-label col-md-3">Yeni Şifre Tekrar</label>
                  <div class="col-md-8">
                    <input class="form-control" type="password" name="sifre2" placeholder="Yeni Şifre Tekrar">
                  </div>
                </div>
                 
            </div>
            <div class="tile-footer">
              <div class="row">
                <div class="col-md-8 col-md-offset-3">
                  <button class="btn btn-primary" type="submit" name="sifreguncelle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Şifre Güncelle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim?>"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                </div>
              </div>
            </div>
            </form>
          <?php
        break;
        #profil işlemleri -end

        #silme işlemleri -start
        case 'kategorisil':
            $id = get('id');
            if(!$id){
              header('Location:'.$yonetim."/kategoriler.php");
            }
            $kategorisil = $db->prepare("DELETE FROM kategoriler WHERE id=:id");
            $kategorisil->execute([':id'=>$id]);
            if($kategorisil){
              $yazipasif = $db->prepare("UPDATE yazilar SET yazi_durum=:d WHERE yazi_kat_id=:id");
              $yazipasif->execute([':d'=>2,':id'=>$id]);
              echo '<div class="alert alert-success">Kategori başarıyla silindi. Bu kategoriye ait konular pasif duruma getirildi.</div>';
              header('refresh:2;url='.$yonetim."/kategoriler.php");
            }else{
              echo '<div class="alert alert-danger">Hata oluştu.</div>';
            }
            break;
        case 'mesajsil':
            $id = get('id');
            if(!$id){
              header('Location:'.$yonetim."/okunmusmesajlar.php");
            }
            $mesajsil = $db->prepare("DELETE FROM mesajlar WHERE id=:id");
            $mesajsil->execute([':id'=>$id]);
            if($mesajsil){
              
              echo '<div class="alert alert-success">Mesaj başarıyla silindi.</div>';
              header('refresh:2;url='.$_SERVER['HTTP_REFERER']);
            }else{
              echo '<div class="alert alert-danger">Hata oluştu.</div>';
            }
            break;

            case 'yorumsil':
            $id = get('id');
            if(!$id){
              header('Location:'.$yonetim."/bekleyenyorumlar.php");
            }
            $yorumsil = $db->prepare("DELETE FROM yorumlar WHERE id=:id");
            $yorumsil->execute([':id'=>$id]);
            if($yorumsil){
              
              echo '<div class="alert alert-success">Yorum başarıyla silindi.</div>';
              header('refresh:2;url='.$_SERVER['HTTP_REFERER']);
            }else{
              echo '<div class="alert alert-danger">Hata oluştu.</div>';
            }
            break;

            case 'sosyalmedyasil':
            $id = get('id');
            if(!$id){
              header('Location:'.$yonetim."/sosyalmedya.php");
            }
            $sosyalmedyasil = $db->prepare("DELETE FROM sosyalmedya WHERE id=:id");
            $sosyalmedyasil->execute([':id'=>$id]);
            if($sosyalmedyasil){
              
              echo '<div class="alert alert-success">Sosyal medya hesabı başarıyla silindi.</div>';
              header('refresh:2;url='.$_SERVER['HTTP_REFERER']);
            }else{
              echo '<div class="alert alert-danger">Hata oluştu.</div>';
            }
            break;

            case 'yazisil':
                $id = get('id');
                if(!$id){
                  header('Location:'.$yonetim."/konular.php");
                }

                $yazibul = $db->prepare("SELECT * FROM yazilar WHERE yazi_id=:id");
                $yazibul->execute([':id' => $id]);

                if($yazibul->rowCount()){

                    $yazirow = $yazibul->fetch(PDO::FETCH_OBJ);

                    $yazisil = $db->prepare("DELETE FROM yazilar WHERE yazi_id=:id");
                    $yazisil->execute([':id'=>$id]);

                    if($yazisil){
                        
                      $yorumlarisil = $db->prepare("DELETE FROM yorumlar WHERE yorum_yazi_id=:id");
                      $yorumlarisil->execute([':id' => $id]);

                      unlink("../".$yazirow->yazi_resim);
                      echo '<div class="alert alert-success">Yazı başarıyla silindi.</div>';
                      header('refresh:2;url='.$_SERVER['HTTP_REFERER']);
                    }else{
                      echo '<div class="alert alert-danger">Hata oluştu.</div>';
                    }
                }
            break;
        #silme işlemleri -end

        #ekleme işlemleri -start
        case 'yenikategori':

          if(isset($_POST['kategoriekle'])){
            $kat_adi  = post('kat_adi');
            $kat_sef  = sef_link($kat_adi);
            $kat_keyw = post('kat_keyw');
            $kat_desc = post('kat_desc');

            if(!$kat_adi || !$kat_keyw || !$kat_desc){
              echo '<div class="alert alert-danger">Boş alan bırakmayınız.</div>';
            }else{

              $varmi = $db->prepare("SELECT * FROM kategoriler WHERE kat_sef=:s");
              $varmi->execute([':s' => $kat_sef]);
              if($varmi->rowCount()){
                echo '<div class="alert alert-danger">Bu kategori zaten kayıtlı.</div>';

              }else {
                $kategori_ekle = $db->prepare("INSERT INTO kategoriler SET
                      kat_adi=:adi,
                      kat_sef=:sef,
                      kat_keyw=:keyw,
                      kat_desc=:descc");
                $kategori_ekle->execute([':adi' => $kat_adi,':sef' => $kat_sef,':keyw' => $kat_keyw,':descc' => $kat_desc]);

                if($kategori_ekle->rowCount()){
                  echo '<div class="alert alert-success">Kategori başarıyla eklendi.</div>';
                  header('refresh:2;url='.$yonetim."/kategoriler.php");
                }else{
                  echo '<div class="alert alert-danger">Hata oluştu.</div>';
                }
              }
            }
          }
          ?>

         <form class="form-horizontal" action="" method="post">
            <div class="tile-body">
              
                <div class="form-group row">
                  <label class="control-label col-md-3">Kategori Adı</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="kat_adi" placeholder="kategori adı">
                  </div>
                </div>
                  <div class="form-group row">
                  <label class="control-label col-md-3">Kategori Anahtar Kelimeler</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="kat_keyw" placeholder="kategori anahtar kelimeler">
                  </div>
                </div>
                  <div class="form-group row">
                  <label class="control-label col-md-3">Kategori Açıklaması</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="kat_desc" placeholder="kategori açıklaması">
                  </div>
                </div>
            
            </div>
            <div class="tile-footer">
              <div class="row">
                <div class="col-md-8 col-md-offset-3">
                  <button class="btn btn-primary" type="submit" name="kategoriekle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Kategori Ekle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim?>/kategoriler.php"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                </div>
              </div>
            </div>
            </form>
        
            <?php
        break;
        case 'yenisosyalmedya':

          if(isset($_POST['sosyalmedyaekle'])){
            $ikon  = post('ikon');
            $link = post('link');
            

            if(!$ikon || !$link){
              echo '<div class="alert alert-danger">Boş alan bırakmayınız.</div>';
            }else{

              
                $sosyalmedya_ekle = $db->prepare("INSERT INTO sosyalmedya SET
                      ikon=:ik,
                      link=:li
                      ");
                $sosyalmedya_ekle->execute([':ik' => $ikon,':li' => $link]);

                if($sosyalmedya_ekle->rowCount()){
                  echo '<div class="alert alert-success">Sosyal medya başarıyla eklendi.</div>';
                  header('refresh:2;url='.$yonetim."/sosyalmedya.php");
                }else{
                  echo '<div class="alert alert-danger">Hata oluştu.</div>';
                }
              
            }
          }
            ?>

          <form class="form-horizontal" action="" method="post">
            <div class="tile-body">
              
                <div class="form-group row">
                  <label class="control-label col-md-3">Sosyal Medya İkon</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="ikon" placeholder="ikon">
                  </div>
                </div>
                  <div class="form-group row">
                  <label class="control-label col-md-3">Sosyal Medya Link</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="link" placeholder="link">
                  </div>
                </div>
            
            </div>
            <div class="tile-footer">
              <div class="row">
                <div class="col-md-8 col-md-offset-3">
                  <button class="btn btn-primary" type="submit" name="sosyalmedyaekle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Sosyal Medya Ekle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim?>/sosyalmedya.php"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                </div>
              </div>
            </div>
            </form>
        
            <?php
        break;
        case 'yenikonu':

              if(isset($_POST['yeniyaziekle'])){

                  require_once 'inc/class.upload.php';
                  require_once 'inc/mail/class.phpmailer.php';


                  $baslik    = post('baslik');
                  $sefbaslik = sef_link($baslik);
                  $kategori  = post('kategori');
                  $icerik    = $_POST['icerik'];
                  $etiketler = post('etiketler');
              

                if(!$baslik || !$kategori || !$icerik || !$etiketler){
                    echo '<div class="alert alert-danger">Boş alan bırakmayınız.</div>';
                  }else{
        
                    #etiketlieri seflinke çevirmek için yazıldı.
                    $sefyap = explode(',', $etiketler);
                    $dizi   = array();
                    foreach ($sefyap as $par) {
                      $dizi[] = sef_link($par);
                    }
                    $deger = implode(',', $dizi);


                    $image = new Upload($_FILES['resim']);

                    if($image->uploaded){

                      $rname = md5(uniqid());
                      $image->allowed = array("image/*");
                      $image->image_convert = "webp";
                      $image->file_new_name_body = $rname;
                      $image->process("../images");

                      if($image->processed){

                      $konu_ekle = $db->prepare("INSERT INTO yazilar SET
                          yazi_baslik=:baslik,
                          yazi_sef=:sef,
                          yazi_kat_id=:yazikatid,
                          yazi_resim=:resim,
                          yazi_icerik=:icerik,
                          yazi_etiketler=:etiketler,
                          yazi_sef_etiketler=:sefetiketler
                            ");

                      $konu_ekle->execute([
                        ':baslik' => $baslik,
                        ':sef' => $sefbaslik, 
                        ':yazikatid' => $kategori,
                        ':resim' => "images/".$rname.".webp",
                        ':icerik' => $icerik,
                        ':etiketler' => $etiketler,
                        ':sefetiketler' => $deger
                      ]);

                      if($konu_ekle->rowCount()){

                        $sonid = $db->lastInsertId();
                        $konubul = $db->prepare("SELECT *FROM yazilar WHERE yazi_id=:id");
                        $konubul->execute([':id' => $sonid]);
                        $konurow = $konubul->fetch(PDO::FETCH_OBJ);
                        #abonelere mail gönderme kısmı start

                        #aboneler mail gönderme kısmı end
                        echo '<div class="alert alert-success">Yazı başarıyla eklendi.</div>';
                        header('refresh:2;url='.$yonetim."/konular.php");
                      }else{
                        echo '<div class="alert alert-danger">Hata oluştu.</div>';
                      }


                      }else{
                        echo '<div class="alert alert-danger">Resim yükleme başarısız.</div>';
                      }
                    }else{
                      echo '<div class="alert alert-danger">Resim seçmediniz.</div>';
                    }

                    
                  }
                }
              ?>
              <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                  <div class="tile-body">
                    
                      <div class="form-group row">
                        <label class="control-label col-md-3">Yazı Başlığı</label>
                        <div class="col-md-8">
                          <input class="form-control" type="text" name="baslik" placeholder="baslik">
                        </div>
                      </div>
                        <div class="form-group row">
                        <label class="control-label col-md-3">Yazı Kategorisi</label>
                        <div class="col-md-8">
                          <select name="kategori" class="form-control">
                            <?php 
                              $kategoriler = $db->prepare("SELECT * FROM kategoriler");
                              $kategoriler->execute();
                              if($kategoriler->rowCount()){
                                foreach ($kategoriler as $row) {
                                  echo '<option value="'.$row['id'].'">'.$row['kat_adi'].'</option>';
                                }
                              }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="control-label col-md-3">Yazı Resim</label>
                        <div class="col-md-8">
                          <input class="form-control" type="file" name="resim">
                        </div>
                      </div>

                       <div class="form-group row">
                        <label class="control-label col-md-3">Yazı İçerik</label>
                        <div class="col-md-8">
                          <textarea name="icerik" class="ckeditor"></textarea>
                        </div>
                      </div>

                       <div class="form-group row">
                        <label class="control-label col-md-3">Yazı Etiketler</label>
                        <div class="col-md-8">
                          <input class="form-control" type="text" name="etiketler" placeholder="etiketler">
                        </div>
                      </div>
                  
                  </div>
                  <div class="tile-footer">
                    <div class="row">
                      <div class="col-md-8 col-md-offset-3">
                        <button class="btn btn-primary" type="submit" name="yeniyaziekle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Yeni Yazı Ekle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim?>/konular.php"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                      </div>
                    </div>
                  </div>
                  </form>
              
              <?php
        #ekleme işlemleri -end
        break;
        # duzenleme ve okuma işlemleri -start
        case 'kategoriduzenle':

          $id = get('id');
          if(!$id){
            header('Location:'.$yonetim."/kategoriler.php");
          }
          $kategoribul = $db->prepare("SELECT *FROM kategoriler WHERE id=:id");
          $kategoribul->execute([':id' => $id]);
          if($kategoribul->rowCount()){
            $row = $kategoribul->fetch(PDO::FETCH_OBJ);



            if(isset($_POST['kategoriduzenle'])){
              $kat_adi  = post('kat_adi');
              $kat_sef  = sef_link($kat_adi);
              $kat_keyw = post('kat_keyw');
              $kat_desc = post('kat_desc');

              if(!$kat_adi || !$kat_keyw || !$kat_desc){
                echo '<div class="alert alert-danger">Boş alan bırakmayınız.</div>';
              }else{

                $varmi = $db->prepare("SELECT * FROM kategoriler WHERE kat_sef=:s AND id !=:id");
                $varmi->execute([':s' => $kat_sef, ':id' => $id]);
                if($varmi->rowCount()){
                  echo '<div class="alert alert-danger">Bu kategori zaten kayıtlı.</div>';

                }else {
                  $kategori_guncelle = $db->prepare("UPDATE kategoriler SET
                        kat_adi=:adi,
                        kat_sef=:sef,
                        kat_keyw=:keyw,
                        kat_desc=:descc WHERE id=:id");
                  $kategori_guncelle->execute([':adi' => $kat_adi,':sef' => $kat_sef,':keyw' => $kat_keyw,':descc' => $kat_desc,':id' => $id]);

                  if($kategori_guncelle){
                    echo '<div class="alert alert-success">Kategori başarıyla güncellendi.</div>';
                    header('refresh:2;url='.$yonetim."/kategoriler.php");
                  }else{
                    echo '<div class="alert alert-danger">Hata oluştu.</div>';
                  }
                }
              }
            }

          }
          ?>

         <form class="form-horizontal" action="" method="post">
            <div class="tile-body">
              
                <div class="form-group row">
                  <label class="control-label col-md-3">Kategori Adı</label>
                  <div class="col-md-8">
                    <input class="form-control" value="<?php echo $row->kat_adi;?>" type="text" name="kat_adi" placeholder="kategori adı">
                  </div>
                </div>
                  <div class="form-group row">
                  <label class="control-label col-md-3">Kategori Anahtar Kelimeler</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" value="<?php echo $row->kat_keyw;?>" name="kat_keyw" placeholder="kategori anahtar kelimeler">
                  </div>
                </div>
                  <div class="form-group row">
                  <label class="control-label col-md-3">Kategori Açıklaması</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" value="<?php echo $row->kat_desc;?>" name="kat_desc" placeholder="kategori açıklaması">
                  </div>
                </div>
            
            </div>
            <div class="tile-footer">
              <div class="row">
                <div class="col-md-8 col-md-offset-3">
                  <button class="btn btn-primary" type="submit" name="kategoriduzenle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Kategori Düzenle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim?>/kategoriler.php"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                </div>
              </div>
            </div>
            </form>
        
        <?php
        break;

        case 'mesajoku':

            $id = get('id');
            if(!$id){
              header('Location:'.$yonetim."/yenimesajlar.php");
            }

            $mesajbul = $db->prepare("SELECT *FROM mesajlar WHERE id=:id");
            $mesajbul->execute([':id' => $id]);
            if($mesajbul->rowCount()){
              $row = $mesajbul->fetch(PDO::FETCH_OBJ);

              $guncelle = $db->prepare("UPDATE mesajlar SET durum=:d WHERE id=:id");
              $guncelle->execute([':d' => 1, ':id' => $id]);
              echo "<b>İsim: </b>".$row->isim."<br/>";
              echo "<b>E-Posta: </b>".$row->eposta."<br/>";
              echo "<b>Konu: </b>".$row->konu."<br/>";
              echo "<b>İçerik: </b>".$row->mesaj."<br/>";

              echo '<hr/>';
              echo '<div class="elert alert-info">Bu mesaj<b> '.date('d.m.Y H:i:', strtotime($row->tarih)).'</b> tarihinde  <b>'.$row->ip.'</b> ip adresinden göderilmiştir.</div>';
              echo '<a href="'.$yonetim.'/yenimesajlar.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i>Listeye Dön</a>';
            }else{
                header('Location:'.$yonetim."/yenimesajlar.php");
            }
        break;

        case 'yorumoku':
            $id = get('id');
            if(!$id){
              header('Location:'.$yonetim."/bekleyenyorumlar.php");
            }

            $yorumbul = $db->prepare("SELECT *FROM yorumlar 
            INNER JOIN yazilar ON yazilar.yazi_id=yorumlar.yorum_yazi_id 
            WHERE id=:id");
            $yorumbul->execute([':id' => $id]);
            if($yorumbul->rowCount()){
              $row = $yorumbul->fetch(PDO::FETCH_OBJ);

             
              echo "<b>İsim: </b>".$row->yorum_isim."<br/>";
              echo "<b>Hangi Konuya Yapıldı: </b><a href='".$arow->site_url."/postdetail.php?yazi_sef=".$row->yazi_sef."&id=".$row->yazi_id."' target='_blank'>".$row->yazi_baslik."</a><br/>";
              echo "<b>E-Posta: </b>".$row->yorum_eposta."<br/>";
              echo "<b>Website: </b>".$row->yorum_website."<br/>";
              echo "<b>İçerik: </b>".$row->yorum_icerik."<br/>";

              echo '<hr/>';
              echo '<div class="elert alert-info">Bu yorum<b> '.date('d.m.Y H:i:', strtotime($row->yorum_tarih)).'</b> tarihinde  <b>'.$row->yorum_ip.'</b> ip adresinden yapılmıştır.</div>';

              if($row->yorum_durum == 1){ ?>

                <a class="btn btn-danger" onclick="return confirm('Onaylıyor musunuz ?');" href="<?php echo $yonetim."/islemler.php?islem=yorumsil&id=".$row->id;?>"><i class="fa fa-eraser"></i>Yorumu Sil</a>
              <?php
              }else{ ?>
                 <a class="btn btn-success" onclick="return confirm('Onaylıyor musunuz ?');" href="<?php echo $yonetim."/islemler.php?islem=yorumonayla&id=".$row->id;?>"><i class="fa fa-check"></i>Yorumu Onayla</a>
            <?php  }
              echo '<a href="'.$yonetim.'/bekleyenyorumlar.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i>Listeye Dön</a>';
            }else{
                header('Location:'.$yonetim."/bekleyenyorumlar.php");
            }
        break;


        case 'yorumonayla':

            $id = get('id');
            if(!$id){
              header('Location:'.$yonetim."/bekleyenyorumlar.php");
            }

            $onayla = $db->prepare("UPDATE yorumlar SET yorum_durum=:d WHERE id=:id");
            $onayla->execute([':d'=>1, ':id' => $id]);
            if($onayla){
              echo '<div class="alert alert-success">Yorum onaylanmıştır.</div>';
              header('refresh:2;url='.$_SERVER['HTTP_REFERER']);
            }else{
              echo '<div class="alert alert-danger">Hata oluştu.</div>';
            }
        break;

        case 'sosyalmedyaduzenle':
            $id = get('id');
            if(!$id){
              header('Location:'.$yonetim."/sosyalmedya.php");
            }
            $smedyabul = $db->prepare("SELECT *FROM sosyalmedya WHERE id=:id");
            $smedyabul->execute([':id' => $id]);
            if($smedyabul->rowCount()){
              
                $row = $smedyabul->fetch(PDO::FETCH_OBJ);

                if(isset($_POST['sosyalmedyaduzenle'])){
                $ikon  = post('ikon');
                $link = post('link');
                $durum = post('durum');
                

                if(!$ikon || !$link || !$durum){
                  echo '<div class="alert alert-danger">Boş alan bırakmayınız.</div>';
                }else{

                  
                    $sosyalmedya_guncelle = $db->prepare("UPDATE sosyalmedya SET
                          ikon=:ik,
                          link=:li,
                          durum=:d WHERE id=:id
                          ");
                    $sosyalmedya_guncelle->execute([':ik' => $ikon,':li' => $link,':d' => $durum, ':id' => $id]);

                    if($sosyalmedya_guncelle){
                      echo '<div class="alert alert-success">Sosyal medya başarıyla güncellendi.</div>';
                      header('refresh:2;url='.$yonetim."/sosyalmedya.php");
                    }else{
                      echo '<div class="alert alert-danger">Hata oluştu.</div>';
                    }
                  
                }
              }
                ?>
            <form class="form-horizontal" action="" method="post">
                <div class="tile-body">
                  
                    <div class="form-group row">
                      <label class="control-label col-md-3">Sosyal Medya İkon</label>
                      <div class="col-md-8">
                        <input class="form-control" type="text" value="<?php echo $row->ikon;?>" name="ikon" placeholder="ikon">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="control-label col-md-3">Sosyal Medya Link</label>
                      <div class="col-md-8">
                        <input class="form-control" type="text" value="<?php echo $row->link;?>" name="link" placeholder="link">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="control-label col-md-3">Sosyal Medya Durumu</label>
                      <div class="col-md-8">
                        <select name="durum" class="form-control">
                          <option value="1" <?php echo $row->durum == 1 ? 'selected' : null;?>>Aktif</option>
                          <option value="2" <?php echo $row->durum == 2 ? 'selected' : null;?>>Pasif</option>
                        </select>
                      </div>
                    </div>
                
                </div>
                <div class="tile-footer">
                  <div class="row">
                    <div class="col-md-8 col-md-offset-3">
                      <button class="btn btn-primary" type="submit" name="sosyalmedyaduzenle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Sosyal Medya Düzenle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim?>/sosyalmedya.php"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                    </div>
                  </div>
                </div>
                </form>

            <?php

            }
        break;

        case 'genel':

            if(isset($_POST['genelguncel'])){

              $url = post('url');
              $baslik = post('baslik');
              $anahtar = post('anahtar');
              $aciklama = post('aciklama');
              $durum = post('durum');

                if(!$url || !$baslik || !$anahtar || !$aciklama || !$durum){
                    echo '<div class="alert alert-danger">Boş alan bırakmayınız.</div>';
                }else{

                    $guncelle = $db->prepare("UPDATE ayarlar SET
                        site_url=:url,
                        site_baslik=:baslik,
                        site_keyw=:anahtar,
                        site_desc=:aciklama,
                        site_durum=:durum WHERE id=:id
                      ");
                    $guncelle->execute([
                        ':url' => $url,
                        ':baslik' => $baslik,
                        ':anahtar' => $anahtar,
                        ':aciklama' => $aciklama,
                        ':durum' => $durum,
                        ':id' => 1 
                      ]);

                    if($guncelle){
                      echo '<div class="alert alert-success">Genel ayarlar başarıyla güncellendi.</div>';
                      header('refresh:2;url='.$_SERVER['HTTP_REFERER']);
                    }else{
                      echo '<div class="alert alert-danger">Hata oluştu.</div>';
                    }
                }
            }
                ?>
              <form class="form-horizontal" action="" method="post">
                <div class="tile-body">
                  
                    <div class="form-group row">
                      <label class="control-label col-md-3">Site URL</label>
                      <div class="col-md-8">
                        <input class="form-control" value="<?php echo $arow->site_url;?>" type="text" name="url" placeholder="Site URL">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="control-label col-md-3">Site Başlık</label>
                      <div class="col-md-8">
                        <input class="form-control" value="<?php echo $arow->site_baslik;?>" type="text" name="baslik" placeholder="Site Başlık">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="control-label col-md-3">Site Anahtar Kelimeler</label>
                      <div class="col-md-8">
                        <input class="form-control" value="<?php echo $arow->site_keyw;?>" type="text" name="anahtar" placeholder="Site Anahtar Kelimeler">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="control-label col-md-3">Site Açıklama</label>
                      <div class="col-md-8">
                        <input class="form-control" value="<?php echo $arow->site_desc;?>" type="text" name="aciklama" placeholder="Site Açıklama">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="control-label col-md-3">Site Durum</label>
                      <div class="col-md-8">
                        <select name="durum" class="form-control">
                          <option value="1" <?php echo $arow->site_durum == 1 ? 'selected' : null;?>>Aktif</option>
                          <option value="2" <?php echo $arow->site_durum == 2 ? 'selected' : null;?>>Pasif</option>
                        </select>
                      </div>
                    </div>
                     
                
                </div>
                <div class="tile-footer">
                  <div class="row">
                    <div class="col-md-8 col-md-offset-3">
                      <button class="btn btn-primary" type="submit" name="genelguncel"><i class="fa fa-fw fa-lg fa-check-circle"></i>Genel Ayarları Güncelle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim?>"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Geri Dön</a>
                    </div>
                  </div>
                </div>
              </form>
            <?php
        break;

        case 'iletisim':

            if(isset($_POST['iletisimguncelle'])){

              $mail = post('mail');


                if(!$mail){
                    echo '<div class="alert alert-danger">Boş alan bırakmayınız.</div>';
                }else{

                    $guncelle = $db->prepare("UPDATE ayarlar SET
                        site_mail=:mail WHERE id=:id
                      ");
                    $guncelle->execute([
                        ':mail' => $mail,
                        ':id' => 1
                      ]);

                    if($guncelle){
                      echo '<div class="alert alert-success">İletişim ayarları başarıyla güncellendi.</div>';
                      header('refresh:2;url='.$_SERVER['HTTP_REFERER']);
                    }else{
                      echo '<div class="alert alert-danger">Hata oluştu.</div>';
                    }
                }
            }
                ?>
              <form class="form-horizontal" action="" method="post">
                <div class="tile-body">
                  
                    <div class="form-group row">
                      <label class="control-label col-md-3">Site Mail</label>
                      <div class="col-md-8">
                        <input class="form-control" value="<?php echo $arow->site_mail;?>" type="text" name="mail" placeholder="Site URL">
                      </div>
                    </div>
                
                </div>
                <div class="tile-footer">
                  <div class="row">
                    <div class="col-md-8 col-md-offset-3">
                      <button class="btn btn-primary" type="submit" name="iletisimguncelle"><i class="fa fa-fw fa-lg fa-check-circle"></i>İletişim Ayarları Güncelle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim?>"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Geri Dön</a>
                    </div>
                  </div>
                </div>
              </form>
            <?php
        break;

        case 'logo':

          if(isset($_POST['logoduzenle'])){

              require_once 'inc/class.upload.php';
              $image = new Upload($_FILES['logo']);

            if($image->uploaded){

                $rname = md5(uniqid());
                $image->allowed = array("image/*");
                $image->image_convert = "webp";
                $image->file_new_name_body = $rname;
                $image->process("../images");

                if($image->processed){

                    $guncelle = $db->prepare("UPDATE ayarlar SET site_logo=:l");
                    $guncelle->execute([':l' => "images/".$rname.".webp"]);

                    if($guncelle){
                      echo '<div class="alert alert-success">Logo güncellendi.</div>';
                      header('refresh=2;url='.$_SERVER['HTTP_REFERER']);
                    }else{
                      echo '<div class="alert alert-danger">Hata oluştu.</div>';
                    }
                }else{
                  echo '<div class="alert alert-danger">Resim taşınmadı.</div>';
                }
            }else{
              echo '<div class="alert alert-danger">Resim seçmediniz.</div>';
            }
          }
          ?>
          <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
            <div class="tile-body">
              
                <div class="form-group row">
                  <label class="control-label col-md-3">Logo</label>
                  <div class="col-md-8">
                    <img src="<?php echo $arow->site_url;?>/<?php echo $arow->site_logo;?>" width="250" height="100"/>
                    <input class="form-control" type="file" name="logo">
                  </div>
                </div>
               
            </div>
            <div class="tile-footer">
              <div class="row">
                <div class="col-md-8 col-md-offset-3">
                  <button class="btn btn-primary" type="submit" name="logoduzenle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Logo Düzenle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim?>"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                </div>
              </div>
            </div>
            </form>
            <?php
        break;

        case 'favicon':

          if(isset($_POST['faviconduzenle'])){

              require_once 'inc/class.upload.php';

              $image = new Upload($_FILES['favicon']);

            if($image->uploaded){

                $rname = md5(uniqid());
                $image->allowed = array("image/*");
                $image->image_convert = "webp";
                $image->file_new_name_body = $rname;
                $image->process("../images");

                if($image->processed){

                    $guncelle = $db->prepare("UPDATE ayarlar SET site_favicon=:f WHERE id=:id");
                    $guncelle->execute([':f' => "images/".$rname.'.webp',':id' =>1]);

                    if($guncelle){
                      echo '<div class="alert alert-success">Favicon güncellendi.</div>';
                      header('refresh=2;url='.$_SERVER['HTTP_REFERER']);
                    }
                    else{
                      echo '<div class="alert alert-danger">Hata oluştu.</div>';
                    }
                }
                else{
                  echo '<div class="alert alert-danger">Resim taşınmadı.</div>';
                }
            }
            else{
              echo '<div class="alert alert-danger">Resim seçmediniz.</div>';
            }
          }
          ?>
          <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
            <div class="tile-body">
              
                <div class="form-group row">
                  <label class="control-label col-md-3">Favicon</label>
                  <div class="col-md-8">
                    <img src="<?php echo $arow->site_url;?>/<?php echo $arow->site_favicon;?>" width="250" height="100"/>
                    <input class="form-control" type="file" name="favicon">
                  </div>
                </div>
               
            </div>
            <div class="tile-footer">
              <div class="row">
                <div class="col-md-8 col-md-offset-3">
                  <button class="btn btn-primary" type="submit" name="faviconduzenle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Favicon Düzenle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim?>"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                </div>
              </div>
            </div>
            </form>
            <?php
        break;

        case 'dogrulama':

            if(isset($_POST['dogrulamaguncelle'])){

              $google = post('google');
              $bing = post('bing');
              $yandex = post('yandex');
              $analytics = post('analytics');


                if(!$google || !$bing || !$yandex || !$analytics){
                    echo '<div class="alert alert-danger">Boş alan bırakmayınız.</div>';
                }else{

                    $guncelle = $db->prepare("UPDATE ayarlar SET
                        google_dogrulama_kodu=:google,
                        bing_dogrulama_kodu=:bing,
                        yandex_dogrulama_kodu=:yandex,
                        analytics_kodu=:analytics WHERE id=:id
                      ");
                    $guncelle->execute([
                        ':google' => $google,
                        ':bing' => $bing,
                        ':yandex' => $yandex,
                        ':analytics' => $analytics,
                        ':id' => 1
                      ]);

                    if($guncelle){
                      echo '<div class="alert alert-success">Webmaster araçları dogrulama ayarları başarıyla güncellendi.</div>';
                      header('refresh:2;url='.$_SERVER['HTTP_REFERER']);
                    }else{
                      echo '<div class="alert alert-danger">Hata oluştu.</div>';
                    }
                }
            }
                ?>
              <form class="form-horizontal" action="" method="post">
                <div class="tile-body">
                    <div class="form-group row">
                      <label class="control-label col-md-3">Google Dogrulama Kodu</label>
                      <div class="col-md-8">
                        <input class="form-control" value="<?php echo $arow->google_dogrulama_kodu;?>" type="text" name="google">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="control-label col-md-3">Bing Dogrulama Kodu</label>
                      <div class="col-md-8">
                        <input class="form-control" value="<?php echo $arow->bing_dogrulama_kodu;?>" type="text" name="bing" >
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="control-label col-md-3">Yandex Dogrulama Kodu</label>
                      <div class="col-md-8">
                        <input class="form-control" value="<?php echo $arow->yandex_dogrulama_kodu;?>" type="text" name="yandex" >
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="control-label col-md-3">Google Analytics Kodu</label>
                      <div class="col-md-8">
                        <input class="form-control" value="<?php echo $arow->analytics_kodu;?>" type="text" name="analytics">
                      </div>
                    </div>
                </div>
                <div class="tile-footer">
                  <div class="row">
                    <div class="col-md-8 col-md-offset-3">
                      <button class="btn btn-primary" type="submit" name="dogrulamaguncelle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Dogrulama Ayarlarını Güncelle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim?>"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Geri Dön</a>
                    </div>
                  </div>
                </div>
              </form>
            <?php
        break;
        # duzenleme ve okuma işlemleri -end
        }
      }
        ?>
        </div>
        </div>
      </div>
    </main>
<?php require_once 'inc/alt.php'; ?>