<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('userid');
        $this->setPrimaryKey('userid');
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'Created' => 'new'
                ]
            ]
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
            ->integer('userid')
            ->allowEmpty('userid', 'create');

        $validator
            ->boolean('admin')
            ->notEmpty('admin');

        $validator
            ->scalar('username')
            ->maxLength('username', 60)
            ->requirePresence('username', 'create')
            ->notEmpty('username');

        $validator
            ->scalar('userpw')
            ->maxLength('userpw', 60)
            ->requirePresence('userpw', 'create')
            ->notEmpty('userpw');

        $validator
            ->scalar('firstname')
            ->maxLength('firstname', 40)
            ->allowEmpty('firstname');

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 40)
            ->allowEmpty('lastname');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->scalar('organization')
            ->maxLength('organization', 60)
            ->requirePresence('organization', 'create')
            ->notEmpty('organization');

        $validator
            ->scalar('position')
            ->maxLength('position', 60)
            ->requirePresence('position', 'create')
            ->notEmpty('position');

        $validator
            ->date('Created')
            ->allowEmpty('Created');

        $validator
            ->scalar('securityquestion1')
            ->maxLength('securityquestion1', 60)
            ->allowEmpty('securityquestion1');

        $validator
            ->scalar('securityanswer1')
            ->maxLength('securityanswer1', 120)
            ->allowEmpty('securityanswer1');

        $validator
            ->scalar('securityquestion2')
            ->maxLength('securityquestion2', 60)
            ->allowEmpty('securityquestion2');

        $validator
            ->scalar('securityanswer2')
            ->maxLength('securityanswer2', 120)
            ->allowEmpty('securityanswer2');

        $validator
            ->scalar('securityquestion3')
            ->maxLength('securityquestion3', 60)
            ->allowEmpty('securityquestion3');

        $validator
            ->scalar('securityanswer3')
            ->maxLength('securityanswer3', 120)
            ->allowEmpty('securityanswer3');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}