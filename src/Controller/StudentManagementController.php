<?php
declare(strict_types=1);

namespace App\Controller;

class StudentManagementController extends AppController
{
    public function beforeRender(\Cake\Event\EventInterface $event)
    {
        parent::beforeRender($event);
        if ($this->viewBuilder()->getLayout() === 'default') {
            $this->viewBuilder()->setLayout('management');
        }
    }

    public function index()
    {
        // Main dashboard / Class list
    }

    public function classDetails($id)
    {
        // Students in a class
        $this->set('classId', $id);
    }

    public function login()
    {
        // Login page
        $this->viewBuilder()->setLayout('login');
    }
}
