<div style="margin: 0px -20px;">
				<div class="asset-stats">
								<div class="span5">
												<div class="assets-sum"><?php	echo	number_format($material_goal->total);	?></div>
												<div class="assets-subdetail">hrs digitized</div>

								</div>
								<div class="span5">
												<div class="assets-sum"><?php	echo	$percentage_hours	.	'%';	?></div>
												<div class="assets-subdetail">of <?php	echo	'of '	.	$total_hours	.	' hrs';	?> </div>

								</div>
								<div class="span5">
												<div class="assets-sum"><?php	echo	number_format($at_crawford);	?></div>
												<div class="assets-subdetail">hrs at Crawford</div>

								</div>
				</div>
				<div style="clear: both;"></div>
				<?php	$this->load->view('dashboard/_region');	?>

				<?php	$this->load->view('dashboard/_tv_radio'); ?>
				<div class="clearfix"></div>
				<?php	$this->load->view('dashboard/_formats');	?>
</div>

