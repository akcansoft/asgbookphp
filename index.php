<?PHP
session_start();
ob_start();
/*========================================
 aSgbookPHP
 akcanSoft Guestbook PHP v2.5.191103 1530
 
 © 2003-2019 Mesut Akcan
 
 makcan@gmail.com
 http://www.akcanSoft.com
 http://youtube.com/mesutakcan
 http://facebook.com/akcansoft
 http://twitter.com/akcansoft

==> Neler Yeni <===
* Mesaj gönderimini geçici devre dışı bırakma özelliği eklendi
* Mesaj düzenleme ve silme işlemi sonunda 1. sayfa açılıyordu. Mesajın olduğu sayfa açılıyor.
* Mesaj düzenlenip gönderildikten sonra şablon varsayılan oluyordu. Düzeltildi.
* Kodlarda iyileştirmeler yapıldı. PHP 7'ye uygun değişiklikler yapıldı

==> YAPILACAKLAR <===
* data Dosyasına yazmada hata varsa başta belirt
* SAYFA NUMARASI LİNKTEKİ SAYFA NO İLE AYNI DEĞİL
* SAYFA ŞABLONUNU COOKIE OLARAK KAYDET VE OKU
* Mesaj şablonunu düzenleme ekle
* Mesajda izin verilen etiketleri belirle #edit# #name# #yer# #time# #email# #web# #ip# gibi
* Github üzerinden sürüm kontrolü
=========================================*/

//error_reporting(0); // Hata raporlama tamamen kapalı
//error_reporting(E_ALL); // Tüm hataları göster
//error_reporting(E_ERROR | E_WARNING | E_PARSE); // Basit çalışma hatalarını göster
//error_reporting(E_ALL ^ E_NOTICE); // Notice Hataları hariç tüm hataları göster
//error_reporting(E_ALL ^ E_WARNING); // Warning Hataları hariç tüm hataları göster
//error_reporting(E_ERROR | E_PARSE);
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$config_file = "ayarlar.php";
$tmpdir ="templates/";

global $setok;
global $pname;
global $homepage;
global $template;
global $templateselectable;
global $admin_email;
global $chrset;
global $tformat;
global $msgcnt;
global $mpp;
global $linkkonum;
global $sf;
global $wait_time;
global $gk;
global $gkks;
global $htmltags;
global $web2link;
global $data_file;
global $admin_pwd;
global $sendmsg2me;
global $entersil;
global $mesajiptal;

if(file_exists($config_file)){
	include ($config_file);
	}
else{
	hatamsj("HATA: Ayarlar dosyası bulunamadı",
	"Ayarların kaydedildiği <b>$config_file</b> dosyası bulunamadı !");
	exit;
}
//$_SESSION['kod'] = session_id(); // Resimli güvenlik kodu içindi. İptal
$sca = "aSgbookPHP v";
$ver = "2.5.191103";
$scradr = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

$pno=isset($_GET['pno']) ? (int)$_GET['pno'] : 0; // sayfa no
if ($pno<=0){$pno=0;}
$sd=''; // sayfa şablonu
if ($templateselectable == 1){ // "Ziyaretçi şablonu değiştirebilir" ayarlıysa
	if (isset($_GET['sd'])){
		$sd = htmkodsil($_GET['sd']);
		if(file_exists($tmpdir.$sd."/index.htm")){$template=$sd;}
		else{$sd='';}
	}
}
$yonlen = "Refresh: 5; url=$scradr?sd=$sd"; // ana sayfaya yönlendirme kodu
if ($template == ''){$template='notebook';} // şablon belirtilmemişse şablon notebook
$l = isset($_GET['l']) ? intval($_GET['l']) : null; // onay bekleyen mesajları filtrele = 1, tümü = 0
$r_pwd = isset($_SESSION['reg_pwd']) ? $_SESSION['reg_pwd'] : null;
$c_pwd = isset($_COOKIE['c_pwd']) ? $_COOKIE['c_pwd'] : null;

// doğru parola ile giriş yapıldıysa
if ((($admin_pwd == $r_pwd) and (isset($r_pwd))) OR (($admin_pwd == $c_pwd) and (isset($c_pwd)))){
	$admin=TRUE;$admin_msg = "Çıkış";$log = "out";$mno=isset($_REQUEST['mno']) ? (int)$_REQUEST['mno'] : 0;}
else{$admin=FALSE;unset($l);unset($mno);$admin_msg = "Giriş";$log = "in";}

$btn_msj="<a class='buton' href='$scradr'>Mesajlar</a>";
$btn_ayar="<a class='buton' href='$scradr?a=ayar'>Ayarlar</a>";
$a=isset($_REQUEST['a']) ? htmkodsil($_REQUEST['a']) : "";
$cde=base64_decode('aHR0cDovL3d3dy5ha2NhbnNvZnQuY29t');
switch ($a){
case "login": // Yönetici giriş form 
    sayfabasligi("Yönetici Giriş Formu");
    echo "<div class='baslik'>asGbookPHP Yönetici Giriş</div><form method='POST'><input type='hidden' name='a' value='login2'><table><tr><td align='right'>Yönetici Parolası:</td><td><input type='password' name='pass'> <input type='submit' value='Gönder' class='buton3'></td></tr><tr><td> </td><td><label><input name='hatirla' type='checkbox' value=1>Beni hatırla</label></td></tr></table></form>";
    break;
case "login2": // YÖNETİCİ GİRİŞ 
	$pass=htmkodsil($_POST['pass']); // yönetici giriş parolası
	if ($pass == $admin_pwd){  // girilen parola kayıtlı parola ile aynıysa
		$_SESSION['reg_pwd'] = $admin_pwd;  // oturuma parolayı kaydet
		if (isset($_POST['hatirla']) and (intval($_POST['hatirla'])== 1)){ // "Beni hatırla" işaretli ise
			$cerez_suresi=time()+2600000;} // 1 ay hatırla
		else{$cerez_suresi=time()-3600;} // unut
		setcookie("c_pwd", $admin_pwd, $cerez_suresi); // çerez kaydı
		sayfabasligi("Yönetici Giriş Kontrol");
		echo "<div class=baslik>asGbookPHP Yönetici Giriş Kontrol</div>";
		echo "<div class=divb>Yönetici girişi başlarıyla yapıldı</div><br>$btn_msj $btn_ayar";
	}
	else{
		hatamsj("Yönetici Giriş kontrol","Parola geçersiz. Giriş yapılmadı.");
		echo $btn_msj;
	}
	yonlendir();
	break;
case "logout": // Yönetici Çıkış *******************************
	unset($_SESSION['reg_pwd']);
	if (isset($c_pwd)){setcookie("c_pwd", $admin_pwd,time()-3600);}
	sayfabasligi("Yönetici Çıkış");
	echo "<div class=baslik>asGbookPHP Yönetici Çıkış</div>";
	echo "<div class=divb>Yönetici çıkışı yapıldı</div><br>$btn_msj";
	yonlendir();
	break;
case "form": // Mesaj Yazma Formu ***********************
	sayfabasligi("Yeni Mesaj Yaz");
	echo "<noscript>Bu sayfa için JavaScript'e izin verilmelidir. Tarayıcı ayarlarını kontrol ediniz.<br><br>This page needs JavaScript activated to work.<br><br><a class='buton' href='$scradr'>Ziyaretçi Defteri</a><style>div { display:none; }</style></noscript><div>";
	if  (strstr($scradr, 'index.php/')){
		echo "Üzgünüm :(<div class=satir>Geçersiz işlem !</div><a class='buton' href='../'>Mesajlar</a>";
		break;
	}
	if ($mesajiptal == 1){ // mesaj gönderme devre dışı ise
		echo "Üzgünüm :(<div class=satir>Mesaj gönderme geçici olarak devre dışıdır !</div><a class='buton' href='./'>Mesajlar</a>";
		break;
	}
	if (isset($_COOKIE["sgs"])){  // sgs=son gönderi saati
		$tf=time()-$_COOKIE["sgs"];
	}
	else{
		if (isset($_SESSION['sgs'])){
			$tf=time()-$_SESSION['sgs'];
		}
		else{$tf=$wait_time +1;}
	}
	if ($tf>$wait_time){ // Yeni mesaj yazmaj için bekleme süresi dolduysa
		$data = implode('',file("form_mesajgonder.h"));
		$butonm = "value=' Mesajı gönder '";
		if(!(file_exists($data_file))){
			$data .= "<div class='uyarimsj'>$data_file Data dosyası bulunamadığı için mesaj kaydedilemeyecek !</div><br>";
		}
		if (!(is_writable($data_file))){ // data dosyası kaydedilebilir değilse
			$data .= "<div class='uyarimsj'>$data_file Data dosyasına yazma sorunu nedeniyle mesaj kaydedilemeyecek !</div></br>";
			$butonm .= " disabled=disabled title='Data dosyasına kayıt sorunu var'";
		}
		if ($msgcnt !=0){ // mesajlar kontrol edilecekse
			$data .= "<div class='uyarimsj'>Mesajınız incelendikten sonra yayınlanacaktır !</div>";
		}
		if ($gk == 1){ // Güvenlik kodu olacaksa
			if ($gkks<1){$gkks=1;}
			elseif ($gkks>5){$gkks=5;}
			$gkod = strtoupper(substr(md5(rand(0,999999)),-1 * $gkks));
			$_SESSION['sgk'] = $gkod; // gkodunu oturuma kaydet
			$gkodyerine = "<tr><td valign='top'>Güvenlik kodu:</td><td><input type='text' id='txtgk' name='txtgk' size=5> <b><span style='font-family:Times; color:#FFF; font-size: 20px; background-color:#008; padding:0px 10px;'>$gkod</span></b> <span style='font-size:12px'>yandaki kodun aynısını giriniz.</span></td></tr>";
		}
		$ifadeliste = file("img/ifade/liste.txt");
		$ifadeyerine='';
        foreach ($ifadeliste as $satir){
			list($ifade,$dosya) = explode("\t",$satir,2);
			$ifadeyerine .= "<a href=\"javascript:;\" onClick=\"javascript:yyaz('$ifade','')\"><img src=\"img/ifade/$dosya\" title=\"$ifade\"></a> ";
        }
		$trtable = array(
			"#BASLIK#" => "Mesaj Yaz",
			"<!--EKLE-->" => "",
			"#YER#" => "",
			"#ADI#" => "",
			"#EMAIL#" => "",
			"#WEB#" => "",
			"#GKODU#" => $gkodyerine,
			"#HTMLTAGS#" => htmlspecialchars($htmltags),
			"#KALAN#" => " onkeyup='kalan()'",
			"#MESAJ#" => "",
			"#iFADE#" => $ifadeyerine,
			"#BUTONM#" => $butonm,
			"#CVPBTN#" => "hidden",
			"#ACT#" => "post");
		echo (strtr($data,$trtable));
	}
	else{ // yeni mesaj için bekleme süresi dolmadıysa
		$bekleme = $wait_time - $tf;
		// $lnk_msj_gnd = "<a class='buton3' href='?a=form$eksd'>Mesaj Yaz</a> ";
		echo "<div class=satir>Üzgünüm :(<br>Yeni bir mesaj yazmak için <b><span id='gerisay'>$bekleme</span></b> saniye beklemelisiniz</div><script type='text/javascript' src='gerisayma.js'></script><br>$btn_msj"; // $lnk_msj_gnd";
		}
	echo "</div>";
    break;
case "edit": // Mesaj Düzenleme Formu  *************************
	if ($admin){
		sayfabasligi("Mesaj Değiştir/Sil");
		$butonm = "value=' Değişiklikleri kaydet '";
		if (!(is_writable($data_file))){
			$butonm .= " disabled=disabled title='Data dosyasına kayıt sorunu var'";
		}
		$data = file($data_file);
		$data = array_reverse ($data);
				
		list($time,$check,$ra,$name,$email,$yer,$web,$mesaj) = explode("||",$data[$mno]);
		$data2 = implode('',file("form_mesajgonder.h"));
		$data2 = str_replace("#sayfabasligi#","Mesajı Düzenle: Değiştir/Sil",$data2);
		$frm_ek  = "<input type='hidden' name='time' value='$time'>";
		$frm_ek .= "<input type='hidden' name='ra' value='$ra'>"; // ip no
		$frm_ek .= "<input type='hidden' name='mno' value='$mno'>";
		$frm_ek .= "<input type='hidden' name='check' value='$check'>";
		$frm_ek .= "<input type='checkbox' name='sil' value='YES'><span style='color:red'>Mesajı Sil</span>";
		$frm_ek .= "<br><input type='checkbox' name='check' value='YES'";
		if ($check == 1){$frm_ek .=" checked";}
		$frm_ek .= "><span style='color:green'>Mesajı Onayla</span>";
		$mesaj = str_replace("<br>","\n",$mesaj);
		$mesaj = str_replace('#S#' , "\n" , $mesaj);
		$ifadeliste = file("img/ifade/liste.txt");
		$ifadeyerine="";
		foreach ($ifadeliste as $satir){
			list($ifade,$dosya) = explode("\t",$satir,2);
			$ifadeyerine .= "<a href=\"javascript:;\" onClick=\"javascript:yyaz('$ifade','')\"><img src=\"img/ifade/$dosya\" title=\"$ifade\"></a> ";
		}
		$trtable = array(
			"<!--EKLE-->" => $frm_ek,
			"#BASLIK#" => "Mesaj Değiştir/Sil",
			"#ADI#" => $name,
			"#EMAIL#" => $email,
			"#WEB#" => $web,
			"#YER#" => $yer,
			"#HTMLTAGS#" => htmlspecialchars($htmltags),
			"#KALAN#" => "",
			"#MESAJ#" => $mesaj,
			"#BUTONM#" => $butonm,
			"#iFADE#" => $ifadeyerine,
			"#CVPBTN#" => "",
			"#GKODU#" => "",
			"#ACT#" => "post2");
		echo (strtr($data2,$trtable));
    }
	else{header ("Location: $scradr?a=login&sd=$sd");} // login sayfasına git
	break;
case "post": // Gönder - Kaydet *************************
	if (isset($_POST['fgonder'])) { // gönderi form ile gönderildiyse
		if ($gk == 1){
			if (strtoupper($_POST['txtgk']) != $_SESSION['sgk']){
				// oturumdaki kod ile formdaki kod aynı değilse
				hatamsj("Hata: Güvenlik kodu !","Güvenlik kodu yanlış girildi.");
				break;
			}
		}
		$time = time(); // mesaj tarih saati
		$name = htmkodsil($_POST['name']); // html kodları engelle
		$yer = htmkodsil($_POST['yer']);
		$email=$_POST['email'];
		$web = $_POST['web'];
		if (strlen($name) > 25){$name=substr($name,0,25);}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)){$email='';}
		if (!filter_var($web, FILTER_VALIDATE_URL)){$web='';}
		if (strlen($yer) > 25){$yer=substr($yer,0,25);}
		$mesaj = stripslashes($_POST['mesaj']);
		$mesaj = str_replace("||","&#124;&#124;",$mesaj);
		$mesaj = str_replace("\r","",$mesaj);
		// birden çok boş satırı tek'e indir
		if ($entersil==1){$mesaj = preg_replace("/\n+/","\n",$mesaj);}
		$mail_mesaj = $mesaj; // emaille gönderilecek mesaj
		// $mesaj = htmkodsil($mesaj,$htmltags);
		$mesaj = str_replace("\n","<br>",$mesaj);
		$dp = fopen($data_file, "a");
		flock($dp,2);
		// mesajlar hemen yayınlanacaksa onaylı kaydet
		if ($msgcnt==0){$check=1;}else{$check=0;}
		$ip = userip(); // Kullanıcı ip no al
		if (fwrite($dp, "$time||$check||$ip||$name||$email||$yer||$web||$mesaj||\n") === FALSE) {
			 hatamsj("HATA: Data dosyasına yazılamadı",
			 "Mesajların kaydedildiği data dosyasına bir sorun yüzünden kayıt yapılamıyor.<br>Mesajınız kaydedilmedi.");
			 break;
		}
		flock($dp,3);
		fclose($dp);
		$_SESSION['sgs'] = time(); // sgs=son gönderi saati
		setcookie("sgs", time(), time()+$wait_time); 
		// yöneticiye e-maili var ve mesaj yöneticiye e-maille gönderilecekse
		if (($sendmsg2me == 1) and ($admin_email)){
			$time = date($tformat,((int)$sf*60+(int)$time));
			if ($email == ""){$email=$admin_email;}
			$mail_mesaj .="\r\n\r\n--\r\nIP:$ip\r\nTARİH:$time\r\n$scradr\r\n";
			mail($admin_email, "$pname ZD yeni mesaj $name", $mail_mesaj,
			"Content-type: text/html; charset=$chrset\r\n"
			."From: $name <$email>\r\n"
			."Reply-To: $name <$email>\r\n"
			."X-Mailer: PHP/" . phpversion());
		}
		sayfabasligi("Mesaj gönderildi");
		echo "<div class=baslik>Mesajınız Gönderildi</div>";
		echo "<div class=divb>Mesajınız gönderilmiştir. İlginizden dolayı teşekkürler.";
		if ($msgcnt !=0){ // mesajlar kontrol edilecekse
			echo "<br><div class=uyarimsj>Mesajınız incelendikten sonra yayınlanacaktır.</div>";
		}
		echo "</div><br>$btn_msj";
	}
	else{ // form dışı kullanım
		hatamsj("Geçersiz kullanım !","Bu şekilde bir kullanıma izin verilmez.");
	}
	yonlendir();
	break;
case "post2": // Değiştir/Sil & Kaydet ********************
	if ($admin){  //  and (isset($_POST['fgonder'])))
		$data = file($data_file);
		$data = array_reverse ($data);
		// formda sil işaretli ise kaydı sil
		$sil=$_REQUEST['sil'];
		if ($sil=="YES"){
			unset ($data[$mno]);
		}
		else{
			// Mesajı onayla işaretli ise onaylı kaydet
			$check = $_POST['check'];
			if ($check == "YES"){$check=1;} else {$check='';}
			$name = htmkodsil($_POST['name']);
			$yer = htmkodsil($_POST['yer']);
			$email=$_POST['email'];
			$web = $_POST['web'];
			if (strlen($name) > 25){$name=substr($name,0,25);}
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)){unset ($email);}
			if (!filter_var($web, FILTER_VALIDATE_URL)){unset ($web);}
			$mesaj = stripslashes($_POST['mesaj']);
			$mesaj = str_replace("||","&#124;&#124;",$mesaj);
			$mesaj = str_replace("\r","",$mesaj);
			$mesaj = preg_replace("/\n+/","\n",$mesaj);
			//$mesaj = nl2br($mesaj);
			$mesaj = str_replace("\n","<br>",$mesaj);
			$ra=$_POST['ra'];
			$time=$_POST['time'];
			$data[$mno] = "$time||$check||$ra||$name||$email||$yer||$web||$mesaj||\n";
		}
		$data = array_reverse ($data);
		$data = implode('',$data);
		//$data = preg_replace("/\r+/","",$data);
		$dp = fopen($data_file, "w");
		flock($dp,2);
		if (fwrite($dp, $data) === FALSE) {
			hatamsj("HATA: Data dosyasına yazılamadı",
			"Mesajların kaydedildiği data dosyasına bir sorun yüzünden kayıt yapılamıyor.<br>Mesajınız kaydedilmedi.");
			yonlendir();
			break;
			}
		flock($dp,3);
		fclose($dp);
		$_REQUEST['pno'];
		$adr = "?pno=$pno";
		if (isset($l)){$adr .="&l=$l";}
		if (isset($sd)){$adr .="&sd=$sd";}
		header("Location: $scradr$adr");
	}
	else{header ("Location: $scradr?a=login&sd=$sd");}
	break;
case "onay": // Bekleyen Mesajı onayla
	if ($admin){
		$data = file($data_file);
		$data = array_reverse ($data);
		list($time,$check,$ra,$name,$email,$yer,$web,$mesaj) = explode("||",$data[$mno]);
		$data[$mno] = "$time||1||$ra||$name||$email||$yer||$web||$mesaj||\n";
		$data = array_reverse ($data);
		$data = implode('',$data);
		$data = preg_replace('/\r+/',"",$data);
		$dp = fopen($data_file, "w");
		flock($dp,2);
		if (fwrite($dp, $data) === FALSE){
			hatamsj("HATA: Data dosyasına yazılamadı",
			"Mesajların kaydedildiği data dosyasına bir sorun yüzünden kayıt yapılamadı.<br>Mesajınız kaydedilmedi.");
			yonlendir();
			break;
		}
		flock($dp,3);
		fclose($dp);
		$adr = "?pno=$pno";
		if (isset($l)){$adr .="&l=$l";}
		if (isset($sd)){$adr .="&sd=$sd";}
		header("Location: $adr");
	}
	else{header ("Location: $scradr?a=login&sd=$sd");}
	break;
case "ayar": // Ayarlar Sayfası
	$err=0;
	if ($admin){
		sayfabasligi ("Ziyaretçi Defteri Ayarları");
		echo "<div class=baslik>$pname Ziyaretçi Defteri Ayarları</div>";
		// Config dosyası varlığı ve yazma özelliği kontrolü
		echo "<div class=divb>";
		if (file_exists($config_file)){
			echo "<b>$config_file</b> dosyasının yazma özelliği: ";
			if (is_writable($config_file)){echo "<span style='color:green'><b>AÇIK</b></span>";}
			else {
				echo "<span style='color:red'><b>KAPALI</b></span><br>";
				echo "Bu yüzden ayarları kaydedemeyeceksiniz.<br>Dosyaya yazma özelliği atayınız.";
				$err=1;
			}
		}
		else{
			echo "<b>$config_file</b> ayarlar dosyası <span style='color:red'><b>YOK</b></span><br>Dosyayı oluşturunuz";
			$err=1;
		}
		// sürüm kontrolü
		/*********************
		2.4 sürümünden sonra sürüm kontrolü iptal edildi
		// YAPILACAK : Github üzerinden sürüm kontrolü 
		
		$sonsurum = file_get_contents('http://www.akcansoft.com/versions/asgbookphp',FILE_TEXT);
		if ($sonsurum != FALSE){
			$sonsurum2 = explode(".",$sonsurum);
			$ver2 = explode(".",$ver);
			echo "<br><br><div><b>Kullanılan sürüm:</b> $ver<br><b>Son sürüm:</b> $sonsurum</div>";
			if ($ver2[2] < $sonsurum2[2]){
				echo "<div class='uyarimsj'>Eski sürüm kullanıyorsunuz. Yeni Sürüm çıktı.</div>";
				echo "<div>Yeni sürümü <a href='http://akcansoft.com/asgbookphp_ziyaretcidefteri.htm'><b>script web sayfası</b></a>ndan indirebilirsiniz.</div>";
			}
			if ($ver2[2] == $sonsurum2[2]){
				echo "<div><span style='color:green'><b>Son sürümü kullanıyorsunuz</b></span></div>";
			}
		}
		***************************/
		echo "</div>";
		if ($err==0){include ("form_ayarlar.h");}
	}
	else{header ("Location: $scradr?a=login&sd=$sd");}
	break;
case "ayarkaydet": // Ayarları kaydet
    if ($admin){
		// YAPILACAK: Şifre eşit değilse mesaj kutusu ile bildir.
		// YAPILACAK: Şifreyi göster seçeneği ekle
		if ($_POST['admin_pwd1'] != $_POST['admin_pwd2']){
			hatamsj("HATA: Parolalar eşit değil !","Parolalar eşit değil !.");
			break;
  		}
		$data = "<?PHP\n";
		$_POST['admin_pwd'] = $_POST['admin_pwd1'];
		unset ($_POST['admin_pwd1']);
		unset ($_POST['admin_pwd2']);
		unset ($_POST['a']);

		foreach ($_POST as $anahtar=>$deger ){$data .= "$"."$anahtar = \"$deger\" ;\n";}
		$data .= "?>\n";
	 	$dp = fopen($config_file, "w");
	 	flock($dp,2);
		if (fwrite($dp, $data) === FALSE){
			hatamsj("HATA: Data dosyasına yazılamadı",
			"Ayarların kaydedildiği data dosyasına bir sorun yüzünden kayıt yapılamadı.<br>Ayarlar kaydedilmedi.");
			break;
		}
	 	flock($dp,3);
	 	fclose($dp);
	 	sayfabasligi("İşlem Tamam");
	 	echo "<div class='satir'>İşlem Tamam.<br>Ayarlar kaydedildi.</div><br>$btn_msj";
		yonlendir();
	}
	else{header ("Location: $scradr?a=login&sd=$sd");}
	break;
case "otms": // onaylanmamış tüm mesajları sil
	if ($admin){
		$data = file($data_file);
		foreach ($data as $sno=>$satir){
			list($time,$check,$ra,$name,$email,$yer,$web,$mesaj) = explode("||",$satir);
			if ($check == 0){unset($data[$sno]);}
		}
		$dp = fopen($data_file, "w");
		flock($dp,2);
		$data = implode('',$data);
		if (fwrite($dp, $data) === FALSE){
			hatamsj("HATA: Data dosyasına yazılamadı",
			"Mesajların kaydedildiği data dosyasına bir sorun yüzünden kayıt yapılamadı.<br>Silinme işlemi gerçekleşmedi.");
			break;
		}
		flock($dp,3);
		fclose($dp);
		header("Location: $scradr?&sd=$sd");
	}
	else{header ("Location: $scradr?a=login&sd=$sd");}
	break;
default: // Mesajları listele
	// congfig.php de $setok = 1 yoksa
	if ($setok != 1){
		// yönetici girişi yapıldı ise ayarlar sayfasına
		if ($admin){header("Location: $scradr?a=ayar");}
		sayfabasligi("Kurulum");
		echo "<div class='baslik'>aSgbookPHP Ziyaretçi Defteri Kurulumu</div>";
		$err=0;
		// Data dosyası varlığı ve yazma özelliği kontrolü
		if (file_exists($data_file)){
			echo "<b>$data_file</b> dosyasına yazma özelliği: ";
			if (is_writable($data_file)){
				echo "<span style='color:green'><b>AÇIK</b></span><br><br>";
			}
			else {
				echo "<span style='color:red'><b>KAPALI</b></span><br>Dosyaya yazma özelliği atayınız. Mesajların kaydedilmesi için bu gerekli.<br>";
				$err=1;
			}
		}
		else{
			echo "<b>$data_file</b> data dosyası <span style='color:red'><b>YOK</b></span><br>Dosyayı oluşturunuz";
			$err=1;
		}
		// Dosyada sorun yoksa
		if ($err == 0){
			// Kuruluma devam et
			echo "Yönetici girişi yapıp ziyaretçi defteri ayarlarını düzenleyiniz.<br><br>";
			echo "İlk kurulum yönetici parolası <b>admin</b> 'dir.<br>Parolayı değiştirmeyi unutmayınız.<br><br>";
			echo "<span style='color:red'><b>Devam etmek için sayfayı en alta kaydırınız.</b></span>";
			include ("README_TR.htm");
			echo "<a class='buton' href='$scradr?a=login'> Devam </a>";
		}
		// Dosyalarda sorun varsa. Kuruluma devam etme
		else{
			echo "<br><br><span style='color:red'>Kurulum devam edemiyor !</span>";
		}
		exit;
	}
	/// Config de setok=1 ayarlı. Ayarlama yapılmış ise	
	sayfabasligi("Ziyaretçi Defteri Mesajları");
	echo "<div class=baslik>$pname Ziyaretçi Defteri Mesajları</div><br>";
	if(file_exists($tmpdir.$template."/index.htm")){
		$templatef = implode('',file($tmpdir.$template."/index.htm"));} //template dosya içeriği al
	else{
		echo "<b>HATA !</b><br>$tmpdir$template/index.htm bulunamadı<br>Eksik dosyayı yükleyiniz ya da başka sayfa şablonu seçiniz<br><br>$btn_ayar - <a class='buton' href='$scradr'>Ziyaretçi defteri ana sayfa</a>";
		exit;	
	}
	// dosya varsa verileri al
	if (file_exists($data_file)){
		$data = file($data_file);
		$msg_count = count($data);
	}
	else{
		$msg_count = 0;
	}
	if (isset($l)){$ekl="&l=$l";} else {$ekl="";} // linke eklenecek &l=
	if (isset($sd)){$eksd="&sd=$sd";} else {$eksd="";} // linke eklenecek &sd=
	// önceki butonu
	if($pno==0){ // sayfa no 0 ise önceki linki pasif
		$lnk_onceki_btn = "<span class='buton'  span style='color:#aaa'>&laquo; Önceki</span>";
	}
	else{
		$gcc = $pno-1;	// $gcc -> 	geçici sayfa no
		$lnk_onceki_btn = "<a class='buton' href='?pno=$gcc$ekl$eksd'>&laquo; Önceki</a>";
	}
	$gcc = $pno * $mpp + $mpp;
	// sonraki butonu
	if ($msg_count > $gcc){ 
		$gcc = $pno + 1;
		$lnk_sonraki_btn = "<a class='buton' href='?pno=$gcc$ekl$eksd'>Sonraki &raquo;</a>";
	}
	else { // Son sayfada ise sonraki butonu pasif
		$lnk_sonraki_btn = "<span class='buton' style='color:#aaa'>Sonraki &raquo;</span>";
	}
	// mesaj yaz linki
	if ($mesajiptal == 1){ // mesaj yazma kapalı ise link pasif
		$lnk_msj_gnd = "<span style='color:#ddd; background-color:#bbb; padding:5px;'> Mesaj Yazma Kapalı ! </span> ";
	}
	else{
		$lnk_msj_gnd = "<a class='buton3' href='?a=form$eksd'>Mesaj Yaz</a> ";
	}
	// admin ise ayarlar linki ekle
	if ($admin) {
		$lnk_ayarlar = $btn_ayar;
	}
	else {
		$lnk_ayarlar = "<a class='buton' href='$homepage'>Ana Sayfa</a>";
	}
	$lnk_admin = "<a class='buton' href='?a=log$log&sd=$sd'>Yönetici $admin_msg</a>";
	$lnk_toplam_msj = "Toplam : <b>$msg_count</b> mesaj";

	// Sayfalar linklerini hazırla
	$sayfasayisi = ceil($msg_count/$mpp);

	$lnk_sayfalar="";
	// 1. sayfa ve ...
	if ($pno>2){
		$lnk_sno=$sayfasayisi-1;
		$lnk_sayfalar ="<a class='buton' href='?pno=0$ekl$eksd'>1</a>...";
	}
	// sayfa no ve ona 2 yakın sayfa linkleri
	$ilkr = $pno-2;$sonr = $pno+2;
	if ($ilkr<0){$ilkr=0;}
	if ($sonr>($sayfasayisi-1)){$sonr=($sayfasayisi-1);}
	
	for ($sn=$ilkr;$sn<=$sonr;$sn++){
		$lnk_sno=$sn+1;
		// sayfa numarası ise buton basık
		if ($sn == $pno){$lnk_sayfalar .= "<span class='buton2'>$lnk_sno</span>";}
        // buton normal
		else{$lnk_sayfalar .= "<a class='buton' href='?pno=$sn$ekl$eksd'>$lnk_sno</a>";}
	}
	// ... - Son sayfa - sonraki
	if (($sayfasayisi-$pno)>3){
		$lnk_sno=$sayfasayisi-1;
		$lnk_sayfalar .="...<a class='buton' href='?pno=$lnk_sno$ekl$eksd'>$sayfasayisi</a>";
	}
	$lnk_otmg="";
	$lnk_ony_tum="";
	if ($admin){
		if ($l == 0){$lnk_ony_tum = "<a class='buton' href='$scradr?l=1&pno=$pno$eksd'>Yalnız onay bekleyenleri göster</a>";}
		else{$lnk_ony_tum = "<a class='buton' href='$scradr?pno=$pno$eksd'>Tümünü göster</a>";}
		$lnk_otmg = " <a class='buton' href='$scradr?a=otms$eksd'>Onaylanmamış tüm mesajları sil</a>";
	}
	$frm_sablon_sec='';
	// Şablon listesi gösterilecekse şablon listesi oluştur.
	if ($templateselectable == 1){
		$frm_sablon_sec = "<form>Sayfa Şablonu: <select size=1 name='sd'>";
		$s = 0; $klasor = array();
		if ($dizin = opendir($tmpdir)){
			while (false !== ($dosya = readdir($dizin))) {
				if ($dosya != "." && $dosya != ".." && $dosya != "index.htm" ) {
					$klasor[$s] = $dosya;
					$s++;
				}
			}
			closedir($dizin);
			sort($klasor);
			foreach ($klasor as $skn){
				$frm_sablon_sec .= "<option";
				if ($template == $skn ){$frm_sablon_sec .= " selected";}
				$frm_sablon_sec .= ">$skn</option>";
			}
			$frm_sablon_sec .= "<input class='buton3' type='submit' value='Değiştir'></select></form>";
		}
	}
	
	$links ="<div class=satir>$lnk_ony_tum $lnk_otmg $lnk_ayarlar $lnk_admin</div>";
	$links .="<div class=satir>$frm_sablon_sec</div>";
	$links .="<div class=satir>$lnk_sayfalar $lnk_onceki_btn $lnk_sonraki_btn</div>";
	$links .="<div class=satir>$lnk_toplam_msj</div>";
	$links .="<div class=satir>$lnk_msj_gnd</div>";

	// linkleri göster
	if ($linkkonum != 2){echo $links;}

	if ($msg_count){
		// mesaj varsa
		// ifade dosyasını al
		$ifadeliste = file("img/ifade/liste.txt");
		$yeniifadeliste = array();
		foreach ($ifadeliste as $satir){
			list($ifade,$dosya) = explode("\t",$satir,2);
			$ifade = trim($ifade); $dosya = trim($dosya);
			$yeniifadeliste[$ifade] = "<img src='img/ifade/$dosya'/>";
		}
		unset ($ifadeliste);
		// sırayı ters çevir
		$data = array_reverse ($data);
		$first_m = $pno * $mpp;
		$last_m = $first_m + $mpp;
		if ($last_m > $msg_count){$last_m=$msg_count;}

		// ilk ve son mesajlar
		for ($lno=$first_m; $lno<$last_m; $lno++){
			$temp = $templatef;
			list($time,$check,$ip,$name,$email,$yer,$web,$mesaj) = explode("||",$data[$lno]);
			// yönetici giriş yaptı ve yalnız onay bekleyenler listele ise onaylıları geç
			if (($check == 1) and ($admin) and ($l == 1)) {continue;}
			// admin değil ve mesaj onaysız ve mesaj gösterilmesin ayarlı ise
			// mesajı göstermeden sonraki mesaja atla
			if (($msgcnt == 2) and ($check == 0) and (!$admin)){continue;}
			// Ad girilmemişse Adsız yaz
			if ($name==""){$name="Adsız";}
			$time = date($tformat,((int)$sf*60+(int)$time));
			
			// CODE arasını kodla
			$mesaj = htmlspecialchars($mesaj);
			$mesaj = preg_replace("|&lt;CODE&gt;(.*?)&lt;/CODE&gt;|i" , "<div class=satir><b><u>KOD:</u></b></div><pre><CODE>$1</CODE></pre>" ,$mesaj);

			$mesaj = preg_replace("|\[CEVAP\](.*?)\[\/CEVAP\]|i" , '<div class=satir><b><u>Cevap:</u></b></div><div class=cevap>$1</div>' , $mesaj);
			$mesaj = str_ireplace('&lt;b&gt;' , "<b>" , $mesaj);
			$mesaj = str_ireplace('&lt;u&gt;' , "<u>" , $mesaj);
			$mesaj = str_ireplace('&lt;i&gt;' , "<i>" , $mesaj);
			$mesaj = str_ireplace('&lt;/b&gt;' , "</b>" , $mesaj);
			$mesaj = str_ireplace('&lt;/u&gt;' , "</u>" , $mesaj);
			$mesaj = str_ireplace('&lt;/i&gt;' , "</i>" , $mesaj);
			$mesaj = str_ireplace('&lt;br&gt;' , "<br>" , $mesaj);		
			
			$mesaj = stripslashes($mesaj);
			// ifadeleri resme dönüştür
			$mesaj = strtr($mesaj,$yeniifadeliste);
			// kelimeler uzunsa böl
			// $mesaj = preg_replace("([^ ]{60})", "\\1<br>", $mesaj);
			// web adresleri linklere dönüştürülecekse
			if ($web2link == 1){
				$mesaj = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.-]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $mesaj);
				$mesaj = preg_replace("/([\n >\(])www((\.[\w\-_]+)+(:[\d]+)?((\/[\w\-_%]+(\.[\w\-_%]+)*)|(\/[~]?[\w\-_%]*))*(\/?(\?[&;=\w\+%]+)*)?(#[\w\-_]*)?)/", "\\1<a target=\"_blank\" href=\"http://www\\2\">www\\2</a> ", $mesaj);
			}
			$temp = str_replace('#time#',$time,$temp);
			$temp = str_replace('#name#',$name,$temp);
			// yönetici giriş yaptıysa ; yönetici fonksiyonlarını ekle
			if ($admin){
				if (isset($l)){$ekl="&l=$l";}
				if (isset($sd)){$eksd="&sd=$sd";}
				// mesaj onaylı değilse
				if ($check != 1){$mesaj = "<span style='color:red'><b>MESAJ ONAY BEKLİYOR !</b></span><br>$mesaj";}
				$admno = $lno+1;
				$replace  ="$admno <a href='?a=edit&mno=$lno&pno=$pno$eksd'><img border=0 src='img/ed.gif' title='Düzenle'></a> ";
				$replace .="<a href='?a=post2&pno=$pno$ekl$eksd&mno=$lno&sil=YES'>";
				$replace .="<img border=0 src='img/del.gif' title='Sil'></a> ";
				if ($check == 0){$replace .= "<a href='?a=onay&mno=$lno&pno=$pno$ekl$eksd'><img border=0 src='img/ok.gif' title='Onayla'></a>";}
			}
			else {
				if ($check==0){$mesaj = "<span style='color:red'><b>MESAJINIZ ONAY BEKLİYOR !</b></span>";}
				$replace='';
				// ip'in son rakamlarını ### e dönüştür
				$ip = preg_replace("/(\d+)\.(\d+)\.(\d+)\.(\d+)/","$1.$2.$3.###",$ip);
			}
			$temp = str_replace('#edit#',$replace,$temp);
			$temp = str_replace('#ip#',"<img border=0 src='img/ip.gif' title='IP:$ip'>",$temp);
			// email varsa linke dönüştür
			if ($email){$replace = "<a href='mailto:$email'><img border=0 src='img/em.gif' title='$email'></a>";}
			else{$replace = '';}
			$temp = str_replace('#email#',$replace,$temp);
			$temp = str_replace('#yer#',$yer,$temp);
			// web sayfası varsa linke dönüştür
			if ($web){$replace = "<a target='_blank' href='$web'><img border=0 src='img/web.gif' title='$web'></a>";}
			else{$replace = '';}
			$temp = str_replace('#web#',$replace,$temp);
			$temp = str_replace('#message#',$mesaj,$temp);if(md5($cde)=="2878f1eead3d0fcd4fae797487c73dfe"){echo $temp;}else{echo base64_decode('OiggQmlyIMWfZXlsZXIgdGVycyBnaXR0aSAhPGJyPg==');}
		}
	}
	else{echo "Kayıtlı mesaj yok";}
	if ($linkkonum != 1){echo "<br>$links";}
}
echo "<hr width='50%' noshade size=1><span style='background-color:#ddd;font-size:8pt;'> <a href='$cde'>$sca$ver</a></div></body></html>";
exit;

// FONKSİYONLAR 

// ana sayfaya yönlendirme
function yonlendir(){
global $yonlen;
?>
<div style="margin:10px">Ziyaretçi defteri sayfasına otomatik yönlendiriliyor...</div>
<progress id="pbar" max="100" value="1" style="width:500px; height:20px"></progress>
<script>
var elem = document.getElementById("pbar");
var width = 1;
var id = setInterval(frame, 50);
function frame() {
if (width >= 100) {
clearInterval(id);
} else {
width++;
elem.value = width;}}
</script>
<?PHP
header($yonlen);
}

function sayfabasligi ($ptitle){
	global $chrset, $template, $tmpdir;
	//header("Content-type: text/html;charset=$chrset");
	echo "<html><head><title>$ptitle</title>";
	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=$chrset\">";
	echo "<link href='$tmpdir$template/template.css' rel='stylesheet' type='text/css'></head><body><div align='center'>";
}

// HATA Mesaj sayfası
function hatamsj ($ptitle,$hmesaj){
	sayfabasligi($ptitle);
	echo "<div class=baslik>asGbookPHP</div><h3>Bir hata oluştu</h3>
	<div class=uyarimsj>$hmesaj</div><br><br><a class='buton' href='javascript:history.back()'>Geri Dön</a>";
}

/// kodları temizle
function htmkodsil ($postcode,$htmtag='') {
	$postcode = strip_tags($postcode,$htmtag);
	$postcode = str_replace("||","",$postcode);
	return $postcode;
}

/// Kullanıcı IP adresi
function userip(){
if(isSet($_SERVER)){
if(isSet($_SERVER["HTTP_X_FORWARDED_FOR"])){
$IP=$_SERVER["HTTP_X_FORWARDED_FOR"];
}elseif(isSet($_SERVER["HTTP_CLIENT_IP"])){
$IP=$_SERVER["HTTP_CLIENT_IP"];}
else{$IP=$_SERVER["REMOTE_ADDR"];}}
else{if(getenv('HTTP_X_FORWARDED_FOR')){
$IP=getenv('HTTP_X_FORWARDED_FOR');} 
elseif(getenv('HTTP_CLIENT_IP')){$IP=getenv('HTTP_CLIENT_IP');}
else{$IP=getenv('REMOTE_ADDR');}}
return $IP;}
?>
