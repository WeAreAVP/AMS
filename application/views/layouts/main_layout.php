<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="author" content="Nouman Tayyab"/>

        <title>AMS</title>
        <script type="text/javascript">
			var site_url = '<?php echo site_url() ?>';
        </script>
		<script src="/tiny_mce/tiny_mce.js" type="text/javascript"></script>
		<?php
		echo link_js('jquery-1.8.2.js');
		echo link_js('jquery-ui-1.9.0.custom.js');
		echo link_js('bootstrap/bootstrap.js');
		echo link_js('jquery.tablesorter.js');
		echo link_js('highcharts.js');
		echo link_js('custom.js');
		echo link_js('modules/exporting.js');
		echo link_js('jquery.blockUI.js');
		echo link_js('json2.js');
		echo link_js('jquery.multiselect.min.js');
		echo link_js('jquery.dataTables.js');
		echo link_js('FixedColumns.js');
		echo link_js('FixedHeader.js');
		echo link_js('ColReorder.js');
		echo link_js('ColVis.js');
		echo link_js('dataTables.scroller.js');
		echo link_js('flowplayer/flowplayer.min.js');
		echo link_js('jPlayer/jquery.jplayer.min.js');
		echo link_js('jwplayer/jwplayer.js');
		echo link_js('html2canvas.js');
		echo link_js('jquery.datepick.js');
		echo link_js('jquery.datepick.ext.js');

		echo link_js('date.js');
		echo link_js('daterangepicker.jQuery.js');

		echo link_tag("css/ui.daterangepicker.css");
		echo link_tag("css/tableSorter.css");
		
		
		
		echo link_tag("css/smoothness/jquery-ui-1.9.0.custom.css");
		echo link_tag("css/smoothness.datepick.css");
				echo link_tag("css/ui-smoothness.datepick.css");
		

		
		
		echo link_tag("css/ColReorder.css");
		echo link_tag("css/ColVis.css");
		echo link_tag("css/dataTables.scroller.css");


		echo link_tag("css/bootstrap/bootstrap.css");
		echo link_tag("css/style.css");
		echo link_tag("css/jplayer.blue.monday.css");
		echo link_tag("js/flowplayer/skin/minimalist.css");
		?> 
		<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
    </head>
	<body>
        <div class="navbar navbar-fixed-top">
			<?php
			if ($this->dx_auth->is_logged_in())
			{
				?>
				<div class="custom-nav">
					<div style="margin: 0 auto;width: 960px;background: none;border: none;" id="top_setting_nav">
						<div class="dropdown pull-right" style="margin-left:5px;margin-right:17px;">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog icon-white"></i></a>
							<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel" style="right:-15px;top:18px;min-width: auto;">
								<li><a href="<?php echo site_url('settings/index') ?>">Settings</a> </li>
								<li><a href="<?php echo site_url('auth/logout') ?>">Logout</a> </li>
							</ul>
						</div>

						<div  class="pull-right" id="msg_text_link">
							<?php
							if (isset($this->total_unread) && $this->total_unread > 0 && ($this->is_station_user || $this->session->userdata['DX_email'] === $this->config->item('crawford_email')))
							{
								?>
								<a class="message_box" href="<?php echo site_url('messages/inbox') ?>"><i class="icon-envelope icon-white"></i><span class="badge label-important message-alert"><?php echo $this->total_unread ?></span></a>
								<?php
							}
							else
							{
								?>
								<a class="message_box" href="<?php echo site_url('messages/inbox') ?>"><i class="icon-envelope icon-white"></i></a>
							<?php } ?>
						</div>
						<div class="dropdown pull-right" style="margin-right:5px;">
							<a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo site_url('help') ?>" title="Inventory Help"><i class="icon-question-sign icon-white"></i></a>

						</div>


					</div>
				</div>
			<?php } ?>
			<div class="custom-nav-inner">
				<div class="navbar-inner" style="margin: 0 auto;width: 960px;background: none;border: none;">
					<a class="brand" href="<?php echo site_url() ?>"><img src="/images/cpb_ams.png"/></a>
					<?php
					if ($this->dx_auth->is_logged_in())
					{
						?>
						<div class="nav-collapse">
							<ul class="nav custom-nav-ul">
								<?php
								$station_tab_name = 'Station';
								if ( ! $this->is_station_user)
								{
									$station_tab_name = 'Stations';
									?>
									<li class="<?php echo active_anchor('dashboard', 'index'); ?>"><a href="<?php echo site_url('dashboard/index') ?>">Dashboard</a></li>
								<?php }
								?>

								<li class="<?php echo (is_route_method(array('records' => array('index', 'flagged', 'details'), 'instantiations' => array('index', 'detail', 'edit', 'add'), 'asset' => array('edit', 'add')))) ? 'active' : ''; ?>"><a href="<?php echo site_url('records/index') ?>">Records</a></li>
								<li class="<?php echo active_anchor('stations', array('index', 'detail')); ?>"><a href="<?php echo site_url('stations/index') ?>"><?php echo $station_tab_name; ?></a></li>
								<?php
								if ( ! $this->is_station_user)
								{
									?>

									<li class="<?php echo active_anchor('reports', 'index'); ?>"><a href="<?php echo site_url('reports/index') ?>">Reports</a></li>
								<?php }
								?>
	<!--																												<li class="<?php echo (is_route_method(array('settings' => array('index', 'users', 'edit_profile'), 'templatemanager' => array('add', 'lists', 'edit', 'details', 'readmessage', 'manage_crawford')))) ? 'active' : ''; ?>"><a href="<?php echo site_url('settings/index') ?>">Settings</a></li> -->

							</ul>
						</div><!--/.nav-collapse -->
					<?php } ?>

				</div>
			</div>
		</div>
        <div class="container" style="width:960px;margin:0 auto;margin-top: 55px;">

            <div class="content" >
				<?php
				if (is_route_method(array('settings' => array('index', 'edit_profile'), 'templatemanager' => array('add', 'lists', 'edit', 'details', 'readmessage', 'manage_crawford'))
				)
				)
				{
					?>
					<ul class="records-nav">
						<?php
						if ($this->can_compose_alert || $this->role_id == 20)
						{
							?>
							<li class="<?php echo active_anchor('templatemanager', array('add', 'lists', 'edit', 'details', 'readmessage')); ?>"><a href="<?php echo site_url('templatemanager/lists'); ?>" >Email Template</a></li>
						<?php } ?>
						<?php
						if ( ! $this->is_station_user)
						{
							?>
							<li class="<?php echo active_anchor('templatemanager', 'manage_crawford'); ?>"><a href="<?php echo site_url('templatemanager/manage_crawford'); ?>">Crawford Contact Details</a></li> 
						<?php }
						?>
						<li class="<?php echo active_anchor('settings', 'index'); ?>"><a href="<?php echo site_url('settings/index'); ?>">Users</a></li>
						<li class="<?php echo active_anchor('settings', 'edit_profile'); ?>"><a href="<?php echo site_url('settings/edit_profile'); ?>">Edit Profile</a></li> 


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


		<script type="text/javascript">
			$(document).ready(function() {
				$('#myGeneral').on('hidden', function() {
					$('#myGeneral_body').html('');
				});
//				if ($.browser.msie) {
//					if ($.browser.version != '7.0' || $.browser.version != '8.0') {
//						document.addEventListener("touchstart", function() {
//						}, true);
//					}
//				}
//				else {
//					document.addEventListener("touchstart", function() {
//					}, true);
//				}

				var dates = $("#start_date, #end_date").datepicker({
					defaultDate: "+1w",
					changeMonth: true,
					numberOfMonths: 1,
					dateFormat: 'yy-mm-dd',
					onSelect: function(selectedDate) {
						var option = this.id == "start_date" ? "minDate" : "maxDate",
						instance = $(this).data("datepicker"),
						date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings);
						dates.not(this).datepicker("option", option, date);
					}
				});
				$("[rel=tooltip]").tooltip();
				$("#station_table").tablesorter();
				$("#user_table_list").tablesorter();

			});
		</script>
    </body>

</html>
