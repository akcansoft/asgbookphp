<script type='text/javascript' src='form.js'></script>
<form method="POST" name="form1" onSubmit="return formGonder()">
<p class="baslik">#BASLIK#</p>
<table border="0">
<tr><td>Adı Soyadı: <font color=red>*</font></td><td><input id="adsoyad" name="name" type="text" value="#ADI#"></td></tr>
<tr><td>Email:</td><td><input type="email" name="email" value="#EMAIL#"></td></tr>
<tr><td>Web:</td><td><input type="url" name="web" value="#WEB#"></td></tr>
<tr><td>Memleket:</td><td><input type="text" name="yer" value="#YER#"></td></tr>
<tr><td>&nbsp;</td>
<td>Kalan harf:
  <input type="text" size="4" name="gen" style="background-color: #FFFFFF; border: none" readonly /></td>
</tr>
<tr><td valign="top"><p>Mesaj:  <font color=red>*</font><p>
<p style="font-size:8pt; color:#888">İzin verilen<br>
HTML etiketleri:<br>
#HTMLTAGS#</p></td>
<td valign="top"><textarea id="mesajalani" rows="15" name="mesaj" cols="50" #KALAN#>#MESAJ#</textarea></td>
</tr>
<tr><td>&nbsp;</td><td id=ifadeler>
<div style="border: 1px solid #FF6600; padding: 4px 4px 1px 1px">
<input class="buton3" type="button" value="B" onclick="yyaz('<B>','</B>')">
<input class="buton3" type="button" value="I" onclick="yyaz('<I>','</I>')">
<input class="buton3" type="button" value="U" onclick="yyaz('<U>','</U>')">
<input class="buton3" type="button" value="KOD" onclick="yyaz('<CODE>','</CODE>')">
<input class="buton3" type="button" value="CEVAP" #CVPBTN# onclick="yyaz('[CEVAP]','[/CEVAP]')">
<br><br>
#iFADE#
</div></td></tr>
#GKODU#
<tr><td align="left"><!--EKLE--></td><td>
<input class="buton3" id="btnGonder" type="submit" #BUTONM# > 
<input class="buton3" type="reset" value="Sıfırla"> 
<input class="buton3" type="button" value="&lt;&lt; Geri dön" onclick="history.back();"><br/>
</td></tr></table>
<input type="hidden" name="a" value="#ACT#">
<input type="hidden" name="fgonder" value="1">
</form>