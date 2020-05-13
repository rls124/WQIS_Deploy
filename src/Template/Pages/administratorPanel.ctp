<?php
if ($admin) {
echo $this->Html->css("administratorPanel.css");
echo $this->Html->script("administratorPanel.js");
?>

<p class="centeredText" style="font-size:2.5rem;"><span class="glyphicon glyphicon-home" style="font-size:20pt;"></span>  Administrator Panel</p>
<hr>

<div class="card-deck mb-3">
	<div class="card big-card">
		<div class="card-header">
			<h5 class="card-title centeredText mb-0" style="font-size: 1.15rem"><span class="glyphicon glyphicon-list-alt" style="font-size:14pt"></span>  Water Quality Data Entry</h5>
		</div>
		<div class="card-block">
			<?=
		    $this->Form->create("entryForm", [
			"url" => 'javascript:void(0);',
			"id" => 'entryForm',
			"name" => 'entryForm'
		    ])
			?>
			<div class="row">
		    <?=
			$this->Form->select("entryType", [
			    "bacteria" => "Bacteria Entry Form",
			    "nutrient" => "Nutrient Entry Form",
			    "pesticide" => "Pesticide Entry Form",
			    "physical" => "Physical Properties Entry Form"
			    ], [
			    "class" => "ml-3 mr-3 mb-3 btn btn-default col-sm",
			    "empty" => "Select Entry Form...",
			    "id" => "entryType"
			    ]
			)
			?>
			</div>

			<div class="row center mb-0">
                <input type="submit" class="btn btn-basic col-md-5 mb-0" value="Go To Entry Form" id="EntryFormBtn" name="EntryForm" style="height:43px;" disabled>
			</div>
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
				"url" => 'javascript:void(0);',
				'type' => 'file',
				"id" => 'fileupload',
				"name" => 'fileupload'
				])
				?>
				<div class="row mb-2">
					<label class="btn btn-file btn-basic ml-3 mr-1" style="font-size: 12pt; margin-top: 5px">Choose File <input type="file" name="file" accept=".csv" id="chooseFileButton"> </label>

					<div class="col-sm ml-2 mr-3 mb-1 mt-1" id="FileUploadDiv" style="padding-left: 5px;">
						<label id="FileUploadLabel" style="font-size: 12pt; margin-top: 8px; color: #919191"> File Name</label>
					</div>
				</div>

				<div class="row center mb-2">
					<label class="ml-3 mr-1" style="font-size: 12pt; margin-top: 5px;">Overwrite duplicate sample numbers <input type="checkbox" name="overwrite" id="overwrite" value="true"> </label>
				</div>
		
                <div class="row center mb-0">
                    <a href="..\webroot\files\exampleFiles.zip" class="mb-0 mt-1 btn btn-basic col-sm sampleFile" id="sampleFile" style="height: 43px;">Download Sample File</a>
					
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
				<a onclick="location.href = '<?php echo $this->Url->build(["controller" => 'MeasurementSettings', "action" => 'measurements']) ?>';">
					<div class="card-header" style="height: 50px;">
						<h5 class="card-title centeredText mb-0" style="font-size:1.15rem;"><span class="glyphicon glyphicon-scale" style="font-size: 14pt;"></span>  Measurement Settings</h5>
					</div>
				</a>
			</div>
			<div class="card" style="width:100%; margin-top:10px;">
				<a onclick="location.href = '<?php echo $this->Url->build(["controller" => 'users', "action" => 'usermanagement']) ?>';">
					<div class="card-header" style="height: 50px;">
						<h5 class="card-title centeredText mb-0" style="font-size: 1.15rem;"><span class="glyphicon glyphicon-user" style="font-size:14pt;"></span>  User Management</h5>
					</div>
				</a>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="card" style="width:100%; margin-top:10px;">
				<a onclick="location.href = '<?php echo $this->Url->build(["controller" => 'SiteLocations', "action" => 'sitemanagement']) ?>';">
					<div class="card-header" style="height: 50px;">
						<h5 class="card-title centeredText mb-0" style="font-size:1.15rem;"><span class="glyphicon glyphicon-map-marker" style="font-size: 14pt;"></span>  Site Management</h5>
					</div>
				</a>
			</div>
			<div class="card" style="width:100%; margin-top:10px;">
				<a onclick="location.href = '<?php echo $this->Url->build(["controller" => 'SiteGroups', "action" => 'sitegroups']) ?>';">
					<div class="card-header" style="height: 50px;">
						<h5 class="card-title centeredText mb-0" style="font-size:1.15rem;"><span class="glyphicon glyphicon-folder-open" style="font-size: 14pt;"></span>&nbsp; Site Groups</h5>
					</div>
				</a>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="card" style="width:100%; margin-top:10px;">
				<a onclick="location.href = '<?php echo $this->Url->build(["controller" => 'contact', "action" => 'viewfeedback']) ?>';">
					<div class="card-header" style="height: 50px;">
						<h5 class="card-title centeredText mb-0" style="font-size: 1.15rem;"><span class="glyphicon glyphicon-envelope" style="font-size:14pt;"></span>  View Feedback</h5>
					</div>
				</a>
			</div>
		</div>
	</div>
</div>

<?php
}
else {
	?>
	<h3>You must be an administrator to access this page</h3>
	<a href="javascript:history.back()">Go Back</a>
	<?php
}
?>