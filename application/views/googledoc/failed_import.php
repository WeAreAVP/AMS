<div class="row">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Spreadsheet Name</th>
				<th>Format</th>
				<th>GUID</th>
				<th>Reason</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($failed_import as $row): ?>
				<tr>
					<td><?php echo $row->spreadsheet_name; ?></td>
					<td><?php echo $row->format; ?></td>
					<td><?php echo $row->guid; ?></td>
					<td>Unable to find instantiation.</td>
				</tr>
			<?php endforeach;
			?>
		</tbody>
	</table>
</div>