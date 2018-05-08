<?php

    namespace App\Model\Table;

    use Cake\ORM\Query;
    use Cake\ORM\RulesChecker;
    use Cake\ORM\Table;
    use Cake\Validation\Validator;

    /**
     * BacteriaSamples Model
     *
     * @property \App\Model\Table\SiteLocationsTable|\Cake\ORM\Association\BelongsTo $SiteLocations
     *
     * @method \App\Model\Entity\BacteriaSample get($primaryKey, $options = [])
     * @method \App\Model\Entity\BacteriaSample newEntity($data = null, array $options = [])
     * @method \App\Model\Entity\BacteriaSample[] newEntities(array $data, array $options = [])
     * @method \App\Model\Entity\BacteriaSample|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
     * @method \App\Model\Entity\BacteriaSample patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
     * @method \App\Model\Entity\BacteriaSample[] patchEntities($entities, array $data, array $options = [])
     * @method \App\Model\Entity\BacteriaSample findOrCreate($search, callable $callback = null, $options = [])
     */
    class BacteriaSamplesTable extends Table {

        /**
         * Initialize method
         *
         * @param array $config The configuration for the Table.
         * @return void
         */
        public function initialize(array $config) {
            parent::initialize($config);

            $this->setTable('bacteria_samples');
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
                ->integer('EcoliRawCount')
                ->allowEmpty('EcoliRawCount');

            $validator
                ->integer('Ecoli')
                ->allowEmpty('Ecoli');

            $validator
                ->integer('EcoliException')
                ->allowEmpty('EcoliException');

            $validator
                ->integer('TotalColiformRawCount')
                ->allowEmpty('TotalColiformRawCount');

            $validator
                ->integer('TotalColiform')
                ->allowEmpty('TotalColiform');

            $validator
                ->integer('ColiformException')
                ->allowEmpty('ColiformException');

            $validator
                ->scalar('Comments')
                ->maxLength('Comments', 200)
                ->allowEmpty('Comments');

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
