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
				
			</div>
		</div>
	</div>
<?php } ?>