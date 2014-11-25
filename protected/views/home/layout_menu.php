<!-- Top Navbar =========================================== -->
<div class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!--
            <a class="navbar-brand" href="#">Backend</a>
            -->
        </div>
        <div class="navbar-collapse collapse">

            <ul class="nav navbar-nav">
                <li><a href="<?php echo $this->createUrl('/'); ?>">Home</a></li>
                <li><a href="<?php echo $this->createUrl('/home/reindex'); ?>">重新索引</a></li>
            </ul>

            <ul class="nav navbar-nav pull-right">

            </ul>

        </div>
    </div>
</div>
<!-- Top Navbar =========================================== -->
