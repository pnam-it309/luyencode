<?php $this->assign('title', 'Đăng nhập'); ?>

<style>
    .login-page {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    }
    .login-card {
        width: 100%;
        max-width: 400px;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-muted);
    }
    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        box-sizing: border-box;
    }
</style>

<div class="login-page">
    <div class="card login-card">
        <h2 style="text-align: center; margin-bottom: 2rem;">Đăng nhập</h2>
        <form id="loginForm">
            <div class="form-group">
                <label>Tên đăng nhập</label>
                <input type="text" name="username" class="form-control" required value="admin">
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" class="form-control" required value="admin123">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Vào hệ thống</button>
        </form>
        <div id="loginMessage" style="margin-top: 1rem; text-align: center; color: #ef4444;"></div>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);
        
        try {
            const response = await fetch('/api/users/login.json', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            if (result.success) {
                localStorage.setItem('user', JSON.stringify(result.user));
                window.location.href = '/student-management';
            } else {
                document.getElementById('loginMessage').innerText = result.message;
            }
        } catch (err) {
            document.getElementById('loginMessage').innerText = 'Lỗi kết nối máy chủ';
        }
    });
</script>
