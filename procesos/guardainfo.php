<?php
$str_info = $_POST['info_to_save'];
$json_info = json_decode($str_info);
$bombas = $json_info->{'bombas'};
$clientes = $json_info->{'clientes'};
$fulldate = $json_info->{'full_date'};
foreach ($bombas as $nameBomba => $value) {
	print $nameBomba .' == '.$value;
}
//print(json_encode($bombas));
?>