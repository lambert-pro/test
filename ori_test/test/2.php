<?php

// 设定行数
$rows = 10;

for ($i = 1; $i <= $rows; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        echo '* ';
    }
    echo "<br>";
}
?>