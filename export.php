<?php

$list = json_decode($_POST['content'], true);
$file = fopen("export.csv","w");

$header_row = array_keys(flatten($list['data'][0]['statuses'][0]));
fputcsv($file,$header_row);


foreach ($list['data'] as $key => $row) {
	foreach ($row as $key => $lines) {
		foreach ( $lines as $line ) {
			fputcsv($file,flatten($line));
		}
    }
}

fclose($file);

echo json_encode('success');

function flatten($array, $prefix = '') {
    $result = array();
    foreach($array as $key=>$value) {
        if(is_array($value)) {
            $result = $result + flatten($value, $prefix . $key . '.');
        }
        else {
            $result[$prefix.$key] = $value;
        }
    }
    return $result;
}