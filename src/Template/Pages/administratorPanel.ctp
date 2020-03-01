<?php
if ($admin) { ?>
<link href="../css/administratorPanel.css" rel="stylesheet" type="text/css"/>

<p class="centeredText" style="font-size:2.5rem;"><span class="glyphicon glyphicon-home" style="font-size:20pt;"></span>  Administrator Panel
        <a data-toggle="collapse" href="#collapseInfo" role="button" aria-expanded="false" aria-controls="collapseInfo">
			<span class="glyphicon glyphicon-question-sign" style="font-size:18pt;" data-toggle="tooltip" title="Information" id="infoGlyph"></span>
		</a>
	</p>
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
		    <span class="glyphicon glyphicon-scale" style="font-size: 14pt;"></span>
		    - Used to define acceptable values and benchmarks for water quality data.
		</li>
		<li class="info-li">
		    <span class="glyphicon glyphicon-map-marker" style="font-size: 14pt;"></span>
		    - Used to add, edit, or delete sites.
		</li>
		<li class="info-li">
		    <span class="glyphicon glyphicon-envelope" style="font-size: 14pt;"></span>
		    - Used to view feedback from non-admin users.
		</li>
		<li class="info-li">
		    <span class="glyphicon glyphicon-user" style="font-size:14pt;"></span>
		    - Used to add, edit, or delete users.
		</li>
		<li class="info-li">
		    <span class="glyphicon glyphicon-folder-open" style="font-size:14pt;"></span>
		    - Used to add, edit, or delete site groups.
		</li>
	    </ul>
	</div>
    </div>

    <div class="card-deck mb-3">
        <div class="card big-card">
            <div class="card-header">
                <h5 class="card-title centeredText mb-0" style="font-size: 1.15rem;"><span class="glyphicon glyphicon-list-alt" style="font-size:14pt;"></span>  Water Quality Data Entry</h5>
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
			    'physical' => 'Physical Properties Entry Form'
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
                <h5 class="card-title centeredText mb-0" style="font-size: 1.15rem;"><span class="glyphicon glyphicon-upload" style="font-size:14pt;"></span>  Import</h5>
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
                <div class="row"></div>
                <div class="row mb-3">
                    <label class="btn btn-file btn-basic  ml-3 mr-1" style="font-size: 12pt; margin-top: 5px;">Choose File <input type="file" name="file" accept=".csv" id="chooseFileButton"> </label>
					
                    <div class="col-sm ml-2 mr-3 mb-1 mt-1" id="FileUploadDiv" style="padding-left: 5px;">
                        <label id="FileUploadLabel" style="font-size: 12pt; margin-top: 8px; color: #919191;"> File Name</label>
                    </div>
                </div>
				
				<div class="row mb-3"></div>
				<div class="row mb-3"></div>
		
                <div class="row center mb-0">
                    <a type="submit" href="..\webroot\files\exampleFiles.zip"  class="mb-0 mt-1 btn btn-basic col-sm sampleFile" id="sampleFileDisabled" name="sampleFile" style="height: 43px;">Download Sample File</a>
					
                    <input type="submit" class="mb-0 ml-4 mt-1 btn btn-basic col-sm" value="Import Data" id="submitFile" name="submitFile" style="height: 43px;" disabled >
					<img id="loadingIcon" src="..\webroot\img\loading.gif" style="display: block; margin-top: 12px; margin-left: 6px; width: 32px; height: 32px; visibility: hidden;">
                </div>
		<?= $this->Form->end() ?>
            </div>
        </div>
    </div>

    <hr>
	<div class="row">
		<div class="col-sm-4">
			<div class="card" style="width:100%; margin-top:10px;">
				<a class="cardModal" onclick="location.href = '<?php echo $this->Url->build(['controller' => 'MeasurementSettings', 'action' => 'measurementsettings']) ?>';">
					<div class="card-header" style="height: 50px;">
						<h5 class="card-title centeredText mb-0" style="font-size:1.15rem;"><span class="glyphicon glyphicon-scale" style="font-size: 14pt;"></span>  Measurement Settings</h5>
					</div>
				</a>
			</div>
			<div class="card" style="width:100%; margin-top:10px;">
				<a class="cardModal" onclick="location.href = '<?php echo $this->Url->build(['controller' => 'users', 'action' => 'usermanagement']) ?>';">
					<div class="card-header" style="height: 50px;">
						<h5 class="card-title centeredText mb-0" style="font-size: 1.15rem;"><span class="glyphicon glyphicon-user" style="font-size:14pt;"></span>  User Management</h5>
					</div>
				</a>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="card" style="width:100%; margin-top:10px;">
				<a class="cardModal" onclick="location.href = '<?php echo $this->Url->build(['controller' => 'SiteLocations', 'action' => 'sitemanagement']) ?>';">
					<div class="card-header" style="height: 50px;">
						<h5 class="card-title centeredText mb-0" style="font-size:1.15rem;"><span class="glyphicon glyphicon-map-marker" style="font-size: 14pt;"></span>  Site Management</h5>
					</div>
				</a>
			</div>
			<div class="card" style="width:100%; margin-top:10px;">
				<a class="cardModal" onclick="location.href = '<?php echo $this->Url->build(['controller' => 'SiteGroups', 'action' => 'sitegroups']) ?>';">
					<div class="card-header" style="height: 50px;">
						<h5 class="card-title centeredText mb-0" style="font-size:1.15rem;"><span class="glyphicon glyphicon-folder-open" style="font-size: 14pt;"></span>&nbsp; Site Groups</h5>
					</div>
				</a>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="card" style="width:100%; margin-top:10px;">
				<a class="cardModal" onclick="location.href = '<?php echo $this->Url->build(['controller' => 'feedback', 'action' => 'adminfeedback']) ?>';">
					<div class="card-header" style="height: 50px;">
						<h5 class="card-title centeredText mb-0" style="font-size: 1.15rem;"><span class="glyphicon glyphicon-envelope" style="font-size:14pt;"></span>  View Feedback</h5>
					</div>
				</a>
			</div>
		</div>
	</div>

<script>
$("#entryType").change(function () {
	$("#EntryFormBtn").prop('disabled', ($(this).val() == "")); //enable the button if any entry form type is selected, disable it otherwise
});

$("#chooseFileButton").change(function () {
	//might be good to have a "clear" button for this...
	$("#submitFile").prop('disabled', false);
});

$(document).ready(function () {
	$('#infoGlyph').tooltip();
	$("input:file").change(function () {
		var fileName = $(this).val();
		fileName = fileName.replace(/^.*[\\\/]/, '');
		document.getElementById("FileUploadLabel").style.color = "black";
		$("#FileUploadLabel").html(fileName);
	});
	
	$("#entryForm").attr("action", "<?= $this->Html->Url->build(['controller' => 'GenericSamples', 'action' => 'entryform']); ?>");
	$("#fileupload").attr("action", "<?= $this->Html->Url->build(['controller' => 'GenericSamples', 'action' => 'uploadlog']); ?>");
});

$(function(){
	$('#fileupload').submit(function(){
		$("input[type='submit']", this)
			.val("Please Wait...")
			.attr('disabled', 'disabled');
	
		$('#loadingIcon').css('visibility', 'visible');
		return true;
	});
});
</script>

<?php
}
else {
	?>
	<h3>You must be an administrator to access this page</h3>
	<a href="javascript:history.back()">Go Back</a>
	<?php
}
?>
?>