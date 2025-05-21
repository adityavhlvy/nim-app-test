<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>CRUD NIM App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <h1>Data Mahasiswa</h1>

    <table border="1" id="studentTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Program Studi</th>
                <th>Angkatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <h2>Tambah Mahasiswa</h2>
    <form id="studentForm">
        @csrf
        <input type="text" id="name" placeholder="Nama" required />
        <input type="text" id="program_studi" placeholder="Kode Program Studi (2 digit)" maxlength="2" required />
        <input type="number" id="angkatan" placeholder="Angkatan (4 digit)" required />
        <button type="submit">Tambah</button>
    </form>

    <script>
        const apiBase = '{{ url('/api/students') }}';

        async function fetchStudents() {
            const res = await fetch(apiBase);
            const students = await res.json();
            const tbody = document.querySelector('#studentTable tbody');
            tbody.innerHTML = '';

            students.forEach(s => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${s.id}</td>
                    <td>${s.nim}</td>
                    <td>${s.name}</td>
                    <td>${s.program_studi}</td>
                    <td>${s.angkatan}</td>
                    <td>
                        <button onclick="deleteStudent(${s.id})">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function addStudent(e) {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const program_studi = document.getElementById('program_studi').value;
            const angkatan = document.getElementById('angkatan').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const res = await fetch(apiBase, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ name, program_studi, angkatan })
            });

            if (res.ok) {
                document.getElementById('studentForm').reset();
                fetchStudents();
            } else {
                alert('Gagal menambahkan mahasiswa');
            }
        }

        async function deleteStudent(id) {
            if (!confirm('Yakin ingin hapus?')) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const res = await fetch(`${apiBase}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (res.ok) {
                fetchStudents();
            } else {
                alert('Gagal hapus mahasiswa');
            }
        }

        document.getElementById('studentForm').addEventListener('submit', addStudent);

        fetchStudents();
    </script>

</body>

</html>