<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Benchmarks Model
 *
 * @method \App\Model\Entity\Benchmark get($primaryKey, $options = [])
 * @method \App\Model\Entity\Benchmark newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Benchmark[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Benchmark|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Benchmark patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Benchmark[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Benchmark findOrCreate($search, callable $callback = null, $options = [])
 */
class BenchmarksTable extends Table
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

        $this->setTable('benchmarks');
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
            ->numeric('Minimum_Acceptable_Value')
            ->allowEmpty('Minimum_Acceptable_Value');

        $validator
            ->numeric('Maximum_Acceptable_Value')
            ->requirePresence('Maximum_Acceptable_Value', 'create')
            ->notEmpty('Maximum_Acceptable_Value');

        return $validator;
    }
}
