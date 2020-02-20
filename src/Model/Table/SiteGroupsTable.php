<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SiteGroups Model
 *
 * 
 * @method \App\Model\Entity\SiteGroups get($primaryKey, $options = [])
 * @method \App\Model\Entity\SiteGroups newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SiteGroups[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SiteGroups|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SiteGroups patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SiteGroups[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SiteGroups findOrCreate($search, callable $callback = null, $options = [])
 */
class SiteGroupsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('site_groups');
        $this->setDisplayField('groupKey');
        $this->setPrimaryKey('groupKey');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->scalar('groupKey')
            ->maxLength('groupKey', 11)
            ->allowEmpty('groupKey', 'create');

        $validator
            ->scalar('groupName')
            ->maxLength('groupName', 160)
            ->requirePresence('groupName', 'create')
            ->notEmpty('groupName');
			
		$validator
            ->scalar('groupDescription')
            ->maxLength('groupDescription', 460)
            ->allowEmpty('groupDescription');

        return $validator;
    }
}