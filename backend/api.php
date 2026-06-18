<?php
//Unified API: Admin + User actions
session_start();
require_once __DIR__ . '/DBConn.php';

$action = $_REQUEST['action'] ?? '';


// ADMIN ACTIONS (require admin login, use redirects & session flashes)
$adminActions = [
    'view_profile',
    'suspend_user', 'unsuspend_user',
    'delete_listing', 'approve_listing',
    'remove_flagged', 'keep_flagged'
];

if (in_array($action, $adminActions)) {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: ../frontend/admin.php");
        exit();
    }

    $redirect = $_SERVER['HTTP_REFERER'] ?? '../frontend/admin.php';
    $id = (int)($_GET['id'] ?? 0);

    switch ($action) {
        case 'view_profile':
        if ($id) {
            header("Location: ../frontend/admin-view-profile.php?id=$id");
            exit();
        }
        break;
        
        case 'suspend_user':
            if ($id) {
                $conn->query("UPDATE tbluser SET status = 'suspended' WHERE user_id = $id");
                $_SESSION['success'] = "User suspended successfully.";
            }
            break;

        case 'unsuspend_user':
            if ($id) {
                $conn->query("UPDATE tbluser SET status = 'approved' WHERE user_id = $id");
                $_SESSION['success'] = "User reactivated successfully.";
            }
            break;

        case 'delete_listing':
            if ($id) {
                $conn->query("DELETE FROM tblclothes WHERE clothing_id = $id");
                $_SESSION['success'] = "Listing removed successfully.";
            }
            break;

        case 'approve_listing':
            if ($id) {
                $conn->query("UPDATE tblclothes SET status = 'available' WHERE clothing_id = $id");
                $_SESSION['success'] = "Listing approved.";
            }
            break;

        case 'remove_flagged':
            if ($id) {
                $conn->query("UPDATE tblclothes SET status = 'removed' WHERE clothing_id = $id");
                $_SESSION['success'] = "Flagged item removed.";
            }
            break;

        case 'keep_flagged':
            if ($id) {
                $conn->query("UPDATE tblclothes SET status = 'available' WHERE clothing_id = $id");
                $_SESSION['success'] = "Item kept live.";
            }
            break;
    }

    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../frontend/admin.php'));
    exit();
}


// USER / GENERAL ACTIONS (JSON responses)
header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Invalid request'];

switch ($action) {

    // ---------- CREATE LISTING (seller only) ----------
    case 'create_listing':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
            $response['message'] = "Unauthorized. Sellers only.";
            break;
        }

        $title       = trim($_REQUEST['title'] ?? '');
        $price       = (float)($_REQUEST['price'] ?? 0);
        $condition   = $_REQUEST['condition'] ?? '';
        $size        = $_REQUEST['size'] ?? '';
        $material    = $_REQUEST['material'] ?? '';
        $city        = $_REQUEST['city'] ?? '';
        $category    = $_REQUEST['category'] ?? '';
        $description = trim($_REQUEST['description'] ?? '');
        $brand       = $_REQUEST['brand'] ?? '';   // not used in INSERT but kept

        if (empty($title) || $price <= 0) {
            $response['message'] = "Title and price are required.";
            break;
        }

        $stmt = $conn->prepare("INSERT INTO tblclothes 
            (seller_id, title, description, price, `condition`, size, material, category, city, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'available')");
        $stmt->bind_param("issdsssss", $_SESSION['user_id'], $title, $description, $price, $condition, $size, $material, $category, $city);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Listing created successfully!";
            $response['clothing_id'] = $conn->insert_id;
        } else {
            $response['message'] = "Failed to create listing.";
        }
        break;

    // ---------- ADD TO CART (any logged-in user) ----------
    case 'add_to_cart':
        if (!isset($_SESSION['user_id'])) {
            $response['message'] = "Please login to add items to cart.";
            break;
        }

        $clothing_id = (int)($_REQUEST['clothing_id'] ?? 0);
        $quantity    = (int)($_REQUEST['quantity'] ?? 1);

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $_SESSION['cart'][$clothing_id] = $quantity;

        $response['success'] = true;
        $response['message'] = "Item added to cart!";
        $response['cart_count'] = count($_SESSION['cart']);
        break;

    default:
        $response['message'] = "Unknown action.";
}

echo json_encode($response);
exit();
?>