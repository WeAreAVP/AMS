<div>
	Hi <?php echo $user_profile->first_name . ' ' . $user_profile->last_name; ?>
</div>
<br/>
<div>
	Your AMS XML export is ready. You can download the file from <a href="<?php echo site_url('xml/download/' . base64_encode($export_id)); ?>">here</a>
</div>
<br/>
<br/>
<div>CPB AMS</div>