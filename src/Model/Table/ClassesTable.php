<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Classes Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Teachers
 * @property \App\Model\Table\StudentsTable&\Cake\ORM\Association\HasMany $Students
 *
 * @method \App\Model\Entity\Class newEmptyEntity()
 * @method \App\Model\Entity\Class newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Class> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Class get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Class findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Class patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Class> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Class|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Class saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Class>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Class>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Class>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Class> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Class>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Class>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Class>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Class> deleteManyOrFail(iterable $entities, array $options = [])
 */
class ClassesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('classes');
        $this->setDisplayField('class_name');
        $this->setPrimaryKey('id');
        $this->belongsTo('Teachers', [
            'foreignKey' => 'teacher_id',
            'className' => 'Users',
        ]);
        $this->hasMany('Students', [
            'foreignKey' => 'class_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('class_name')
            ->maxLength('class_name', 100)
            ->requirePresence('class_name', 'create')
            ->notEmptyString('class_name');

        $validator
            ->scalar('class_code')
            ->maxLength('class_code', 20)
            ->requirePresence('class_code', 'create')
            ->notEmptyString('class_code')
            ->add('class_code', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->integer('teacher_id')
            ->allowEmptyString('teacher_id');

        $validator
            ->dateTime('created_at')
            ->allowEmptyDateTime('created_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['class_code']), ['errorField' => 'class_code']);
        $rules->add($rules->existsIn(['teacher_id'], 'Teachers'), ['errorField' => 'teacher_id']);

        return $rules;
    }
}
