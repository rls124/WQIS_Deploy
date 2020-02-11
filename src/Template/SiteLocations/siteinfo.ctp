<h1>Sites</h1>
<p>
	WQIS maintains sample data from <?=$numSites?> collection sites
</p>

<table id="tableView" class="table table-striped table-responsive" style="width: 80%">
	<thead>
		<tr>
			<th>View in map</th>
			<th>Site Number</th>
			<th>Longitude</th>
			<th>Latitude</th>
			<th>Site Location</th>
			<th>Site Name</th>
			<th>Notes</th>
		</tr>
	</thead>
	<tbody id="siteTable">
	<?php
		$row = 0;
		foreach ($SiteLocations as $siteData):
		    ?>
		    <tr id='tr-<?= $siteData->ID ?>'>
				<td><a href="chartselection?site=<?=$siteData->Site_Number?>">View</a></td>
				<td id='<?php echo 'td-' . $siteData->ID . '-siteNum'; ?>'><?= $siteData->Site_Number ?></td>
				<td id='<?php echo 'td-' . $siteData->ID . '-longitude'; ?>'><?= $siteData->Longitude ?></td>
				<td id='<?php echo 'td-' . $siteData->ID . '-latitude'; ?>'><?= $siteData->Latitude ?></td>
				<td id='<?php echo 'td-' . $siteData->ID . '-siteLoc'; ?>'><?= $siteData->Site_Location ?></td>
				<td id='<?php echo 'td-' . $siteData->ID . '-siteName'; ?>'><?= $siteData->Site_Name ?></td>
				<td></td>
				<?php
				$row++;
				?>
			</tr>
	<?php endforeach; ?>
	</tbody>
</table>