<?php
    namespace App\Model\Table;

    use Cake\ORM\Query;
    use Cake\ORM\RulesChecker;
    use Cake\ORM\Table;
    use Cake\Validation\Validator;
	use Cake\Event\Event;

    class NutrientSamplesTable extends Table {
        public function initialize(array $config) {
            parent::initialize($config);

            $this->setTable("nutrient_samples");
            $this->setDisplayField("Sample_Number");
            $this->setPrimaryKey("Sample_Number");

            $this->belongsTo("SiteLocations", [
                "foreignKey" => "site_location_id"
            ]);
        }

        public function validationDefault(Validator $validator) {
            $validator
                ->integer("ID")
                ->allowEmpty("ID", "create");

            $validator
                ->date("Date", "mdy")
                ->requirePresence("Date", "create")
                ->notEmpty("Date")
				->add("Date", "custom", [
					"rule" => function ($value, $context) {
						//validate that the row actually has some sample data associated with it
						$data = $context["data"];
						
						$allNull = true;
						
						foreach (["Phosphorus", "NitrateNitrite", "DRP", "Ammonia", "NutrientComments"] as $key) {
							if (isset($data[$key]) && $data[$key] != null && $data[$key] != "") {
								$allNull = false;
								break;
							}
						}
						
						return !$allNull;
					},
					"message" => "null row"
				]);

            $validator
                ->integer("Sample_Number")
                ->requirePresence("Sample_Number", "create")
                ->notEmpty("Sample_Number")
                ->add("Sample_Number", "unique", ["rule" => "validateUnique", "provider" => "table"]);

            $validator
                ->decimal("Phosphorus")
                ->allowEmpty("Phosphorus");

            $validator
                ->decimal("NitrateNitrite")
                ->allowEmpty("NitrateNitrite");

            $validator
                ->decimal("DRP")
                ->allowEmpty("DRP");
				
			$validator
				->decimal("Ammonia")
				->allowEmpty("Ammonia");

            $validator
                ->scalar("NutrientComments")
                ->maxLength("NutrientComments", 200)
                ->allowEmpty("NutrientComments");

            return $validator;
        }

        public function buildRules(RulesChecker $rules) {
            $rules->add($rules->isUnique(["Sample_Number"]));

            return $rules;
        }
		
		public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options) {
			//treat values like "n/a" or "no data" as null fields (they'll still show as the original values if theres other errors though, to help the user figure out what went wrong)
			foreach (["Phosphorus", "NitrateNitrite", "DRP", "Ammonia"] as $key) {
				if (isset($data[$key]) && is_string($data[$key])) {
					if (in_array(strtolower($data[$key]), ["n/a", "na", "nd", "no data"])) {
						$data[$key] = null;
					}
				}
			}
		}
    }