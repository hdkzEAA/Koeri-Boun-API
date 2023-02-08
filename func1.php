<?php 


function getEarthquakeDataFromServer() {

	// uzak sunucudan verileri çek
	$response_data = file_get_contents('http://www.koeri.boun.edu.tr/scripts/lst8.asp'); // uzak siteden dosya al


	$data = explode('<pre>', $response_data)[1]; // pre tagının başından itibaren al
	$data = explode('</pre>',$data); // pre tagının sonuna kadar al
	$data = explode(PHP_EOL, trim($data[0])); // her satırı trimleyerek listeye at

	// baş metinleri ayıkla
	for ($i=0; $i <= 5; $i++) { 
		unset($data[$i]); // baş açıklama metinlerini sil
	}

	// veriyi güvenli bir formata dönüştür.
	foreach ($data as $key => $value) {

		$value = EarthquakeDataConvertSafeFormat($value);

		if ($value['op']) {
			$data[$key] = $value; // veride bir problem yok ve atandı
		} else {
			unset($data[$key]); // veri bozuk ve ana array dan da silindi
		}
	}

	return $data;
}







?>
