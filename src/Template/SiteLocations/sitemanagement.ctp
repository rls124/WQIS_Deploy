<?= $this->Html->script('siteManagement.js') ?>
<?= $this->Html->css('measurementBenchmarks.css') ?>
<?= $this->Html->css('loading.css') ?>

<div id="message" class="message hidden"></div>

<p class="centeredText" id="wqisHeading" style='font-size:2.5rem;'><span class="glyphicon glyphicon-map-marker" style="font-size: 20pt;"></span>  Sites
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
<table id="tableView" class="table table-striped table-responsive">
	<thead>
		<tr>
			<th>Site Number</th>
			<?php if ($admin) {?><th>Monitored</th><?php }?>
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
		    <tr id='tr-<?= $siteData->ID ?>'>
			<td class='sitenum' id='<?php echo 'td-' . $siteData->ID . '-siteNum'; ?>'><?= $siteData->Site_Number ?></td>
			
			<?php
			echo "<td id=\"td-" . $siteData->ID . "-monitored\">";
			echo $this->Form->create(false, [
				'id' => 'checkboxForm'
			]);
			echo $this->Form->checkbox('monitored-' . $siteData->ID, [
				'class' => "form-control checkbox",
				'checked' => $siteData->Monitored,
				'value' => $siteData->Monitored,
				'id' => 'td-' . $siteData->ID . '-monitoredcheckbox'
			]);
			?>
			</td>
			<td>
			<?php
			echo $this->Form->control('longitude', ['maxlength' => '12',
				'id' => 'longitude-' . $row,
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
			?>
			</td>
			
			<td>
			<?php
			echo $this->Form->control('latitude', ['maxlength' => '12',
				'id' => 'latitude-' . $row,
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
			?>
			</td>
			
			<td>
			<?php
			echo $this->Form->control('siteName', ['maxlength' => '12',
				'id' => 'siteName-' . $row,
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
			?>
			</td>
			
			<td>
			<?php
			echo $this->Form->control('siteLoc', ['maxlength' => '12',
				'id' => 'siteLoc-' . $row,
				'class' => 'inputfields tableInput',
				'size' => '11',
				'value' => $siteData->Site_Location,
				'style' => 'display: none',
				'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $siteData->Site_Location . ' '
				]
			]);
			?>
			</td>
			
			<td id='<?php echo 'td-' . $siteData->ID . '-groups'; ?>'><?= $siteData->groups ?></td>
			<td>
			<?php if ($admin) {?>
				<a id="edit-tooltip" data-toggle="tooltip" title="Edit Site">
			    <?=
			    $this->Html->tag('span', "", [
					'class' => "edit glyphicon glyphicon-pencil",
					'id' => 'edit-' . $siteData->ID,
					'name' => 'edit-' . $siteData->ID,
					'data-toggle' => "modal",
					'data-target' => "#editSiteModal"
			    ]);
			    ?>
				</a>
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
<input type='button' class='addSitebtn btn-basic btn mb-3 btn-md' value='Add Site' id='addSiteBtn' name='addSiteBtn' style='float: right;' data-toggle="modal" data-target="#addSiteModal"/>

<!-- Modal Stuff for edit button -->
<div id="editSiteModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

	<form id="updateSiteForm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="edit-header">Edit Site: </h4>
                <p hidden id="edit-site"></p>
                <p hidden id="edit-sitenumber"></p>
            </div>
            <div class="modal-body">
		<?=
		    $this->Form->control('longitude', [
			'label' => [
			    'text' => 'Longitude',
			    'class' => 'label-reg lol'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control textinput",
			'name' => "longitude",
			'id' => "edit-longitude",
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
			'class' => "form-control textinput",
			'name' => "latitude",
			'id' => "edit-latitude",
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
			'class' => "form-control textinput",
			'name' => "sitelocation",
			'id' => "edit-sitelocation",
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
			'class' => "form-control textinput",
			'name' => "sitename",
			'id' => "edit-sitename",
			'placeholder' => "Site Name..."
		    ]);
		?>
            </div>
            <div class="modal-footer">
                <button type="button" id='update-btn' name='update-btn' class="btn btn-default btn-basic btn btn-sm" onclick="updateButton()">Save</button>
                <button type="button" id="edit-close" class="btn btn-default btn-sm btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
	</form>
    </div>
</div>


<!-- Modal Stuff for Add Site button -->
<div id="addSiteModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        
	<?=
	    $this->Form->create(false, [
		'id' => 'addSiteForm'//,
		]
	    )
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
                <button type="submit" id='add-btn' name='add-btn' class="btn btn-default btn-basic btn btn-sm">Add Site</button>
                <button type="button" id='add-close' class="btn btn-default btn-sm btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
	<?= $this->Form->end() ?>
	</div>
</div>
<?php } ?>