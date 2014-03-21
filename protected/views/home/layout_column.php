<!doctype html>
<html lang="en">
<head>
    <?php echo MetaTagManager::render(); ?>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Le styles -->
    <script type="text/javascript">
        <?php echo cc('appConfigOutput'); ?>
    </script>
</head>
<body>

    <section>
        <?php
            $this->beginContent('//home/layout_menu');
            $this->endContent();
        ?>
    </section>

    <section style="margin:20px;">

        <?php echo showFromMessage(); ?>
        <?php echo $content; ?>

        <footer class="footer">
        </footer>

    </section>

</body>
</html>
<?php

    function showFromMessage()
    {
        return cc('formMessage');
    }
