<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>AMS</title>
        <script src="/js/jquery-1.8.2.js" type="text/javascript"></script>
        <script src="/js/jquery-ui-1.9.0.custom.js" type="text/javascript"></script>
        <script src="/js/jquery.tablesorter.js" type="text/javascript"></script>
        <script src="/js/bootstrap/bootstrap.js" type="text/javascript"></script>
        <script src="/js/custom.js" type="text/javascript"></script>

        <link href="/css/tableSorter.css" rel="stylesheet" />
        <link href="/css/smoothness/jquery-ui-1.9.0.custom.css" rel="stylesheet" />
        <link href="/css/bootstrap/bootstrap.css" rel="stylesheet" />
        <link href="/css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
        <link href="/css/style.css" rel="stylesheet" />


        <script type="text/javascript">
	var site_url='<?php echo site_url()?>';
	
</script>

        <style type="text/css">
            html, body {
                /*                background-color: #eee !important;*/
            }
            .navbar-inner{padding-left: 20px !important;padding-right: 20px !important;}
            .container {
                zoom : 1;
                margin-top: 70px;
            }

            /* The white background content wrapper */
            .container > .content {
                background-color: #fff;
                padding: 20px;
                margin: 0 -20px; /* negative indent the amount of the padding to maintain the grid system */
                -webkit-border-radius: 0 0 6px 6px;
                -moz-border-radius: 0 0 6px 6px;
                border-radius: 0 0 6px 6px;
                -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                box-shadow: 0 1px 2px rgba(0,0,0,.15);
            }

        </style> 
        <script type="text/javascript"> 
            $(document).ready(function() {
                $("#start_date").datepicker({dateFormat: 'yy-dd-mm'});
                
                $("#station_table").tablesorter();
            });
        </script>
    </head>

    <body>
        <div class="navbar navbar-fixed-top">
            <?php if ($this->dx_auth->is_logged_in()) { ?>
                <div class="custom-nav">
                    <a>Messages</a>
                    <a href="<?php echo site_url('auth/logout') ?>">Log Out</a>
                </div>
            <?php } ?>
            <div class="navbar-inner">
                <a class="brand" href="<?php echo site_url() ?>">AMS</a>
                <?php if ($this->dx_auth->is_logged_in()) { ?>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <li class="<?php echo active_anchor('dashboard', 'index'); ?>"><a href="">Dashboard</a></li>
                            <li class="<?php echo active_anchor('objects', 'index'); ?>"><a href="">Objects</a></li>

                            <li class="<?php echo active_anchor('stations', 'index'); ?>"><a href="<?php echo site_url('stations/index') ?>">Stations</a></li>
                            <li class="<?php echo active_anchor('reports', 'index'); ?>"><a href="">Reports</a></li>
                            <li class="<?php echo active_anchor('settings', 'index'); ?>"><a href="<?php echo site_url('settings/index') ?>">Settings</a></li> 

                        </ul>
                    </div><!--/.nav-collapse -->
                <?php } ?>

            </div>
        </div>
        <div class="container">

            <div class="content" >
               <?php if (active_anchor('settings', 'index') || active_anchor('settings', 'edit_profile') || active_anchor('templatemanager', 'add') || active_anchor('templatemanager', 'lists')) { ?>
                    <ul class="nav nav-tabs">
                        <li><a href="<?php echo site_url('templatemanager/add');?>" >Email Template</a></li>
                        <li><a href="<?php echo site_url('templatemanager/lists');?>" >List Template</a></li>
                        <li class="<?php echo active_anchor('settings', 'index'); ?>"><a href="<?php echo site_url('settings/index');?>">Users</a></li>
                        
                    </ul>
                    <script>
                        $(function () {
                            $('#myTab a:last').tab('show');
                        })
                        
                    </script>
                <?php } ?>
                {yield}
            </div>
        </div>
    </body>
</html>
