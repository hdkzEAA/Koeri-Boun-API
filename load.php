<?php

include 'func1.php'; // uzak siteden veri çekme fonksiyonu
include 'func2.php'; // çekilen text verisini düzgün bir php array fornatına dönüştürme fonksiyonu


$fonksiyon_listesi = array(
	'getEarthquakeDataFromServer' => "uzak siteden verileri alır ve düzenler. pre",
	'EarthquakeDataConvertSafeFormat' => "alınan verileri düzenli formata döndürür.",
);


?>

<pre>
	<?php print_r(getEarthquakeDataFromServer()); ?>
</pre>
