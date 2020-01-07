                            <th>Atrazine<br>(µg/L)</th>
                            <th>Alachlor<br>(µg/L)</th>
                            <th>Metochlor<br>(µg/L)</th>
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
									'id' => 'sample_number-0',
									'class' => 'form-control entryControl samplenumber',
									'readonly' => true
								])
							?>
							<?=
								$this->Form->control('atrazine-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control entryControl'
								])
							?>
							<?=
								$this->Form->control('alachlor-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control entryControl'
								])
							?>
							<?=
								$this->Form->control('metolachlor-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control entryControl'
								])
							?>
							<?=
								$this->Form->control('pesticidecomments-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'text',
									'class' => 'form-control entryControl',
									'placeholder' => 'Comments...'
								])
							?>