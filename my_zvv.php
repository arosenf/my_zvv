<?php
$url = 'http://transport.opendata.ch/v1/stationboard?station=Maillartstrasse&limit=1';
$url80 = 'http://transport.opendata.ch/v1/stationboard?station=Chaletweg&limit=1';

$ch = curl_init();
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it prints the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
$response = curl_exec($ch);

curl_setopt($ch, CURLOPT_URL, $url80);
$response80 = curl_exec($ch);

curl_close($ch);

$obj = json_decode($response); // object
// $obj = json_decode($response, true); // associative array

$obj80 = json_decode($response80);

$stationboard = $obj->stationboard[0]; // there is only 1 stop at maillartstrasse
$stationboard80 = $obj80->stationboard[0];

$text = $stationboard->number . ' -> ';
$text80 = $stationboard80->number . ' -> ';

$now = new DateTime();
$now->setTimezone(new DateTimeZone('Europe/Zurich'));

$nextDeparture = $stationboard->stop->departureTimestamp;
$nextDeparture80 = $stationboard80->stop->departureTimestamp;

$dep = date_create_from_format('U', $nextDeparture);
//$dep = new DateTime();
//$dep->setTimestamp($nextDeparture);
$dep->setTimezone(new DateTimeZone('Europe/Zurich'));

$dep80 = date_create_from_format('U', $nextDeparture80);
$dep80->setTimezone(new DateTimeZone('Europe/Zurich'));

$diff = date_diff($dep, $now);
$minutes = $diff->days * 24 * 60;
$minutes += $diff->h * 60;
$minutes += $diff->i;

$diff80 = date_diff($dep80, $now);
$minutes80 = $diff80->days * 24 * 60;
$minutes80 += $diff80->h * 60;
$minutes80 += $diff80->i;

//$text = $text . $minutes . 'm ' . $diff->s . 's';
$text = $minutes . 'm';
$text80 = $minutes80 . 'm';

$out = array('frames'=>array(
    array(
        'text'=>'64: ' . $text,
        'icon'=>'i996')
,array(
        'text'=>'80: ' . $text80,
        'icon'=>'i996')
));

echo json_encode($out, JSON_PRETTY_PRINT);

//phpinfo();
?>
