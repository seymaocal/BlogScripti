<?php 

ob_start();

try{
	$db = new PDO("mysql:host=localhost;dbname=myblog;charset=utf8;","root","");
	$db->query("SET CHARACTER UTF8");
	$db->query("SET NAMES UTF8");
	//echo "baglandı";
}catch(PDOException $hata){
	echo $hata->getMessage();
}

#ayarlar tablosuna bağlanma
$ayarlar=$db->prepare('SELECT * FROM ayarlar');
$ayarlar->execute();
$arow = $ayarlar->fetch(PDO::FETCH_OBJ);
$site = $arow->site_url;
$sitebaslik = $arow->site_baslik;
$sitekeyw = $arow->site_keyw;
$sitedesc = $arow->site_desc;
$logo = $arow->site_logo;

if($arow->site_durum != 1){
	header('Location:bakimmodu.php');
}
/*
$db=@mysqli_connect('localhost','root','','myblog');

if (mysqli_connect_errno())
  {
  echo "Bağlantı Yapılamadı. Hata :" . mysqli_connect_error();
  }
  
//Türkçe Karakter Sorunu Çözümü
$db->set_charset("utf8");
$db->query('SET NAMES utf8');

*/



/*
$ayarlar="SELECT * FROM ayarlar";
$sorgu=mysqli_query($db,$ayarlar);
$ayarcek=mysqli_fetch_assoc($sorgu);
if($ayarcek["site_durum"] != 1){
	header('Location:bakimmodu.php');
}
*/
 ?>