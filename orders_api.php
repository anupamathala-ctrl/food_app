<?php
// Set response header to JSON format
header('Content-Type: application/json');
include 'db_connect.php'; // Include the database connection script

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Handle GET request (e.g., awana app getting menu, staff app getting orders)
        if ($_GET['action'] == 'fetch_orders') {
            $sql = "SELECT order_id, status, total_amount, delivery_address, users.username AS customer FROM orders JOIN users ON orders.user_id = users.user_id WHERE status IN ('pending', 'processing', 'out_for_delivery') ORDER BY order_date DESC";
            $result = $conn->query($sql);
            $orders = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $orders[] = $row;
                }
            }
            echo json_encode(['success' => true, 'data' => $orders]);
        }
        break;

    case 'POST':
        // Handle POST request (e.g., awana app submitting a new order)
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data['action'] == 'place_order') {
            // ... (Insert into orders and order_items tables)
            echo json_encode(['success' => true, 'message' => 'Order placed successfully.']);
        }
        break;

    case 'PUT':
        // Handle PUT request (e.g., staff app updating order status)
        parse_str(file_get_contents("php://input"), $_PUT);
        if ($_PUT['action'] == 'update_status') {
            $order_id = $_PUT['order_id'];
            $new_status = $_PUT['status'];
            // Basic validation and update query
            $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
            $stmt->bind_param("si", $new_status, $order_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Order status updated.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Order not found or status already set.']);
            }
            $stmt->close();
        }
        break;
}

$conn->close();
?>