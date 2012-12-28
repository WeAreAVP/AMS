<div class="row">
				<div style="margin-left: 10px;">
								<h2>Instantiation Details: <?php	echo	$asset_details->title;	?></h2>
				</div>
				<div class="clearfix"></div>
				<?php	$this->load->view('partials/_list');	?>

				<div class="span9">

								<table>
												<tr>
																<td>
																				<label><i class="icon-question-sign"></i>* Instantiation ID:</label>
																</td>
																<td>
																				<?php
																				if($instantiation_detail->instantiation_identifier)
																				{
																								echo	$instantiation_detail->instantiation_identifier;
																								if($instantiation_detail->instantiation_source)
																								{
																												echo	' ('	.	$instantiation_detail->instantiation_source	.	')';
																								}
																				}
																				?>
																</td>
												</tr>
								</table>
				</div>

</div>



