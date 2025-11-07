<?php
require_once __DIR__ . '/../includes/util.php';
header('Content-Type: application/json');

$reviewsFile = __DIR__ . '/../data/reviews.json'; // ensure correct path
$method = $_SERVER['REQUEST_METHOD'];

// --- GET: Fetch reviews for a book ---
if ($method === 'GET') {
    $book_id = (int)($_GET['book_id'] ?? 0);
    $all = file_exists($reviewsFile) ? json_decode(file_get_contents($reviewsFile), true) : [];
    $filtered = array_values(array_filter($all, fn($r) => $r['book_id'] === $book_id));
    echo json_encode($filtered);
    exit;
}

// --- POST: Add a new review ---
if ($method === 'POST') {
    $user = current_user();
    if (!$user) {
        http_response_code(401);
        echo json_encode(['error' => 'login']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || empty($input['book_id']) || empty($input['rating']) || empty(trim($input['comment']))) {
        http_response_code(400);
        echo json_encode(['error' => 'invalid input']);
        exit;
    }

    // Read existing reviews
    $all = file_exists($reviewsFile) ? json_decode(file_get_contents($reviewsFile), true) : [];

    // Add new review
    $newReview = [
        'id'         => count($all) ? max(array_column($all, 'id')) + 1 : 1,
        'book_id'    => (int)$input['book_id'],
        'user_id'    => $user['id'],
        'user_name'  => $user['name'],
        'rating'     => max(1, min(5, (int)$input['rating'])),
        'comment'    => trim($input['comment']),
        'created_at' => date('Y-m-d H:i:s')
    ];

    $all[] = $newReview;
    file_put_contents($reviewsFile, json_encode($all, JSON_PRETTY_PRINT));

    // Return updated reviews for this book
    $filtered = array_values(array_filter($all, fn($r) => $r['book_id'] === (int)$input['book_id']));
    echo json_encode($filtered);
    exit;
}

// --- Method not allowed ---
http_response_code(405);
echo json_encode(['error' => 'method not allowed']);
