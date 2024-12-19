// scripts by: Mesut Akcan // http://akcansoft.blogspot.com
var gk, gkks;

function kalan(){
	var msjtxt,msjgen,azalma,kalanh;
	var maxharf = 1700;
	if (document.form1.mesaj.value != null){
		msjtxt = document.form1.mesaj.value;
		msjgen = msjtxt.length;
		if (msjgen > maxharf){document.form1.mesaj.value=msjtxt.substring(0,maxharf);kalanh = 0;}
		else {kalanh = maxharf - msjgen;}
		document.form1.gen.value = kalanh;
	}
}
/*
function GuvenlikKoduYap(){
	// var img=new Image();
	var img=document.createElement('img');
	var rsayi=Math.round(Math.random()*Math.pow(10,gkks))+'00000';
	gk = rsayi.substring(0,gkks);
	img.src = 'guvenlikkodu.php?r=' + gk;
	//img.height = 45;
	document.getElementById('gkimg').appendChild(img);
}
*/

function formGonder() {
	var formgk='';
	var hm='';
	var hata;
	var adg=document.getElementById('adsoyad').value.length;
	var mg=document.getElementById('mesajalani').value.length;
	//if (document.getElementById('gktxt')){formgk=document.getElementById('gktxt').value;}
	if (adg < 6){hm = "Ad soyad çok kısa ya da girilmemiş! (" + adg + "<6)\n";hata = 1;}
	if (mg < 10){hm += "Mesajınız çok kısa ya da girilmemiş! (" + mg + "<10)\n";hata = 1;}
	//if ((gk>0) && (gk!=formgk)){hm += "Güvenlik kodu yanlış girilmiş!\n";hata = 1;}
	if (document.getElementById('txtgk')){
		formgk=document.getElementById('txtgk').value.length;
		if (formgk < 1){hm += "Güvenlik kodu girilmemiş!\n";hata = 1;}
	}
	if (hata==1){
		hm += " \nMESAJINIZ GÖNDERİLMEDİ !\n<Lütfen hataları kontrol ediniz>";
		alert (hm);
		return false;
	}
	document.getElementById('btnGonder').disabled = true;
	return;
}

function yyaz(a,b){
	var o=document.getElementById('mesajalani');
	if(document.selection && !window.opera){
		var rg=document.selection.createRange();
		if(rg.parentElement()==o) {
			rg.text = a+rg.text+b;
			rg.select();
		}
		else o.value += a+b;
	}
	else if(o.textLength||window.opera){
		var s = o.value;
		o.value = s.substring(0,o.selectionStart)+a+s.substring(o.selectionStart,o.selectionEnd)+b+s.substring(o.selectionEnd,o.textLength);
	}
	else o.value += a+b;
	o.focus();
	//document.onmouseup = document.selection.empty;
	return false;
}
