<div class="row">
				<div style="margin: 2px 0px 10px 0px;">
								<h2>Instantiation Details: <?php	echo	$asset_details->title;	?></h2>
				</div>
				<div class="clearfix"></div>
				<?php	$this->load->view('partials/_list');	?>

				<div class="span9" style="margin-left: 285px;">

								<table>
												<!--				Instantiation ID	Start		-->
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
												<!--				Instantiation ID	End		-->
												<!--				Date 	Start		-->
												<tr>
																<td>
																				<label><i class="icon-question-sign"></i><b>Date:</b></label>
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
												<!--				Date 	End		-->
								</table>
				</div>

</div>



