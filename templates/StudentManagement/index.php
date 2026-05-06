<?php $this->assign('title', 'Danh sách lớp học'); ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Danh sách lớp học</h1>
    <button onclick="showAddClassModal()" class="btn btn-primary">+ Thêm lớp mới</button>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Mã lớp</th>
                <th>Tên lớp</th>
                <th>Mô tả</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="classList">
            <!-- Data will be loaded here -->
        </tbody>
    </table>
</div>

<!-- Modal Thêm/Sửa Lớp -->
<div id="classModal" class="modal">
    <div class="card" style="width: 400px;">
        <h3 id="modalTitle">Thêm lớp học</h3>
        <form id="classForm">
            <input type="hidden" name="id" id="classId">
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem;">Tên lớp</label>
                <input type="text" name="class_name" id="className" class="form-control" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem;">Mã lớp</label>
                <input type="text" name="class_code" id="classCode" class="form-control" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem;">Mô tả</label>
                <textarea name="description" id="classDescription" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;"></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="closeModal()" class="btn" style="background: #e2e8f0;">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu lại</button>
            </div>
        </form>
    </div>
</div>

<script>
    async function loadClasses() {
        const response = await fetch('/api/classes.json');
        const data = await response.json();
        const list = document.getElementById('classList');
        list.innerHTML = '';
        data.classes.forEach(cls => {
            list.innerHTML += `
                <tr>
                    <td><strong>${cls.class_code}</strong></td>
                    <td>${cls.class_name}</td>
                    <td>${cls.description || ''}</td>
                    <td>
                        <button onclick="window.location.href='/student-management/class-details/${cls.id}'" class="btn" style="background: #dcfce7; color: #166534;">Xem sinh viên</button>
                        <button onclick="editClass(${cls.id}, '${cls.class_name}', '${cls.class_code}', '${cls.description || ''}')" class="btn" style="background: #fef9c3; color: #854d0e;">Sửa</button>
                        <button onclick="deleteClass(${cls.id})" class="btn" style="background: #fee2e2; color: #991b1b;">Xóa</button>
                    </td>
                </tr>
            `;
        });
    }

    function showAddClassModal() {
        document.getElementById('modalTitle').innerText = 'Thêm lớp học';
        document.getElementById('classForm').reset();
        document.getElementById('classId').value = '';
        document.getElementById('classModal').classList.add('active');
    }

    function editClass(id, name, code, desc) {
        document.getElementById('modalTitle').innerText = 'Sửa lớp học';
        document.getElementById('classId').value = id;
        document.getElementById('className').value = name;
        document.getElementById('classCode').value = code;
        document.getElementById('classDescription').value = desc;
        document.getElementById('classModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('classModal').classList.remove('active');
    }

    document.getElementById('classForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('classId').value;
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);
        
        const url = id ? `/api/classes/${id}.json` : '/api/classes.json';
        const method = id ? 'PATCH' : 'POST';
        
        await fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        closeModal();
        loadClasses();
    });

    async function deleteClass(id) {
        if (confirm('Bạn có chắc muốn xóa lớp này?')) {
            await fetch(`/api/classes/${id}.json`, { method: 'DELETE' });
            loadClasses();
        }
    }

    loadClasses();
</script>
