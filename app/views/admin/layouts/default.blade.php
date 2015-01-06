<!doctype html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="description" content=""/>
    <meta name="author" content="Scotch"/>
    
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo URL::to('/') ?>/admintheme/css/bootstrap.min.css" rel="stylesheet"/>

    <!-- Custom CSS -->
    <link href="<?php echo URL::to('/') ?>/admintheme/css/sb-admin.css" rel="stylesheet"/>

    <!-- Morris Charts CSS -->
    <link href="<?php echo URL::to('/') ?>/admintheme/css/plugins/morris.css" rel="stylesheet"/>

    <!-- Custom Fonts -->
    <link href="<?php echo URL::to('/') ?>/admintheme/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo URL::to('/') ?>/admintheme/js/plugins/pretty/pretty-json.css" rel="stylesheet" type="text/css"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    
    <!-- jQuery Version 1.11.0 -->
    <script src="<?php echo URL::to('/') ?>/admintheme/js/jquery-1.11.0.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo URL::to('/') ?>/admintheme/js/bootstrap.min.js"></script>
    <script src="<?php echo URL::to('/') ?>/admintheme/js/plugins/morris/raphael.min.js"></script>
    <script src="<?php echo URL::to('/') ?>/admintheme/js/plugins/morris/morris.min.js"></script>
    <script src="<?php echo URL::to('/') ?>/admintheme/js/plugins/pretty/underscore-min.js"></script>
    <script src="<?php echo URL::to('/') ?>/admintheme/js/plugins/pretty/backbone-min.js"></script>
    <script src="<?php echo URL::to('/') ?>/admintheme/js/plugins/pretty/pretty-json-min.js"></script>

    @yield('header')

</head>
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
            <a class="navbar-brand" href="<?php echo URL::to('/logs') ?>">MovieHot</a>
        </div>
        
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
                <li class="{{Request::segment(1) == 'logs' ? 'active' : ''}}">
                    <a href="{{URL::to('/logs')}}">Logs</a>
                </li>

                <li class="dropdown {{(Request::segment(1) == 'setApiDoc' || Request::segment(1) == 'apidocs') ? 'active' : ''}}">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-puzzle-piece"></i> REST APIs <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo URL::to('/apidocs') ?>"><i class="fa fa-table"></i> APIs Docs</a></li>
                        <li><a href="<?php echo URL::to('/setApiDoc') ?>"><i class="fa fa-users"></i> APIs Manager</a></li>
                    </ul>
                </li>
            </ul>
        </div>    
    </nav>
    
    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- /.row -->
            <div style="min-height: 400px;">
                @yield('content')
            </div>
        </div>
    </div>
</div>
</body>
</html>
