<?php

    namespace App\Model\Table;

    use Cake\ORM\Query;
    use Cake\ORM\RulesChecker;
    use Cake\ORM\Table;
    use Cake\Validation\Validator;

    /**
     * SiteLocations Model
     *
     * @property \App\Model\Table\BacteriaSamplesTable|\Cake\ORM\Association\HasMany $BacteriaSamples
     * @property \App\Model\Table\HydrolabSamplesTable|\Cake\ORM\Association\HasMany $HydrolabSamples
     * @property \App\Model\Table\NutrientSamplesTable|\Cake\ORM\Association\HasMany $NutrientSamples
     * @property \App\Model\Table\PesticideSamplesTable|\Cake\ORM\Association\HasMany $PesticideSamples
     * @property \App\Model\Table\WaterQualitySamplesTable|\Cake\ORM\Association\HasMany $WaterQualitySamples
     * @property \App\Model\Table\GroupingsTable|\Cake\ORM\Association\HasMany $Groupings
     *
     * @method \App\Model\Entity\SiteLocation get($primaryKey, $options = [])
     * @method \App\Model\Entity\SiteLocation newEntity($data = null, array $options = [])
     * @method \App\Model\Entity\SiteLocation[] newEntities(array $data, array $options = [])
     * @method \App\Model\Entity\SiteLocation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
     * @method \App\Model\Entity\SiteLocation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
     * @method \App\Model\Entity\SiteLocation[] patchEntities($entities, array $data, array $options = [])
     * @method \App\Model\Entity\SiteLocation findOrCreate($search, callable $callback = null, $options = [])
     */
    class SiteLocationsTable extends Table {

        /**
         * Initialize method
         *
         * @param array $config The configuration for the Table.
         * @return void
         */
        public function initialize(array $config) {
            parent::initialize($config);

            $this->setTable('site_locations');
            $this->setDisplayField('Site_Number');
            $this->setPrimaryKey('ID');

            $this->hasMany('BacteriaSamples', [
                'foreignKey' => 'site_location_id'
            ]);
            $this->hasMany('HydrolabSamples', [
                'foreignKey' => 'site_location_id'
            ]);
            $this->hasMany('NutrientSamples', [
                'foreignKey' => 'site_location_id'
            ]);
            $this->hasMany('PesticideSamples', [
                'foreignKey' => 'site_location_id'
            ]);
            $this->hasMany('WaterQualitySamples', [
                'foreignKey' => 'site_location_id'
            ]);
            $this->HasMany('Groupings', [
                'foreignKey' => 'site_ID'
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
                ->integer('Site_Number')
                ->requirePresence('Site_Number', 'create')
                ->notEmpty('Site_Number');

            $validator
                ->boolean('Monitored')
                ->requirePresence('Monitored', 'create')
                ->notEmpty('Monitored');
            
            $validator
                ->numeric('Longitude')
                ->requirePresence('Longitude', 'create')
                ->notEmpty('Longitude');

            $validator
                ->numeric('Latitude')
                ->requirePresence('Latitude', 'create')
                ->notEmpty('Latitude');

            $validator
                ->scalar('Site_Location')
                ->maxLength('Site_Location', 120)
                ->requirePresence('Site_Location', 'create')
                ->notEmpty('Site_Location');

            $validator
                ->scalar('Site_Name')
                ->maxLength('Site_Name', 120)
                ->requirePresence('Site_Name', 'create')
                ->notEmpty('Site_Name');

            return $validator;
        }

        public function buildRules(RulesChecker $rules) {
            $rules->add($rules->isUnique(['Site_Number']));

            return $rules;
        }
    }