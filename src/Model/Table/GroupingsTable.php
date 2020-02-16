<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Groupings Model
 * 
 * @property \App\Model\Table\SiteLocationsTable|\Cake\ORM\Association\BelongsTo $SiteLocations
 * @property \App\Model\Table\SiteGroupsTable|\Cake\ORM\Association\BelongsTo $SiteGroups
 * 
 * @method \App\Model\Entity\SiteGroups get($primaryKey, $options = [])
 * @method \App\Model\Entity\SiteGroups newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SiteGroups[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SiteGroups|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SiteGroups patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SiteGroups[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SiteGroups findOrCreate($search, callable $callback = null, $options = [])
 */
class GroupingsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('groupings');
        $this->setDisplayField('groupingKey');
        $this->setPrimaryKey('groupingKey');
        
        $this->belongsTo('SiteLocations', [
            'foreignKey' => 'site_ID'
        ]);
        $this->belongsTo('SiteGroups', [
            'foreignKey' => 'group_ID'
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
            ->scalar('groupingKey')
            ->maxLength('groupingKey', 100)
            ->allowEmpty('groupingKey', 'create');

        $validator
            ->scalar('group_ID')
            ->maxLength('group_ID', 100)
            ->notEmpty('group_ID');
			
		$validator
            ->scalar('site_ID')
            ->maxLength('site_ID', 100)
            ->notEmpty('site_ID');

        return $validator;
    }
}