<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MeasurementSettings Model
 *
 * @method \App\Model\Entity\MeasurementSettings get($primaryKey, $options = [])
 * @method \App\Model\Entity\MeasurementSettings newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MeasurementSettings[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MeasurementSettings|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MeasurementSettings patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MeasurementSettings[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MeasurementSettings findOrCreate($search, callable $callback = null, $options = [])
 */
class MeasurementSettingsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('measurement_settings');
        $this->setDisplayField('measureKey');
        $this->setPrimaryKey('measureKey');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->scalar('measureKey')
            ->maxLength('measureKey', 100)
            ->allowEmpty('measureKey', 'create');

        $validator
            ->numeric('benchmarkMinimum')
            ->allowEmpty('benchmarkMinimum');
			
		$validator
            ->numeric('benchmarkMaximum')
            ->allowEmpty('benchmarkMaximum');

		$validator
            ->numeric('detectionMinimum')
            ->allowEmpty('detectionMinimum');
			
		$validator
            ->numeric('detectionMaximum')
            ->allowEmpty('detectionMaximum');

        return $validator;
    }
}