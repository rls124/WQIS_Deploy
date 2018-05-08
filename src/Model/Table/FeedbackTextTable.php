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
class FeedbackTable extends Table
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

        $this->setTable('feedback');
        $this->setDisplayField('FeedbackText');
        $this->setPrimaryKey('ID');
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
            ->scalar('FeedbackText')
            ->maxLength('FeedbackText', 4096)
            ->notEmpty('FeedbackText');

        $validator
            ->dateTime('Date')
            ->notEmpty('Date');

        $validator
            ->numeric('ID')
            ->requirePresence('ID', 'create')
            ->notEmpty('ID');

        return $validator;
    }
}
