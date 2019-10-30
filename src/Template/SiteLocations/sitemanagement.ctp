<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
<?= $this->Html->script('siteManagement.js') ?>
<?= $this->Html->css('loading.css') ?>

<div id ='message' class="message hidden"></div>

<div class="container roundGreyBox">
    
    <p class="centeredText" id="wqisHeading" style='font-size:2.5rem;'><span class="glyphicon glyphicon-map-marker" style="font-size: 20pt;"></span>  Site Management
        <a data-toggle="collapse" href="#collapseInfo" role="button" aria-expanded="false" aria-controls="collapseInfo">
	    <span class="glyphicon glyphicon-question-sign" style="font-size:18pt;" data-toggle="tooltip" title="Information" id="infoGlyph"></span>
	</a></p>

    <hr>
    <div class="collapse" id="collapseInfo">
        <div class="card card-body">
            <p> This page is used to add, edit, or delete sites. </p>
            <ul>
                <li>To add a site, click the 'Add Site' button.</li>
                <li>To delete a site, click the delete icon in the row containing the site to delete.</li>
                <li>To edit a site, click the edit icon in the row containing the site to edit.</li>
            </ul>
        </div>
    </div>
    <input type='button' class='addSitebtn btn-basic btn mt-2 mb-2 btn-md' value='Add Site' id='addSiteBtn' name='addSiteBtn' data-toggle="modal" data-target="#addSiteModal"/>
    <table id='tableView'  class="table table-striped table-responsive">
        <thead>
            <tr>
                <th>Site<br>Number</th>
                <th>Monitored</th>
                <th>Longitude</th>
                <th>Latitude</th>
                <th>Site<br>Location</th>
                <th>Site<br>Name</th>
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
                        <td id='<?php echo 'td-' . $siteData->ID . '-monitored'; ?>'>
                        <?=
                                $this->Form->create(false, [
                                    'id' => 'checkboxForm'
                                    ]
                                )
                           ?>
                        <?=
                            $this->Form->checkbox('monitored-' . $siteData->ID, [
                                'class' => "form-control checkbox",
                                'checked' => $siteData->Monitored,
                                'value' => $siteData->Monitored,
                                'id' => 'td-' . $siteData->ID . '-monitoredcheckbox'
                            ]);
                        ?>

                            </td>
			<td id='<?php echo 'td-' . $siteData->ID . '-longitude'; ?>'><?= $siteData->Longitude ?></td>
			<td id='<?php echo 'td-' . $siteData->ID . '-latitude'; ?>'><?= $siteData->Latitude ?></td>
			<td id='<?php echo 'td-' . $siteData->ID . '-siteLoc'; ?>'><?= $siteData->Site_Location ?></td>
			<td id='<?php echo 'td-' . $siteData->ID . '-siteName'; ?>'><?= $siteData->Site_Name ?></td>
                        <td><a id="edit-tooltip" data-toggle="tooltip" title="Edit Site">
			    <?=
			    $this->Html->tag('span', "", [
				'class' => "edit glyphicon glyphicon-pencil",
				'id' => 'edit-' . $siteData->ID,
				'name' => 'edit-' . $siteData->ID,
				'data-toggle' => "modal",
				'data-target' => "#editSiteModal"
			    ])
			    ?>
                            </a><a id="delete-tooltip" data-toggle="tooltip" title="Delete Site">
			    <?=
			    $this->Html->tag('span', "", [
				'class' => "delete glyphicon glyphicon-trash",
				'id' => 'delete-' . $siteData->ID,
				'name' => 'delete-' . $siteData->ID
			    ])
			    ?>
                            </a>
			</td>
			<?php
			$row++;
			?>
		    </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
    <input type='button' class='addSitebtn btn-basic btn mb-3 btn-md' value='Add Site' id='addSiteBtn' name='addSiteBtn' style='float: right;' data-toggle="modal" data-target="#addSiteModal"/>
</div>

<!-- Modal Stuff for edit button -->
<div id="editSiteModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

	<?=
	    $this->Form->create(false, [
		'id' => 'updateSiteForm'
		]
	    )
	?>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="edit-header">Edit Site: </h4>
                <p hidden id="edit-site"></p>
                <p hidden id="edit-sitenumber"></p>
            </div>
            <div class="modal-body">
		<div class="csscssload-load-frame loadingspinner-edit">
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		    <div class="cssload-dot"></div>
		</div>
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
                <button type="submit" id='update-btn' name='update-btn' class="btn btn-default btn-basic btn btn-sm">Save</button>
                <button type="button" id="edit-close" class="btn btn-default btn-sm btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
	<?= $this->Form->end() ?>
    </div>
</div>


<!-- Modal Stuff for Add Site button -->
<div id="addSiteModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        
	<?=
	    $this->Form->create(false, [
		'id' => 'addSiteForm'//,
		//'onsubmit' => 'return validate()'
		]
	    )
	?>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!--button type="button" class="close" data-dismiss="modal">&times;</button-->
                
                <h4 class="modal-title">Add New Site</h4>
            </div>
            <div class="modal-body">
                <div class="csscssload-load-frame loadingspinner-add">
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                    <div class="cssload-dot"></div>
                </div>
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
			'placeholder' => "Site Number...",
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
			'placeholder' => "Longitude...",
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
			'placeholder' => "Latitude...",
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
			'placeholder' => "Site Location...",
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
			'placeholder' => "Site Name...",
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