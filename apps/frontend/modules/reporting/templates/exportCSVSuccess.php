<?php
	echo utf8_bom();
	
    $header = array();
	foreach ($all_rows[0] as $cells) {
        $header[] = $cells[0];
    }
    echo csv_line($header);

    foreach($all_rows as $row) {
        $line = array();
        foreach($row as $cells) {
            $line[] = $cells[1];
        }
        echo csv_line($line);
    }
?>
