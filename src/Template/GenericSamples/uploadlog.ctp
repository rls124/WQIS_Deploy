<div class="container roundGreyBox">
    <h1>File Upload Report</h1>
    <?php
        if (isset($valid)) {
            echo "<p>Error with file upload: </p>";
            echo "<p>" . $valid['errorMessage'] . "</p>";
        }
		else if (isset($log)) {
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
                                    echo "Sample Number already exists at that location";
                                } else {
                                    foreach ($v as $errorKey => $errorVal) {
                                        echo $errorKey . '<br>' . $errorVal[key($errorVal)];
                                    }
                                }
                            } else {
                                echo $v;
                            }
                            echo "</td>";
                        }
                        echo "</tr>";
                    }
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
