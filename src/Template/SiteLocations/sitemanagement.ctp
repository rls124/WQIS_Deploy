<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

<?php
echo $this->Html->script("siteManagement.js");
echo $this->Html->css("measurementBenchmarks.css");
echo $this->Html->css("loading.css");

if ($admin) {
	echo "<script>var admin = true;</script>";
}
?>

<div id="message" class="message hidden"></div>

<p class="centeredText" style="font-size:2.5rem;"><span class="glyphicon glyphicon-map-marker" style="font-size: 20pt;"></span>  Sites
<?php if ($admin) {?>
	<a data-toggle="collapse" href="#collapseInfo" role="button" aria-expanded="false" aria-controls="collapseInfo">
		<span class="glyphicon glyphicon-question-sign" style="font-size:18pt;" data-toggle="tooltip" title="Information" id="infoGlyph"></span>
	</a>
<?php }?>
</p>

<hr>
<?php
if ($admin) { ?>
<div class="collapse" id="collapseInfo">
	<div class="card card-body">
		<p>This page is used to add, edit, or delete sites.</p>
		<ul>
			<li>To add a site, click the 'Add Site' button.</li>
			<li>To delete a site, click the delete icon in the row containing the site to delete.</li>
			<li>To edit a site, click the edit icon in the row containing the site to edit.</li>
		</ul>
	</div>
</div>
<input type="button" class="addSitebtn btn-basic btn mt-2 mb-2 btn-md" value="Add Site" id="addSiteBtn" name="addSiteBtn" data-toggle="modal" data-target="#addSiteModal"/>
<?php }?>
<p>
	WQIS maintains sample data from <?=$numSites?> collection sites
</p>
<table class="table table-striped table-responsive">
	<thead>
		<tr>
			<th>Site Number</th>
			<th>Measured from</th>
			<th>Longitude</th>
			<th>Latitude</th>
			<th>Site Name</th>
			<th>Site Location</th>
			<th>Groups</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody id="siteTable">
	<?php
	$row = 0;
	foreach ($SiteLocations as $siteData):
	?>
		<tr id="tr-<?= $siteData->ID ?>">
			<td class="sitenum" id="<?php echo "td-" . $siteData->ID . "-siteNum"; ?>"><?= $siteData->Site_Number ?></td>
			<td>
			<?=$siteData->dateRange?>
			</td>
			<td>
			<?php
			if ($admin) {
				echo $this->Form->control('Longitude', ['maxlength' => '12',
					'id' => 'Longitude-' . $row,
					'class' => 'inputfields tableInput',
					'size' => '11',
					'value' => $siteData->Longitude,
					'style' => 'display: none',
					'label' => [
						'style' => 'display: in-line; cursor: pointer',
						'class' => 'btn btn-thin inputHide',
						'text' => $siteData->Longitude . ' '
					]
				]);
			}
			else {
				echo $siteData->Longitude;
			}
			?>
			</td>
			<td>
			<?php
			if ($admin) {
				echo $this->Form->control('Latitude', ['maxlength' => '12',
					'id' => 'Latitude-' . $row,
					'class' => 'inputfields tableInput',
					'size' => '11',
					'value' => $siteData->Latitude,
					'style' => 'display: none',
					'label' => [
						'style' => 'display: in-line; cursor: pointer',
						'class' => 'btn btn-thin inputHide',
						'text' => $siteData->Latitude . ' '
					]
				]);
			}
			else {
				echo $siteData->Latitude;
			}
			?>
			</td>
		
			<td>
			<?php
			if ($admin) {
				echo $this->Form->control('Site_Name', ['maxlength' => '12',
					'id' => 'Site_Name-' . $row,
					'class' => 'inputfields tableInput',
					'size' => '11',
					'value' => $siteData->Site_Name,
					'style' => 'display: none',
					'label' => [
						'style' => 'display: in-line; cursor: pointer',
						'class' => 'btn btn-thin inputHide',
						'text' => $siteData->Site_Name . ' '
					]
				]);
			}
			else {
				echo $siteData->Site_Name;
			}
			?>
			</td>
		
			<td>
				<?php if ($admin) { ?>
				<label style="display: table-cell; cursor: pointer; white-space:normal !important; overflow-wrap: anywhere" class="btn btn-thin inputHide" for="<?php echo 'siteLoc-' . $row;?>"><?php echo $siteData->Site_Location;?> </label>
				<textarea rows="4" cols="50" class="tableInput" name="siteLoc-<?php echo $row;?>" style="display: none" id="siteLoc-<?php echo $row;?>"><?php echo $siteData->Site_Location;?></textarea>		
				<?php
				}
				else {
					echo $siteData->Site_Location;
				}
				?>
			</td>
		
			<td>
				<?php if ($admin) { ?>
				<select class="form-control groupSelect" id="<?php echo $siteData->Site_Number . "-groups"; ?>" name="<?php echo $siteData->Site_Number . "-groups[]";?>" multiple="multiple" style="width: 100%"></select>
				<?php
				}
				else {
					echo "<span id=\"groups-" . $siteData->Site_Number . "\"></span>";
				}
				?>
			</td>
	
			<td>
			<?php if ($admin) {?>
				<a id="delete-tooltip" data-toggle="tooltip" title="Delete Site">
				<?=
				$this->Html->tag('span', "", [
					'class' => "delete glyphicon glyphicon-trash",
					'id' => 'delete-' . $siteData->ID,
					'name' => 'delete-' . $siteData->ID
				]);
				?>
				</a>
			<?php }?>
				<a href="chartselection?site=<?=$siteData->Site_Number?>">View</a>
			</td>
		<?php
		$row++;
		?>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php if ($admin) {?>
<input type="button" class="addSitebtn btn-basic btn mb-3 btn-md" value="Add Site" id="addSiteBtn" name="addSiteBtn" style="float: right;" data-toggle="modal" data-target="#addSiteModal"/>

<!-- Modal Stuff for Add Site button -->
<div id="addSiteModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        
	<?=
	    $this->Form->create(false, [
			"id" => "addSiteForm"
		])
	?>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Site</h4>
            </div>
            <div class="modal-body">
		<?=
		    $this->Form->control('sitenumber', [
			'label' => [
			    'text' => 'Site Number',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput addinput",
			'name' => "sitenumber",
			'id' => "add-sitenumber",
			'placeholder' => "Site Number..."
		    ]);
		?>
		<?=
		    $this->Form->control('longitude', [
			'label' => [
			    'text' => 'Longitude',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput addinput",
			'name' => "longitude",
			'id' => "add-longitude",
			'placeholder' => "Longitude..."
		    ]);
		?>
		<?=
		    $this->Form->control('latitude', [
			'label' => [
			    'text' => 'Latitude',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput addinput",
			'name' => "latitude",
			'id' => "add-latitude",
			'placeholder' => "Latitude..."
		    ]);
		?>
		<?=
		    $this->Form->control('sitelocation', [
			'label' => [
			    'text' => 'Site Location',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput addinput",
			'name' => "sitelocation",
			'id' => "add-sitelocation",
			'placeholder' => "Site Location..."
		    ]);
		?>
		<?=
		    $this->Form->control('sitename', [
			'label' => [
			    'text' => 'Site Name',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput addinput",
			'name' => "sitename",
			'id' => "add-sitename",
			'placeholder' => "Site Name..."
		    ]);
		?>
            </div>
            <div class="modal-footer">
                <button type="submit" id="add-btn" name="add-btn" class="btn btn-default btn-basic btn btn-sm">Add Site</button>
                <button type="button" id='add-close' class="btn btn-default btn-sm btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
	<?= $this->Form->end() ?>
	</div>
</div>
<?php } ?>