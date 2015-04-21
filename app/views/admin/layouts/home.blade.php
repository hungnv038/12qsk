<html>
<header>
    <meta charset="utf-8"/>
    <meta name="description" content=""/>
    <meta name="author" content="Scotch"/>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo URL::to('/') ?>/admintheme/css/bootstrap.min.css" rel="stylesheet"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


    <<!-- jQuery Version 1.11.0 -->
    <script src="<?php echo URL::to('/') ?>/admintheme/js/jquery-1.11.0.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo URL::to('/') ?>/admintheme/js/bootstrap.min.js"></script>

    @yield('header')
</header>
<body>
<div id="wrapper">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Video Management</a>
        </div>

        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="#">Add new Video</a>
                </li>
                <li class="">
                    <a href="#">Chanels</a>
                </li>
                <li class="">
                    <a href="#">Videos</a>
                </li>
            </ul>
        </div>
    </nav>
    <div id="page-wrapper" style="padding-top: 60px;">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>

</div>

</body>
@yield('footer')
</html>