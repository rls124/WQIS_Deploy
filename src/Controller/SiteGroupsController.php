<?php
	namespace App\Controller;

	use App\Controller\AppController;

	/**
	 * Site Groups Controller
	 *
	 * @property \App\Model\Table\SiteGroupsTable $SiteGroups
	 * @property \App\Model\Table\SiteLocationsTable $SiteLocations
	 *
	 * @method \App\Model\Entity\SiteGroups[] paginate($object = null, array $settings = [])
	 */
	class SiteGroupsController extends AppController {
		public function sitegroups() {
			$SiteGroups = $this->SiteGroups->find('all');
			$this->set(compact('SiteGroups'));
			
			$this->SiteLocations = $this->loadModel('SiteLocations');
			$Groupings = $this->SiteLocations->find()->select(['Site_Number', 'groups']);
			$this->set(compact('Groupings'));
			
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

			$this->loadModel('SiteLocations');
			$groupings = $this->SiteLocations
				->find('all')
				->where(function (\Cake\Database\Expression\QueryExpression $exp, \Cake\ORM\Query $q) {
					return $exp->like('groups', '%A' . $groupkey . '%A'); //WHERE groups LIKE "%Agroupkey%A"
				})
				->select('Site_Number');

			$sites = array();
			$i = 0;
			foreach ($groupings as $grouping) {
				$sites[$i] = $grouping->Site_Number;
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

			$this->loadModel('SiteLocations');
			$groupings = $this->SiteLocations
				->find('all')
				->where(function (\Cake\Database\Expression\QueryExpression $exp, \Cake\ORM\Query $q) {
					return $exp->like('groups', '%A' . $groupkey . '%A'); //WHERE groups LIKE "%Agroupkey%A"
				})
				->select('Site_Number');

/* //what is even happening here?
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
*/			
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

					$this->loadModel('SiteLocations');
					foreach ($this->request->getData('sites') as $site) {
						//add this group to the sites group list
						$this->log($site, 'debug');
						//first get the site
						$siteObj = $this->SiteLocations
							->find('all')
							->where(['Site_Number = ' => $site])
							->first();
						
						//get its existing list of groups
						$existingGroups = $siteObj->groups;
						
						if ($existingGroups == null) { //no groups present
							$siteObj->groups = $group->groupKey;
						}
						else {
							$siteObj->groups = $siteObj->groups . "," . $group->groupKey;
						}
						
						$this->SiteLocations->save($siteObj);
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

			//then delete the site_group
			$this->SiteGroups->delete($group);
		}
	}
?>