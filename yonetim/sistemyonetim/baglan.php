<?php 
@session_start();
@ob_start();
@date_default_timezone_set("Europe/Istanbul");
try{
	$db = new PDO("mysql:host=localhost;dbname=myblog;charset=utf8;","root","");
	$db->query("SET CHARACTER UTF8");
	$db->query("SET NAMES UTF8");
	//echo "baglandı";
}catch(PDOException $hata){
	echo $hata->getMessage();
}



if(@$_SESSION['oturum'] == sha1(md5(@$_SESSION['id'].IP()))){

	$yoneticibul = $db->prepare("SELECT * FROM yoneticiler WHERE id=:id");
	$yoneticibul->execute([':id'=>@$_SESSION['id']]);
	if($yoneticibul->rowCount()){

		$yrow   = $yoneticibul->fetch(PDO::FETCH_OBJ);
		$yid    = $yrow->id;
		$ykadi  = $yrow->kadi;
		$yposta = $yrow->eposta;

	}
}


#ayarlar tablosuna bağlanma
$ayarlar=$db->prepare('SELECT * FROM ayarlar');
$ayarlar->execute();
$arow = $ayarlar->fetch(PDO::FETCH_OBJ);
$yonetim = $arow->site_url."/yonetim";

?>