<?php
header('Content-Type: application/json');

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_FILES["file"])) {
        throw new Exception("Tidak ada file yang diunggah.");
    }

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
        throw new Exception("Gagal membuat folder penyimpanan.");
    }

    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $uploadDir . $fileName;

    if ($_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
        throw new Exception("Upload error: " . $_FILES["file"]["error"]);
    }

    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
        throw new Exception("Gagal mengunggah file.");
    }

    echo json_encode(["status" => "success", "message" => "File berhasil diupload"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}