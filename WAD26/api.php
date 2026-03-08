<?php
header('Content-Type: application/json');

// 1. SECURITY: Load database credentials from .env file
// Ini memastikan password kamu tidak terlihat di GitHub!
$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) {
    die(json_encode(['error' => '.env file missing. Please create one!']));
}
$env = parse_ini_file($envFile);

// 2. DATABASE CONNECTION
$conn = new mysqli(
    $env['DB_HOST'], 
    $env['DB_USER'], 
    $env['DB_PASS'], 
    $env['DB_NAME']
);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// ACTION: CLEAR CHAT (Useful for testing)
if (isset($_POST['action']) && $_POST['action'] == 'clear') {
    $conn->query("TRUNCATE TABLE chats");
    echo json_encode(['status' => 'success']);
    exit;
}

// LOGIKA 1: SIMPAN PESAN & ALAMAT (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender = $_POST['sender'] ?? ''; 
    $message = $_POST['message'] ?? '';
    $address = $_POST['address'] ?? null; // Menangkap data alamat baru

    // Simpan ke tabel chats
    // Jika ada alamat (address), kita masukkan ke kolom address (pastikan kolom ini ada di DB)
    $stmt = $conn->prepare("INSERT INTO chats (sender, message, address) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $sender, $message, $address);
    $stmt->execute();

    // Logika Order History (Sesuai panduan 'Order history' di dokumen)
    if ($sender === 'Buyer' && strpos($message, 'Order:') !== false) {
        $stmt_order = $conn->prepare("INSERT INTO orders (items) VALUES (?)");
        $stmt_order->bind_param("s", $message);
        $stmt_order->execute();
    }

    echo json_encode(['status' => 'success']);
    exit;
}

// LOGIKA 2: AMBIL PESAN (GET)
// Mengambil semua chat untuk ditampilkan di Buyer & Seller panel
$result = $conn->query("SELECT * FROM chats ORDER BY created_at ASC");
$messages = [];
while($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
echo json_encode($messages);

$conn->close();
?>