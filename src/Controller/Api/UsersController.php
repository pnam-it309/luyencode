<?php
declare(strict_types=1);

namespace App\Controller\Api;

class UsersController extends ApiController
{
    public function login()
    {
        $this->request->allowMethod(['post']);
        $username = $this->request->getData('username');
        $password = $this->request->getData('password');
        
        $user = null;
        $success = false;

        // 1. Kiểm tra tài khoản cứng
        if ($username === 'admin' && $password === 'admin123') {
            $user = [
                'id' => 1,
                'username' => 'admin',
                'full_name' => 'Administrator',
                'role' => 'admin'
            ];
            $success = true;
        } else {
            try {
                // 2. Nếu không phải tài khoản cứng, kiểm tra DB
                $dbUser = $this->Users->find()
                    ->where(['username' => $username])
                    ->first();
                
                if ($dbUser && password_verify($password, $dbUser->password)) {
                    $user = $dbUser;
                    $success = true;
                }
            } catch (\Exception $e) {
                // DB error fallback
            }
        }

        if ($success) {
            return $this->jsonResponse([
                'success' => true,
                'user' => $user
            ]);
        } else {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Sai tài khoản hoặc mật khẩu'
            ], 401);
        }
    }

    /**
     * Helper to return raw JSON response
     */
    protected function jsonResponse($data, $status = 200)
    {
        return $this->response
            ->withStatus($status)
            ->withType('application/json')
            ->withStringBody(json_encode($data));
    }
}
