<?php
require_once 'includes/config.php';

$response = ['success' => false, 'errors' => [], 'message' => ''];

try {
    $action = $_POST['action'] ?? '';

    // Common validation function
    function validateTaskData($data)
    {
        $errors = [];

        // Title validation
        if (empty(trim($data['title'] ?? ''))) {
            $errors['title'] = 'Title is required';
        } elseif (strlen(trim($data['title'])) > 255) {
            $errors['title'] = 'Title must be less than 255 characters';
        }

        // Description validation (optional)
        if (isset($data['description']) && strlen(trim($data['description'])) > 1000) {
            $errors['description'] = 'Description must be less than 1000 characters';
        }

        // Status validation
        $allowedStatuses = ['pending', 'in_progress', 'completed'];
        if (empty($data['status'] ?? '') || !in_array($data['status'], $allowedStatuses)) {
            $errors['status'] = 'Invalid status selected';
        }

        // Due date validation (optional)
        if (!empty($data['due_date'] ?? '')) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['due_date'])) {
                $errors['due_date'] = 'Invalid date format (YYYY-MM-DD)';
            } else {
                $inputDate = new DateTime($data['due_date']);
                $currentDate = new DateTime();
                if ($inputDate < $currentDate) {
                    $errors['due_date'] = 'Due date cannot be in the past';
                }
            }
        }

        return $errors;
    }

    switch ($action) {
        case 'create':
            $errors = validateTaskData($_POST);

            if (!empty($errors)) {
                $response['errors'] = $errors;
                throw new Exception('Validation failed');
            }

            $stmt = $pdo->prepare("INSERT INTO tasks (title, description, status, due_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                trim($_POST['title']),
                trim($_POST['description'] ?? null),
                $_POST['status'],
                !empty($_POST['due_date']) ? $_POST['due_date'] : null
            ]);

            $response['success'] = true;
            $response['message'] = 'Task created successfully';
            break;

        case 'update':
            if (empty($_POST['id'] ?? '')) {
                throw new Exception('Task ID is required');
            }

            $errors = validateTaskData($_POST);

            if (!empty($errors)) {
                $response['errors'] = $errors;
                throw new Exception('Validation failed');
            }

            $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ?, due_date = ? WHERE id = ?");
            $stmt->execute([
                trim($_POST['title']),
                trim($_POST['description'] ?? null),
                $_POST['status'],
                !empty($_POST['due_date']) ? $_POST['due_date'] : null,
                (int)$_POST['id']
            ]);

            $response['success'] = true;
            $response['message'] = 'Task updated successfully';
            break;

        case 'delete':
            if (empty($_POST['id'] ?? '')) {
                throw new Exception('Task ID is required');
            }

            $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
            $stmt->execute([(int)$_POST['id']]);

            $response['success'] = true;
            $response['message'] = 'Task deleted successfully';
            break;

        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    if (!isset($response['errors'])) {
        $response['errors'] = [];
    }
}

echo json_encode($response);
