<?php

    namespace App\Model\Table;

    use Cake\ORM\Query;
    use Cake\ORM\RulesChecker;
    use Cake\ORM\Table;
    use Cake\Validation\Validator;

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
                ->notEmpty('Date');

            $validator
                ->integer('Sample_Number')
                ->requirePresence('Sample_Number', 'create')
                ->notEmpty('Sample_Number')
                ->add('Sample_Number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

            $validator
                ->decimal('Atrazine')
                ->allowEmpty('Atrazine');

            $validator
                ->integer('AtrazineException')
                ->allowEmpty('AtrazineException');

            $validator
                ->decimal('Alachlor')
                ->allowEmpty('Alachlor');

            $validator
                ->integer('AlachlorException')
                ->allowEmpty('AlachlorException');

            $validator
                ->decimal('Metolachlor')
                ->allowEmpty('Metolachlor');

            $validator
                ->integer('MetolachlorException')
                ->allowEmpty('MetolachlorException');

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

    }
