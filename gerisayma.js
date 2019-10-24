var saniye;
var tmp;
function gerisay() {
	saniye = document.getElementById('gerisay').innerHTML;
	saniye = parseInt(saniye, 10);
	if (saniye == 0) {
		tmp = document.getElementById('gerisay');
		return;
	}
	saniye--;
	tmp = document.getElementById('gerisay');
	tmp.innerHTML = saniye;
	timeoutMyOswego = setTimeout(gerisay, 1000);
}
gerisay();