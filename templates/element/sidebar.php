<div class="sidebar">
    <h3 style="margin-bottom: 2rem;">EduManager</h3>
    <div style="margin-bottom: 1rem;">
        <a href="/student-management" style="color: white; text-decoration: none; font-weight: 500;">
            <i class="fas fa-chalkboard"></i> Quản lý lớp học
        </a>
    </div>
    <div style="margin-top: auto; padding-top: 2rem; border-top: 1px solid #334155;">
        <div id="userInfo" style="font-size: 0.875rem; color: #94a3b8; margin-bottom: 1rem;"></div>
        <button onclick="logout()" class="btn" style="background: none; color: #ef4444; padding: 0;">
            <i class="fas fa-sign-out-alt"></i> Đăng xuất
        </button>
    </div>
</div>

<script>
    const userStr = localStorage.getItem('user');
    if (!userStr && window.location.pathname !== '/student-management/login') {
        window.location.href = '/student-management/login';
    } else if (userStr) {
        const user = JSON.parse(userStr);
        const userInfo = document.getElementById('userInfo');
        if (userInfo) userInfo.innerText = `Xin chào, ${user.full_name}`;
    }

    function logout() {
        localStorage.removeItem('user');
        window.location.href = '/student-management/login';
    }
</script>
