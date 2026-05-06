<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StudentClass Entity
 *
 * @property int $id
 * @property string $class_name
 * @property string $class_code
 * @property string|null $description
 * @property int|null $teacher_id
 * @property \Cake\I18n\DateTime|null $created_at
 *
 * @property \App\Model\Entity\User $teacher
 * @property \App\Model\Entity\Student[] $students
 */
class StudentClass extends Entity
{
    protected array $_accessible = [
        'class_name' => true,
        'class_code' => true,
        'description' => true,
        'teacher_id' => true,
        'created_at' => true,
        'teacher' => true,
        'students' => true,
    ];
}
