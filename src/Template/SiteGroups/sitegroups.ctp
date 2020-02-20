<?= $this->Html->script('siteGroups.js') ?>
<?= $this->Html->css('loading.css') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

<div id='message' class="message hidden"></div>
    
<p class="centeredText" id="wqisHeading" style='font-size:2.5rem;'><span class="glyphicon glyphicon-folder-open" style="font-size: 20pt;"></span>  Site Groups
	<a data-toggle="collapse" href="#collapseInfo" role="button" aria-expanded="false" aria-controls="collapseInfo">
		<span class="glyphicon glyphicon-question-sign" style="font-size:18pt;" data-toggle="tooltip" title="Information" id="infoGlyph"></span>
	</a>
</p>

<hr>
<div class="collapse" id="collapseInfo">
	<div class="card card-body">
		<p> This page is used to create, modify, or delete site groups.</p>
		<ul>
			<li>To add a group, click the 'Add Group' button.</li>
			<li>To delete a group, click the delete icon in the row containing the group to delete.</li>
			<li>To edit a group, click the edit icon in the row containing the group to edit.</li>
		</ul>
	</div>
</div>

<input type='button' class='addGroupbtn btn-basic btn mt-2 mb-2 btn-md' value='Add Group' id='addGroupBtn' name='addGroupBtn' data-toggle="modal" data-target="#addGroupModal"/>
<table id='tableView' class="table table-striped table-responsive">
	<thead>
		<tr>
			<th>Group Name</th>
			<th>Description</th>
			<th>Sites</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody id="groupTable">
	<?php
		$row = 0;
		foreach ($SiteGroups as $siteGroup) {
		    ?>
			<tr id='tr-<?= $siteGroup->groupKey ?>'>
				<td class='groupkey' id='<?php echo 'td-' . $siteGroup->groupKey . '-groupKey'; ?>'><?= $siteGroup->groupName ?></td>
				<td id='<?php echo 'td-' . $siteGroup->groupDescription . '-groupDescription'; ?>'><?= $siteGroup->groupDescription ?></td>

				<!-- Display all sites in this group -->
				<td id='<?php echo 'td-' . $siteGroup->groupKey . '-sites'; ?>'>
					<?php
					foreach ($Groupings as $grouping) {
						$groups = explode(',', $grouping->groups);
						if (in_array($siteGroup->groupKey, $groups)) {
							echo $grouping->Site_Number . " ";
						}
					} 
					?>
				</td>
				
				<td>
					<a id="edit-tooltip" data-toggle="tooltip" title="Edit Group">
					<?=
					$this->Html->tag('span', "", [
						'class' => "edit glyphicon glyphicon-pencil",
						'id' => 'edit-' . $siteGroup->groupKey,
						'name' => 'edit-' . $siteGroup->groupKey,
						'data-toggle' => "modal",
						'data-target' => "#editGroupModal"
					]);
					?>
					</a>
					<a id="delete-tooltip" data-toggle="tooltip" title="Delete Group">
					<?=
					$this->Html->tag('span', "", [
						'class' => "delete glyphicon glyphicon-trash",
						'id' => 'delete-' . $siteGroup->groupKey,
						'name' => 'delete-' . $siteGroup->groupKey
					]);
					?>
					</a>
				</td>
				<?php
				$row++;
				?>
			</tr>
		<?php } ?>
	</tbody>
</table>
<input type='button' class='addGroupbtn btn-basic btn mb-3 btn-md' value='Add Group' id='addGroupBtn' name='addGroupBtn' style='float: right;' data-toggle="modal" data-target="#addGroupModal"/>

<!-- Modal for edit button -->
<div id="editGroupModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

	<form id="updateGroupForm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="edit-header">Edit Group: </h4>
                <p hidden id="edit-group"></p>
            </div>
            <div class="modal-body">
				<p hidden id="edit-groupkey"></p>
			<?=
				$this->Form->control('groupname', [
				'label' => [
					'text' => 'Group Name',
					'class' => 'label-reg lol'
				],
				'templates' => [
					'inputContainer' => '{{content}}'
				],
				'class' => "form-control",
				'name' => "groupname",
				'id' => "edit-groupname",
				'placeholder' => "Give the group a clear name..."
				]);
			?>
			<?=
				$this->Form->control('groupdescription', [
				'label' => [
					'text' => 'Group Description',
					'class' => 'label-reg lol'
				],
				'templates' => [
					'inputContainer' => '{{content}}'
				],
				'class' => "form-control",
				'name' => "groupdescription",
				'id' => "edit-groupdescription",
				'placeholder' => "Describe the group..."
				]);
			?>
				<label for="edit-site[]">Sites</label>
				<select class="js-example-placeholder-multiple form-control" id="edit-sites" name="edit-site[]" multiple="multiple" style="width: 100%">
					<?php
					//populate the site drop down box
					foreach ($SiteLocations as $siteLocation) {
						$siteNumber = $this->Number->format($siteLocation->Site_Number);
						$siteName = h($siteLocation->Site_Name);
						$siteLocation = h($siteLocation->Site_Location);
						echo "<option value=$siteNumber title=\"$siteLocation\">$siteNumber $siteName</option>";
					}
					?>
				</select>
            </div>
            <div class="modal-footer">
                <button type="button" id='update-btn' name='update-btn' class="btn btn-default btn-basic btn btn-sm" onclick="updateButton()">Save</button>
                <button type="button" id="edit-close" class="btn btn-default btn-sm btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
	</form>
    </div>
</div>


<!-- Modal for Add Site button -->
<div id="addGroupModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        
	<?=
	    $this->Form->create(false, [
		'id' => 'addGroupForm'
		]
	    )
	?>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Group</h4>
            </div>
            <div class="modal-body">
			<?=
				$this->Form->control('groupname', [
				'label' => [
					'text' => 'Group Name',
					'class' => 'label-reg lol'
				],
				'templates' => [
					'inputContainer' => '{{content}}'
				],
				'class' => "form-control textinput addinput",
				'name' => "groupname",
				'id' => "add-groupname",
				'placeholder' => "Give the group a clear name..."
				]);
			?>
			<?=
				$this->Form->control('groupdescription', [
				'label' => [
					'text' => 'Group Description',
					'class' => 'label-reg lol'
				],
				'templates' => [
					'inputContainer' => '{{content}}'
				],
				'class' => "form-control textinput addinput",
				'name' => "groupdescription",
				'id' => "add-groupdescription",
				'placeholder' => "Describe the group..."
				]);
			?>
				<label for="add-site[]">Sites</label>
				<select class="js-example-placeholder-multiple form-control" id="add-sites" name="add-site[]" multiple="multiple" style="width: 100%">
					<?php
					foreach ($SiteLocations as $siteLocation) {
						$siteNumber = $this->Number->format($siteLocation->Site_Number);
						$siteName = h($siteLocation->Site_Name);
						$siteLocation = h($siteLocation->Site_Location);
						echo "<option value=$siteNumber title=\"$siteLocation\">$siteNumber $siteName</option>";
					}
					?>
				</select>
            </div>
            <div class="modal-footer">
                <button type="submit" id='add-btn' name='add-btn' class="btn btn-default btn-basic btn btn-sm">Add Group</button>
                <button type="button" id='add-close' class="btn btn-default btn-sm btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
	<?= $this->Form->end() ?>
        
    </div>
</div>