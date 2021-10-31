<!DOCTYPE HTML>
<html>

<head>
    <title>Gender</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<?php 
    $num_item = 1;
    echo 'Your gender is'.($num_item != '1' ? ' Male' : ' Female');
?>
</body>