<?php
if ($essence_track)
{
	?>
	<div style="margin-left: 250px;">
		<ul class="nav nav-tabs">
			<?php
			foreach ($essence_track as $key => $value)
			{
				$class = '';
				if ($key == 0)
				{
					$class = 'class="active"';
				}
				?>
				<li <?php echo $class; ?>><a href="#<?php echo $value->id; ?>" data-toggle="tab"><?php echo ucwords($value->essence_track_type); ?></a></li>
				<?php
			}
			?>



		</ul>
		<div class="tab-content">
			<?php
			foreach ($essence_track as $key => $value)
			{
				$class = '';
				if ($key == 0)
				{
					$class = 'active';
				}
				?>
				<div class="tab-pane <?php echo $class; ?>" id="<?php echo $value->id; ?>" style=" margin: 0 auto">
					<table  cellPadding="8" class="record-detail-table">
						<?php
						if ( ! empty($value->essence_track_identifiers) || ! empty($value->essence_track_identifier_source))
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><i class="icon-question-sign"></i><b><span class="label_star"> *</span> Track Identifier:</b></label>
								</td>
								<td>

									<p>
										<?php
										echo $value->essence_track_identifiers . ' ';
										echo ( ! empty($value->essence_track_identifier_source)) ? "($value->essence_track_identifier_source)" : '';
										?>
									</p>

								</td>
							</tr>
						<?php } ?>

						<?php
						if ( ! empty($value->encoding))
						{
							if ( ! empty($value->encoding_ref))
								$encoding = "<a href='$value->encoding_ref'>$value->encoding</a>";
							else
								$encoding = "$value->encoding";
							if ( ! empty($value->encoding_source))
								$encoding .=" ($value->encoding_source)";
							?>
							<tr>
								<td class="record-detail-page">
									<label><i class="icon-question-sign"></i><b><span class="label_star"> *</span> Track Identifier:</b></label>
								</td>
								<td>

									<p>
										<?php
										echo $encoding;
										?>
									</p>

								</td>
							</tr>
						<?php } ?>
					</table>
				</div>
				<?php
			}
			?>

		</div>
	</div>
<?php } ?>