                            <th>Ecoli Raw Count</th>
                            <th>Ecoli<br>(CFU/100 ml)</th>
                            <th>Total Coliform<br>Raw Count</th>
                            <th>Total Coliform<br>(CFU/100 ml)</th>
                            <th>Comments</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr id="row-0">
                            <td>
                                <select class="form-control entryControl siteselect" id="site_location_id-0" name="site_location_id-0">
                                    <option value="" selected="selected">Site</option>
									<?php
										foreach ($siteLocations as $siteLocation) {
											$siteNumber = $this->Number->format($siteLocation->Site_Number);
											$siteName = h($siteLocation->Site_Name);
											$siteLocation = h($siteLocation->Site_Location);
											echo "<option value=$siteNumber>$siteNumber $siteName - $siteLocation</option>";
										}
									?>
                                </select>
                            </td>
							<?=
								$this->Form->control('sample_number-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control entryControl samplenumber',
									'id' => 'sample_number-0',
									'readonly' => true
								])
							?>
                            <td>
								<?=
									$this->Form->select('ecolirawcount-0', $rawCount, [
										'id' => 'ecolirawcount-0',
										'empty' => 'ND',
										'class' => 'form-control entryControl entryDropDown'
									])
								?>
                            </td>
							<?=
								$this->Form->control('ecoli-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control',
									'readonly' => true
								])
							?>
                            <td>
								<?=
									$this->Form->select('totalcoliformrawcount-0', $rawCount, [
										'id' => 'totalcoliformrawcount-0',
										'empty' => 'ND',
										'class' => 'form-control entryControl entryDropDown'
									])
								?>
                            </td>
							<?=
								$this->Form->control('totalcoliform-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control',
									'readonly' => true
								])
							?>
							<?=
								$this->Form->control('bacteriacomments-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'text',
									'class' => 'form-control entryControl',
									'placeholder' => 'Comments...'
								])
							?>