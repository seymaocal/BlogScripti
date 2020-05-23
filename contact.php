<?php 
define('guvenlik', true); 
require_once 'inc/header_left.php'; ?>

        <!-- site content
        ================================================== -->
        <div class="s-content content">
            <main class="row content__page">
                
                <section class="column large-full entry format-standard">

                    <div class="media-wrap">
                        <div>
                            <img src="images/thumbs/contact/contact_image.jpg" srcset="images/thumbs/contact/contact_image.jpg 2000w, 
                                      images/thumbs/contact/contact_image.jpg 1000w, 
                                      images/thumbs/contact/contact_image.jpg 500w" sizes="(max-width: 2000px) 100vw, 2000px" alt="">
                        </div>
                    </div>

                    <div class="content__page-header">
                        <h1 class="display-1">
                      Bana Ulaşın!
                        </h1>
                    </div> <!-- end content__page-header -->

                  

                    <div class="row">
                  
    
                        <div class="column large-six tab-full">
                            <h4>İletişim Bilgileri</h4>
    
                            <p><?php echo $arow->site_mail;?><br>
                            </p>
    
                        </div>
                    </div>

                    <h3 class="h2">Say Hello</h3>
        
                    <form name="contactForm" id="iletisimformu" onsubmit="return false;" method="post" action="" autocomplete="off">
                        <fieldset>
    
                            <div class="form-field">
                                <input name="ad" id="cName" class="full-width" placeholder="Adınız ve Soyadınız" value="" type="text">
                            </div>
    
                            <div class="form-field">
                                <input name="eposta" id="cEmail" class="full-width" placeholder="Mail adresiniz" value="" type="text">
                            </div>
    
                            <div class="form-field">
                                <input name="konu" id="cWebsite" class="full-width" placeholder="Konu" value="" type="text">
                            </div>
    
                            <div class="message form-field">
                                <textarea name="mesaj" id="cMessage" class="full-width" placeholder="Mesajınız"></textarea>
                            </div>
    
                            <input name="submit" id="submit" onclick="mesajGonder();" class="btn btn--primary btn-wide btn--large full-width" value="Mesaj Gönder" type="submit">
                            
    
                        </fieldset>
                    </form> <!-- end form -->

                </section>

            </main>

        </div> <!-- end s-content -->



<?php include 'inc/footer.php'; ?>


   

</body>