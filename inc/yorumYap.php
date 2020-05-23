<?php
require_once '../sistem/fonksiyon.php';

if($_POST){

$adsoyad     = post('adsoyad');
$eposta = post('eposta');
$website = post('website');
$yorum  = post('yorum');
$yaziid  = post('yaziid');


if(!$adsoyad || !$eposta  || !$yorum){
	echo "bos";
}else{
	if(!filter_var($eposta,FILTER_VALIDATE_EMAIL)){
		echo "format";
	}else{

		$kaydet = $db->prepare("INSERT INTO yorumlar SET 
			yorum_yazi_id =:yazi,
			yorum_isim =:isim,
			yorum_eposta =:eposta,
			yorum_icerik =:icerik,
			yorum_website =:web,
			yorum_durum=:d,
			yorum_ip =:ip
			");
		$kaydet->execute([
			':yazi' => $yaziid,
			':isim' => $adsoyad,
			':eposta' => $eposta,
			':icerik' => $yorum,
			':web' => $website,
			':d' => 2,
			':ip' => IP()
		]);
		if($kaydet){
			echo "basarili";
		}else{
			echo "hata";
		}

	}
}
}

?>