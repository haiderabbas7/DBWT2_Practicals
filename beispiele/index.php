<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beispiele Index</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        a {
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<h1>Beispiele</h1>

<?php
$dir = __DIR__;
$files = scandir($dir);

$files = array_diff($files, array('.', '..'));

natsort($files);

foreach ($files as $file) {
    echo "<a href='$file'>$file</a>";
}
?>
</body>
</html>
