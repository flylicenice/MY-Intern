<?php
header('Content-Type: application/json');
require_once 'db.php'; // Path to your db connection file inside includes/

$response = [
    'status' => 'error',
    'data' => [
        'placed' => 0,
        'applying' => 0,
        'not_applying' => 0
    ]
];

$query = "SELECT intern_status, COUNT(*) as total FROM student GROUP BY intern_status";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $status = $row['intern_status'];
        if ($status === 'Placed') {
            $response['data']['placed'] = (int)$row['total'];
        } elseif ($status === 'Still Applying') {
            $response['data']['applying'] = (int)$row['total'];
        } elseif ($status === 'Not Applying') {
            $response['data']['not_applying'] = (int)$row['total'];
        }
    }
    $response['status'] = 'success';
}

echo json_encode($response);
exit();