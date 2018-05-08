<?php
    const PESTICIDE_INFO = array('submit' => 'submitPesticide', 'file' => 'PesticideFile', 'tableName' => 'pesticidetable');
    const NUTRIENT_INFO = array('submit' => 'submitNutrient', 'file' => 'NutrientFile', 'tableName' => 'nutrienttable');
    const WATER_QUALITY_INFO = array('submit' => 'submitWQM', 'file' => 'WQMFile', 'tableName' => 'waterqualitytable');
    const SITE_INFO = array('submit' => 'submitSiteInfo', 'file' => 'SiteInfoFile', 'tableName' => 'sitetable');
    const HYDROLAB_INFO = array('submit' => 'submitHydrolab', 'file' => 'HydrolabFile', 'tableName' => 'hydrolabtable');
    const TABLE_HEADER_COMMON = "<th>Site Number</th><th>Date</th><th>Sample number</th>";
    const TABLE_HEADERS = array(
        'pesticide' => TABLE_HEADER_COMMON . '<th>Atrazine</th><th>Alachlor</th><th>Metolachlor</th><th>Comments</th>',
        'nutrient' => TABLE_HEADER_COMMON . '<th>Phosphorus (mg/L)</th><th>Nitrate/Nitrite (mg/L)</th><th>Dissolved Reative Phosphorus</th><th>Comments</th>',
        'waterquality' => TABLE_HEADER_COMMON . '<th>Time</th><th>Water Temp</th><th>PH</th><th>Conductivity</th><th>TDS</th><th>DO</th><th>Turbidity</th><th>Turbidity (scale value)</th><th>Comments</th><th>Import Date</th><th>Import Time</th><th>Requires Checking</th>',
        'siteinfo' => '<th>Site Number</th><th>Longitude</th><th>Latitude</th><th>Site Location</th><th>Site Name</th>',
        'hydrolab' => '<th>Sample Number</th><th>Index</th><th>Date</th><th>Time</th><th>Temp-C</th><th>Temp-F</th><th>pH</th><th>SpC-mS/cm</th><th>Salin-PSS</th><th>TDS-g/L</th><th>DO%-Sat</th><th>DO-mg/L</th><th>Circ</th><th>ORP-mV</th><th>Depth-m</th><th>Depth-ft</th><th>Turb-NTU</th><th>Batt-v</th><th>CRC</th>');

    if (isset($_POST[NUTRIENT_INFO['submit']])) {
        //$nutrientFile = fileIsValid(NUTRIENT_INFO['file']);
        $nutrientTable = NUTRIENT_INFO['tableName'];
        $_POST['header'] = TABLE_HEADERS['nutrient'];
        //attemptUploadFile($nutrientFile, $nutrientTable);
    }

    function attemptUploadFile($currentFile, $currentTable) {
        if ($currentFile['isValid']) {
            //$message = uploadFile($currentTable);
            $_POST['logMessage'] = $message;
        } else {
            $_POST['errorMessage'] = $currentFile['errorMessage'];
        }
    }
?>

<div class="container roundGreyBox">
    <h1>File Upload Report</h1>
    <?php
        if (isset($valid)) {
            echo "<p>Error with file upload: </p>";
            echo "<p>" . $valid['errorMessage'] . "</p>";
        } else if (isset($log)) {
            ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Site Number</th>
                        <th>Date</th>
                        <th>Sample number</th>
                        <th>Phosphorus (mg/L)</th>
                        <th>Nitrate/Nitrite (mg/L)</th>
                        <th>Dissolved Reative Phosphorus</th>
                        <th>Comments</th>
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
                } else {
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
