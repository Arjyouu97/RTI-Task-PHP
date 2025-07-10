<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>RTI Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container py-4">
        <h2>RTI Task Manager</h2>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Add New Task</button>

        <div class="row mb-3">
            <div class="col-md-3">
                <select id="filter-status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" id="filter-due-date" class="form-control">
            </div>
            <div class="col-md-2">
                <button id="btn-filter" class="btn btn-secondary">Filter</button>
                <button id="btn-reset" class="btn btn-outline-secondary">Reset</button>
            </div>
        </div>

        <table class="table table-bordered" id="tasks-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <?php include 'modals/create.php'; ?>
    <?php include 'modals/edit.php'; ?>
    <?php include 'modals/delete.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>