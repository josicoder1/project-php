<?php
function generateRequestID($pdo)
{
    $maxAttempts = 5;
    $prefix = 'REQ-';
    $entropyLength = 16; // 128-bit entropy

    for ($i = 0; $i < $maxAttempts; $i++) {
        try {
            // Generate unique ID
            $unique = bin2hex(random_bytes($entropyLength / 2));
            $timestamp = time();
            $request_id = sprintf("%s%d-%s", $prefix, $timestamp, $unique);

            // Verify uniqueness
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM transport_requests WHERE request_id = ?");
            $stmt->execute([$request_id]);

            if ($stmt->fetchColumn() === 0) {
                return $request_id;
            }
        } catch (Exception $e) {
            error_log("ID Generation Attempt $i failed: " . $e->getMessage());
        }
    }

    throw new RuntimeException("Failed to generate unique ID after $maxAttempts attempts");
}
?>