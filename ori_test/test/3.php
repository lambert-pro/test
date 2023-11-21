<?php

// 函数执行加法
function add($num1, $num2)
{
    return $num1 + $num2;
}
//main
// 函数执行减法
function subtract($num1, $num2)
{
    return $num1 - $num2;
}

// 函数执行乘法
function multiply($num1, $num2)
{
    return $num1 * $num2;
}

// 函数执行除法
function divide($num1, $num2)
{
    if ($num2 == 0) {
        return '除数不能为零';
    } else {
        return $num1 / $num2;
    }
}

// 调用函数进行计算并输出结果
echo "5 + 3 =".add(5, 3)."<br>";
echo "5 - 3 =".subtract(5, 3).'<br>';
echo "5 * 3 =".multiply(5, 3).'<br>';
echo "5 / 3 =".divide(5, 3).'<br>';
?>