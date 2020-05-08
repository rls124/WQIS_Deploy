<?= $this->Html->script("siteGroups.js") ?>
<?= $this->Html->css("loading.css") ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

<div id="message" class="message hidden"></div>

<p class="centeredText" style="font-size:2.5rem"><span class="glyphicon glyphicon-folder-open" style="font-size: 20pt"></span>  Site Groups
	<a href="/WQIS/pages/help#groups">
		<span class="glyphicon glyphicon-question-sign" style="font-size:18pt" title="Information" id="infoGlyph"></span>
	</a>
</p>
<hr>
<table id="tableView" class="table table-striped">
	<thead>
		<tr>
			<th>Group Name</th>
			<th>Description</th>
			<th>Sites</th>
			<?php if ($admin) { ?>
			<th>Owner</th>
			<?php } else { ?>
			<th>Type</th>
			<?php } ?>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody id="groupTable">
	<?php
		$row = 0;
		foreach ($SiteGroups as $siteGroup) {
		    ?>
			<tr id="tr-<?= $siteGroup->groupKey ?>">
				<td class="groupkey" id="<?php echo "td-" . $siteGroup->groupKey . "-groupKey"; ?>"><?= $siteGroup->groupName ?></td>
				<td id="<?php echo "td-" . $siteGroup->groupDescription . "-groupDescription"; ?>"><?= $siteGroup->groupDescription ?></td>

				<!-- display all sites in this group -->
				<td id="<?= "td-" . $siteGroup->groupKey . "-sites"; ?>">
					<?php
					foreach ($Groupings as $grouping) {
						$groups = explode(",", $grouping->groups);
						if (in_array($siteGroup->groupKey, $groups)) {
							echo $grouping->Site_Number . " ";
						}
					}
					?>
				</td>
				
				<td id="<?= "td-" . $siteGroup->groupKey . "-visibility"?>">
					<?php
					if ($siteGroup->owner == "all") {
						echo "Public";
					}
					else if (!$admin) {
						echo "Private";
					}
					else {
						echo $siteGroup->owner;
					}
					?>
				</td>
				
				<td>
				<?php if ($admin || $siteGroup->owner == $userinfo["userid"]) { ?>
					<a id="edit-tooltip" data-toggle="tooltip" title="Edit Group">
					<?=
					$this->Html->tag("span", "", [
						"class" => "edit glyphicon glyphicon-pencil",
						"id" => "edit-" . $siteGroup->groupKey,
						"name" => "edit-" . $siteGroup->groupKey,
						"data-toggle" => "modal",
						"data-target" => "#editGroupModal"
					]);
					?>
					</a>
					<a id="delete-tooltip" data-toggle="tooltip" title="Delete Group">
					<?=
					$this->Html->tag("span", "", [
						"class" => "delete glyphicon glyphicon-trash",
						"id" => "delete-" . $siteGroup->groupKey,
						"name" => "delete-" . $siteGroup->groupKey
					]);
					?>
					</a>
				<?php } ?>
					<a href="/WQIS/site-locations/chartselection?group=<?=$siteGroup->groupKey?>">View</a>
				</td>
				<?php $row++; ?>
			</tr>
		<?php } ?>
	</tbody>
</table>
<input type="button" class="btn-basic btn mt-2 mb-2 btn-md" value="Add <?php if (!$admin) { echo "Custom"; }?> Group" id="addGroupBtn" data-toggle="modal" data-target="#addGroupModal"/>

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
					$this->Form->control("groupname", [
					"label" => [
						"text" => "Group Name",
						"class" => "label-reg lol"
					],
					"templates" => [
						"inputContainer" => "{{content}}"
					],
					"class" => "form-control",
					"name" => "groupname",
					"id" => "edit-groupname",
					"placeholder" => "Give the group a clear name..."
					]);
				?>
				<?=
					$this->Form->control("groupdescription", [
					"label" => [
						"text" => "Group Description",
						"class" => "label-reg lol"
					],
					"templates" => [
						"inputContainer" => "{{content}}"
					],
					"class" => "form-control",
					"name" => "groupdescription",
					"id" => "edit-groupdescription",
					"placeholder" => "Describe the group..."
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
					<button type="button" id="update-btn" name="update-btn" class="btn btn-default btn-basic btn btn-sm" onclick="updateButton()">Save</button>
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
			"id" => "addGroupForm"
		])
	?>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Group</h4>
            </div>
            <div class="modal-body">
			<?php if (!$admin) {
				echo "<p>This custom group will only be visible to you</p>";
			}
			echo $this->Form->control("groupname", [
				"label" => [
					"text" => "Group Name",
					"class" => "label-reg lol"
				],
				"templates" => [
					"inputContainer" => "{{content}}"
				],
				"class" => "form-control textinput addinput",
				"name" => "groupname",
				"id" => "add-groupname",
				"placeholder" => "Give the group a clear name..."
				]);
			echo $this->Form->control("groupdescription", [
				"label" => [
					"text" => "Group Description",
					"class" => "label-reg lol"
				],
				"templates" => [
					"inputContainer" => "{{content}}"
				],
				"class" => "form-control textinput addinput",
				"name" => "groupdescription",
				"id" => "add-groupdescription",
				"placeholder" => "Describe the group..."
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
				<?php if ($admin) {?>
					<input type="checkbox" name="makePrivate" id="makePrivate" value="true">
					<label class="ml-3 mr-1" for="makePrivate">Make private</label>
				<?php } ?>
            </div>
            <div class="modal-footer">
                <button type="submit" id="add-btn" name="add-btn" class="btn btn-default btn-basic btn btn-sm">Add Group</button>
                <button type="button" id="add-close" class="btn btn-default btn-sm btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
	<?= $this->Form->end() ?>
    </div>
</div>