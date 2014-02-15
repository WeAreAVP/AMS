<?php
if ($essence_track)
{
	?>
	<div style="margin-left: 250px;">
		<div class="dashboard-nav">
		<div>
			ESSENCE TRACKS
		</div>

	</div>
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
						<!-- Essence Track Identifier Start -->
						<?php
						if ( ! empty($value->essence_track_identifiers) || ! empty($value->essence_track_identifier_source))
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><b><span class="label_star"> *</span> Track Identifier:</b></label>
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
						<!-- Essence Track Identifier End -->
						<!-- Essence Track Encoding Start -->
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
									<label>
										<a data-placement="left" rel="tooltip" href="#" data-original-title="Identifies how the actual information in a media item is compressed, interpreted, or formulated using a particular scheme."><i class="icon-question-sign"></i></a>
										<b><span class="label_star"> *</span> Encoding:</b></label>
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
						<!-- Essence Track Encoding End -->
						<!-- Essence Track Standard Start -->
						<?php
						if ( ! empty($value->standard))
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><b><span class="label_star"> *</span> Track Standard:</b></label>
								</td>
								<td>

									<p>
										<?php
										echo $value->standard;
										?>
									</p>

								</td>
							</tr>
						<?php } ?>
						<!-- Essence Track Standard End -->
						<!-- Essence Track Data Rate Start -->
						<?php
						if ( ! empty($value->data_rate))
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><b> Track Data Rate:</b></label>
								</td>
								<td>

									<p>
										<?php
										echo $value->data_rate .' '. $value->unit_of_measure;
										?>
									</p>

								</td>
							</tr>
						<?php } ?>
						<!-- Essence Track Date Rate End -->
						<!-- Essence Track Frame Rate Start -->
						<?php
						if ( ! empty($value->frame_rate))
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><b>Frame Rate:</b></label>
								</td>
								<td>

									<p>
										<?php
										echo $value->frame_rate;
										?>
									</p>

								</td>
							</tr>
						<?php } ?>
						<!-- Essence Track Frame Rate End -->
						<!-- Essence Track Playback Speed Start -->
						<?php
						if ( ! empty($value->playback_speed))
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><b>Playback Speed:</b></label>
								</td>
								<td>

									<p>
										<?php
										echo $value->playback_speed;
										?>
									</p>

								</td>
							</tr>
						<?php } ?>
						<!-- Essence Track Playback Speed End -->
						<!-- Essence Track Sampling Rate Start -->
						<?php
						if ( ! empty($value->sampling_rate))
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><b>Sampling Rate:</b></label>
								</td>
								<td>

									<p>
										<?php
										echo $value->sampling_rate;
										?>
									</p>

								</td>
							</tr>
						<?php } ?>
						<!-- Essence Track Sampling Rate End -->
						<!-- Essence Track Bit Depth Start -->
						<?php
						if ( ! empty($value->bit_depth))
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><b>Bit Depth:</b></label>
								</td>
								<td>

									<p>
										<?php
										echo $value->bit_depth .' bits';
										?>
									</p>

								</td>
							</tr>
						<?php } ?>
						<!-- Essence Track Sampling Bit Depth -->
						<!-- Essence Track Frame Size Start -->
						<?php
						if ( ! empty($value->width) && ! empty($value->height))
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><b>Frame Size:</b></label>
								</td>
								<td>

									<p>
										<?php
										echo $value->width . ' x ' . $value->height;
										?>
									</p>

								</td>
							</tr>
						<?php } ?>
						<!-- Essence Track Frame Bit Size -->
						<!-- Essence Track Aspect Ratio Start -->
						<?php
						if ( ! empty($value->aspect_ratio))
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><b>Aspect Ratio:</b></label>
								</td>
								<td>

									<p>
										<?php
										echo $value->aspect_ratio;
										?>
									</p>

								</td>
							</tr>
						<?php } ?>
						<!-- Essence Track Aspect Ratio End -->
						<!-- Essence Track Time Start Start -->
						<?php
						if ( ! empty($value->time_start))
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><b>Time Start:</b></label>
								</td>
								<td>

									<p>
										<?php
										echo $value->time_start;
										?>
									</p>

								</td>
							</tr>
						<?php } ?>
						<!-- Essence Track Time Start End -->
						<!-- Essence Track Duration Start -->
						<?php
						if ( ! empty($value->duration))
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><b>Duration:</b></label>
								</td>
								<td>

									<p>
										<?php
										echo $value->duration;
										?>
									</p>

								</td>
							</tr>
						<?php } ?>
						<!-- Essence Track Duration End -->
						<!-- Essence Track Language Start -->
						<?php
						if ( ! empty($value->language))
						{
							?>
							<tr>
								<td class="record-detail-page">
									<label><b>Track Language:</b></label>
								</td>
								<td>

									<p>
										<?php
										echo $value->language;
										?>
									</p>

								</td>
							</tr>
						<?php } ?>
						<!-- Essence Track Language End -->
						<!-- Essence Track Annotation Start -->
						<?php
						if (get_essence_track_annotation($value->id))
						{
							$annotations = get_essence_track_annotation($value->id);
							$combine_annotation = '';
							foreach ($annotations as $key => $annotation)
							{
								$combine_annotation .=$annotation->annotation;
								$combine_annotation .=( ! empty($annotation->annotation_type)) ? " ($annotation->annotation_type)" : '';
								$combine_annotation .='<br/>';
							}
							?>
							<tr>
								<td class="record-detail-page">
									<label><b>Track Annotation:</b></label>
								</td>
								<td>

									<p>
										<?php
										echo $combine_annotation;
										?>
									</p>

								</td>
							</tr>
						<?php } ?>
						<!-- Essence Track Annotation End -->
					</table>
				</div>
				<?php
			}
			?>

		</div>
	</div>
<?php } ?>