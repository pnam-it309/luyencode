<?php $this->assign('title', 'Danh sách sinh viên'); ?>

<div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 2rem;">
    <a href="/student-management" class="btn" style="background: #e2e8f0;">< Quay lại</a>
    <h1 id="classTitle">Danh sách sinh viên</h1>
    <button onclick="showAddStudentModal()" class="btn btn-primary" style="margin-left: auto;">+ Thêm sinh viên</button>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>MSSV</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="studentList">
        </tbody>
    </table>
</div>

<!-- Modal Thêm/Sửa Sinh Viên -->
<div id="studentModal" class="modal">
    <div class="card" style="width: 400px;">
        <h3 id="modalTitle">Thêm sinh viên</h3>
        <form id="studentForm">
            <input type="hidden" name="id" id="studentId">
            <input type="hidden" name="class_id" value="<?= $classId ?>">
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem;">Mã sinh viên</label>
                <input type="text" name="student_code" id="studentCode" class="form-control" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem;">Họ tên</label>
                <input type="text" name="full_name" id="studentName" class="form-control" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem;">Email</label>
                <input type="email" name="email" id="studentEmail" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem;">Số điện thoại</label>
                <input type="text" name="phone" id="studentPhone" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="closeModal()" class="btn" style="background: #e2e8f0;">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu lại</button>
            </div>
        </form>
    </div>
</div>

<script>
    const classId = <?= $classId ?>;
    
    async function loadClassInfo() {
        const response = await fetch(`/api/classes/${classId}.json`);
        const data = await response.json();
        document.getElementById('classTitle').innerText = `Danh sách sinh viên - ${data.class.class_name}`;
    }

    async function loadStudents() {
        const response = await fetch(`/api/classes/${classId}/students.json`);
        const data = await response.json();
        const list = document.getElementById('studentList');
        list.innerHTML = '';
        data.students.forEach(s => {
            list.innerHTML += `
                <tr>
                    <td><strong>${s.student_code}</strong></td>
                    <td>${s.full_name}</td>
                    <td>${s.email || ''}</td>
                    <td>${s.phone || ''}</td>
                    <td>
                        <button onclick="editStudent(${s.id}, '${s.student_code}', '${s.full_name}', '${s.email || ''}', '${s.phone || ''}')" class="btn" style="background: #fef9c3; color: #854d0e;">Sửa</button>
                        <button onclick="deleteStudent(${s.id})" class="btn" style="background: #fee2e2; color: #991b1b;">Xóa</button>
                    </td>
                </tr>
            `;
        });
    }

    function showAddStudentModal() {
        document.getElementById('modalTitle').innerText = 'Thêm sinh viên';
        document.getElementById('studentForm').reset();
        document.getElementById('studentId').value = '';
        document.getElementById('studentModal').classList.add('active');
    }

    function editStudent(id, code, name, email, phone) {
        document.getElementById('modalTitle').innerText = 'Sửa sinh viên';
        document.getElementById('studentId').value = id;
        document.getElementById('studentCode').value = code;
        document.getElementById('studentName').value = name;
        document.getElementById('studentEmail').value = email;
        document.getElementById('studentPhone').value = phone;
        document.getElementById('studentModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('studentModal').classList.remove('active');
    }

    document.getElementById('studentForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('studentId').value;
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);
        
        const url = id ? `/api/students/${id}.json` : '/api/students.json';
        const method = id ? 'PATCH' : 'POST';
        
        await fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        closeModal();
        loadStudents();
    });

    async function deleteStudent(id) {
        if (confirm('Bạn có chắc muốn xóa sinh viên này?')) {
            await fetch(`/api/students/${id}.json`, { method: 'DELETE' });
            loadStudents();
        }
    }

    loadClassInfo();
    loadStudents();
</script>
