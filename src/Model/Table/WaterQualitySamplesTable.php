<?php

    namespace App\Model\Table;

    use Cake\ORM\Query;
    use Cake\ORM\RulesChecker;
    use Cake\ORM\Table;
    use Cake\Validation\Validator;
	use Cake\Event\Event;
	use ArrayObject;

    /**
     * WaterQualitySamples Model
     *
     * @property \App\Model\Table\SiteLocationsTable|\Cake\ORM\Association\BelongsTo $SiteLocations
     *
     * @method \App\Model\Entity\WaterQualitySample get($primaryKey, $options = [])
     * @method \App\Model\Entity\WaterQualitySample newEntity($data = null, array $options = [])
     * @method \App\Model\Entity\WaterQualitySample[] newEntities(array $data, array $options = [])
     * @method \App\Model\Entity\WaterQualitySample|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
     * @method \App\Model\Entity\WaterQualitySample patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
     * @method \App\Model\Entity\WaterQualitySample[] patchEntities($entities, array $data, array $options = [])
     * @method \App\Model\Entity\WaterQualitySample findOrCreate($search, callable $callback = null, $options = [])
     */
    class WaterQualitySamplesTable extends Table {

        /**
         * Initialize method
         *
         * @param array $config The configuration for the Table.
         * @return void
         */
        public function initialize(array $config) {
            parent::initialize($config);

            $this->setTable('water_quality_samples');
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
                ->notEmpty('Date');

            $validator
                ->integer('Sample_Number')
                ->requirePresence('Sample_Number', 'create')
                ->notEmpty('Sample_Number')
                ->add('Sample_Number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

            $validator
                ->time('Time')
                ->requirePresence('Time', 'create')
                ->allowEmpty('Time');

            $validator
                ->decimal('Bridge_to_Water_Height')
                ->allowEmpty('Bridge_to_Water_Height');

            $validator
                ->decimal('Water_Temp')
                ->allowEmpty('Water_Temp');

            $validator
                ->decimal('pH')
                ->allowEmpty('pH');

            $validator
                ->decimal('Conductivity')
                ->allowEmpty('Conductivity');

            $validator
                ->decimal('TDS')
                ->allowEmpty('TDS');

            $validator
                ->decimal('DO')
                ->allowEmpty('DO');

            $validator
                ->decimal('Turbidity')
                ->allowEmpty('Turbidity');

            $validator
                ->integer('Turbidity_Scale_Value')
                ->allowEmpty('Turbidity_Scale_Value');

            $validator
                ->scalar('WaterQualityComments')
                ->maxLength('WaterQualityComments', 200)
                ->allowEmpty('WaterQualityComments');

            $validator
                ->date('Import_Date')
                ->allowEmpty('Import_Date');

            $validator
                ->time('Import_Time')
                ->allowEmpty('Import_Time');

            $validator
                ->scalar('Requires_Checking')
                ->maxLength('Requires_Checking', 200)
                ->allowEmpty('Requires_Checking');

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
		
		//format times before we attempt to load them into the database. Necessary because otherwise, single-digit hour times would fail
		public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options) {
			foreach (['Time', 'Import_Time'] as $key) {
				if (isset($data[$key]) && is_string($data[$key])) {
					$data[$key] = \Cake\I18n\Time::parseTime($data[$key], 'HH:mm');
				}
			}
		}
    }