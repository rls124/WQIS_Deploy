<style>
.error {
	color: red;
}
</style>

<div class="container roundGreyBox">
    <h1>File Upload Report</h1>
    <?php
        if (isset($valid)) {
            echo "<p>Error with file upload: </p>";
            echo "<p>" . $valid['errorMessage'] . "</p>";
        }
		else if (isset($log)) {
            ?>
			
			<?php
			if ($countFails > 0) {
				$totalCount = $countFails + $countSuccesses;
				echo "<span class='error'>There were problems with your file upload. " . $countSuccesses . " out of " . $totalCount . " rows successfully uploaded</span><br>";
				echo "Rows with problems are displayed below";
			?>
            <table class="table">
                <thead>
					<tr>
					<?php
						foreach ($columnsText as $col) {
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
                                if (isset($v['Sample_Number'])) {
									echo "<span class='error'>Sample Number already exists at that location</span>";
                                }
								else {
									foreach ($v as $errorKey => $errorVal) {
										echo "<span class='error'>" . $errorKey . '</span><br><span>' . $errorVal[key($errorVal)] . "</span>";
									}
								}
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
	
	<?php
			}
			else {
				echo "File uploaded successfully. " . $countSuccesses . " rows added.";
			}
	?>
				<?php
                }
				else {
                    echo '<p> No file selected for upload </p>';
                }
            ?>
        </tbody>
    </table>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
