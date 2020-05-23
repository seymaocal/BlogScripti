var url="http://localhost/MyBlog";

function mesajGonder(){


	var deger=$("#iletisimformu").serialize();
	console.log(deger);
	$.ajax({
		type : "POST",
		url  : url + "/inc/mesajGonder.php",
		data : deger,
		success : function (sonuc){

			swal($.trim(sonuc));
			if($.trim(sonuc) == "bos"){
				swal("Hata:","Lütfen Tüm Alanları Doldurun","error");
			}
			else if($.trim(sonuc) == "format"){
				swal("Hata:","E-Posta Formatı Yanlış","error");
			}
			else if($.trim(sonuc) == "hata"){
				swal("Hata:","Sistem Hatası Oluştu","error");
			}
			else if($.trim(sonuc) == "basarili"){
				swal("Başarılı:","Mesajınız Alınmıştır. En Kısa Sürede Dönüş Sağlanacaktır.","success");
				$("input[name=ad]").val('');
				$("input[name=eposta]").val('');
				$("input[name=konu]").val('');
				$("textarea[name=mesaj]").val('');
				
			}
		}

	});
}

function yorumyap(){
	var deger = $('#yorumformu').serialize();
		$.ajax({
		type : "POST",
		url  : url + "/inc/yorumYap.php",
		data : deger,
		success : function (sonuc){

        //swal($.trim(sonuc));
			if($.trim(sonuc) == "bos"){
				swal("Hata:","Lütfen Tüm Alanları Doldurun","error");
			}
			else if($.trim(sonuc) == "format"){
				swal("Hata:","E-Posta Formatı Yanlış","error");
			}
			else if($.trim(sonuc) == "hata"){
				swal("Hata:","Sistem Hatası Oluştu","error");
			}
			else if($.trim(sonuc) == "basarili"){
				swal("Başarılı:","Yorumunuz Alınmıştır. Yönetici tarafından onaylandığında yayınlanacaktır.","success");
				$("input[name=adsoyad]").val('');
				$("input[name=eposta]").val('');
				$("input[name=website]").val('');
				$("textarea[name=yorum]").val('');
				
			}
			
		}

	});

}