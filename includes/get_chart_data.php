<?php
require_once 'db.php';

header('Content-Type: application/json');

$data = [
    'placed' => 0,
    'applying' => 0,
    'not_applying' => 0
];

$query = "SELECT intern_status, COUNT(*) as total FROM student GROUP BY intern_status";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $status = $row['intern_status'];
        $count = (int)$row['total'];
        
        if ($status === 'Placed') {
            $data['placed'] = $count;
        } elseif ($status === 'Still Applying') {
            $data['applying'] = $count;
        } elseif ($status === 'Not Applying' || $status === 'Inactive') {
            $data['not_applying'] += $count;
        }
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => $data
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to query data records'
    ]);
}
exit();