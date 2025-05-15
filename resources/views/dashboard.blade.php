<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Task Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* Navbar */
        nav.navbar {
            background-color: #026aa7;
        }
        nav.navbar .navbar-brand, nav.navbar .nav-link, nav.navbar button {
            color: #fff;
        }
        nav.navbar button:hover, nav.navbar .nav-link:hover {
            color: #ddd;
        }
        /* Task Cards */
        #task-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }
        .task-card {
            background: #fff;
            border: 1px solid #dfe1e6;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(9,30,66,.25);
            padding: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: box-shadow 0.2s ease;
        }
        .task-card:hover {
            box-shadow: 0 4px 8px rgba(9,30,66,.35);
        }
        .task-card h4 {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.25rem;
        }
        /* Badge status */
        .badge.pending {
            background-color: #f2a93b;
            color: #fff;
        }
        .badge.done {
            background-color: #4caf50;
            color: #fff;
        }
        .badge.in-progress {
            background-color: #2978b5;
            color: #fff;
        }
        .task-card p {
            margin: 0.25rem 0;
            flex-grow: 1;
            color: #4a4a4a;
        }
        .task-actions {
            margin-top: 1rem;
            text-align: right;
        }
        .task-actions button {
            margin-left: 0.5rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container d-flex align-items-center">
            <a class="navbar-brand" href="dashboard">Task Management</a>
            <a class="nav-link me-3" href="dashboard">Dashboard</a>
            
            <div id="navbar-menu" class="d-flex align-items-center gap-3">
            <!-- Admin Page muncul di sini -->
            </div>
            
            <button class="btn btn-outline-light ms-auto" onclick="logout()">Logout</button>
        </div>
    </nav>


    <div class="container py-4">
        <div id="user-info" class="mb-4"></div>

        <div id="tasks-container" class="mb-5">
            <h4>Tasks</h4>
            <div class="mb-3">
                <label for="status-filter" class="form-label">Filter by Status:</label>
                <select id="status-filter" class="form-select" style="max-width: 200px;">
                    <option value="all" selected>All</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="done">Done</option>
                </select>
            </div>
            <div id="task-list">Loading tasks...</div>
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

            // Show Admin Page if role is admin
            if (user.role === 'admin') {
                const navbarMenu = document.getElementById('navbar-menu');
                navbarMenu.innerHTML = `<a href="admin" class="nav-link text-white">Admin Page</a>`;
            }

            loadTasks(user.role, user.id);
        }

        let allTasks = [];
        async function loadTasks(role, userId) {
            const res = await fetch('/api/tasks', { headers });
            if (!res.ok) {
                alert('Failed to load tasks.');
                return;
            }
            allTasks = await res.json();
            renderTasks(role, userId, document.getElementById('status-filter').value);
        }

        function renderTasks(role, userId, statusFilter) {
            const container = document.getElementById('task-list');
            container.innerHTML = '';

            let filteredTasks = allTasks.filter(task => {
                if (statusFilter === 'all') return true;
                return task.status === statusFilter;
            });

            if (filteredTasks.length === 0) {
                container.innerHTML = '<p>No tasks found.</p>';
                return;
            }

            filteredTasks.forEach(task => {
                let showTask = false;
                if (role === 'admin') showTask = true;
                else if (role === 'manager') showTask = (task.created_by === userId || task.assigned_to === userId);
                else if (role === 'staff') showTask = (task.assigned_to === userId);
                if (!showTask) return;

                let badgeClass = '';
                let statusLabel = '';

                if (task.status === 'pending') {
                    badgeClass = 'pending';
                    statusLabel = 'Pending';
                } else if (task.status === 'done') {
                    badgeClass = 'done';
                    statusLabel = 'Done';
                } else if (task.status === 'in_progress') {
                    badgeClass = 'in-progress';
                    statusLabel = 'In Progress';
                }

                let actionsHTML = '';
                if (role === 'admin' || (role === 'manager' && task.created_by === userId)) {
                    actionsHTML = `
                        <div class="task-actions">
                            <button class="btn btn-sm btn-primary me-2">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteTask('${task.id}')">Delete</button>
                        </div>
                    `;
                }

                const taskDiv = document.createElement('div');
                taskDiv.className = 'task-card';
                taskDiv.innerHTML = `
                    <h4>${task.title} <span class="badge ${badgeClass}">${statusLabel}</span></h4>
                    <p>${task.description || '-'}</p>
                    <p><small>Due: ${task.due_date}</small></p>
                    ${actionsHTML}
                `;
                container.appendChild(taskDiv);
            });
        }


        async function deleteTask(taskId) {
        console.log('Deleting task with id:', taskId);
            if (!confirm('Are you sure want to delete this task?')) return;

            try {
                const res = await fetch(`/api/tasks/${taskId}`, {
                    method: 'DELETE',
                    headers
                });

                const data = await res.json();  // parse response biar jelas
                console.log('Response data:', data);

                if (!res.ok) {
                    alert(data.message || 'Failed to delete task.');
                    return;
                }
                alert('Task deleted.');
                getUserInfo();
            } catch (error) {
                console.error('Network error:', error);
                alert('Network error.');
            }
        }



        // Filter status change event
        document.getElementById('status-filter').addEventListener('change', () => {
            getUserInfo();
        });

        function logout() {
            localStorage.removeItem("token");
            window.location.href = "/login";
        }

        // Start
        getUserInfo();
    </script>

</body>


</html>
