<div class="row" style="height: 600px;">
				<div style="margin-left: 10px;">
								<h2>Instantiation Details: <?php	echo	$asset_details->title;	?></h2>
				</div>
    <div class="clearfix"></div>
				<div class="detail-menu">
								<?php
								$class	=	'';
								if(	!	isset($inst_id)	&&	empty($inst_id))
								{
												$inst_id	=	FALSE;
												$class	=	' active';
								}
								?>
								<div class="detail-sidebar<?php	echo	$class;	?>">
												<a class="menu-anchor" href="<?php	echo	site_url('records/details/'	.	$asset_id);	?>" >	<i class="icon-stop menu-img"></i><h4>Asset Information</h4></a>
								</div>
								<?php
								if(isset($asset_instantiations['records'])	&&	!	empty($asset_instantiations['records']))
								{
												?>
												<?php
												foreach($asset_instantiations['records']	as	$asset_instantiation)
												{
																if($asset_instantiation->id	==	$inst_id)
																				$class	=	' active';
																else
																				$class	=	'';
																?>


																<div class="detail-sidebar-ins<?php	echo	$class;	?>" >
																				<a  href="<?php	echo	site_url('instantiations/detail/'	.	$asset_instantiation->id)	?> "><div><i class=" icon-th menu-img"></i>INSTANTIATION</div>
																								<div style="margin-left: 18px;">
																												<?php
																												if(isset($asset_instantiation->generation)	&&	($asset_instantiation->generation	!=	NULL))
																												{
																																?>
																																<div><h4><?php	echo	$asset_instantiation->generation;	?></h4></div>
																												<?php	}
																												?>


																												<?php
																												echo	(isset($asset_instantiation->instantiation_identifier)	&&	($asset_instantiation->instantiation_identifier	!=	NULL))	?	"Instantiation ID: "	.	$asset_instantiation->instantiation_identifier	.	'<br/>'	:	'';
																												echo	(isset($asset_instantiation->format_name)	&&	($asset_instantiation->format_name	!=	NULL)	)	?	"Format: "	.	$asset_instantiation->format_name	.	'<br/>'	:	'';

																												echo	($asset_instantiation->actual_duration	>	0)	?	"Actual Duration: "	.	duration($asset_instantiation->actual_duration)	.	'<br/>'	:	'';
																												echo	($asset_instantiation->projected_duration	>	0)	?	"Projected Duration: "	.	duration($asset_instantiation->projected_duration)	.	'<br/>'	:	'';
																												?>
																								</div>
																				</a>
																</div>
												<?php	}
												?>

								<?php	}	?>

				</div>



</div>



<?php
//$this->load->view('records/_list');	?>