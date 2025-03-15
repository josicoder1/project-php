<?php
function generateRequestID($pdo)
{
    static $machineId = null;

    // Database Sequence Fallback
    try {
        $pdo->exec("CALL get_next_request_id(@next_id)");
        $stmt = $pdo->query("SELECT @next_id");
        return 'REQ-' . $stmt->fetchColumn();
    } catch (PDOException $e) {
        // Fallback to high-entropy method
        $bytes = random_bytes(16);
        return 'REQ-' . bin2hex($bytes);
    }
}
?>