<?php
/**
 * Import Data Script — Chỉ import INSERT từ SQL dump cũ vào DB hiện tại
 * Giữ nguyên cấu trúc bảng từ Laravel migration, chỉ lấy dữ liệu.
 * 
 * Cách dùng: php import_data.php "đường_dẫn_tới_file.sql"
 */

if (php_sapi_name() !== 'cli') {
    die("Chỉ chạy từ command line!\n");
}

$sqlFile = $argv[1] ?? 'c:/Users/trant/Downloads/shop_tlo.sql';

if (!file_exists($sqlFile)) {
    die("Không tìm thấy file: $sqlFile\n");
}

// Load .env
$envFile = __DIR__ . '/.env';
$env = [];
foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if (strpos($line, '#') === 0) continue;
    if (strpos($line, '=') !== false) {
        list($key, $val) = explode('=', $line, 2);
        $env[trim($key)] = trim($val, " \t\n\r\0\x0B\"'");
    }
}

$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = $env['DB_PORT'] ?? '3306';
$db   = $env['DB_DATABASE'] ?? 'shop_tlo';
$user = $env['DB_USERNAME'] ?? 'root';
$pass = $env['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Loi ket noi DB: " . $e->getMessage() . "\n");
}

echo "=== TLO Import Data ===\n";
echo "File: $sqlFile\n";
echo "DB: $db\n\n";

// Read file
$content = file_get_contents($sqlFile);

// Disable FK checks
$pdo->exec("SET FOREIGN_KEY_CHECKS=0");
$pdo->exec("SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO'");

// Truncate data tables (skip system tables)
$skipTables = ['migrations', 'cache', 'cache_locks', 'sessions', 'jobs', 'job_batches', 'failed_jobs', 'password_reset_tokens'];

$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $t) {
    if (!in_array($t, $skipTables)) {
        echo "  Xoa du lieu: $t\n";
        $pdo->exec("TRUNCATE TABLE `$t`");
    }
}

echo "\n--- Import du lieu ---\n\n";

// Extract only INSERT INTO statements
// phpMyAdmin format: INSERT can span multiple lines, ending with ;
// We need to carefully extract complete INSERT statements

$lines = explode("\n", $content);
$insertSQL = '';
$inInsert = false;
$count = 0;
$errors = 0;

foreach ($lines as $line) {
    $trimmed = trim($line);
    
    // Start of INSERT statement
    if (stripos($trimmed, 'INSERT INTO') === 0) {
        $inInsert = true;
        $insertSQL = $trimmed;
    } elseif ($inInsert) {
        $insertSQL .= ' ' . $trimmed;
    }
    
    // End of statement
    if ($inInsert && substr(rtrim($trimmed), -1) === ';') {
        $inInsert = false;
        
        // Extract table name
        if (preg_match('/INSERT INTO `(\w+)`/', $insertSQL, $m)) {
            $tableName = $m[1];
            
            // Skip system tables
            if (in_array($tableName, $skipTables)) {
                echo "  SKIP: $tableName (bang he thong)\n";
                $insertSQL = '';
                continue;
            }
            
            // Check if table exists
            if (!in_array($tableName, $tables)) {
                echo "  SKIP: $tableName (khong ton tai)\n";
                $insertSQL = '';
                continue;
            }
            
            try {
                $pdo->exec($insertSQL);
                $affected = $pdo->query("SELECT ROW_COUNT()")->fetchColumn();
                echo "  OK: $tableName ($affected hang)\n";
                $count++;
            } catch (PDOException $e) {
                $errMsg = $e->getMessage();
                // If column mismatch, try to handle
                if (strpos($errMsg, 'Column count') !== false || strpos($errMsg, "doesn't have a default") !== false) {
                    echo "  WARN: $tableName - cau truc khac, thu INSERT IGNORE...\n";
                    try {
                        $ignoreSQL = preg_replace('/^INSERT INTO/i', 'INSERT IGNORE INTO', $insertSQL);
                        $pdo->exec($ignoreSQL);
                        $count++;
                        echo "  OK: $tableName (INSERT IGNORE)\n";
                    } catch (PDOException $e2) {
                        echo "  LOI: $tableName - " . substr($e2->getMessage(), 0, 100) . "\n";
                        $errors++;
                    }
                } else {
                    echo "  LOI: $tableName - " . substr($errMsg, 0, 100) . "\n";
                    $errors++;
                }
            }
        }
        $insertSQL = '';
    }
}

// Re-enable FK checks
$pdo->exec("SET FOREIGN_KEY_CHECKS=1");

echo "\n=== Ket qua ===\n";
echo "Thanh cong: $count\n";
echo "Loi: $errors\n";

// Show row counts
echo "\n--- So luong ban ghi ---\n";
foreach ($tables as $t) {
    if (!in_array($t, $skipTables)) {
        $cnt = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
        if ($cnt > 0) {
            echo "  $t: $cnt\n";
        }
    }
}

echo "\nHoan tat!\n";
