<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>NIM App</title>
</head>
<body>
<h2>Input Mahasiswa</h2>
<form id="studentForm">
  <input type="text" id="name" placeholder="Nama" required />
  <select id="program_studi" required>
    <option value="">Pilih Program Studi</option>
    <option value="Teknik Informatika">Teknik Informatika</option>
    <option value="Sistem Informasi">Sistem Informasi</option>
    <option value="Teknik Elektro">Teknik Elektro</option>
  </select>
  <input type="number" id="angkatan" placeholder="Angkatan (YYYY)" required />
  <button type="submit">Submit</button>
</form>

<h2>Data Mahasiswa</h2>
<table border="1" cellpadding="5" cellspacing="0" id="studentTable">
  <thead>
    <tr>
      <th>NIM</th>
      <th>Nama</th>
      <th>Program Studi</th>
      <th>Angkatan</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

<script>
const apiURL = '/api/students';

function loadStudents() {
  fetch(apiURL)
    .then(res => res.json())
    .then(data => {
      const tbody = document.querySelector('#studentTable tbody');
      tbody.innerHTML = '';
      data.forEach(student => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${student.nim}</td>
          <td>${student.name}</td>
          <td>${student.program_studi}</td>
          <td>${student.angkatan}</td>
          <td>
            <button onclick="deleteStudent(${student.id})">Delete</button>
          </td>`;
        tbody.appendChild(tr);
      });
    });
}

function deleteStudent(id) {
  fetch(`${apiURL}/${id}`, { method: 'DELETE' })
    .then(res => res.json())
    .then(() => loadStudents());
}

document.getElementById('studentForm').addEventListener('submit', e => {
  e.preventDefault();
  const name = document.getElementById('name').value;
  const program_studi = document.getElementById('program_studi').value;
  const angkatan = document.getElementById('angkatan').value;

  fetch(apiURL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ name, program_studi, angkatan }),
  })
  .then(res => {
    if(!res.ok) throw new Error('Failed to create');
    return res.json();
  })
  .then(() => {
    loadStudents();
    e.target.reset();
  })
  .catch(alert);
});

loadStudents();
</script>
</body>
</html>
<style>
  body {
    font-family: Arial, sans-serif;
    margin: 20px;
  }
  h2 {
    color: #333;
  }
  form {
    margin-bottom: 20px;
  }
  input, select, button {
    margin-right: 10px;
  }