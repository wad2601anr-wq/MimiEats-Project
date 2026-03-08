<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "mimieats");

// Jika ada error koneksi
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed']));
}

// --- LOGIKA BARU: MENGHAPUS CHAT (CLEAR) ---
if (isset($_POST['action']) && $_POST['action'] == 'clear') {
    // Perintah untuk mengosongkan tabel chats
    if ($conn->query("TRUNCATE TABLE chats")) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    exit;
}

// LOGIKA 1: MENERIMA PESAN BARU (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender = $_POST['sender']; 
    $message = $_POST['message'];
    $stmt = $conn->prepare("INSERT INTO chats (sender, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $sender, $message);
    $stmt->execute();
    echo json_encode(['status' => 'success']);
    exit;
}

// LOGIKA 2: MENGAMBIL SEMUA PESAN (GET)
$result = $conn->query("SELECT * FROM chats ORDER BY created_at ASC");
$messages = [];
while($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
echo json_encode($messages);

?>
