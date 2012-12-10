<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="author" content="Ali Raza & Nouman Tayyab"/>
        <title>AMS</title>
        <script type="text/javascript">
            var site_url='<?php echo site_url() ?>';
        </script>
        <?php
        echo link_js('jquery-1.8.2.js');
        echo link_js('jquery-ui-1.9.0.custom.js');
        echo link_js('jquery.tablesorter.js');
        echo link_js('bootstrap/bootstrap.js');
        echo link_js('highcharts.js');
        echo link_js('modules/exporting.js');
        echo link_js('jquery.blockUI.js');
        echo link_js('jquery.blockUI.js');
        echo link_js('jquery.multiselect.min.js');
        echo link_js('jquery.dataTables.js');
        echo link_js('FixedColumns.js');
        echo link_js('FixedHeader.js');
        echo link_js('ColReorder.js');
        echo link_js('ColVis.js');
        echo link_js('dataTables.scroller.js');


        echo link_tag("css/tableSorter.css");
        echo link_tag("css/smoothness/jquery-ui-1.9.0.custom.css");
        echo link_tag("css/bootstrap/bootstrap.css");
        echo link_tag("css/style.css");
        echo link_tag("css/ColReorder.css");
        echo link_tag("css/ColVis.css");
        echo link_tag("css/dataTables.scroller.css");

        ?> 
        <script src="<?php echo base_url('tiny_mce/tiny_mce.js') ?>" type="text/javascript"></script>
        <?php echo link_js('custom.js'); ?>



        <script type="text/javascript"> 
            $(document).ready(function() {
                $('#myGeneral').on('hidden', function () {
                    $('#myGeneral_body').html(''); 
                })
                
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
                //                var dates = $( "#start_date_range, #end_date_range" ).datepicker({
                //                    defaultDate: "+1w",
                //                    changeMonth: true,
                //                    numberOfMonths: 1,
                //                    dateFormat: 'yy-mm-dd',
                //                    onSelect: function( selectedDate ) {
                //                        $('#end_date_range').val(selectedDate);
                //                        var option = this.id == "start_date_range" ? "minDate" : "maxDate",
                //                        instance = $( this ).data( "datepicker" ),
                //                        date = $.datepicker.parseDate(
                //                        instance.settings.dateFormat ||
                //                            $.datepicker._defaults.dateFormat,
                //                        selectedDate, instance.settings );
                //                        dates.not( this ).datepicker( "option", option, date );
                //                    }
                //                });
                $("[rel=tooltip]").tooltip();
                $("#station_table").tablesorter();
                $("#user_table_list").tablesorter();
                
            });
        </script>
    </head>

    <body>
        <div class="navbar navbar-fixed-top">
            <?php
            if ($this->dx_auth->is_logged_in())
            {
                ?>
                <div class="custom-nav">
                    <span id="msg_text_link">
                        <?php
                        if (isset($this->total_unread) && $this->total_unread > 0 && $this->is_station_user)
                        {
                            ?>
                            <a class="btn large message" href="<?php echo site_url('messages/inbox') ?>">Messages<span class="badge label-important message-alert"><?php echo $this->total_unread ?></span></a>
                            <?php
                        } else
                        {
                            ?>
                            <a href="<?php echo site_url('messages/inbox') ?>">Messages</a>
                        <?php } ?>
                    </span>
                    <a href="<?php echo site_url('auth/logout') ?>">Log Out</a> 
                </div>
            <?php } ?>
            <div class="navbar-inner">
                <a class="brand" href="<?php echo site_url() ?>">AMS</a>
                <?php
                if ($this->dx_auth->is_logged_in())
                {
                    ?>
                    <div class="nav-collapse">
                        <ul class="nav">



                            <li class="<?php echo active_anchor('dashboard', 'index'); ?>"><a href="<?php echo site_url('dashboard/index') ?>">Dashboard</a></li>


                            <li class="<?php echo (is_route_method(array('records' => array('index', 'flagged', 'details'), 'instantiations' => array('index', 'detail')))) ? 'active' : ''; ?>"><a href="<?php echo site_url('records/index') ?>">Records</a></li>


                            <li class="<?php echo active_anchor('stations', array('index', 'detail')); ?>"><a href="<?php echo site_url('stations/index') ?>">Stations</a></li>
                            <li class="<?php echo active_anchor('reports', 'index'); ?>"><a href="<?php echo site_url('reports/index') ?>">Reports</a></li>
                            <li class="<?php echo (is_route_method(array('settings' => array('index', 'users', 'edit_profile'), 'templatemanager' => array('add', 'lists', 'edit', 'details', 'readmessage')))) ? 'active' : ''; ?>"><a href="<?php echo site_url('settings/index') ?>">Settings</a></li> 

                        </ul>
                    </div><!--/.nav-collapse -->
                <?php } ?>

            </div>
        </div>
        <div class="container" style="width:1170px;margin:0 auto;margin-top: 70px;">

            <div class="content" >
                <?php
                if (is_route_method(array('settings' => array('index', 'edit_profile'), 'templatemanager' => array('add', 'lists', 'edit', 'details', 'readmessage'))
                        )
                )
                {
                    ?>
                    <ul class="nav nav-tabs">
                        <?php
                        if ($this->can_compose_alert)
                        {
                            ?>
                            <li class="<?php echo active_anchor('templatemanager', array('add', 'lists', 'edit', 'details', 'readmessage')); ?>"><a href="<?php echo site_url('templatemanager/lists'); ?>" >Email Template</a></li>
                        <?php } ?>
                        <li class="<?php echo active_anchor('settings', 'index'); ?>"><a href="<?php echo site_url('settings/index'); ?>">Users</a></li>
                        <li class="<?php echo active_anchor('settings', 'edit_profile'); ?>"><a href="<?php echo site_url('settings/edit_profile'); ?>">Edit Profile</a></li> 

                    </ul>

                <?php } ?>
                <?php
                if (is_route_method(array('records' => array('index', 'flagged'), 'instantiations' => array('index'))))
                {
                    ?>
                    <ul class="nav nav-tabs">
                        <li class="<?php echo active_anchor('records', array('index', 'flagged')); ?>"><a href="<?php echo site_url('records/index'); ?>">Assets</a></li>
                        <li class="<?php echo active_anchor('instantiations', array('index', 'detail')); ?>"><a href="<?php echo site_url('instantiations/index'); ?>">Instantiations</a></li>

                    </ul>

                <?php } ?>
                <?php
                if ((active_anchor('messages', array('inbox', 'sent'))) && $this->can_compose_alert)
                {

                    $this->load->view('messages/compose');
                }
                ?>

                {yield}
            </div>
        </div>
        <a href="#myGeneral" data-toggle="modal" id="showPopUp"></a>
        <div class="modal hide" id="myGeneral" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div id="myGeneral_body" > </div>
        </div>
    </body>
</html>
