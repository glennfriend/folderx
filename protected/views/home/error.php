<?php

$url        = $this->createUrl('/admin/default/login');
$message    = CHtml::encode( $error['message'] );
$code       = $error['code'];
$theme      = Yii::app()->request->baseUrl . "/templates/bootstrap";

?><!doctype html>
<html lang="zh-tw">
<head>
    <meta charset="utf-8"/>
    <title>Error Page</title>
</head>
<body>

    <H1>Error <?php echo $code; ?> . admin page not found</H1>
    <div><?php echo $message; ?></div>
        
    <H2>You can choose</H2>
    <ul>
        <li>Link to <a href="<?php echo $url; ?>">login</a> page</li>
    </ul>

</body>
</html>