<style type="text/css">
.frm a{font:12px Verdana}
.frm a[data]{position: relative;}
.frm a[data]::after{
content: attr(data);
display: block;
position: absolute;
padding: 4px 8px;
color: black;
border-radius: 8px;
left: 20px;
width:250px;
background-color:#FC0 ;
border:1px black solid;
z-index: 2;
transform: scale(0);
transition:transform ease-out 150ms;
}
.frm a[data]:hover::after{
	transform: scale(1);
}
</style>

<script>
function dakika() {
    var t = document.getElementById("saniye").value;
	var d = parseInt(t/86400)+'g '+(new Date(t%86400*1000)).toUTCString().replace(/.*(\d{2}):(\d{2}):(\d{2}).*/, "$1:$2:$3");
    document.getElementById("dakika").innerHTML = d;
}
</script>

<div class="frm">
<form method="POST">
<input type=hidden name="a" value="ayarkaydet">
<input type=hidden name="setok" value="1">
<table cellpadding="2" bordercolor="#FF9933" border="1" cellspacing="3">
<colgroup align="right" bgcolor="#FFBB77"></colgroup>
<colgroup bgcolor="#FFFFFF"></colgroup>
<tr>
<td >Web Sayfa Adı</td>
<td>
<input name="pname" size="35" value="<?php echo "$pname"; ?>">
<a data='Web sayfa adını girin. Bu ad ziyaretçi defteri başlığında da görünür'><img src="img/tooltip.gif"></a>
</td>
</tr>
<tr>
<td>Ana Sayfa Adresi</td>
<td>
<input name="homepage" size="30" value="<?php echo "$homepage"; ?>">
<a data='Ziyaretçi defterinden ana sayfaya erişmek için link bulunur. Bu link tıklandığında açılacak ana sayfa adresini giriniz. http:// kısmını unutmayın'>
<img src="img/tooltip.gif"></a>
</td>
</tr>
<tr>
<td>Sayfa şablonu(tema)</td>
<td><select size="1" name="template">
<?PHP
// templates klasöründeki klasörleri al
if ($dizin = opendir('templates')) {
while (false !== ($dosya = readdir($dizin))) {
if ($dosya != "." && $dosya != ".." && $dosya != "index.htm" ){$klasor[$s] = $dosya;$s++;}
}
closedir($dizin);
sort($klasor);
foreach ($klasor as $tmpl){
echo "<option";
if ($template == $tmpl ){echo " selected";}
echo ">$tmpl</option>";
}
}
?>
</select>
<a data='Sayfanın görünümünü var olan bir arayüz ile kolayca değiştirebilirsiniz.'>
<img src="img/tooltip.gif"></a>
</td>
</tr>
<tr>
<td>Ziyaretçi şablonu değiştirebilir</td>
<td>
<p>
<input type="checkbox" name="templateselectable" value="1" <?php if ($templateselectable==1) {echo "checked";} ?> />
Evet
<a data='İşaretli ise ziyaretçi defteri mesajlar sayfasında arayüz açılır listesi görünür. Listeden arayüz seçerek değiştirebilir. Bu değişiklik sadece o ziyaretçiyi etkiler. Kalıcı ayar değişikliği yapmaz.'>
<img src="img/tooltip.gif"></a>
</td>
</tr>
<tr>
<td>Yönetici Parolası</td>
<td>
<p><input name="admin_pwd1" size="20" value="<?php echo "$admin_pwd"; ?>" type="password">
<a data='Ziyaretçi defterinin ayarlarını yapmak ve mesajları yönetmek için bir parola belirleyin.Parolayı değiştirmeyecekseniz yeniden girmeniz gerekmez.'>
<img src="img/tooltip.gif"></a>
</td>
</tr>
<tr>
<td>Yönetici Parolası(tekrar)</td>
<td>
<input name="admin_pwd2" size="20" value="<?php echo "$admin_pwd"; ?>" type="password">
<a data='Üstteki parolanın aynısını girin'>
<img src="img/tooltip.gif"></a>
</td>
</tr>
<?php unset ($admin_pwd); ?>
<tr>
<td>Yönetici E-maili</td>
<td>
<input name="admin_email" size="30" value="<?php echo "$admin_email"; ?>">
<a data='Email adresini girin. Eğer mesajaların bir kopyasının emailinize gönderilmesini isterseniz mesajlar bu emaile gönderilir.'>
<img src="img/tooltip.gif"></a> 
</td>
</tr>
<tr>
<td>Sayfa dil karakter kodu</td>
<td>
<input name="chrset" size="20" value="<?php echo "$chrset"; ?>">
<a data='Sayfadaki yazıların doğru karakterlerle gösterilmesi için kullanılan dile uygun karakter kodunu giriniz. Türkçe için: ISO-8859-9'><img src="img/tooltip.gif"></a> 
</td>
</tr>
<tr>
<td>Tarih biçimi</td>
<td>
<input name="tformat" size="20" value="<?php echo "$tformat"; ?>">
<a data='Mesajların tarih ve saatinin gösteriliş biçim kodunu giriniz. Detaylı bilgiyi http://tr.php.net/manual/en/function.date.php adresinden edinebilirsiniz. Türkiye için: d/m/Y H:i girebilirsiniz.'><img src="img/tooltip.gif"></a> 
</td>
</tr>
<tr>
<td>Mesaj yazıldıktan sonra</td>
<td>
<input type="radio" value="0" name="msgcnt" <?php if ($msgcnt==0){echo "checked";} ?>>Hemen yayınla
<a data='Mesaj onaylı kaydedilir ve onay beklemeden yayınlanır'><img src="img/tooltip.gif"></a> 

<br>
<input type="radio" value="1" name="msgcnt" <?php if ($msgcnt==1){echo "checked";} ?>>&quot;<span style='color:red'>Mesajınız Onay Bekliyor</span>&quot; yaz
<a data='Mesaj içeriği onaylanana kadar görünmez. Mesaj içeriği dışındaki bilgiler görünür.Mesaj onay bekliyor uyarı yazısı görünür.'><img src="img/tooltip.gif"></a>


<br>
<input type="radio" value="2" name="msgcnt" <?php if ($msgcnt==2){echo "checked";} ?>>Mesajı 
gösterme 
<a data='Mesaj onaylanana kadar mesaj ile ilgili hiçbir bilgi görünmez. Ancak yönetici görebilir.'><img src="img/tooltip.gif"></a> 

</td>
</tr>
<tr>
<td>Mesaj e-maille yöneticiye de gitsin</td>
<td>
<input type="checkbox" name="sendmsg2me" value="1" <?php if ($sendmsg2me==1) {echo "checked";} ?>>
<a data='İşaretli ise mesajların bir kopyası email adresinize gönderilir.'><img src="img/tooltip.gif"></a>
</td>
</tr>
<tr>
<td>Sayfada Mesaj Sayısı</td>
<td><input name="mpp" size="4" value="<?php echo "$mpp"; ?>"> 
<a data='Bir sayfada gösterilecek mesaj sayısını belirleyiniz.'><img src="img/tooltip.gif"></a>
</td>
</tr>
<td> Sayfa linkleri gösterme konumu
</td>
<td><p>
<input type="radio" value="1" name="linkkonum" <?php if ($linkkonum==1){echo "checked";} ?> />
Sayfa başında 
<a data='Sayfa üstünde sayfa linkleri gösterilir.'><img src="img/tooltip.gif"></a>
<br />
<input type="radio" value="2" name="linkkonum" <?php if ($linkkonum==2){echo "checked";} ?> />
Sayfa sonunda 
<a data='Sayfa altında sayfa linkleri gösterilir.'><img src="img/tooltip.gif"></a>
<br />
<input type="radio" value="0" name="linkkonum" <?php if ($linkkonum==0){echo "checked";} ?> />
Her ikisinde 
<a data='Sayfa üstü ve altında sayfa linkleri gösterilir.'><img src="img/tooltip.gif"></a>
</p></td>
</tr>
<tr>
<td>Saat Farkı </td>
<td><input name="sf" size="6" value="<?php echo "$sf"; ?>"> 
dakika
<a data='Sunucu saati ile yerel bölgenizin saat farkını girerek tarih ve saatin doğru görünmesini sağlayabilirsiniz.'><img src="img/tooltip.gif"></a> 

<br>
Sunucu saati: <?php echo date("d/m/Y H:i"); ?></td>
</tr>
<tr> 
<td>Aynı bilgisayardan<br>
yeni mesaj göndermek için<br>
bekleme aralığı </td>
<td>
<input id="saniye" name="wait_time" size="6" value="<?php echo "$wait_time"; ?>" onkeyup="dakika()"> saniye
= <span id="dakika"></span>
<script>dakika();</script>
<a data='Aynı bilgisayardan ard ardına mesajlar yazarak ziyaretçi defterini kötüye kullananları önlemek için bekleme süresi belirleyiniz. 0(Sıfır) girerseniz hiç beklmeden tekrar mesaj gönderebilir'><img src="img/tooltip.gif"></a>
</td>
</tr>
<tr>
<td>
Mesaj gönderiminde güvenlik kodu</td>
<td>
<input type="checkbox" name="gk" value="1"<?php if ($gk==1) {echo "checked";} ?>>Olsun
<input name="gkks" size="2" value="<?php echo "$gkks"; ?>">
karakter
(1-5) 
<a data='Mesaj gönderme formunda güvenlik kodu seçili ise mesaj gönderen kişi çıkan güvenlik kodunu girmelidir.Karakter sayısı oluşacak güvenlik kod uzunluğudur. 1 ile 5 arası bir rakam giriniz'><img src="img/tooltip.gif"></a>
</td>
</tr>
<tr>
<td>
İzin verilen HTML etiketleri</td>
<td>
<input name="htmltags" size="40" value="<?php echo "$htmltags"; ?>"> 
<a data='Mesajlarda izin verilen HTML etiketlerini belirtiniz.&gt;&lt;B&gt;&lt;I&gt;&lt;U&gt;&lt;CODE&gt; gibi'><img src="img/tooltip.gif"></a>
</td>
</tr>
<tr>
<td>
Web adreslerini köprüye dönüştür</td>
<td>
<input type="checkbox" name="web2link" value="1" <?php if ($web2link==1) {echo "checked";} ?>>Evet 
<a data='İşaretli ise mesajlardaki web adresleri otomatik linke dönüştürülür.'><img src="img/tooltip.gif"></a>
</td>
</tr>
<tr>
<td>
Birden çok boş satırı engelle</td>
<td>
<input type="checkbox" name="entersil" value="1" <?php if ($entersil==1) {echo "checked";} ?>>Evet 
<a data='İşaretli ise mesajlardaki birden çok sayıda entere basılmışsa bunları tek entere dönüştürür. Böylece fazladan boş satırlar silinir.'><img src="img/tooltip.gif"></a>
</td>
</tr>

<tr>
<td>Mesaj yazma devre dışı</td>

<td>
<input type="checkbox" name="mesajiptal" value="1" <?php if ($mesajiptal==1) {echo "checked";} ?>>Evet 
<a data='İşaretli ise mesaj yazma özelliği devere dışı bırakılır.'><img src="img/tooltip.gif"></a>
</td>
</tr>

<tr>
<td>Mesaj data dosyası</td>
<td>
	<input name="data_file" value="<?PHP echo $data_file; ?>"> 
	<a data='Mesajların kaydedildiği data dosyası adı. VAR ve YAZILABİLİR olması gerekir'><img src="img/tooltip.gif"></a>
	<br>
	<?PHP 
	if (file_exists($data_file)){
	echo "<span style='color:green'><b>DOSYA VAR,</b></span>";
	if (is_writable($data_file)){echo " <span style='color:green'><b>YAZILABİLİR</b></span>";}else {echo " <span style='color:red'><b>YAZILAMAZ</b></span>";}
	}
	else {echo "<span style='color:red'><b>DOSYA YOK,</b></span>";}?>
</td>
</tr>

</table>
<input class="buton3" type="submit" value="Ayarları Kaydet">
<input class="buton3" type="button" value="&lt;&lt; Geri dön" onclick="history.back();">
</form>
</div>
