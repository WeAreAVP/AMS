

<div class="detail-menu">
	<?php
	$class = '';
	if ( ! isset($inst_id) && empty($inst_id))
	{
		$inst_id = FALSE;
		$class = ' active';
	}
	?>
	<div class="detail-sidebar<?php echo $class; ?>">
		<a class="menu-anchor" href="<?php echo site_url('records/details/' . $asset_id); ?>" >	<i class="icon-stop menu-img"></i><h4>Asset Information</h4></a>
	</div>
	<?php
	if (isset($list_assets) && count($list_assets) > 0)
	{
		?>
		<?php
		foreach ($list_assets as $asset_instantiation)
		{
			if ($asset_instantiation->id == $inst_id)
				$class = ' active';
			else
				$class = '';
			?>


			<div class="detail-sidebar-ins<?php echo $class; ?>" >
				<a  class="menu-anchor" href="<?php echo site_url('instantiations/detail/' . $asset_instantiation->id) ?> "><div style="font-size: 11px;"><i class=" icon-th-large menu-img"></i><b>INSTANTIATION</b></div>
					<div style="margin-left: 18px;font-size: 11px;">
						<?php
						if (isset($asset_instantiation->generation) && ($asset_instantiation->generation != NULL))
						{
							?>
							<div><h4><?php echo $asset_instantiation->generation; ?></h4></div>
							<?php
						}
						if ($asset_instantiation->instantiation_identifier || $asset_instantiation->instantiation_source)
						{
							$ins_identifier = explode(' | ', trim(str_replace('(**)', '', $asset_instantiation->instantiation_identifier)));
							$ins_identifier_src = explode(' | ', trim(str_replace('(**)', '', $asset_instantiation->instantiation_source)));
							$combine_identifier = '';
							foreach ($ins_identifier as $index => $identifier)
							{
								$combine_identifier.= '<span>';
								$combine_identifier.= $identifier;
								if (isset($ins_identifier_src[$index]) && ! empty($ins_identifier_src[$index]))
									$combine_identifier.=' (' . $ins_identifier_src[$index] . ')';
								$combine_identifier.= '</span>&nbsp;';
							}
							?>
							<div>
								<?php echo $combine_identifier; ?>
							</div>
							<?php
						}
						if (isset($asset_instantiation->format_name) && ($asset_instantiation->format_name != NULL))
						{
							$format = 'Format: ';
							if (isset($asset_instantiation->format_type) && ($asset_instantiation->format_type != NULL))
							{
								if ($asset_instantiation->format_type === 'physical')
									$format = 'Physical Format: ';
								if ($asset_instantiation->format_type === 'digital')
									$format = 'Digital Format: ';
							}
							?>	
							<div><b><?php echo $format ?></b><?php echo $asset_instantiation->format_name; ?></div>
							<?php
						}
						if ($asset_instantiation->actual_duration != '')
						{
							?>
							<div><b>Actual Duration: </b><?php echo date('H:i:s', strtotime($asset_instantiation->actual_duration)); ?></div>
							<?php
						}
						if ( ! empty($asset_instantiation->projected_duration))
						{
							?>
							<div><b>Projected Duration: </b><?php echo $asset_instantiation->projected_duration; ?></div>
						<?php } ?>

					</div>
				</a>
			</div>
		<?php }
		?>

	<?php } ?>

</div>



