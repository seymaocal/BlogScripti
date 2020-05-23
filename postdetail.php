<?php 
define('guvenlik', true);
require_once 'inc/header_left.php'; 
?>


        <!-- site content
            ================================================== -->
            <div class="s-content content">
                <main class="row content__page">

                    <?php 
                    $yazisef = strip_tags(trim($_GET['yazi_sef']));
                    $yaziid = strip_tags(trim($_GET['id']));
                    if(!$yaziid || !$yazisef){
                    header('Location:'.$arow->site_url);
                }

                //Sonraki ve Önceki konuları bulmak için yazıldı-start
                $sonrakiid = $yaziid + 1;
                $oncekiid = $yaziid - 1;

                $sonrakikonubul = $db->prepare("SELECT yazi_sef, yazi_id,yazi_baslik FROM yazilar WHERE yazi_id=:id AND yazi_durum=:d");
                $sonrakikonubul->execute([':id'=>$sonrakiid,':d'=>1]);
                $sonrakikonurow=$sonrakikonubul->fetch(PDO::FETCH_OBJ);

                $oncekikonubul = $db->prepare("SELECT yazi_sef, yazi_id,yazi_baslik FROM yazilar WHERE yazi_id=:id AND yazi_durum=:d");
                $oncekikonubul->execute([':id'=>$oncekiid,':d'=>1]);
                $oncekikonurow=$oncekikonubul->fetch(PDO::FETCH_OBJ);
                //-end


                $konubul = $db->prepare("SELECT * FROM yazilar 
                INNER JOIN kategoriler ON kategoriler.id = yazilar.yazi_kat_id
                WHERE yazi_sef=:sef AND yazi_id=:id AND yazi_durum=:d");
                $konubul->execute([':sef'=>$yazisef,':id'=>$yaziid,':d'=>1]);

                if($konubul->rowCount()){
                //burada gelen sonucu parçalayıp dizi haline getirdik.
                $row=$konubul->fetch(PDO::FETCH_OBJ);

                $goruntulenme = @$_COOKIE[$row->yazi_id];
                if(!$goruntulenme){
                $okunmasayisi = $db->prepare("UPDATE yazilar SET yazi_goruntulenme=:g WHERE yazi_id=:id");
                $okunmasayisi->execute([':g'=>$row->yazi_goruntulenme + 1,':id' => $yaziid]);
                setcookie($row->yazi_id,'1',time() + 3600);
            }
                ?>

                
                <article class="column large-full entry format-standard">

                    <div class="media-wrap entry__media">
                        <div class="entry__post-thumb">
                            <img src="<?php echo $arow->site_url;?>/<?php echo $row->yazi_resim;?>" 
                            srcset="<?php echo $row->yazi_resim;?> 2000w, 
                            <?php echo $row->yazi_resim;?> 1000w, 
                            <?php echo $row->yazi_resim;?> 500w" sizes="(max-width: 2000px) 100vw, 2000px" alt="<?php echo $row->yazi_baslik;?>">
                        </div>
                    </div>

                    <div class="content__page-header entry__header">
                        <h1 class="display-1 entry__title">
                            <?php echo $row->yazi_baslik;?>
                        </h1>
                        <ul class="entry__header-meta">
                            <li class="author"><?php echo date('d.m.Y',strtotime($row->yazi_tarih));?></li>
                            <li class="date"><?php echo $row->yazi_goruntulenme;?> Görüntülenme</li>
                            <li class="cat-links">
                                <a href="#0"><?php echo $row->kat_adi;?></a>
                            </li>
                        </ul>
                    </div> <!-- end entry__header -->

                    <div class="entry__content">

                        <div style="word-wrap: break-word;">
                      <?php echo $row->yazi_icerik;?></div>

                      <p class="entry__tags">
                        <span>Post Tags</span>

                        <span class="entry__tag-list">
                            <?php 
                            $etiketler = explode(",",$row->yazi_etiketler);
                            $dizi = array();
                            foreach($etiketler as $etiket) {
                            $dizi[] = '<a title="'.$etiket.'" href="tagdetail.php?etiket='.sef_link($etiket).'">'.$etiket.'</a> ';
                        }
                        $sonuc = implode(' ',$dizi);
                        echo $sonuc;
                        ?>
                    </span>

                </p>
            </div> <!-- end entry content -->

            <div class="entry__pagenav">
                <div class="entry__nav">
                    <div class="entry__prev">
                        <a href="<?php echo $arow->site_url;?>/postdetail.php?yazi_sef=<?php echo $oncekikonurow->yazi_sef;?>&id=<?php echo $oncekikonurow->yazi_id;?>" rel="prev">
                             <?php if($oncekikonubul->rowCount()){ ?>
                            <span>Önceki Konu</span>
                            <span><?php echo $oncekikonurow->yazi_baslik; ?></span>
                        </a>
                        <?php }else{ ?>
                        <span>Önceki Konu Bulunmuyor</span>
                        <?php } ?>
                    </div>
                    <div class="entry__next">
                        <?php if($sonrakikonubul->rowCount()){ ?>
                        <a href="<?php echo $arow->site_url;?>/postdetail.php?yazi_sef=<?php echo $sonrakikonurow->yazi_sef;?>&id=<?php echo $sonrakikonurow->yazi_id;?>" rel="next">
                            <span>Sonraki Konu</span>
                            <span><?php echo $sonrakikonurow->yazi_baslik; ?></span>
                            
                        </a>
                        <?php }else{ ?>
                        <span>Sonraki Konu Bulunmuyor</span>
                        <?php } ?>
                    </div>
                </div>
            </div> <!-- end entry__pagenav -->

            <div class="entry__related">
                <h3 class="h2">Popüler Konular</h3>

                <ul class="related">
                    <?php 
                    
                    $populer = $db->prepare("SELECT * FROM yazilar 
                    INNER JOIN kategoriler ON kategoriler.id = yazilar.yazi_kat_id
                    WHERE yazi_durum=:d ORDER BY yazi_goruntulenme DESC LIMIT :lim");
                    $populer->bindValue(':d',(int) 1, PDO::PARAM_INT);
                    $populer->bindValue(':lim',(int) 3, PDO::PARAM_INT);
                    $populer->execute();

                    if($populer->rowCount()){
                    
                    foreach($populer as $item){

                    
                    ?>

                    <li class="related__item">
                        <a href="<?php echo $arow->site_url;?>/postdetail.php?yazi_sef=<?php echo $item['yazi_sef'];?>&id=<?php echo $item['yazi_id'];?>" class="related__link">
                            <img src="<?php echo $arow->site_url;?>/<?php echo $item['yazi_resim'];?>" alt="<?php echo $item['yazi_baslik'];?>">
                        </a>
                        <h5 class="related__post-title"><?php echo $item['yazi_baslik'];?></h5>
                        
                            <p style="font-size:12px;"><?php echo date('d.m.Y',strtotime($item['yazi_tarih']));?> <?php echo $item['yazi_goruntulenme'];?> Görüntülenme  <?php echo $item['kat_adi'];?></p>
                   
                    </li>

                    <?php
                }
                }
                    ?>
                    
                    
                </ul>
            </div> <!-- end entry related -->

        </article> <!-- end column large-full entry-->


        <div style="width: 100%;" class="comments-wrap">
        <div id="comments" class="column large-12">
                    <?php 
                    $yorumlar = $db->prepare("SELECT * FROM yorumlar WHERE yorum_yazi_id=:id AND yorum_durum=:d");
                    $yorumlar->execute([':id'=> $row->yazi_id, ':d'=>1]);
                    if($yorumlar->rowCount()){  ?>
                      
                <h3 class="h2"> Yorumlar (<?php echo $yorumlar->rowCount();?>)</h3>

                <!-- START commentlist -->
                <ol class="commentlist">

                    <?php 
                    foreach ($yorumlar as $yor) { ?>
                        <li class="depth-1 comment">

                        <div class="comment__avatar">
                            <img class="avatar" src="images/avatars/unnamed.jpg" alt="yorum_profil resmi" width="50" height="50">
                        </div>

                        <div class="comment__content">

                            <div class="comment__info">
                                <div class="comment__author"><a href="<?php echo $yor['yorum_website']; ?>"><?php echo $yor['yorum_isim'];?></a></div>

                                <div class="comment__meta">
                                    <div class="comment__time"><?php echo date('d.m.Y',strtotime($yor['yorum_tarih']));?></div>
                                    
                                </div>
                            </div>

                            <div class="comment__text">
                                <p><?php echo $yor['yorum_icerik'];?></p>
                            </div>

                        </div>

                    </li> <!-- end comment level 1 -->

                    <?php
                    }
                    ?>
                   
                </ol>
                <!-- END commentlist -->

           

                    <?php 
                    }else{
                        ?>
                         <h3 class="h2"> Yorumlar(0)</h3>
                        <p>Bu konuya henüz yorum yapılmamıştır.</p>
                        <?php
                    }
                    ?>
 </div> <!-- end comments -->
            <div class="column large-12 comment-respond">

                <!-- START respond -->
                <div id="respond">

                    <h3 class="h2">Yorum Yazın <span>E-Posta hesabınız yayınlanmayacak</span></h3>

                    <form name="contactForm" id="yorumformu" method="POST" action="" onsubmit="return false;">
                        <fieldset>

                            <div class="form-field">
                                <input name="adsoyad" id="cName" class="full-width" placeholder="Ad Soyad" value="" type="text">
                            </div>

                            <div class="form-field">
                                <input name="eposta" id="cEmail" class="full-width" placeholder="E-Posta" value="" type="text">
                            </div>

                            <div class="form-field">
                                <input name="website" id="cWebsite" class="full-width" placeholder="Website" value="" type="text">
                            </div>

                            <div class="message form-field">
                                <textarea name="yorum" id="cMessage" class="full-width" placeholder="Yorum Yazınız"></textarea>
                            </div>

                            <input type="hidden" name="yaziid" value="<?php echo $row->yazi_id; ?>"/>

                            <input name="submit" id="submit" onclick="yorumyap();" class="btn btn--primary btn-wide btn--large full-width"  value="Yorum Yap" type="submit">
                            

                        </fieldset>
                    </form> <!-- end form -->

                </div>
                <!-- END respond-->

            </div> <!-- end comment-respond -->
            
        </div> <!-- end comments-wrap -->

        <?php
    
    }else{
    header('Location:'.$arow->site_url);
}

?>
</main>

</div> <!-- end s-content -->


<?php require_once 'inc/footer.php'; ?>