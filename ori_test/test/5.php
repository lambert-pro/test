<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <title>无标题文档</title>
</head>

<body>
<?php
print_r(range(1, 6));die;
$shuaizi = [1,2,3,4,5,6];
$key1 = [];
$key2 = [];
for ($i=0;$i<6;$i++){
    $key1[] = array_rand($shuaizi,1);
    $key2[] = array_rand($shuaizi,1);
}

$sum1 = 0;
$fenshu1 = '';
foreach ($key1 as $key => $value) {
    $sum1 += $shuaizi[$value];
    $fenshu1 .= "第".($key+1)."次掷筛子分数为：".$shuaizi[$value]."<br>";
}

$sum2 = 0;
$fenshu2 = '';
foreach ($key2 as $key => $value) {
    $sum2 += $shuaizi[$value];
    $fenshu2 .= '第'.($key + 1).'次掷筛子分数为：'.$shuaizi[$value].'<br>';
}

echo "甲：<br>";
echo $fenshu1;
echo "甲总分数：".$sum1;
echo '<br><br>';

echo '乙：<br>';
echo $fenshu2;
echo '乙总分数：'.$sum2;
echo '<br><br>';

if ($sum1 > $sum2) {
    echo '甲胜';
} elseif ($sum1 == $sum2) {
    echo '平局';
} else {
    echo '乙胜';
}

?>
</body>
</html>