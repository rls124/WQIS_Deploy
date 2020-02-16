<?php
	namespace App\Controller;

	use App\Controller\AppController;

	/**
	 * Site Groups Controller
	 *
	 * @property \App\Model\Table\SiteGroupsTable $SiteGroups
     * @property \App\Model\Table\GroupingsTable $Groupings
	 * @property \App\Model\Table\SiteLocationsTable $SiteLocations
	 *
	 * @method \App\Model\Entity\SiteGroups[] paginate($object = null, array $settings = [])
	 */
	class SiteGroupsController extends AppController {

		public function sitegroups() {
			$SiteGroups = $this->SiteGroups->find('all');
			$this->set(compact('SiteGroups'));
			
			$this->Groupings = $this->loadModel('Groupings');
			$Groupings = $this->Groupings->find('all');
			$this->set(compact('Groupings'));

			$this->SiteLocations = $this->loadModel('SiteLocations');
			$SiteLocations = $this->SiteLocations->find('all');
			$this->set(compact('SiteLocations'));
		}

		public function fetchgroupdata() {
			$this->render(false);
			//Check if groupkey is set
			if (!$this->request->getData('groupkey')) {
				return;
			}
			$groupkey = $this->request->getData('groupkey');

			$group = $this->SiteGroups
				->find('all')
				->where(['groupKey = ' => $groupkey])
				->first();

			$this->loadModel('Groupings');
			$groupings = $this->Groupings
				->find('all')
				->where(['group_ID = ' => $groupkey])
				->select('site_ID');

			$sites = array();
			$i = 0;
			foreach ($groupings as $grouping) {
				$sites[$i] = $grouping->site_ID;
				$i++;
			}

			$json = json_encode(['groupname' => $group->groupName,
				'groupdescription' => $group->groupDescription,
				'sites' => $sites]);

			$this->response = $this->response->withStringBody($json);
			$this->response = $this->response->withType('json');

			return $this->response;
		}

		public function updategroupdata() {
			$this->render(false);

			if (!$this->request->getData('groupkey')) {
				return;
			}

			$groupkey = $this->request->getData('groupkey');

			$group = $this->SiteGroups
				->find('all')
				->where(['groupKey = ' => $groupkey])
				->first();
				
			$this->loadModel('Groupings');
			$groupings = $this->Groupings
				->find('all')
				->where(['group_ID = ' => $group->groupKey]);

			foreach ($groupings as $grouping) {
				if ($this->Groupings->delete($grouping)); // delete every grouping...
			}
			
			//...then add all of the new groups
			foreach ($this->request->getData('sites') as $site) {
				$Grouping = $this->Groupings->newEntity(['group_ID' => $group->groupKey, 'site_ID' => $site]);
				if (!$this->Groupings->save($Grouping)) {
					return;
				}
			}
			
			$group->groupName = $this->request->getData('groupname');
			$group->groupDescription = $this->request->getData('groupdescription');

			if ($this->SiteGroups->save($group)) {
				return;
			}
		}

		public function addgroup() {
			$this->render(false);

			if ($this->request->is('post')) {
				$SiteGroup = $this->SiteGroups->newEntity(['groupName' => $this->request->getData('groupname'), 'groupDescription' => $this->request->getData('groupdescription')]);
				$groupName = $this->request->getData('groupname');
				
				if ($this->SiteGroups->save($SiteGroup)) {
					$group = $this->SiteGroups
						->find('all')
						->where(['groupName = ' => $groupName])
						->first();

					$this->loadModel('Groupings');
					// save each of the groupings to the DB
					foreach ($this->request->getData('sites') as $site) {
						$Grouping = $this->Groupings->newEntity(['group_ID' => $group->groupKey, 'site_ID' => $site]);
						if (!$this->Groupings->save($Grouping)) {
							return;
						}
					}

					$this->response->type('json');
					$json = json_encode(['groupKey' => $group->groupKey]);
					$this->response->body($json);
					return;
				}
			}
		}

		public function deletegroup() {
			$this->render(false);
			//Check if groupKey is set
			if (!$this->request->getData('groupkey')) {
				return;
			}
			$groupkey = $this->request->getData('groupkey');

			$group = $this->SiteGroups
				->find('all')
				->where(['groupKey = ' => $groupkey])
				->first();

			//then the site_group
			$this->SiteGroups->delete($group);
		}
	}
?>