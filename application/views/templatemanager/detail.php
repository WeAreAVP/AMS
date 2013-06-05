<div class="row-fluid">
	<div class="span3" style="width: 240px;">

		<div>
			<h6 class="filter_title" id="filter_criteria" style="font-weight: bold;">EMAIL TEMPLATE</h6>

			<?php
			foreach ($templates as $data)
			{
				?>
				<?php
				if ($data->id == $template_id)
				{
					?>
					<div style="background: #ebebeb;">
						<div class="detail-sidebar active">
							<a class="menu-anchor" href="<?php echo site_url('templatemanager/details/' . $data->id) ?>" ><h5><?php echo str_replace("_", " ", $data->system_id); ?></h5></a>
						</div>
					</div>
					<?php
				}
				else
				{
					?>
					<div style="background: #ebebeb;">
						<div class="detail-sidebar">
							<a class="menu-anchor" href="<?php echo site_url('templatemanager/details/' . $data->id) ?>" ><h5><?php echo str_replace("_", " ", $data->system_id); ?></h5></a>
						</div>
					</div>
					<?php
				}
			}
			?>
		</div>
		
	</div>
	<div class="span9" style="width: 699px;">
		<div id="success_message" style="margin-bottom: 0px; margin-top: 0px;display: none;" class="alert"></div>
		<div style="height: 600px;">
			<table class="tablesorter table table-bordered">
				<?php
				if (count($template_detail) > 0)
				{
					?>
					<?php /* ?>  <tr>
					  <td > System Id </td>
					  <td><?php echo $template_detail->system_id; ?></td>
					  </tr>
					  <tr>
					  <td> Subject </td>
					  <td><?php echo $template_detail->subject; ?></td>
					  </tr>
					  <tr>
					  <td> Plain Body </td>
					  <td><?php echo $template_detail->body_plain; ?>
					  </tr>
					  <tr>
					  <td> Html Body </td><?php */ ?>
					<?php
					if ($template_detail->body_html)
					{
						?>
						<tr>
							<td><div style="padding:10px"><?php echo $template_detail->body_html; ?></div></td></tr>
					<?php } ?>
					<?php
					if ($template_detail->body_plain)
					{
						?>
						<tr>
							<td><div style="padding:10px"><?php echo $template_detail->body_plain; ?></div></td></tr>
							<?php } ?>
							<?php /* ?> </tr>
							  <tr>
							  <td> Email Type </td>
							  <td><?php echo $template_detail->email_type; ?></td>
							  </tr>
							  <tr>
							  <td> Email From </td>
							  <td><?php echo $template_detail->email_from; ?>
							  </tr>
							  <tr>
							  <td> Reply To </td>
							  <td><?php echo $template_detail->reply_to; ?>
							  </tr>
							  <tr>
							  <td> Replaceables </td>
							  <td><?php echo $template_detail->replaceables; ?>
							  </tr><?php */ ?>
							<?php
						}
						else
						{
							?>
					<td><h1>The requested template not found</h1></td>
					</tr><?php }
						?>
			</table>
		</div>
	</div>
</div>
