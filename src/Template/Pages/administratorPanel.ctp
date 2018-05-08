<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

<link href="../css/administratorPanel.css" rel="stylesheet" type="text/css"/>

<div class="container roundGreyBox">
    <p class="centeredText" style="font-size:2.5rem;"><span class="glyphicon glyphicon-home" style="font-size:20pt;"></span>  Administrator Panel
        <a data-toggle="collapse" href="#collapseInfo" role="button" aria-expanded="false" aria-controls="collapseInfo">
	    <span class="glyphicon glyphicon-question-sign" style="font-size:18pt;" data-toggle="tooltip" title="Information" id="infoGlyph"></span>
	</a></p>
    <hr>

    <div class="collapse" id="collapseInfo">
	<div class="card card-body card-info">
	    <ul>
		<li class="info-li">
		    <span class="glyphicon glyphicon-list-alt" style="font-size: 14pt;"></span>
		    - Used to enter measurements for one or more sites taken on a particular date.
		</li>
		<li class="info-li">
		    <span class="glyphicon glyphicon-upload" style="font-size:14pt;"></span>
		    - Used to import water quality data.
		</li>
		<hr>
		<li class="info-li">
		    <span class="glyphicon glyphicon-flag" style="font-size: 14pt;"></span>
		    - Used to define the lowest and highest acceptable values when entering water quality data.
		</li>
		<li class="info-li">
		    <span class="glyphicon glyphicon-user" style="font-size:14pt;"></span>
		    - Used to add, edit or delete users.
		</li>
		<li class="info-li">
		    <span class="glyphicon glyphicon-map-marker" style="font-size: 14pt;"></span>
		    - Used to add, edit, or delete sites.
		</li>
		<li class="info-li">
		    <span class="glyphicon glyphicon-scale" style="font-size:14pt;"></span>
		    - Used to define the benchmark values for highlighting abnormal water quality data.
		</li>
		<li class="info-li">
		    <span class="glyphicon glyphicon-download-alt" style="font-size: 14pt;"></span>
		    - Used to export bulk data into CSV files.
		</li>
	    </ul>
	</div>
    </div>

    <div class="card-deck mb-3">
        <div class="card big-card">
            <div class="card-header">
                <h5 class="card-title centeredText mb-0" style="font-size: 1.25rem;"><span class="glyphicon glyphicon-list-alt" style="font-size:14pt;"></span>  Water Quality Data Entry</h5>
            </div>
            <div class="card-block">
		<?=
		    $this->Form->create('entryForm', [
			'url' => 'javascript:void(0);',
			'id' => 'entryForm',
			'name' => 'entryForm'
		    ])
		?>
                <div class="row">
		    <?=
			$this->Form->select('entryType', [
			    'bacteria' => 'Bacteria Entry Form',
			    'nutrient' => 'Nutrient Entry Form',
			    'pesticide' => 'Pesticide Entry Form',
			    'wqm' => 'WQM Entry Form'
			    ], [
			    'class' => 'ml-3 mr-3 mb-3 btn btn-default col-sm',
			    'empty' => 'Select Entry Form...',
			    'id' => 'entryType'
			    ]
			)
		    ?>
                </div>

                <div class="row mb-3" style="visibility: hidden">
                    <label class="ml-3" style="font-size: 14pt; margin-top: 3px;">File Name: </label>
                    <div class="col-sm ml-2 mr-3" id="FileUploadDiv" style="padding-left: 5px;">
                        <label id="Hiddendidden" style="font-size: 14pt; margin-top: 5px; font-weight: bold"></label>
                    </div>
                </div>

                <input type="submit" class="btn btn-basic col-md-5 mb-0" value="Go To Entry Form" id="EntryFormBtn" name="EntryForm" style="float: right; height:43px;" disabled>
		<?= $this->Form->end() ?>
            </div>
        </div>
        <div class="card big-card">
            <div class="card-header">
                <h5 class="card-title centeredText mb-0" style="font-size: 1.25rem;"><span class="glyphicon glyphicon-upload" style="font-size:14pt;"></span>  Import</h5>
            </div>
            <div class="card-block">
		<?=
		    $this->Form->create('fileupload', [
			'url' => 'javascript:void(0);',
			'type' => 'file',
			'id' => 'fileupload',
			'name' => 'fileupload'
		    ])
		?>
                <div class="row">
		    <?=
			$this->Form->select('fileType', [
			    'bacteria' => 'Bacteria Data Import',
			    'nutrient' => 'Nutrient Data Import',
			    'pesticide' => 'Pesticide Data Import',
			    'wqm' => 'Water Quality Meter Data Import',
			    'site' => 'Site Information Import'
			    ], [
			    'class' => 'ml-3 mr-3 mb-3 btn btn-default col-sm',
			    'empty' => 'Select File Type...',
			    'id' => 'fileType'
			    ]
			)
		    ?>
                </div>

                <div class="row mb-3">
                    <label class="ml-3" style="font-size: 14pt; margin-top: 3px;">File Name: </label>
                    <div class="col-sm ml-2 mr-3" id="FileUploadDiv" style="padding-left: 5px;">
                        <label id="FileUploadLabel" style="font-size: 12pt; margin-top: 5px; font-weight: bold"></label>
                    </div>
                </div>

                <div class="row center mb-0">
                    <a type="submit" href="" onclick="return false" class="mb-0 btn btn-basic col-sm sampleFileDisabled" id="sampleFile" name="sampleFile" style="height: 43px;">Download Sample File</a>
                    <label class = "btn btn-file btn-basic col-sm ml-1 mr-1" style="height:43px">
			Select File <input type="file" name="file" accept=".csv" id="upload">
                    </label>
                    <input type="submit" class="mb-0 btn btn-basic col-sm" value="Import Data" id="submitFile" name="submitFile" style="height: 43px;" disabled>
                </div>
		<?= $this->Form->end() ?>
            </div>
        </div>
    </div>

    <hr>

    <div class="card-columns">
        <div class="card">
            <a class="cardModal" onclick="location.href = '<?php echo $this->Url->build(['controller' => 'DetectionLimits', 'action' => 'limits']) ?>';">
                <div class="card-header" style="height: 50px;">
                    <h5 class="card-title centeredText mb-0" style="font-size:1.25rem;"><span class="glyphicon glyphicon-flag" style="font-size: 14pt;"></span>  Detection Limits</h5>
                </div>
            </a>
        </div>
        <div class="card">
            <a class="cardModal" onclick="location.href = '<?php echo $this->Url->build(['controller' => 'export', 'action' => 'export']) ?>';">
                <div class="card-header" style="height: 50px;">
                    <h5 class="card-title centeredText mb-0" style="font-size:1.25rem;"><span class="glyphicon glyphicon-download-alt" style="font-size: 14pt;"></span>  Export</h5>
                </div>
            </a>
        </div>
        <div class="card">
            <a class="cardModal" onclick="location.href = '<?php echo $this->Url->build(['controller' => 'users', 'action' => 'usermanagement']) ?>';">
                <div class="card-header" style="height: 50px;">
                    <h5 class="card-title centeredText mb-0" style="font-size: 1.25rem;"><span class="glyphicon glyphicon-user" style="font-size:14pt;"></span>  User Management</h5>
                </div>
            </a>
        </div>
        
        <div class="card">
            <a class="cardModal" onclick="location.href = '<?php echo $this->Url->build(['controller' => 'SiteLocations', 'action' => 'sitemanagement']) ?>';">
                <div class="card-header" style="height: 50px;">
                    <h5 class="card-title centeredText mb-0" style="font-size:1.25rem;"><span class="glyphicon glyphicon-map-marker" style="font-size: 14pt;"></span>  Site Management</h5>
                </div>
            </a>
        </div>
        <div class="card">
            <a class="cardModal" onclick="location.href = '<?php echo $this->Url->build(['controller' => 'benchmarks', 'action' => 'measurementbenchmarks']) ?>';">
                <div class="card-header" style="height: 50px;">
                    <h5 class="card-title centeredText mb-0" style="font-size: 1.25rem;"><span class="glyphicon glyphicon-scale" style="font-size:14pt;"></span>  Benchmarks</h5>
                </div>
            </a>
        </div>
        
         <div class="card">
            <a class="cardModal" onclick="location.href = '<?php echo $this->Url->build(['controller' => 'feedback', 'action' => 'adminfeedback']) ?>';">
                <div class="card-header" style="height: 50px;">
                    <h5 class="card-title centeredText mb-0" style="font-size: 1.25rem;"><span class="glyphicon glyphicon-list-alt" style="font-size:14pt;"></span>  Feedback</h5>
                </div>
            </a>
        </div>
        
    </div>
</div>

<script>
    $("#fileType").change(function () {
	var fileType = $(this).val();
	var location;
	var downloadLocation;
	var onclick = "return true";

	switch (fileType) {
	    case 'bacteria':
		location = "<?= $this->Html->Url->build(['controller' => 'BacteriaSamples', 'action' => 'uploadlog']); ?>";
		downloadLocation = "../files/sample-bacteria-data-import1.csv";
		$("#sampleFile").removeClass('sampleFileDisabled');
		checkSubmitFileBtn();
		break;
	    case 'nutrient':
		location = "<?= $this->Html->Url->build(['controller' => 'NutrientSamples', 'action' => 'uploadlog']); ?>";
		downloadLocation = "../files/sample-nutrients-data-import.csv";
		$("#sampleFile").removeClass('sampleFileDisabled');
		checkSubmitFileBtn();
		break;
	    case 'pesticide':
		location = "<?= $this->Html->Url->build(['controller' => 'PesticideSamples', 'action' => 'uploadlog']); ?>";
		downloadLocation = "../files/sample-pesticide-data-import.csv";
		$("#sampleFile").removeClass('sampleFileDisabled');
		checkSubmitFileBtn();
		break;
	    case 'wqm':
		location = "<?= $this->Html->Url->build(['controller' => 'WaterQualitySamples', 'action' => 'uploadlog']); ?>";
		downloadLocation = "../files/sample-wqm-data-import.csv";
		$("#sampleFile").removeClass('sampleFileDisabled');
		checkSubmitFileBtn();
		break;
	    case 'site':
		location = "<?= $this->Html->Url->build(['controller' => 'SiteLocations', 'action' => 'uploadlog']); ?>";
		downloadLocation = "../files/sample-site-import.csv";
		$("#sampleFile").removeClass('sampleFileDisabled');
		checkSubmitFileBtn();
		break;
	    default:
		location = "javascript:void(0);";
		downloadLocation = "";
		onclick = "return false";
		$("#sampleFile").removeClass('sampleFileDisabled');
		$("#sampleFile").addClass('sampleFileDisabled');
		checkSubmitFileBtn();
		break;
	}
	//onclick="window.open('file.doc')"
	$("#fileupload").attr("action", location);
	$("#sampleFile").attr("href", downloadLocation);
	$("#sampleFile").attr("onclick", onclick);
	//$("#sampleFile").attr("onclick", "window.open('" + downloadLocation + "')");
	//$("#downloadSampleBtn").attr("action", downloadLocation);
    });
</script>
<script>
    $(document).ready(function () {
	$('#infoGlyph').tooltip();
	$("input:file").change(function () {
	    var fileName = $(this).val();
	    fileName = fileName.replace(/^.*[\\\/]/, '');
	    $("#FileUploadLabel").html(fileName);
	    checkSubmitFileBtn();
	});
    });


</script>
<script>
    $("#entryType").change(function () {
	var entryType = $(this).val();
	var location;
	switch (entryType) {
	    case 'bacteria':
		location = "<?= $this->Html->Url->build(['controller' => 'BacteriaSamples', 'action' => 'entryform']); ?>";
		$("#EntryFormBtn").prop('disabled', false);
		break;
	    case 'nutrient':
		location = "<?= $this->Html->Url->build(['controller' => 'NutrientSamples', 'action' => 'entryform']); ?>";
		$("#EntryFormBtn").prop('disabled', false);
		break;
	    case 'pesticide':
		location = "<?= $this->Html->Url->build(['controller' => 'PesticideSamples', 'action' => 'entryform']); ?>";
		$("#EntryFormBtn").prop('disabled', false);
		break;
	    case 'wqm':
		location = "<?= $this->Html->Url->build(['controller' => 'WaterQualitySamples', 'action' => 'entryform']); ?>";
		$("#EntryFormBtn").prop('disabled', false);
		break;
	    default:
		location = "javascript:void(0);";
		$("#EntryFormBtn").prop('disabled', true);
		break;
	}
	$("#entryForm").attr("action", location);
    });
</script>
<script>
    function checkSubmitFileBtn() {
	if (document.getElementById("upload").files.length !== 0 && document.getElementById("fileType").selectedIndex !== 0) {
	    $("#submitFile").prop('disabled', false);
	} else {
	    $("#submitFile").prop('disabled', true);
	}
    }
</script>

