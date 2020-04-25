<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class FeedbackTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);

		$this->setTable("feedback");
		$this->setPrimaryKey("ID");
	}

	public function validationDefault(Validator $validator) {
		$validator
			->scalar("Feedback")
			->maxLength("Feedback", 4096)
			->notEmpty("Feedback");

		$validator
			->dateTime("Date")
			->notEmpty("Date");

		$validator
			->scalar("Name");

		$validator
			->scalar("Email");

		$validator
			->numeric("ID")
			->requirePresence("ID", "create")
			->notEmpty("ID");

		return $validator;
	}
}