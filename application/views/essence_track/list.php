<?php
if ($essence_track)
{
	?>
	<div>
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
				<li <?php echo $class; ?>><a href="#<?php echo $value->id; ?>" data-toggle="tab"><?php echo $value->essence_track_type; ?></a></li>
				<?php
			}
			?>



		</ul>

		<div class="tab-content">
			<div class="tab-pane active" id="region_digitized" style=" margin: 0 auto">
				<div class="container-map">
					<div class="first-asset">
						<h2><?php echo number_format($total_region_digitized['west']); ?></h2>
						<span>Assets</span>
					</div><!--end of first-asset-->
					<div class="second-asset">
						<h2><?php echo number_format($total_region_digitized['midwest']); ?></h2>
						<span>Assets</span>
					</div><!--end of first-asset-->
					<div class="third-asset">
						<h2><?php echo number_format($total_region_digitized['south']); ?></h2>
						<span>Assets</span>
					</div><!--end of first-asset-->
					<div class="forth-asset">
						<h2><?php echo number_format($total_region_digitized['northeast']); ?></h2>
						<span>Assets</span>
					</div><!--end of first-asset-->
					<div class="fifth-asset">
						<h2><?php echo number_format($total_region_digitized['other']); ?></h2>
						<span>Assets</span>
					</div><!--end of first-asset-->
					<div class="other">
						<h3>Other</h3>
					</div><!--end of other-->
				</div><!--end of container-map-->
			</div>
		</div>
	</div>
<?php } ?>