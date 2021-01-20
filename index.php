<html>
<head>
   <link href="renamed.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php
 include 'Calendar.php';

$calendar = new Calendar();
echo $calendar->show();
// calculate current week num
//ob_clean();
?>
</body>
</html>

