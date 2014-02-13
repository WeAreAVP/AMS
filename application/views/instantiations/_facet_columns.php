<!-- Organization  Start      -->
<?php
if (count($stations) > 0 && ! $this->is_station_user)
{
	?>
	<div class="field-filters">
		<div class="filter-fileds" onclick="showHideSearch('org_div', this);" style="cursor: pointer;">
			<b>Organization</b><span class="caret custom-caret" style="margin-top: 8px;margin-left: 3px;" ></span>
		</div>
		<div class="filter-fileds" id="org_div" style="display: none;">
			<?php
			foreach ($stations as $key => $value)
			{
				?>
				<?php
				if ($key < 4)
				{
					?>
					<div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['organization']); ?>', 'organization_main');"><?php echo $value['organization'] . ' (' . number_format($value['@count']) . ')'; ?></a></div>
					<?php
				}
				else if ($key == 4)
				{
					?>
					<div class="dropdown">
						<a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
							More
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu custom-dropdown-menu" role="menu" aria-labelledby="dLabel">
							<li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['organization']); ?>', 'organization_main');"><?php echo $value['organization'] . ' (' . number_format($value['@count']) . ')'; ?></a></li>
							<?php
						}
						else
						{
							?>
							<li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['organization']); ?>', 'organization_main');"><?php echo $value['organization'] . ' (' . number_format($value['@count']) . ')'; ?></a></li>
							<?php
						}
					}
					if (count($stations) > 4)
					{
						?>
					</ul>
				</div>
			<?php } ?>
		</div>
	</div>
<?php } ?>
<!-- Organization  End      -->
<!--  States Start      -->
<?php
if (count($org_states) > 0)
{
	?>
	<div class="field-filters">
		<div class="filter-fileds" onclick="showHideSearch('org_st_div', this);" style="cursor: pointer;">
			<b>States</b><span class="caret custom-caret" style="margin-top: 8px;margin-left: 3px;" ></span>
		</div>
		<div class="filter-fileds" id="org_st_div" style="display: none;">
			<?php
			foreach ($org_states as $key => $value)
			{
				if ($key < 4)
				{
					?>
					<div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['state']); ?>', 'states_main');"><?php echo $value['state'] . ' (' . number_format($value['@count']) . ')'; ?></a></div>
					<?php
				}
				else if ($key == 4)
				{
					?>
					<div class="dropdown">
						<a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
							More
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu custom-dropdown-menu" role="menu" aria-labelledby="dLabel">
							<li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['state']); ?>', 'states_main');"><?php echo $value['state'] . ' (' . number_format($value['@count']) . ')'; ?></a></li>  
							<?php
						}
						else
						{
							?>
							<li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['state']); ?>', 'states_main');"><?php echo $value['state'] . ' (' . number_format($value['@count']) . ')'; ?></a></li>  
							<?php
						}
					}
					if (count($org_states) > 4)
					{
						?>
					</ul>
				</div>
			<?php } ?>
		</div>
	</div>
<?php } ?>
<!--  States End      -->
<!--  Nomination Status Start      -->
<?php
if (count($nomination_status) > 0)
{
	?>
	<div class="field-filters">
		<div class="filter-fileds" onclick="showHideSearch('n_div', this);" style="cursor: pointer;">
			<b>Nomination Status</b><span class="caret custom-caret" style="margin-top: 8px;margin-left: 3px;" ></span>
		</div>
		<div class="filter-fileds" id="n_div" style="display: none;">
			<?php
			foreach ($nomination_status as $key => $value)
			{
				if ($key < 4)
				{
					?>
					<div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['status']); ?>', 'nomination_status_main');"><?php echo $value['status'] . ' (' . number_format($value['@count']) . ')'; ?></a></div>
					<?php
				}
				else if ($key == 4)
				{
					?>
					<div class="dropdown">
						<a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
							More
							<b class="caret"></b>
						</a> 
						<ul class="dropdown-menu custom-dropdown-menu" role="menu" aria-labelledby="dLabel">
							<li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['status']); ?>', 'nomination_status_main');"><?php echo $value['status'] . ' (' . number_format($value['@count']) . ')'; ?></a></li>  
							<?php
						}
						else
						{
							?>
							<li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['status']); ?>', 'nomination_status_main');"><?php echo $value['status'] . ' (' . number_format($value['@count']) . ')'; ?></a></li>  
							<?php
						}
					}
					if (count($nomination_status) > 4)
					{
						?>
					</ul>
				</div>
			<?php } ?>
		</div>
	</div>
<?php } ?>
<!--  Nomination Status End      -->
<!--  Media Type Start      -->
<?php
if (count($media_types) > 0)
{
	?>
	<div class="field-filters">
		<div class="filter-fileds" onclick="showHideSearch('md_div', this);" style="cursor: pointer;">
			<b>Media Type</b><span class="caret custom-caret" style="margin-top: 8px;margin-left: 3px;" ></span>
		</div>
		<div class="filter-fileds" id="md_div" style="display: none;">
			<?php
			$less_common = FALSE;
			foreach ($media_types as $key => $value)
			{
				if ($key < 4)
				{
					?>
					<div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['media_type']); ?>', 'media_type_main');"><?php echo $value['media_type'] . ' (' . number_format($value['@count']) . ')'; ?></a></div>
					<?php
				}
				else if ($key == 4)
				{
					?>
					<div class="dropdown">
						<a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
							More
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu custom-dropdown-menu" role="menu" aria-labelledby="dLabel">
							<?php
							if ($value['@count'] >= 100)
							{
								?>
								<li><a href="javascript://"><b>--commonly used--</b></a></li>  
								<?php
							}
							else
							{
								?>
								<li><a href="javascript://"><b>--less commonly used--</b></a></li>  
								<?php
								$less_common = TRUE;
							}
							?>
							<li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['media_type']); ?>', 'media_type_main');"><?php echo $value['media_type'] . ' (' . number_format($value['@count']) . ')'; ?></a></li>  
							<?php
						}
						else
						{
							?>
							<?php
							if ($value['@count'] < 100 && ! $less_common)
							{
								$less_common = TRUE;
								?>
								<li><a href="javascript://"><b>--less commonly used--</b></a></li>  
							<?php } ?>
							<li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['media_type']); ?>', 'media_type_main');"><?php echo $value['media_type'] . ' (' . number_format($value['@count']) . ')'; ?></a></li>  
							<?php
						}
					}
					if (count($media_types) > 4)
					{
						?>
					</ul>
				</div>
			<?php } ?>
		</div>
	</div>
<?php } ?>
<!--  Media Type End      -->
<!--  Physical Format Start      -->
<?php
if (count($physical_formats) > 0)
{
	?>
	<div class="field-filters">
		<div class="filter-fileds" onclick="showHideSearch('pf_div', this);" style="cursor: pointer;">
			<b>Physical Format</b><span class="caret custom-caret" style="margin-top: 8px;margin-left: 3px;" ></span>
		</div>
		<div class="filter-fileds" id="pf_div" style="display: none;">
			<?php
			$less_common = FALSE;
			foreach ($physical_formats as $key => $value)
			{
				if ($key < 4)
				{
					?>
					<div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['format_name']); ?>', 'physical_format_main');"><?php echo $value['format_name'] . ' (' . number_format($value['@count']) . ')'; ?></a></div>
					<?php
				}
				else if ($key == 4)
				{
					?>
					<div class="dropdown">
						<a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
							More
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu custom-dropdown-menu" role="menu" aria-labelledby="dLabel">
							<?php
							if ($value['@count'] >= 100)
							{
								?>
								<li><a href="javascript://"><b>--commonly used--</b></a></li>  
								<?php
							}
							else
							{
								?>
								<li><a href="javascript://"><b>--less commonly used--</b></a></li>  
								<?php
								$less_common = TRUE;
							}
							?>
							<li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['format_name']); ?>', 'physical_format_main');"><?php echo $value['format_name'] . ' (' . number_format($value['@count']) . ')'; ?></a></li>  
							<?php
						}
						else
						{
							?>
							<?php
							if ($value['@count'] < 100 && ! $less_common)
							{
								$less_common = TRUE;
								?>
								<li><a href="javascript://"><b>--less commonly used--</b></a></li>  
							<?php } ?>
							<li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['format_name']); ?>', 'physical_format_main');"><?php echo $value['format_name'] . ' (' . number_format($value['@count']) . ')'; ?></a></li>  
							<?php
						}
					}
					if (count($physical_formats) > 4)
					{
						?>
					</ul>
				</div>
			<?php } ?>
		</div>
	</div>
<?php } ?>
<!-- Physical Format End      -->
<!--  Digital Format Start      -->
<?php
if (count($digital_formats) > 0)
{
	?>
	<div class="field-filters">
		<div class="filter-fileds" onclick="showHideSearch('df_div', this);" style="cursor: pointer;">
			<b>Digital Format</b><span class="caret custom-caret"  style="margin-top: 8px;margin-left: 3px;" ></span>
		</div>
		<div class="filter-fileds" id="df_div" style="display: none;">
			<?php
			$less_common = FALSE;
			foreach ($digital_formats as $key => $value)
			{
				if ($key < 4)
				{
					?>
					<div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['format_name']); ?>', 'digital_format_main');"><?php echo $value['format_name'] . ' (' . number_format($value['@count']) . ')'; ?></a></div>
					<?php
				}
				else if ($key == 4)
				{
					?>
					<div class="dropdown">
						<a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
							More
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu custom-dropdown-menu" role="menu" aria-labelledby="dLabel">
							<?php
							if ($value['@count'] >= 100)
							{
								?>
								<li><a href="javascript://"><b>--commonly used--</b></a></li>  
								<?php
							}
							else
							{
								?>
								<li><a href="javascript://"><b>--less commonly used--</b></a></li>  
								<?php
								$less_common = TRUE;
							}
							?>
							<li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['format_name']); ?>', 'digital_format_main');"><?php echo $value['format_name'] . ' (' . number_format($value['@count']) . ')'; ?></a></li>  
							<?php
						}
						else
						{
							?>
							<?php
							if ($value['@count'] < 100 && ! $less_common)
							{
								$less_common = TRUE;
								?>
								<li><a href="javascript://"><b>--less commonly used--</b></a></li>  
							<?php } ?>
							<li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['format_name']); ?>', 'digital_format_main');"><?php echo $value['format_name'] . ' (' . number_format($value['@count']) . ')'; ?></a></li>  
							<?php
						}
					}
					if (count($digital_formats) > 4)
					{
						?>
					</ul>
				</div>
			<?php } ?>
		</div>
	</div>
<?php } ?>
<!-- Digital Format End      -->
<!--  Generation Start      -->
<?php
if (count($generations) > 0)
{
	?>
	<div class="field-filters">
		<div class="filter-fileds" onclick="showHideSearch('generation_search_div', this);" style="cursor: pointer;">
			<b>Generations</b><span class="caret custom-caret" style="margin-top: 8px;margin-left: 3px;" ></span>
		</div>
		<div class="filter-fileds" id="generation_search_div" style="display: none;">
			<?php
			$less_common = FALSE;
			foreach ($generations as $key => $value)
			{
				if ($key < 4)
				{
					?>
					<div><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['facet_generation']); ?>', 'generation_main');"><?php echo $value['facet_generation'] . ' (' . number_format($value['@count']) . ')'; ?></a></div>
					<?php
				}
				else if ($key == 4)
				{
					?>
					<div class="dropdown">
						<a class="dropdown-toggle btn" id="dLabel" role="button" data-toggle="dropdown">
							More
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu custom-dropdown-menu" role="menu" aria-labelledby="dLabel">
							<?php
							if ($value['@count'] >= 100)
							{
								?>
								<li><a href="javascript://"><b>--commonly used--</b></a></li>  
								<?php
							}
							else
							{
								?>
								<li><a href="javascript://"><b>--less commonly used--</b></a></li>  
								<?php
								$less_common = TRUE;
							}
							?>
							<li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['facet_generation']); ?>', 'generation_main');"><?php echo $value['facet_generation'] . ' (' . number_format($value['@count']) . ')'; ?></a></li>  
							<?php
						}
						else
						{
							?>
							<?php
							if ($value['@count'] < 100 && ! $less_common)
							{
								$less_common = TRUE;
								?>
								<li><a href="javascript://"><b>--less commonly used--</b></a></li>  
							<?php } ?>
							<li><a href="javascript://" onclick="add_token('<?php echo htmlentities($value['facet_generation']); ?>', 'generation_main');"><?php echo $value['facet_generation'] . ' (' . number_format($value['@count']) . ')'; ?></a></li>  
							<?php
						}
					}
					if (count($generations) > 4)
					{
						?>
					</ul>
				</div>
			<?php } ?>
		</div>
	</div>
<?php } ?>
<!-- Generation End      -->
<!--				Digitized Start				-->
<?php
if (count($digitized) > 0)
{
	?>
	<div class="field-filters">
		<div class="filter-fileds">
			<?php
			$digitized_check = 0;
			if (isset($this->session->userdata['digitized']) && ! empty($this->session->userdata['digitized']))
			{
				$batch_check = $this->session->userdata['digitized'];
			}
			?>
			<span id="digitized" style="cursor: default;">
				<input type="hidden" id="digitized_state" name="digitized" value="<?php echo $batch_check; ?>"/> 
			</span>
			<b>Digitized</b>
				<!--<b>Digitized</b><span style="margin: 0px 10px;"><input type="checkbox" name="digitized" id="digitized" value="1" <?php echo $checked; ?> onchange="add_checked_token('digitized', 'Digitized');" /></span>-->
		</div>
	</div>
<?php } ?>
<!--				Digitized End      -->
<!--				Migration Start				-->
<?php
if (count($migration) > 0)
{
	?>
	<div class="field-filters">
		<div class="filter-fileds">
			<?php
			$checked = '';
			if (isset($this->session->userdata['migration_failed']) && $this->session->userdata['migration_failed'] === '1')
			{
				$checked = 'checked="checked"';
			}
			?>
			<b>Migration Failed?</b><span style="margin: 0px 10px;"><input type="checkbox" name="migration_failed" id="migration_failed" value="1"  <?php echo $checked; ?> onchange="add_checked_token('migration_failed', 'Migration Failed');" /></span>
		</div>
	</div>
<?php } ?>
<!--				Migration End      -->

<script type="text/javascript">
	initTriStateCheckBox('digitized', 'digitized_state', true);
	
</script>