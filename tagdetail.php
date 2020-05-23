<?php 
define('guvenlik', true);
require_once 'inc/header_left.php'; 

    $s = @intval($_GET['s']);
    if(!$s){
    $s=1;
    } 
    $etiket = strip_tags($_GET['etiket']);
    if(!$etiket){
        header('Location:'.$arow->site_url);
    }

   //toplamda kaç konu olduğunu anlamak için yazıldı.
    $sorgu=$db->prepare("SELECT yazi_kat_id,yazi_durum FROM yazilar INNER JOIN kategoriler ON kategoriler.id = yazilar.yazi_kat_id WHERE yazi_durum=:d AND yazi_sef_etiketler REGEXP :baslik");
    $sorgu->execute([':d'=>1, ':baslik' => $etiket]);
    $toplam = $sorgu->rowCount();
    $lim = 1;
    $goster = $s * $lim -$lim;  

?>
        <div class="s-content">   
            <div class="masonry-wrap">
                <h3 class="entry__title"><?php echo $etiket;?> Etiketine Ait Sonuçlar (<?php echo $toplam;?>)</h3>
                
                <div class="masonry">
                    <div class="grid-sizer"></div>
                   
                    <?php
                    $sorgu = $db->prepare("SELECT * FROM yazilar INNER JOIN kategoriler ON kategoriler.id = yazilar.yazi_kat_id WHERE yazi_durum=:d AND yazi_sef_etiketler REGEXP :baslik ORDER BY yazi_tarih DESC LIMIT :goster, :lim");

                    $sorgu->bindValue(":d",(int) 1, PDO::PARAM_INT);
                    $sorgu->bindValue(":baslik",$etiket, PDO::PARAM_STR);
                    $sorgu->bindValue(":goster",(int) $goster, PDO::PARAM_INT);
                    $sorgu->bindValue(":lim",(int) $lim, PDO::PARAM_INT);
                    $sorgu->execute();

                    if($s > ceil($toplam/$lim)){
                        $s = 1;
                    }    

                    if($sorgu->rowCount()){ 
                        foreach ($sorgu as $row) {
                           
                        
                        ?>
                          
                    <article class="masonry__brick entry format-standard animate-this">
                            
                        <div class="entry__thumb">
                            <a href="<?php echo $arow->site_url;?>/postdetail.php?yazi_sef=<?php echo $row['yazi_sef'];?>&id=<?php echo $row['yazi_id'];?>" class="entry__thumb-link">
                                <img src="<?php echo $row['yazi_resim']; ?>" 
                                        srcset="<?php echo $row['yazi_resim']; ?> 1x, images/thumbs/masonry/woodcraft-1200.jpg 2x" alt="<?php $row['yazi_baslik']; ?>">
                            </a>
                        </div>
        
                        <div class="entry__text">
                            <div class="entry__header">
    
                                <h2 class="entry__title"><a href="<?php echo $arow->site_url;?>/postdetail.php?yazi_sef=<?php echo $row['yazi_sef'];?>&id=<?php echo $row['yazi_id'];?>" class="entry__thumb-link">
                                <img src=""><?php echo $row['yazi_baslik']; ?></a></h2>
                                <div class="entry__meta">
                                    <span class="entry__meta-cat">
                                        <a href="<?php echo $arow->site_url.'/category.php?kat_sef='.$row['kat_sef']; ?>"><?php echo $row['kat_adi']; ?></a>  
                                         <a href=""><?php echo $row['yazi_goruntulenme'];?>Görüntülenme</a> 
                                    </span>
                                    <span class="entry__meta-date">
                                        <a href="single-standard.html"><?php echo date('d.m.Y',strtotime($row['yazi_tarih'])); ?></a>
                                    </span>
                                </div>
                                
                            </div>
                            <div class="entry__excerpt">
                                <p style="word-wrap: break-word;">
                                <?php echo  mb_substr($row['yazi_icerik'],0,200,'utf8').'...'; ?>
                                </p>
                            </div>
                        </div>
        
                    </article> <!-- end article -->
                  <?php
              } ?>

                      

    
    
                    
                   
    
                </div> <!-- end masonry -->

            </div> <!-- end masonry-wrap -->

<!-- Buradaaaa -->
 <div class="row">
                <div class="column large-full">
                    <nav class="pgn">
  <ul>
                                      <?php
                                if($toplam > $lim){
                                    pagination($s,ceil($toplam/$lim), 'tagdetail.php?etiket='.$etiket.'&s=');
                                }
                             ?>
                            
                        </ul>

              <?php
                            }else{
                                echo '<div class="alert alert-danger">'.$etiket.' Etiketine uygun bir yazı bulunamadı.</div>';
                            }                                              
                            ?>
         </nav>
                </div>
            </div>
        </div> <!-- end s-content -->

<li class="ss-facebook"></li>
       <?php require_once 'inc/footer.php'; ?>