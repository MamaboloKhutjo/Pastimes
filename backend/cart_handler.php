<?php
session_start();
require_once __DIR__ . '/DBConn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

switch ($action) {

    // =========================
    // ADD TO CART
    // =========================
    case 'add_to_cart':

        $clothing_id = (int)($_POST['clothing_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($clothing_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid product']);
            exit();
        }

        // check if item exists
        $check = $conn->prepare("
            SELECT cart_id, quantity 
            FROM tblcart 
            WHERE user_id = ? AND clothing_id = ?
        ");
        $check->bind_param("ii", $user_id, $clothing_id);
        $check->execute();
        $result = $check->get_result();

        if ($row = $result->fetch_assoc()) {

            // update quantity
            $newQty = $row['quantity'] + $quantity;

            $stmt = $conn->prepare("
                UPDATE tblcart 
                SET quantity = ? 
                WHERE cart_id = ?
            ");
            $stmt->bind_param("ii", $newQty, $row['cart_id']);

        } else {

            // insert new
            $stmt = $conn->prepare("
                INSERT INTO tblcart (user_id, clothing_id, quantity)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("iii", $user_id, $clothing_id, $quantity);
        }

        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Added to cart']);
        break;


    // =========================
    // UPDATE QUANTITY (FIXED)
    // =========================
    case 'update_quantity':

        $cart_id = (int)($_POST['cart_id'] ?? 0);
        $change  = (int)($_POST['change'] ?? 0);

        $stmt = $conn->prepare("
            SELECT quantity 
            FROM tblcart 
            WHERE cart_id = ? AND user_id = ?
        ");
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$row = $result->fetch_assoc()) {
            echo json_encode(['success' => false]);
            exit();
        }

        $newQty = $row['quantity'] + $change;

        if ($newQty < 1) {
            $newQty = 1;
        }

        $update = $conn->prepare("
            UPDATE tblcart 
            SET quantity = ? 
            WHERE cart_id = ? AND user_id = ?
        ");
        $update->bind_param("iii", $newQty, $cart_id, $user_id);

        echo json_encode([
            'success' => $update->execute(),
            'new_quantity' => $newQty
        ]);
        break;


    // =========================
    // REMOVE ITEM
    // =========================
    case 'remove_from_cart':

        $cart_id = (int)$_POST['cart_id'];

        $stmt = $conn->prepare("
            DELETE FROM tblcart 
            WHERE cart_id = ? AND user_id = ?
        ");
        $stmt->bind_param("ii", $cart_id, $user_id);

        echo json_encode(['success' => $stmt->execute()]);
        break;


    // =========================
    // GET CART
    // =========================
    case 'get_cart':

        $stmt = $conn->prepare("
            SELECT 
                c.cart_id,
                c.quantity,
                cl.*,
                u.first_name,
                u.last_name,
                u.city
            FROM tblcart c
            JOIN tblclothes cl ON c.clothing_id = cl.clothing_id
            JOIN tbluser u ON cl.seller_id = u.user_id
            WHERE c.user_id = ?
            ORDER BY c.added_at DESC
        ");

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            'success' => true,
            'items' => $items
        ]);
        break;
}