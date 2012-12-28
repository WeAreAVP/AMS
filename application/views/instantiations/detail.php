<div class="row">
				<div style="margin: 2px 0px 10px 0px;">
								<h2>Instantiation Details: <?php	echo	$asset_details->title;	?></h2>
				</div>
				<div class="clearfix"></div>
				<?php	$this->load->view('partials/_list');	?>

				<div class="span9" style="margin-left: 285px;">
								<table>
												<!--				Instantiation ID	Start		-->
												<?php
												if($instantiation_detail->instantiation_identifier	||	$instantiation_detail->instantiation_source)
												{
																?>
																<tr>
																				<td>
																								<label><i class="icon-question-sign"></i><b>* Instantiation ID:</b></label>
																				</td>
																				<td>
																								<?php
																								if($instantiation_detail->instantiation_identifier)
																								{
																												?>
																												<span><?php	echo	$instantiation_detail->instantiation_identifier;	?></span>
																												<?php
																												if($instantiation_detail->instantiation_source)
																												{
																																?>
																																<span>	<?php	echo	' ('	.	$instantiation_detail->instantiation_source	.	')';	?></span>
																																<?php
																												}
																								}
																								?>
																				</td>
																</tr>
												<?php	}	?>
												<!--				Instantiation ID	End		-->
												<!--				Date 	Start		-->
												<?php
												if($instantiation_detail->dates	||	$instantiation_detail->date_type)
												{
																?>
																<tr>
																				<td>
																								<label><i class="icon-question-sign"></i><b>  Date:</b></label>
																				</td>
																				<td>
																								<?php
																								if($instantiation_detail->date_type)
																								{
																												?>
																												<span><?php	echo	$instantiation_detail->date_type	.	':';	?></span>
																												<?php
																												if($instantiation_detail->dates)
																												{
																																?>
																																<span><?php	echo	date('Y-m-d',	$instantiation_detail->dates);	?></span>

																												<?php	}	?>

																								<?php	}	?>
																				</td>
																</tr>
												<?php	}	?>
												<!--				Date 	End		-->
												<!--				Media Type 	Start		-->
												<?php
												if($instantiation_detail->media_type)
												{
																$media_type	=	explode(' | ',	$instantiation_detail->media_type);
																?>
																<tr>
																				<td>
																								<label><i class="icon-question-sign"></i><b>* Media Type:</b></label>
																				</td>
																				<td>
																								<?php
																								foreach($media_type	as	$value)
																								{
																												?>
																												<p><?php	echo	$value;	?></p>
																								<?php	}
																								?>
																				</td>
																</tr>
												<?php	}	?>
												<!--				Media Type	End		-->
												<!--				Format 	Start		-->
												<?php
												if($instantiation_detail->format_name)
												{

																$format	=	'Format: ';
																if(isset($instantiation_detail->format_type)	&&	($instantiation_detail->format_type	!=	NULL))
																{
																				if($instantiation_detail->format_type	===	'physical')
																								$format	=	'Physical Format: ';
																				if($instantiation_detail->format_type	===	'digital')
																								$format	=	'Digital Format: ';
																}
																?>	
																?>
																<tr>
																				<td>
																								<label><i class="icon-question-sign"></i><b>  <?php	echo	$format;	?></b></label>
																				</td>
																				<td>
																								<span>	<?php	echo	$instantiation_detail->format_name;	?></span>
																				</td>
																</tr>
												<?php	}	?>
												<!--				Format	End		-->
								</table>
				</div>

</div>



