$(document).ready(function () {
    // Initialize 
    const table = $('#tasks-table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 5, 
        lengthMenu: [5, 10, 25, 50], 
        ajax: {
            url: 'ajax.php?action=get_tasks',
            type: 'GET',
            data: function (d) {
                // custom filters
                d.status = $('#filter-status').val();
                d.due_date = $('#filter-due-date').val();
            },
            error: function (xhr, error, thrown) {
                console.error('DataTables error:', xhr, error, thrown);
                alert('Error loading table data');
            }
        },
        columns: [
            { data: 0, name: 'id' },
            { data: 1, name: 'title' },
            { data: 2, name: 'status' },
            { data: 3, name: 'due_date' },
            {
                data: 4,
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search tasks...",
            lengthMenu: "Show _MENU_ tasks per page",
            zeroRecords: "No tasks found",
            info: "Showing _START_ to _END_ of _TOTAL_ tasks",
            infoEmpty: "No tasks available",
            infoFiltered: "(filtered from _MAX_ total tasks)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        initComplete: function () {
            // Custom search input 
            $('.dataTables_filter input').unbind().bind('keyup', function (e) {
                if (e.keyCode === 13) {
                    table.search(this.value).draw();
                }
            });
        }
    });

    // Filter buttons
    $('#btn-filter').click(() => {
        table.ajax.reload(null, true); 
    });
    $('#btn-reset').click(() => {
        $('#filter-status').val('');
        $('#filter-due-date').val('');
        table.search('').columns().search('').draw(); 
    });

   
    $('.modal').on('hidden.bs.modal', function () {
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').remove();
    });

 
    $('#createTaskForm').submit(function (e) {
        e.preventDefault();
        const form = $(this);

        $.post('process.php', form.serialize() + '&action=create', function (res) {
            if (res.success) {
                $('#createModal').modal('hide');
                table.ajax.reload();
                form[0].reset();
            } else {
             
                if (res.errors) {
                    displayFormErrors(form, res.errors);
                } else {
                    alert(res.message);
                }
            }
        }).fail(() => alert('Server error occurred'));
    });


    window.confirmDelete = function (id) {
        $('#deleteForm input[name="id"]').val(id);
        $('#deleteModal').modal('show');
    };

    $('#deleteForm').submit(function (e) {
        e.preventDefault();
        $.post('process.php', $(this).serialize() + '&action=delete', function (res) {
            if (res.success) {
                $('#deleteModal').modal('hide');
                table.ajax.reload();
            } else {
                alert(res.message);
            }
        }).fail(() => alert('Server error occurred'));
    });

  
    window.openEditModal = function (id) {
        $.get('ajax.php?action=get_task&id=' + id, function (task) {
            const form = $('#editTaskForm');
            form.find('input[name="id"]').val(task.id);
            form.find('input[name="title"]').val(task.title);
            form.find('textarea[name="description"]').val(task.description);
            form.find('select[name="status"]').val(task.status);
            form.find('input[name="due_date"]').val(task.due_date);
            $('#editModal').modal('show');
        }).fail(() => alert('Error loading task'));
    };

    $('#editTaskForm').submit(function (e) {
        e.preventDefault();
        const form = $(this);

        $.post('process.php', form.serialize() + '&action=update', function (res) {
            if (res.success) {
                $('#editModal').modal('hide');
                table.ajax.reload();
            } else {
                // Display validation errors
                if (res.errors) {
                    displayFormErrors(form, res.errors);
                } else {
                    alert(res.message);
                }
            }
        }).fail(() => alert('Server error occurred'));
    });

    //  function to display form errors
    function displayFormErrors(form, errors) {
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();

        Object.entries(errors).forEach(([field, message]) => {
            const input = form.find('[name="' + field + '"]');
            const formGroup = input.closest('.mb-3');

            input.addClass('is-invalid');
            formGroup.append('<div class="invalid-feedback">' + message + '</div>');
        });
    }
});