<?php 
echo !defined('guvenlik') ? die() : null;
require_once 'sistem/fonksiyon.php'; ?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title><?php echo $tit['baslik']; ?></title>
    <meta name="author" content="<?php echo $arow->site_baslik; ?>">
    <meta name="description" content="<?php echo $tit['aciklama']; ?>">
    <meta name="keywords" content="<?php echo $tit['kelimeler']; ?>">

    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="js/sweetalert/sweetalert.css">

    <!-- script
    ================================================== -->
    <script src="js/modernizr.js"></script>
    <script src="https://kit.fontawesome.com/6e7680b38f.js" crossorigin="anonymous"></script>
   
   <!-- <script src="https://kit.fontawesome.com/6e7680b38f.js"></script> -->

    <!-- favicons
    ================================================== -->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $arow->site_url;?>/<?php echo $arow->site_favicon;?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $arow->site_url;?>/<?php echo $arow->site_favicon;?>">
    <link rel="manifest" href="site.webmanifest">

  

    <meta name="google-site-verification" content="<?php echo $arow->google_dogrulama_kodu; ?>"/>
    <meta name="msvaildate.01" content="<?php echo $arow->bing_dogrulama_kodu; ?>">
    <meta name="yandex-verification" content="<?php echo $arow->yandex_dogrulama_kodu; ?>">
    <meta name="robots" content="index, follow">
    
    

</head>

<body>

    <!-- preloader
    ================================================== -->
    <div id="preloader">
        <div id="loader" class="dots-fade">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <div id="top" class="s-wrap site-wrapper">

        <!-- site header
        ================================================== -->
        <header class="s-header">

            <div class="header__top">
                 <a class="site-logo" href="index.php">
                  <h4 style="margin-top: 0px; color:white;">Şeyma Öçal</h4></a>

                        <?php 
                        $sosyalmedya = $db->prepare("SELECT * FROM sosyalmedya WHERE durum=:d");
                        $sosyalmedya->execute([':d' => 1]);
                        if($sosyalmedya->rowCount()){
                            foreach ($sosyalmedya as $item) {
                                ?>             
                                                              
                                    <a href="<?php echo $item['link'];?>" target="_blank">
                                        <i  class="fab fa-<?php echo $item['ikon'];?>">&nbsp</i>
                                    </a>
                               
                                    <?php
                            }
                        }

                        ?>

                


                <div class="header__logo">

                  
                    <a class="site-logo" href="index.php">
                        <!--<img src="<?php echo $arow->site_logo; ?>" alt="Homepage">-->
                    </a> 
                </div>

                <div class="header__search">
    
                    <form role="search" method="get" class="header__search-form" action="search.php">
                        <label>
                            <span class="hide-content">Search for:</span>
                            <input type="search" class="header__search-field" placeholder="Arama Yapın" value="" name="q" title="Search for:" autocomplete="off">
                        </label>
                        <input type="submit" class="header__search-submit" value="Search">
                    </form>
                    <a href="index.php" title="Close Search" class="">X</a>
                   
                   <!-- <a href="search.php" title="Close Search" class="header__search-close">Close</a>-->
        
                </div>  <!-- end header__search -->

                <!-- toggles -->
                <a href="search.php" class="header__search-trigger"></a>
                <a href="#0" class="header__menu-toggle"><span>Menu</span></a>

            </div> <!-- end header__top -->

            <nav class="header__nav-wrap">

                <ul class="header__nav">
                    <li class="current"><a href="index.php" title="">Anasayfa</a></li>
                    <li class="has-children">
                        <a href="#0" title="">Kategoriler</a>
                        <ul class="sub-menu">

                            <?php 
                             $kategoriler=$db->prepare("SELECT * FROM kategoriler");
                             $kategoriler->execute();
                             if($kategoriler->rowCount())
                             {
                                foreach ($kategoriler as $row) {
                                    echo '<li><a href="'.$arow->site_url.'/category.php?kat_sef='.$row['kat_sef'].'">'.$row['kat_adi'].'</a></li>';
                                }
                             }
                           ?>
                    

                        </ul>
                    </li>
                
               
                    <li><a href="about.php" title="">Hakkımda</a></li>
                    <li><a href="<?php echo $arow->site_url.'/contact.php'; ?>" title="">İletişim</a></li>
                </ul> <!-- end header__nav -->

                
                
            </nav> <!-- end header__nav-wrap -->

          

        </header> <!-- end s-header -->