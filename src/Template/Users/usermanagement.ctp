<?= $this->Html->script("userManagement.js") ?>
<?= $this->Html->css("userManagement.css") ?>

<div id="message" class="message hidden"></div>

<p class="centeredText" id="wqisHeading" style="font-size:2.5rem"><span class="glyphicon glyphicon-user" style="font-size: 20pt"></span>  User Management
	<a href="/WQIS/pages/help#userManagement">
		<span class="glyphicon glyphicon-question-sign" style="font-size:18pt" title="Information" id="infoGlyph"></span>
	</a>
</p>

<hr>
<input type="button" class="addUserbtn btn-basic btn mt-2 mb-3 btn-md" value="Add User" id="addUserBtn" name="addUserBtn" data-toggle="modal" data-target="#addUserModal"/>
	<table id="tableView" class="table table-striped table-responsive">
		<thead>
			<tr>
				<th>Username</th>
				<th>User Type</th>
				<th>Full Name</th>
				<th>Email Address</th>
				<th>Organization</th>
				<th>Position</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody id="userTable">
		<?php
		$row = 0;
		foreach ($Users as $userData):
			?>
			<tr id="tr-<?= $userData->username ?>">
			<td><?= $userData->username ?></td>
			<td id="<?php echo "td-" . $userData->username . "-admin"; ?>"><?php
				if ($userData->admin == 1) {
					echo "admin";
				}
				else {
					echo "general";
				}
				?>
			</td>
			<td id="<?php echo "td-" . $userData->username . "-name"; ?>"><?= $userData->firstname . " " . $userData->lastname; ?></td>
			<td id="<?php echo "td-" . $userData->username . "-email"; ?>"><?= $userData->email ?></td>
			<td id="<?php echo "td-" . $userData->username . "-org"; ?>"><?= $userData->organization ?></td>
			<td id="<?php echo "td-" . $userData->username . "-pos"; ?>"><?= $userData->position ?></td>
			<td>
				<a id="edit-tooltip" data-toggle="tooltip" title="Edit User">
				<?=
				$this->Html->tag("span", "", [
					"class" => "edit glyphicon glyphicon-pencil",
					"id" => "edit-" . $userData->username,
					"name" => "edit-" . $userData->username,
					"data-toggle" => "modal",
					"data-target" => "#editUserModal"
				])
				?>
				</a>
				<a id="delete-tooltip" data-toggle="tooltip" title="Delete User">
				<?=
				$this->Html->tag("span", "", [
					"class" => "delete glyphicon glyphicon-trash",
					"id" => "delete-" . $userData->username,
					"name" => "delete-" . $userData->username
				])
				?>
				</a>
			</td>
			<?php $row++; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<input type="button" class="addUserbtn btn-basic btn mb-3 btn-md" value="Add User" id="addUserBtn" name="addUserBtn" style="float: right" data-toggle="modal" data-target="#addUserModal"/>
</div>

<!-- Modal Stuff for edit button -->
<div id="editUserModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="edit-header">Edit User: </h4>
				<p hidden id="edit-username"></p>
			</div>
			<div class="modal-body">
			<?php
			echo $this->Form->create(false, [
				"id" => "updateUserForm"
			]);
			
			$fieldIDs = ["firstname", "lastname", "email", "organization", "position"];
			$fieldNames = ["First Name", "Last Name", "Email", "Organization", "Position"];
			
			for ($i=0; $i<sizeof($fieldIDs); $i++) {
				echo $this->Form->control($fieldIDs[$i], [
					"label" => [
						"text" => $fieldNames[$i],
						"class" => "label-reg lol"
					],
					"templates" => [
						"inputContainer" => "{{content}}"
					],
					"class" => "form-control textinput",
					"name" => $fieldIDs[$i],
					"id" => "edit-" . $fieldIDs[$i],
					"placeholder" => "Your " . $fieldNames[$i] . "..."
				]);
			}
		
			echo "<label for=\"#edit-isadmin\">Set as administrator: </label>";
			echo $this->Form->checkbox("admin", [
				"id" => "edit-isadmin"
			]);
		
			echo "<br>";
		
			echo $this->Form->control("userpw", [
				"label" => [
					"text" => "Password",
					"class" => "label-reg lol"
				],
				"templates" => [
					"inputContainer" => "{{content}}"
				],
				"type" => "password",
				"class" => "form-control textinput",
				"name" => "userpw",
				"id" => "edit-userpw",
				"placeholder" => "Your Password..."
			]);
		
			echo $this->Form->control("Password (again)", [
				"label" => [
					"class" => "label-reg lol"
				],
				"templates" => [
					"inputContainer" => "{{content}}"
				],
				"type" => "password",
				"class" => "form-control textinput",
				"name" => "passConfirm",
				"id" => "edit-passConfirm",
				"placeholder" => "Your Password..."
			]);
			?>
			</div>
			<div class="modal-footer">
				<button type="submit" id="update-btn" name="update-btn" class="btn btn-default btn-basic btn btn-sm">Save</button>
				<button type="button" id="edit-close" class="btn btn-default btn-sm btn-close" data-dismiss="modal">Close</button>
			</div>
		</div>
	<?= $this->Form->end() ?>
	</div>
</div>

<!-- Modal Stuff for Add User button -->
<div id="addUserModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add New User</h4>
			</div>
			<div class="modal-body">
			<?php
			echo $this->Form->create(false, [
				"id" => "addUserForm"
			]);
			
			$fieldIDs = ["firstname", "lastname", "email", "organization", "position", "username"];
			$fieldNames = ["First Name", "Last Name", "Email", "Organization", "Position", "Username"];
			
			for ($i=0; $i<sizeof($fieldIDs); $i++) {
				echo $this->Form->control($fieldIDs[$i], [
					"label" => [
						"text" => $fieldNames[$i],
						"class" => "label-reg lol"
					],
					"templates" => [
						"inputContainer" => "{{content}}"
					],
					"class" => "form-control textinput addinput",
					"name" => $fieldIDs[$i],
					"id" => "add-" . $fieldIDs[$i],
					"placeholder" => "Your " . $fieldNames[$i] . "..."
				]);
			}
			
			echo "<label for=\"#add-admin\">Set as administrator: </label>";
		
			echo $this->Form->checkbox("admin", [
				"label" => [
					"class" => "addinput"
				],
				"checked" => false,
				"id" => "add-admin"
			]);
		
			echo "<br>";
		
			echo $this->Form->control("userpw", [
				"label" => [
					"text" => "Password",
					"class" => "label-reg lol"
				],
				"templates" => [
					"inputContainer" => "{{content}}"
				],
				"type" => "password",
				"class" => "form-control textinput addinput",
				"name" => "userpw",
				"id" => "add-userpw",
				"placeholder" => "Your Password..."
			]);
		
			echo $this->Form->control("Password (again)", [
				"label" => [
					"class" => "label-reg lol"
				],
				"templates" => [
					"inputContainer" => "{{content}}"
				],
				"type" => "password",
				"class" => "form-control textinput addinput",
				"name" => "passConfirm",
				"id" => "add-passConfirm",
				"placeholder" => "Your Password..."
			]);
			?>
			</div>
			<div class="modal-footer">
				<button type="submit" id="add-btn" name="add-btn" class="btn btn-default btn-basic btn btn-sm">Add User</button>
				<button type="button" id="add-close" class="btn btn-default btn-sm btn-close" data-dismiss="modal">Close</button>
			</div>
		</div>
	<?= $this->Form->end() ?>
	</div>
</div>