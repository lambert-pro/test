<!DOCTYPE html>
<html>
<head>
    <title>学生成绩等级判断</title>
</head>
<body>
<h1>学生成绩等级判断</h1>
<form method='post' action=''>
    <label for='score'>请输入学生分数：</label>
    <input type='number' id='score' name='score' min='0' max='100' required>
    <input type='submit' value='提交'>
</form>

<?php
function getGrade($score)
{
    if ($score >= 90 && $score <= 100) {
        return 'A';
    } elseif ($score >= 80 && $score < 90) {
        return 'B';
    } elseif ($score >= 70 && $score < 80) {
        return 'C';
    } elseif ($score >= 60 && $score < 70) {
        return 'D';
    } elseif ($score >= 0 && $score < 60) {
        return 'E';
    } else {
        return 'Invalid score';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $score = $_POST ['score'];
    $grade = getGrade($score);
    echo "<p>学生成绩等级：$grade</p>";
}


?>
</body>
</html>