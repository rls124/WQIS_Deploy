<?= $this->Html->css("uploadlog.css") ?>
<div class="row" style="margin:10px;">
	<div class="col-xs">
		<h1>File Upload Report</h1>
		<h2><?php echo $fileName;?> (<?php echo $fileTypeName;?>)</h2>
	</div>
	<div class="col-md text-right">
		Upload took <span id="loadTimeText"></span> seconds
	</div>
</div>

<?php
if (isset($valid)) {
	echo "<p>Error with upload of file " . $fileName . ": </p>";
	echo "<p>" . $valid["errorMessage"] . "</p>";
}
else if (isset($log)) {
	if ($countFails > 0) {
		$totalCount = $countFails + $countSuccesses;
		?>	
		<p class="errorText">
			There were problems with your file upload. <?php echo $countSuccesses;?> out of <?php echo $totalCount;?> rows successfully uploaded. Rows with problems are displayed below.
		</p>
		
		<table class="table">
			<thead>
				<tr>
				<?php
					foreach ($columnText as $col) {
						echo "<th>" . $col . "</th>";
					}
				?>
					<th>Messages</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($log as $key => $val) {
					echo "<tr>";
					foreach ($val as $k => $v) {
						echo "<td>";
						if (is_array($v)) {
							echo "<ul class='errorList'>";
							if (isset($v['Sample_Number'])) {
								echo "<li>Sample number already exists</li>";
							}
							else {
								foreach ($v as $errorKey => $errorVal) {
									echo "<li><b>" . $errorKey . "</b>: " . $errorVal[key($errorVal)] . "</li>";
								}
							}
							echo "</ul>";
						}
						else {
							echo $v;
						}
						
						echo "</td>";
					}
					echo "</tr>";
				}
				?>
	</tbody>
</table>

<a href="/WQIS/pages/administratorpanel">Return to administrator panel</a>

<?php
		}
		else {
			?>
			<h3>File uploaded successfully. <?=$countSuccesses?> rows added</h3>
			<a href=\"/WQIS/pages/administratorpanel\">Return to administrator panel</a>
			<?php
		}
	}
	else {
		?>
		<h2>Not a valid filetype</h2>
		<p>
			The uploaded file did not match any expected type of data file. Hints:
			<ul>
				<li>
					Ensure that the file is a valid CSV (comma separated values) file
				</li>
				<li>
					Check that the column headers are present, and match the format shown in the appropriate <a href="/WQIS/webroot/files/exampleFiles.zip">example file</a>
				</li>
			</ul>
		</p>
		<p>
			See our <a href="/WQIS/pages/help">help page</a> for more information.
		</p>
	<?php
	}
	?>

<script>
<?php
$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $startTime), 4);
echo "var loadTime = " . $total_time . ";";
?>

$(document).ready(function () {
	//set loading time only after the page has finished loading. Since by this point PHP is already finished, gotta handle it on the client side through javascript
	document.getElementById("loadTimeText").innerHTML = loadTime;
});
</script>