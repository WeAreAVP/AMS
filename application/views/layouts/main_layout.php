<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>AMS</title>
        <?php
        echo link_js('jquery-1.8.2.js');
        echo link_js('jquery-ui-1.9.0.custom.js');
        echo link_js('jquery.tablesorter.js');
        echo link_js('bootstrap/bootstrap.js');
        echo link_js('jquery.freezetablecolumns.1.1.js');

        echo link_js('custom.js');
        echo link_tag("css/tableSorter.css");
        echo link_tag("css/smoothness/jquery-ui-1.9.0.custom.css");
        

        echo link_tag("css/bootstrap/bootstrap.css");
        echo link_tag("css/bootstrap/bootstrap-responsive.css");
        echo link_tag("css/style.css");
        ?> 
        <script type="text/javascript">
            var site_url='<?php echo site_url() ?>';
	
        </script>


        <script type="text/javascript"> 
            $(document).ready(function() {
                
                var dates = $( "#start_date, #end_date" ).datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 1,
                    dateFormat: 'yy-mm-dd',
                    onSelect: function( selectedDate ) {
                        var option = this.id == "start_date" ? "minDate" : "maxDate",
                        instance = $( this ).data( "datepicker" ),
                        date = $.datepicker.parseDate(
                        instance.settings.dateFormat ||
                            $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings );
                        dates.not( this ).datepicker( "option", option, date );
                    }
                });
                $("[rel=tooltip]").tooltip();
                $("#station_table").tablesorter();
                 $('#station_table').freezeTableColumns({
            width:       900,   // required
            height:      700,   // required
            numFrozen:   2,     // optional
//            frozenWidth: 150,   // optional
//            clearWidths: true  // optional
          });//freezeTableColumns
            });
        </script>
    </head>

    <body>
        <div class="navbar navbar-fixed-top">
            <?php if ($this->dx_auth->is_logged_in()) {
							
							 ?>
                <div class="custom-nav">
                <?php if(isset($this->total_unread) && $this->total_unread>0 ){?>
                    <a class="btn large message" href="<?php echo site_url('messages/inbox') ?>">Messages<span class="badge label-important message-alert"><?php echo $this->total_unread ?></span></a>
                    <?php }else{?>
                    <a href="<?php echo site_url('messages/inbox') ?>">Messages</a>
                    <?php }?>

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
                            <li class="<?php echo active_anchor('settings', false); ?>"><a href="<?php echo site_url('settings/index') ?>">Settings</a></li> 

                        </ul>
                    </div><!--/.nav-collapse -->
                <?php } ?>

            </div>
        </div>
        <div class="container">

            <div class="content" >
                <?php if (active_anchor('settings', 'index') || active_anchor('settings', 'edit_profile') || active_anchor('templatemanager', 'add') || active_anchor('templatemanager', 'lists')) { ?>
                    <ul class="nav nav-tabs">
                        <li><a href="<?php echo site_url('templatemanager/add'); ?>" >Email Template</a></li>
                        <li><a href="<?php echo site_url('templatemanager/lists'); ?>" >List Template</a></li>
                        <li class="<?php echo active_anchor('settings', 'index'); ?>"><a href="<?php echo site_url('settings/index'); ?>">Users</a></li>
                        <li class="<?php echo active_anchor('settings', 'edit_profile'); ?>"><a href="<?php echo site_url('settings/edit_profile'); ?>">Edit Profile</a></li> 

                    </ul>
                    <script>
                        $(function () {
                            //                            $('#myTab a:last').tab('show');
                        })
                                                
                    </script>
                <?php } ?>
                <?php
                if (active_anchor('messages', 'inbox') || active_anchor('messages', 'sent')) {

                    $this->load->view('messages/compose');
                }
                ?>

                {yield}
            </div>
        </div>
    </body>
</html>
