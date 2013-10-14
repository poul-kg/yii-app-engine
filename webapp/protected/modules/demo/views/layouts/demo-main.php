<?php
$cs = Yii::app()->getClientScript();
/* @var $cs CClientScript */
$cs->registerCoreScript('jquery');
/* @var $assetManager CGAssetManager */
$assetManager = Yii::app()->getComponent('assetManager');
$ds = DIRECTORY_SEPARATOR;
$assetsDir = realpath(dirname(__FILE__) . "{$ds}..{$ds}..{$ds}assets{$ds}bootstrap");
// publish bootstrap;
$cs->registerCssFile(
    $assetManager->publish(
        $assetsDir . "{$ds}css{$ds}bootstrap.min.css"
    )
);
$cs->registerCssFile(
    Yii::app()->assetManager->publish(
        $assetsDir . '/css/bootstrap-responsive.min.css'
    )
);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Yii project skeleton for Google App Engine</title>
</head>
<body>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">Header</div>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">Content</div>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php echo $content ?>
        </div>
    </div>
</div>

<script src="http://code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
