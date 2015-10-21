<?php
$url = 'http://transport.opendata.ch/v1/stationboard?station=Maillartstrasse&limit=1';

$ch = curl_init();
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it prints the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
$response = curl_exec($ch);
curl_close($ch);

$obj = json_decode($response); // object
// $obj = json_decode($response, true); // associative array

$stationboard = $obj->stationboard[0]; // there is only 1 stop at maillartstrasse

$text = $stationboard->number . ' -> ';

$now = new DateTime();
$now->setTimezone(new DateTimeZone('Europe/Zurich'));

$nextDeparture = $stationboard->stop->departureTimestamp;
$dep = date_create_from_format('U', $nextDeparture);
//$dep = new DateTime();
//$dep->setTimestamp($nextDeparture);
$dep->setTimezone(new DateTimeZone('Europe/Zurich'));

$diff = date_diff($dep, $now);
$minutes = $diff->days * 24 * 60;
$minutes += $diff->h * 60;
$minutes += $diff->i;

//$text = $text . $minutes . 'm ' . $diff->s . 's';
$text = $minutes . ':' . ($diff->s < 10 ? '0' . $diff->s : $diff->s);

$out = array('frames'=>array(
               array('index'=>0,
                     'text'=>'Bus 64',
                     'icon'=>'i996')
               ,array('index'=>1,
                     'text'=>$text,
                     'icon'=>'i996')
          ));

echo json_encode($out, JSON_PRETTY_PRINT);

?>
