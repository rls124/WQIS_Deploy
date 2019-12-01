<?php
    namespace App\Model\Table;

    use Cake\ORM\Query;
    use Cake\ORM\RulesChecker;
    use Cake\ORM\Table;
    use Cake\Validation\Validator;
	use Cake\Event\Event;

    /**
     * PesticideSamples Model
     *
     * @property \App\Model\Table\SiteLocationsTable|\Cake\ORM\Association\BelongsTo $SiteLocations
     *
     * @method \App\Model\Entity\PesticideSample get($primaryKey, $options = [])
     * @method \App\Model\Entity\PesticideSample newEntity($data = null, array $options = [])
     * @method \App\Model\Entity\PesticideSample[] newEntities(array $data, array $options = [])
     * @method \App\Model\Entity\PesticideSample|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
     * @method \App\Model\Entity\PesticideSample patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
     * @method \App\Model\Entity\PesticideSample[] patchEntities($entities, array $data, array $options = [])
     * @method \App\Model\Entity\PesticideSample findOrCreate($search, callable $callback = null, $options = [])
     */
    class PesticideSamplesTable extends Table {

        /**
         * Initialize method
         *
         * @param array $config The configuration for the Table.
         * @return void
         */
        public function initialize(array $config) {
            parent::initialize($config);

            $this->setTable('pesticide_samples');
            $this->setDisplayField('Sample_Number');
            $this->setPrimaryKey('Sample_Number');

            $this->belongsTo('SiteLocations', [
                'foreignKey' => 'site_location_id'
            ]);
        }

        /**
         * Default validation rules.
         *
         * @param \Cake\Validation\Validator $validator Validator instance.
         * @return \Cake\Validation\Validator
         */
        public function validationDefault(Validator $validator) {
            $validator
                ->integer('ID')
                ->allowEmpty('ID', 'create');

            $validator
                ->date('Date', 'mdy')
                ->requirePresence('Date', 'create')
                ->notEmpty('Date')
				->add('Date', 'custom', [
					'rule' => function ($value, $context) {
						//validate that the row actually has some sample data associated with it
						$data = $context["data"];
						
						$allNull = true;
						
						foreach (['Atrazine', 'Alachlor', 'Metolachlor', 'PesticideComments'] as $key) {
							if (isset($data[$key]) && $data[$key] != null && $data[$key] != "") {
								$allNull = false;
								break;
							}
						}
						
						return !$allNull;
					},
					'message' => 'null row'
				]);

            $validator
                ->integer('Sample_Number')
                ->requirePresence('Sample_Number', 'create')
                ->notEmpty('Sample_Number')
                ->add('Sample_Number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

            $validator
                ->decimal('Atrazine')
                ->allowEmpty('Atrazine');

            $validator
                ->decimal('Alachlor')
                ->allowEmpty('Alachlor');

            $validator
                ->decimal('Metolachlor')
                ->allowEmpty('Metolachlor');

            $validator
                ->scalar('PesticideComments')
                ->maxLength('PesticideComments', 200)
                ->allowEmpty('PesticideComments');

            return $validator;
        }

        /**
         * Returns a rules checker object that will be used for validating
         * application integrity.
         *
         * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
         * @return \Cake\ORM\RulesChecker
         */
        public function buildRules(RulesChecker $rules) {
            $rules->add($rules->isUnique(['Sample_Number']));

            return $rules;
        }

		public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options) {
			//treat values like "n/a" or "no data" as null fields (they'll still show as the original values if theres other errors though, to help the user figure out what went wrong)
			foreach (['Atrazine', 'Alachlor', 'Metolachlor'] as $key) {
				if (isset($data[$key]) && is_string($data[$key])) {
					if (in_array(strtolower($data[$key]), ["n/a", "na", "nd", "no data"])) {
						$data[$key] = null;
					}
				}
			}
		}
    }