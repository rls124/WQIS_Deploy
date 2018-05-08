<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DetectionLimits Model
 *
 * @method \App\Model\Entity\DetectionLimit get($primaryKey, $options = [])
 * @method \App\Model\Entity\DetectionLimit newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DetectionLimit[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DetectionLimit|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DetectionLimit patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DetectionLimit[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DetectionLimit findOrCreate($search, callable $callback = null, $options = [])
 */
class DetectionLimitsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('detection_limits');
        $this->setDisplayField('Measure');
        $this->setPrimaryKey('Measure');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->scalar('Measure')
            ->maxLength('Measure', 100)
            ->allowEmpty('Measure', 'create');

        $validator
            ->numeric('Lowest_Acceptable_Value')
            ->requirePresence('Lowest_Acceptable_Value', 'create')
            ->notEmpty('Lowest_Acceptable_Value');

        $validator
            ->numeric('Highest_Acceptable_Value')
            ->requirePresence('Highest_Acceptable_Value', 'create')
            ->notEmpty('Highest_Acceptable_Value');

        return $validator;
    }
}
