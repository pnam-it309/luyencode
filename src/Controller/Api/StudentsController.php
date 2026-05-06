<?php
declare(strict_types=1);

namespace App\Controller\Api;

class StudentsController extends ApiController
{
    public function index()
    {
        $students = $this->Students->find()->all();
        $this->set([
            'success' => true,
            'students' => $students,
            '_serialize' => ['success', 'students']
        ]);
    }

    public function view($id = null)
    {
        try {
            $student = $this->Students->get($id, contain: ['Classes']);
            $this->set([
                'success' => true,
                'student' => $student,
                '_serialize' => ['success', 'student']
            ]);
        } catch (\Exception $e) {
            $this->set([
                'success' => false,
                'message' => 'Không tìm thấy sinh viên',
                '_serialize' => ['success', 'message']
            ]);
            $this->response = $this->response->withStatus(404);
        }
    }

    public function add()
    {
        $student = $this->Students->newEmptyEntity();
        $message = '';
        $success = false;
        $errors = null;

        if ($this->request->is('post')) {
            $student = $this->Students->patchEntity($student, $this->request->getData());
            if ($this->Students->save($student)) {
                $message = 'Đã thêm sinh viên thành công';
                $success = true;
            } else {
                $message = 'Lỗi khi thêm sinh viên';
                $errors = $student->getErrors();
                $this->response = $this->response->withStatus(400);
            }
        }
        $this->set([
            'success' => $success,
            'message' => $message,
            'student' => $student,
            'errors' => $errors,
            '_serialize' => ['success', 'message', 'student', 'errors']
        ]);
    }

    public function edit($id = null)
    {
        $student = $this->Students->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $student = $this->Students->patchEntity($student, $this->request->getData());
            if ($this->Students->save($student)) {
                $message = 'Updated';
            } else {
                $message = 'Error';
                $this->response = $this->response->withStatus(400);
            }
        }
        $this->set([
            'message' => $message,
            'student' => $student,
            '_serialize' => ['message', 'student']
        ]);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);
        $student = $this->Students->get($id);
        $message = 'Deleted';
        if (!$this->Students->delete($student)) {
            $message = 'Error';
            $this->response = $this->response->withStatus(400);
        }
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }
}
