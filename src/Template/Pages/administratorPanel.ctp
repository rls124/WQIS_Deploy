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
                <div class="row"> </div>

				

                <div class="row mb-3">
				
                    <label class="btn btn-file btn-basic  ml-3 mr-1" style="font-size: 14pt; margin-top: 5px;">Choose File <input type="file" name="file" accept=".csv" id="chooseFileButton"> </label>
					
                    <div class="col-sm ml-2 mr-3 mb-1 mt-1" id="FileUploadDiv" style="padding-left: 5px;">
                        <label id="FileUploadLabel" style="font-size: 14pt; margin-top: 8px; color: #919191;"> File Name</label>
                    </div>
					
                </div>
				
				<div class="row mb-3"> </div>
				<div class="row mb-3"> </div>
		

                <div class="row center mb-0">
                    <a type="submit" href="..\webroot\files\All_Sample_Files.zip"  class="mb-0 mt-1 btn btn-basic col-sm sampleFile" id="sampleFileDisabled" name="sampleFile" style="height: 43px;">Download Sample File</a>
					
                    <input type="submit" class="mb-0 ml-4 mt-1 btn btn-basic col-sm" value="Import Data" id="submitFile" name="submitFile" style="height: 43px;" disabled>
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
    $("#chooseFileButton").change(function () {
		var location = "<?= $this->Html->Url->build(['controller' => 'GenericSamples', 'action' => 'uploadlog']); ?>";
		$("#submitFile").prop('disabled', false);
		$("#fileupload").attr("action", location);
    });
</script>

<script>
    $(document).ready(function () {
	$('#infoGlyph').tooltip();
	$("input:file").change(function () {
	    var fileName = $(this).val();
	    fileName = fileName.replace(/^.*[\\\/]/, '');
		document.getElementById("FileUploadLabel").style.color = "black";
	    $("#FileUploadLabel").html(fileName);
	    checkSubmitFileBtn();
	});
    });


</script>

<script>
	$(function(){
	$('#fileupload').submit(function(){
    $("input[type='submit']", this)
      .val("Please Wait...")
      .attr('disabled', 'disabled');
    return true;
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