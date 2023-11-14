<?php
$kemu = [
    '理论',"科目一","科目二"
];

echo "数组kemu中：<br>";

foreach($kemu as $key=>$val){
    echo "第".($key+1)."个元素：".$val."<br>";
}