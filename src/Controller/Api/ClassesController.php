<?php
declare(strict_types=1);

namespace App\Controller\Api;

class ClassesController extends ApiController
{
    public function index()
    {
        $classes = $this->Classes->find()->all();
        $this->set([
            'success' => true,
            'classes' => $classes,
            '_serialize' => ['success', 'classes']
        ]);
    }

    public function view($id = null)
    {
        try {
            $class = $this->Classes->get($id, contain: ['Teachers', 'Students']);
            $this->set([
                'success' => true,
                'class' => $class,
                '_serialize' => ['success', 'class']
            ]);
        } catch (\Exception $e) {
            $this->set([
                'success' => false,
                'message' => 'Không tìm thấy lớp học',
                '_serialize' => ['success', 'message']
            ]);
            $this->response = $this->response->withStatus(404);
        }
    }

    public function add()
    {
        $class = $this->Classes->newEmptyEntity();
        $message = '';
        $success = false;
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if (isset($data['id']) && empty($data['id'])) {
                unset($data['id']);
            }
            $class = $this->Classes->patchEntity($class, $data);
            if ($this->Classes->save($class)) {
                $message = 'Đã lưu thành công';
                $success = true;
            } else {
                $message = 'Lỗi khi lưu dữ liệu';
                $errors = $class->getErrors();
                $this->set('errors', $errors);
                $this->response = $this->response->withStatus(400);
            }
        }
        
        $this->set([
            'success' => $success,
            'message' => $message,
            'class' => $class,
            '_serialize' => ['success', 'message', 'class']
        ]);
    }

    public function edit($id = null)
    {
        $class = $this->Classes->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $class = $this->Classes->patchEntity($class, $this->request->getData());
            if ($this->Classes->save($class)) {
                $message = 'Updated';
            } else {
                $message = 'Error';
                $this->response = $this->response->withStatus(400);
            }
        }
        $this->set([
            'message' => $message,
            'class' => $class,
            '_serialize' => ['message', 'class']
        ]);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);
        $class = $this->Classes->get($id);
        $message = 'Deleted';
        if (!$this->Classes->delete($class)) {
            $message = 'Error';
            $this->response = $this->response->withStatus(400);
        }
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }

    public function students($id = null)
    {
        $studentsTable = $this->fetchTable('Students');
        $students = $studentsTable->find()
            ->where(['class_id' => $id])
            ->all();
        $this->set([
            'success' => true,
            'students' => $students,
            '_serialize' => ['success', 'students']
        ]);
    }
}
