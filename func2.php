<?php 


function EarthquakeDataConvertSafeFormat($index) {

	$array = array(); // veriler bu değişkende taşınır.
	$return = array(); // dönüş verileri bu değişkene atılır.

	// kesme işlemi
	foreach (explode(' ', $index) as $key => $value) { // her boşluğu kesip verileri düzenli şekilde array değerine atar

		if (!empty(trim($value))) { // veri boşluktan ibaret değilse array değerine atar.
			array_push($array, trim($value));
		}
	}


	// verinin anlaşılması için en az 8 adet index taşımalıdır.
	if (count($array) > 9) {


		// ===== TARİH VE ZAMAN İŞLEMLERİ

		// saat dilimini ayarlama
		$hd['h'] = explode(':', $array[1])[0]; // saat
		$hd['i'] = explode(':', $array[1])[1]; // dakika
		$hd['s'] = explode(':', $array[1])[2]; // saniye
		$hd['d'] = explode('.', $array[0])[2]; // gün
		$hd['m'] = explode('.', $array[0])[1]; // ay
		$hd['Y'] = explode('.', $array[0])[0]; // yıl

		// verideki zaman dilimleri ile yeni bir datetime objesi yaratılıp türkiye saatine göre tekrardan ayarlanır.
		$date = new Datetime(date("Y-m-d H.i.s", mktime($hd['h'], $hd['i'], $hd['s'], $hd['m'], $hd['d'], $hd['Y'])));
		date_modify($date, "+0 hour"); // GTM+3 formatına dönüştürülür.


		// ===== TÜRKÇE KARAKTER SORUNU ÇÖZME
		foreach ($array as $key => $value) {
			$array[$key] = mb_convert_encoding($value, "UTF-8", "ISO-8859-9");
		}


		// ===== ATAMALAR 

		// tarih ve zaman
		$return['timecode'] = date_format($date, "YmdHis");
		$return['date'] = date_format($date, "d.m.Y");
		$return['hour'] = date_format($date, "h:i:s");

		// konum ve derinlik
		$return['latitude'] = $array[2];
		$return['longitude'] = $array[3];
		$return['depth'] = $array[4];

		// öncü artçı ve ana deprem seviyeleri
		if ($array[5] == "-.-") { $return['magnitude']['MD'] = "-"; } else { $return['magnitude']['MD'] = $array[5]; }
		if ($array[6] == "-.-") { $return['magnitude']['ML'] = "-"; } else { $return['magnitude']['ML'] = $array[6]; }
		if ($array[7] == "-.-") { $return['magnitude']['MW'] = "-"; } else { $return['magnitude']['MW'] = $array[7]; }

		// deprem çeşidi ve tarih bilgileri
		for ($i=8; $i < count($array); $i++) { 

			if ($array[$i] == "İlksel" or $array[$i] == "REVIZE01" or $array[$i] == "REVIZE02") {
				$return['region'] .= $array[$i]." ";
			} else {
				if (!strpos($return['zone'], ")", true)) {
					$return['zone'] .= $array[$i]." ";				
				} else {
					$return['revize'] = $array[$i];
				}
			}
		}

		// dönüş değerini extra olarak json formatına da dönüştür.
		$return['json'] = json_encode($return);

		$return['op'] = true; // işlem başarılı

		// verinin çekim yeri.
		$return['GET_TYPE'] = "Server";
		$return['DATA_SOURCE'] = "Koeri Boun";

	} else { // Veri bozuktur.
		$return['op'] = false;
		$return['error'] = "wrong data";
	}


	// veriyi geri döndür
	return $return;

}


?>