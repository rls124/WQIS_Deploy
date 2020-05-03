<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class SiteGroupsTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);

		$this->setTable("site_groups");
		$this->setDisplayField("groupKey");
		$this->setPrimaryKey("groupKey");
	}

	public function validationDefault(Validator $validator) {
		$validator
			->scalar("groupKey")
			->maxLength("groupKey", 11)
			->allowEmpty("groupKey", "create");

		$validator
			->scalar("groupName")
			->maxLength("groupName", 160)
			->requirePresence("groupName", "create")
			->notEmpty("groupName");

		$validator
			->scalar("groupDescription")
			->maxLength("groupDescription", 460)
			->allowEmpty("groupDescription");
			
		$validator
			->scalar("owner")
			->maxLength("owner", 100);

		return $validator;
	}
}