<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MeasurementMeta Model
 *
 * @method \App\Model\Entity\MeasurementMeta get($primaryKey, $options = [])
 * @method \App\Model\Entity\MeasurementMeta newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MeasurementMeta[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MeasurementMeta|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MeasurementMeta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MeasurementMeta[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MeasurementMeta findOrCreate($search, callable $callback = null, $options = [])
 */
class MeasurementMetaTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('measurement_meta');
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