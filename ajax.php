<?php
require_once 'includes/config.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_tasks':
        //  parameters
        $start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
        $length = isset($_GET['length']) ? (int)$_GET['length'] : 5;
        $searchValue = $_GET['search']['value'] ?? '';
        $orderColumn = $_GET['order'][0]['column'] ?? 0;
        $orderDirection = $_GET['order'][0]['dir'] ?? 'asc';
        
        // Column mapping
        $columns = [
            0 => 'id',
            1 => 'title',
            2 => 'status',
            3 => 'due_date'
        ];
        $orderBy = $columns[$orderColumn] ?? 'id';
        
        // Base 
        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM tasks WHERE 1=1";
        $countQuery = "SELECT COUNT(*) FROM tasks WHERE 1=1";
        $params = [];
        $searchParams = [];
        
        //  filters
        if (!empty($_GET['status'] ?? '')) {
            $query .= " AND status = ?";
            $countQuery .= " AND status = ?";
            $params[] = $_GET['status'];
            $searchParams[] = $_GET['status'];
        }
        
        if (!empty($_GET['due_date'] ?? '')) {
            $query .= " AND DATE(due_date) = ?";
            $countQuery .= " AND DATE(due_date) = ?";
            $params[] = $_GET['due_date'];
            $searchParams[] = $_GET['due_date'];
        }
        
        //  search
        if (!empty($searchValue)) {
            $query .= " AND (title LIKE ? OR description LIKE ? OR status LIKE ?)";
            $countQuery .= " AND (title LIKE ? OR description LIKE ? OR status LIKE ?)";
            $searchTerm = "%$searchValue%";
            array_push($params, $searchTerm, $searchTerm, $searchTerm);
            array_push($searchParams, $searchTerm, $searchTerm, $searchTerm);
        }
        
        //  ordering
        $query .= " ORDER BY $orderBy $orderDirection";
        
        //  pagination - using integers direct in the query
        $query .= " LIMIT $length OFFSET $start";
        
        //  filtered data
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        //  total records count
        $totalRecords = $pdo->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
        
        //  filtered records count without pagination
        $filteredRecords = $pdo->prepare($countQuery);
        $filteredRecords->execute($searchParams);
        $filteredCount = $filteredRecords->fetchColumn();
        
       
        $output = [
            'draw' => intval($_GET['draw'] ?? 1),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredCount,
            'data' => array_map(function($task, $index) use ($start) {
                ob_start();
                include 'partials/actions.php';
                $actions = ob_get_clean();
                
                return [
                    $start + $index + 1,
                    $task['title'],
                    ucfirst(str_replace('_', ' ', $task['status'])),
                    $task['due_date'] ?? 'N/A',
                    $actions
                ];
            }, $tasks, array_keys($tasks))
        ];
        
        echo json_encode($output);
        break;
        
    case 'get_task':
        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($task) {
            echo json_encode($task);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Task not found']);
        }
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
}
?>