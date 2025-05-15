<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin - User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        nav.navbar {
            background-color: #026aa7;
        }
        nav.navbar .navbar-brand, nav.navbar .nav-link, nav.navbar button {
            color: #fff;
        }
        nav.navbar button:hover, nav.navbar .nav-link:hover {
            color: #ddd;
        }
        #admin-section {
            background: #f4f5f7;
            border: 1px solid #dfe1e6;
            padding: 1rem 1.5rem;
            border-radius: 6px;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container d-flex align-items-center">
            <!-- Bagian kiri: Task Dashboard + Admin Page -->
            <div class="d-flex align-items-center gap-3">
                <a class="navbar-brand" href="dashboard">Task Dashboard</a>
                <a class="nav-link me-3" href="dashboard">Dashboard</a>
                <a href="admin" class="nav-link text-white mb-0">Admin Page</a>
            </div>

            <!-- Spacer agar tombol logout di ujung kanan -->
            <div class="ms-auto">
                <button class="btn btn-outline-light" onclick="logout()">Logout</button>
            </div>
        </div>
    </nav>


    <div class="container py-4">
        <div id="user-info" class="mb-4"></div>

        <div id="admin-section" style="display:none;">
            <h4>Admin Section - Create User</h4>
            <form id="user-form" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" id="user-name" class="form-control" placeholder="Name" required />
                    </div>
                    <div class="col-md-6">
                        <input type="email" id="user-email" class="form-control" placeholder="Email" required />
                    </div>
                    <div class="col-md-6">
                        <input
                            type="password"
                            id="user-password"
                            class="form-control"
                            placeholder="Password (min 6 chars)"
                            minlength="6"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <select id="user-role" class="form-select" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Create User</button>
            </form>

            <h4>User List</h4>
            <table class="table table-striped" id="user-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Active</th>
                    </tr>
                </thead>
                <tbody id="user-list">
                    <tr><td colspan="4">Loading users...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('token');
        if (!token) {
            alert("You must login first!");
            window.location.href = "/login";
        }
        const headers = {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        };

        async function getUserInfo() {
            const res = await fetch('/api/users_login', { headers });
            if (!res.ok) {
                logout();
                return;
            }
            const user = await res.json();
            document.getElementById('user-info').innerHTML = `<p>Welcome, <strong>${user.name}</strong> (${user.role})</p>`;

            if (user.role !== 'admin') {
                alert("Access denied. You are not an admin.");
                window.location.href = 'index.html';
                return;
            }
            document.getElementById('admin-section').style.display = 'block';
            loadUsers();
        }

        async function loadUsers() {
            const res = await fetch('/api/users', { headers });
            if (!res.ok) {
                alert('Failed to load users.');
                return;
            }
            const users = await res.json();
            const tbody = document.getElementById('user-list');
            tbody.innerHTML = '';
            users.forEach(u => {
                tbody.innerHTML += `
                    <tr>
                        <td>${u.name}</td>
                        <td>${u.email}</td>
                        <td>${u.role}</td>
                        <td>${u.status ? 'Yes' : 'No'}</td>
                    </tr>
                `;
            });
        }

        document.getElementById('user-form').addEventListener('submit', async e => {
            e.preventDefault();
            const name = document.getElementById('user-name').value.trim();
            const email = document.getElementById('user-email').value.trim();
            const password = document.getElementById('user-password').value;
            const role = document.getElementById('user-role').value;

            if (!name || !email || !password || !role) {
                alert('Please fill all fields');
                return;
            }

            const res = await fetch('/api/users', {
                method: 'POST',
                headers,
                body: JSON.stringify({ name, email, password, role })
            });
            if (res.ok) {
                alert('User created successfully');
                e.target.reset();
                loadUsers();
            } else {
                const err = await res.json();
                alert('Failed to create user: ' + (err.message || 'Unknown error'));
            }
        });

        function logout() {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }

        getUserInfo();
    </script>
</body>
</html>
