<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ip'])) {
    $ip = $_POST['ip'];

    // Menyimpan IP di file (atau bisa menggunakan database)
    file_put_contents('esp32cam_ip.txt', $ip);

    echo json_encode(['status' => 'success', 'ip' => $ip]);
} else {
    echo json_encode(['status' => 'failed']);
}
?>
