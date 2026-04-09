<?php
/**
 * TLO DB Manager - Quản lý database đầy đủ cho dev
 * Truy cập: http://127.0.0.1:8000/db.php
 */

$envFile = __DIR__ . '/../.env';
$env = [];
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $env[trim($key)] = trim($val, " \t\n\r\0\x0B\"'");
        }
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
    die("<h2 style='color:red;font-family:sans-serif;padding:40px'>Lỗi kết nối DB: " . htmlspecialchars($e->getMessage()) . "</h2>");
}

$msg = '';
$msgType = '';
$action = $_GET['action'] ?? '';
$table = $_GET['table'] ?? '';

// ============ XỬ LÝ ACTIONS ============

// Import SQL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'import') {
    if (isset($_FILES['sqlfile']) && $_FILES['sqlfile']['error'] === 0) {
        $sql = file_get_contents($_FILES['sqlfile']['tmp_name']);
        try {
            $pdo->exec($sql);
            $msg = "Import SQL thành công!";
            $msgType = 'success';
        } catch (PDOException $e) {
            $msg = "Lỗi import: " . $e->getMessage();
            $msgType = 'error';
        }
    } elseif (!empty($_POST['sql_content'])) {
        try {
            $pdo->exec($_POST['sql_content']);
            $msg = "Chạy SQL thành công!";
            $msgType = 'success';
        } catch (PDOException $e) {
            $msg = "Lỗi SQL: " . $e->getMessage();
            $msgType = 'error';
        }
    }
}

// Export DB
if ($action === 'export') {
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . $db . '_' . date('Y-m-d_His') . '.sql"');
    
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "-- TLO DB Export: $db\n-- Date: " . date('Y-m-d H:i:s') . "\n-- ================================\n\n";
    echo "SET FOREIGN_KEY_CHECKS=0;\n\n";
    
    foreach ($tables as $t) {
        $create = $pdo->query("SHOW CREATE TABLE `$t`")->fetch(PDO::FETCH_ASSOC);
        echo "DROP TABLE IF EXISTS `$t`;\n";
        echo $create['Create Table'] . ";\n\n";
        
        $rows = $pdo->query("SELECT * FROM `$t`")->fetchAll(PDO::FETCH_ASSOC);
        if ($rows) {
            $cols = array_keys($rows[0]);
            foreach ($rows as $row) {
                $vals = array_map(function($v) use ($pdo) {
                    return $v === null ? 'NULL' : $pdo->quote($v);
                }, array_values($row));
                echo "INSERT INTO `$t` (`" . implode('`, `', $cols) . "`) VALUES (" . implode(', ', $vals) . ");\n";
            }
            echo "\n";
        }
    }
    echo "SET FOREIGN_KEY_CHECKS=1;\n";
    exit;
}

// Drop table
if ($action === 'drop' && $table && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
        $pdo->exec("DROP TABLE `$table`");
        $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
        $msg = "Đã xóa bảng '$table'!";
        $msgType = 'success';
        $table = '';
    } catch (PDOException $e) {
        $msg = "Lỗi: " . $e->getMessage();
        $msgType = 'error';
    }
}

// Truncate table
if ($action === 'truncate' && $table && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
        $pdo->exec("TRUNCATE TABLE `$table`");
        $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
        $msg = "Đã xóa hết dữ liệu bảng '$table'!";
        $msgType = 'success';
    } catch (PDOException $e) {
        $msg = "Lỗi: " . $e->getMessage();
        $msgType = 'error';
    }
}

// Delete row
if ($action === 'delete_row' && $table && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['row_id'] ?? '';
    $pk = $_POST['pk_col'] ?? 'id';
    if ($id !== '') {
        try {
            $stmt = $pdo->prepare("DELETE FROM `$table` WHERE `$pk` = ?");
            $stmt->execute([$id]);
            $msg = "Đã xóa hàng $pk=$id!";
            $msgType = 'success';
        } catch (PDOException $e) {
            $msg = "Lỗi: " . $e->getMessage();
            $msgType = 'error';
        }
    }
}

// Insert row
if ($action === 'insert' && $table && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $cols = $_POST['col'] ?? [];
    $vals = $_POST['val'] ?? [];
    $insertCols = [];
    $insertVals = [];
    $placeholders = [];
    for ($i = 0; $i < count($cols); $i++) {
        if (!empty($cols[$i]) && isset($vals[$i]) && $vals[$i] !== '') {
            $insertCols[] = "`{$cols[$i]}`";
            $insertVals[] = $vals[$i];
            $placeholders[] = '?';
        }
    }
    if ($insertCols) {
        try {
            $sql = "INSERT INTO `$table` (" . implode(',', $insertCols) . ") VALUES (" . implode(',', $placeholders) . ")";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($insertVals);
            $msg = "Thêm hàng thành công! ID=" . $pdo->lastInsertId();
            $msgType = 'success';
        } catch (PDOException $e) {
            $msg = "Lỗi: " . $e->getMessage();
            $msgType = 'error';
        }
    }
}

// Update row
if ($action === 'update' && $table && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $pk = $_POST['pk_col'] ?? 'id';
    $pkVal = $_POST['pk_val'] ?? '';
    $cols = $_POST['col'] ?? [];
    $vals = $_POST['val'] ?? [];
    $sets = [];
    $params = [];
    for ($i = 0; $i < count($cols); $i++) {
        if (!empty($cols[$i])) {
            $sets[] = "`{$cols[$i]}` = ?";
            $params[] = $vals[$i] === '' ? null : $vals[$i];
        }
    }
    if ($sets && $pkVal !== '') {
        $params[] = $pkVal;
        try {
            $sql = "UPDATE `$table` SET " . implode(', ', $sets) . " WHERE `$pk` = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $msg = "Cập nhật thành công!";
            $msgType = 'success';
        } catch (PDOException $e) {
            $msg = "Lỗi: " . $e->getMessage();
            $msgType = 'error';
        }
    }
}

// Run custom query
$queryResult = null;
$queryError = null;
$customQuery = $_POST['custom_query'] ?? '';
if ($customQuery && $action !== 'import') {
    try {
        $stmt = $pdo->query($customQuery);
        if ($stmt->columnCount() > 0) {
            $queryResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $msg = "Query thành công. " . $stmt->rowCount() . " hàng bị ảnh hưởng.";
            $msgType = 'success';
        }
    } catch (PDOException $e) {
        $queryError = $e->getMessage();
    }
}

// ============ DATA ============
$tables_list = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
$tableData = null;
$tableColumns = null;
$tablePK = 'id';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 50;
$totalRows = 0;
$editRow = null;

if ($table && in_array($table, $tables_list)) {
    $tableColumns = $pdo->query("SHOW COLUMNS FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($tableColumns as $col) {
        if ($col['Key'] === 'PRI') { $tablePK = $col['Field']; break; }
    }
    $totalRows = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
    $offset = ($page - 1) * $perPage;
    $tableData = $pdo->query("SELECT * FROM `$table` LIMIT $perPage OFFSET $offset")->fetchAll(PDO::FETCH_ASSOC);
    $totalPages = ceil($totalRows / $perPage);
    
    // Load row for editing
    if ($action === 'edit' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE `$tablePK` = ?");
        $stmt->execute([$_GET['id']]);
        $editRow = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DB Manager - <?= htmlspecialchars($db) ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #0d1117; color: #c9d1d9; display: flex; height: 100vh; overflow: hidden; }
        
        /* Sidebar */
        .sb { width: 250px; background: #161b22; border-right: 1px solid #21262d; display: flex; flex-direction: column; flex-shrink: 0; }
        .sb-head { padding: 16px; border-bottom: 1px solid #21262d; }
        .sb-head .db-badge { background: linear-gradient(135deg, #238636, #2ea043); color: #fff; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; text-align: center; display: block; margin-bottom: 10px; }
        .sb-head .tools { display: flex; gap: 6px; flex-wrap: wrap; }
        .sb-head .tools a, .sb-head .tools label {
            padding: 5px 10px; font-size: 11px; border-radius: 6px; text-decoration: none; cursor: pointer;
            background: #21262d; color: #8b949e; border: 1px solid #30363d; transition: .2s; font-weight: 600;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .sb-head .tools a:hover, .sb-head .tools label:hover { background: #30363d; color: #e6edf3; }
        .sb-head .tools .export { background: #1f6feb22; color: #58a6ff; border-color: #1f6feb44; }
        .sb-head .tools .import { background: #da363322; color: #f85149; border-color: #da363344; }
        .sb-list { flex: 1; overflow-y: auto; padding: 8px; }
        .sb-list a { display: flex; justify-content: space-between; padding: 7px 12px; color: #8b949e; text-decoration: none; font-size: 13px; border-radius: 6px; margin-bottom: 2px; transition: .15s; }
        .sb-list a:hover { background: #21262d; color: #e6edf3; }
        .sb-list a.active { background: #1f6feb22; color: #58a6ff; font-weight: 600; }
        .sb-list .cnt { font-size: 11px; background: #21262d; padding: 1px 8px; border-radius: 10px; color: #8b949e; }
        
        /* Main */
        .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        
        /* Toolbar */
        .toolbar { background: #161b22; padding: 12px 20px; border-bottom: 1px solid #21262d; display: flex; gap: 12px; align-items: flex-start; }
        .toolbar textarea { flex: 1; background: #0d1117; color: #7ee787; border: 1px solid #21262d; border-radius: 8px; padding: 10px 14px; font-family: 'Consolas', 'Courier New', monospace; font-size: 13px; resize: vertical; min-height: 48px; max-height: 200px; }
        .toolbar textarea:focus { outline: none; border-color: #1f6feb; box-shadow: 0 0 0 3px #1f6feb33; }
        .toolbar button { background: #238636; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; transition: .2s; white-space: nowrap; }
        .toolbar button:hover { background: #2ea043; }
        
        /* Content */
        .content { flex: 1; overflow: auto; padding: 16px 20px; }
        
        /* Alerts */
        .alert { padding: 10px 16px; border-radius: 8px; margin-bottom: 12px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .alert-success { background: #23863622; border: 1px solid #23863644; color: #7ee787; }
        .alert-error { background: #da363322; border: 1px solid #da363344; color: #f85149; }
        
        /* Table info bar */
        .tbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 8px; }
        .tbar h2 { font-size: 16px; color: #e6edf3; display: flex; align-items: center; gap: 8px; }
        .tbar h2 .badge { font-size: 11px; background: #1f6feb22; color: #58a6ff; padding: 3px 10px; border-radius: 10px; font-weight: 600; }
        .tbar-actions { display: flex; gap: 6px; }
        .btn-sm { padding: 5px 12px; font-size: 12px; border-radius: 6px; border: 1px solid #30363d; background: #21262d; color: #c9d1d9; cursor: pointer; font-weight: 600; text-decoration: none; transition: .2s; display: inline-flex; align-items: center; gap: 4px; }
        .btn-sm:hover { background: #30363d; color: #fff; }
        .btn-green { background: #23863622; color: #7ee787; border-color: #23863644; }
        .btn-green:hover { background: #238636; color: #fff; }
        .btn-red { background: #da363322; color: #f85149; border-color: #da363344; }
        .btn-red:hover { background: #da3633; color: #fff; }
        .btn-blue { background: #1f6feb22; color: #58a6ff; border-color: #1f6feb44; }
        .btn-blue:hover { background: #1f6feb; color: #fff; }
        
        /* Data table */
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { background: #161b22; color: #8b949e; padding: 8px 12px; text-align: left; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; position: sticky; top: 0; z-index: 1; white-space: nowrap; border-bottom: 2px solid #21262d; }
        td { padding: 6px 12px; border-bottom: 1px solid #21262d; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: top; }
        tr:hover td { background: #161b2288; }
        td .null { color: #484f58; font-style: italic; font-size: 11px; }
        td .pk { color: #58a6ff; font-weight: 600; }
        td .actions { display: flex; gap: 4px; white-space: nowrap; }
        td .actions a, td .actions button { padding: 3px 8px; font-size: 11px; border-radius: 4px; border: none; cursor: pointer; text-decoration: none; font-weight: 600; transition: .15s; }
        td .actions .edit-btn { background: #1f6feb22; color: #58a6ff; }
        td .actions .edit-btn:hover { background: #1f6feb; color: #fff; }
        td .actions .del-btn { background: #da363322; color: #f85149; }
        td .actions .del-btn:hover { background: #da3633; color: #fff; }
        
        /* Column info table */
        .col-type { font-family: 'Consolas', monospace; font-size: 12px; color: #d2a8ff; }
        .col-pk { background: #da363322; color: #f85149; padding: 1px 6px; border-radius: 4px; font-size: 10px; font-weight: 700; }
        .col-null { color: #7ee787; }
        .col-no { color: #f85149; }
        
        /* Form */
        .edit-form { background: #161b22; border: 1px solid #21262d; border-radius: 10px; padding: 20px; margin-bottom: 16px; }
        .edit-form h3 { font-size: 15px; color: #e6edf3; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .form-grid { display: grid; grid-template-columns: 180px 1fr; gap: 8px; align-items: center; }
        .form-grid label { font-size: 13px; color: #8b949e; font-weight: 600; }
        .form-grid input, .form-grid textarea, .form-grid select { background: #0d1117; color: #c9d1d9; border: 1px solid #30363d; border-radius: 6px; padding: 8px 12px; font-size: 13px; font-family: inherit; }
        .form-grid input:focus, .form-grid textarea:focus { outline: none; border-color: #1f6feb; box-shadow: 0 0 0 3px #1f6feb33; }
        .form-btns { margin-top: 16px; display: flex; gap: 8px; }
        .form-btns button { padding: 8px 20px; border-radius: 8px; border: none; font-weight: 600; font-size: 13px; cursor: pointer; transition: .2s; }
        .form-btns .save { background: #238636; color: #fff; }
        .form-btns .save:hover { background: #2ea043; }
        .form-btns .cancel { background: #21262d; color: #c9d1d9; border: 1px solid #30363d; }
        .form-btns .cancel:hover { background: #30363d; }
        
        /* Pagination */
        .pager { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; font-size: 13px; color: #8b949e; }
        .pager-links { display: flex; gap: 4px; }
        .pager-links a { padding: 4px 10px; border-radius: 6px; background: #21262d; color: #c9d1d9; text-decoration: none; font-size: 12px; border: 1px solid #30363d; transition: .15s; }
        .pager-links a:hover { background: #30363d; }
        .pager-links a.curr { background: #1f6feb; color: #fff; border-color: #1f6feb; }
        
        /* Import modal */
        .modal-bg { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,.6); z-index: 100; align-items: center; justify-content: center; }
        .modal-bg.show { display: flex; }
        .modal { background: #161b22; border: 1px solid #30363d; border-radius: 12px; padding: 24px; width: 90%; max-width: 600px; }
        .modal h3 { color: #e6edf3; margin-bottom: 16px; }
        .modal textarea { width: 100%; background: #0d1117; color: #7ee787; border: 1px solid #30363d; border-radius: 8px; padding: 12px; font-family: 'Consolas', monospace; font-size: 13px; min-height: 150px; margin-bottom: 12px; }
        .modal textarea:focus { outline: none; border-color: #1f6feb; }
        .modal-btns { display: flex; gap: 8px; justify-content: flex-end; }
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #0d1117; }
        ::-webkit-scrollbar-thumb { background: #30363d; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #484f58; }
        
        /* Hidden file input */
        .hidden-input { position: absolute; opacity: 0; width: 0; height: 0; }

        @media (max-width: 768px) {
            .sb { width: 200px; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sb">
    <div class="sb-head">
        <span class="db-badge">⚡ <?= htmlspecialchars($db) ?></span>
        <div class="tools">
            <a href="?action=export" class="export" title="Export toàn bộ DB">📥 Export</a>
            <label class="import" onclick="document.getElementById('importModal').classList.add('show')" title="Import SQL">📤 Import</label>
            <a href="?" title="Refresh">🔄</a>
        </div>
    </div>
    <div class="sb-list">
        <?php foreach ($tables_list as $t): ?>
            <?php $cnt = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn(); ?>
            <a href="?table=<?= urlencode($t) ?>" class="<?= $table === $t ? 'active' : '' ?>">
                <?= htmlspecialchars($t) ?>
                <span class="cnt"><?= number_format($cnt) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- Main -->
<div class="main">
    <!-- Query toolbar -->
    <form method="POST" class="toolbar">
        <textarea name="custom_query" placeholder="SELECT * FROM users WHERE id = 1"><?= htmlspecialchars($customQuery) ?></textarea>
        <button type="submit">▶ Chạy SQL</button>
    </form>

    <div class="content">
        <!-- Messages -->
        <?php if ($msg): ?>
            <div class="alert alert-<?= $msgType ?>"><?= $msgType === 'success' ? '✅' : '❌' ?> <?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <?php if ($queryError): ?>
            <div class="alert alert-error">❌ <?= htmlspecialchars($queryError) ?></div>
        <?php endif; ?>

        <!-- Query results -->
        <?php if ($queryResult !== null): ?>
            <div class="tbar"><h2>Kết quả query <span class="badge"><?= count($queryResult) ?> hàng</span></h2></div>
            <?php if (count($queryResult) > 0): ?>
            <div style="overflow-x:auto">
                <table>
                    <thead><tr>
                        <?php foreach (array_keys($queryResult[0]) as $col): ?><th><?= htmlspecialchars($col) ?></th><?php endforeach; ?>
                    </tr></thead>
                    <tbody>
                        <?php foreach ($queryResult as $row): ?>
                        <tr><?php foreach ($row as $v): ?><td title="<?= htmlspecialchars($v ?? '') ?>"><?= $v === null ? '<span class="null">NULL</span>' : htmlspecialchars(mb_substr($v, 0, 100)) ?></td><?php endforeach; ?></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="alert alert-success">Query trả về 0 kết quả.</div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Table view -->
        <?php if ($table && $tableColumns): ?>
            <!-- Table bar -->
            <div class="tbar">
                <h2>📋 <?= htmlspecialchars($table) ?> <span class="badge"><?= number_format($totalRows) ?> hàng</span></h2>
                <div class="tbar-actions">
                    <a href="?table=<?= urlencode($table) ?>&action=insert_form" class="btn-sm btn-green">➕ Thêm hàng</a>
                    <form method="POST" action="?table=<?= urlencode($table) ?>&action=truncate" style="display:inline" onsubmit="return confirm('XÓA HẾT dữ liệu bảng <?= $table ?>?')">
                        <button class="btn-sm btn-red" type="submit">🗑️ Truncate</button>
                    </form>
                    <form method="POST" action="?table=<?= urlencode($table) ?>&action=drop" style="display:inline" onsubmit="return confirm('XÓA BẢNG <?= $table ?>? Không thể hoàn tác!')">
                        <button class="btn-sm btn-red" type="submit">💥 Drop</button>
                    </form>
                </div>
            </div>

            <!-- Edit form -->
            <?php if ($editRow): ?>
            <form method="POST" action="?table=<?= urlencode($table) ?>&action=update" class="edit-form">
                <h3>✏️ Sửa hàng (<?= $tablePK ?> = <?= htmlspecialchars($editRow[$tablePK]) ?>)</h3>
                <input type="hidden" name="pk_col" value="<?= $tablePK ?>">
                <input type="hidden" name="pk_val" value="<?= htmlspecialchars($editRow[$tablePK]) ?>">
                <div class="form-grid">
                    <?php foreach ($tableColumns as $col): ?>
                        <label><?= htmlspecialchars($col['Field']) ?> <span class="col-type"><?= $col['Type'] ?></span></label>
                        <?php if ($col['Key'] === 'PRI' && $col['Extra'] === 'auto_increment'): ?>
                            <input type="text" value="<?= htmlspecialchars($editRow[$col['Field']] ?? '') ?>" disabled style="opacity:.5">
                        <?php elseif (strpos($col['Type'], 'text') !== false || strpos($col['Type'], 'json') !== false): ?>
                            <textarea name="val[]" rows="3"><?= htmlspecialchars($editRow[$col['Field']] ?? '') ?></textarea>
                            <input type="hidden" name="col[]" value="<?= $col['Field'] ?>">
                        <?php else: ?>
                            <input type="text" name="val[]" value="<?= htmlspecialchars($editRow[$col['Field']] ?? '') ?>">
                            <input type="hidden" name="col[]" value="<?= $col['Field'] ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="form-btns">
                    <button type="submit" class="save">💾 Lưu thay đổi</button>
                    <a href="?table=<?= urlencode($table) ?>" class="cancel" style="padding:8px 20px;text-decoration:none;display:inline-block;border-radius:8px">Hủy</a>
                </div>
            </form>
            <?php endif; ?>

            <!-- Insert form -->
            <?php if ($action === 'insert_form'): ?>
            <form method="POST" action="?table=<?= urlencode($table) ?>&action=insert" class="edit-form">
                <h3>➕ Thêm hàng mới vào <strong><?= htmlspecialchars($table) ?></strong></h3>
                <div class="form-grid">
                    <?php foreach ($tableColumns as $col): ?>
                        <label><?= htmlspecialchars($col['Field']) ?> <span class="col-type"><?= $col['Type'] ?></span></label>
                        <?php if ($col['Key'] === 'PRI' && $col['Extra'] === 'auto_increment'): ?>
                            <input type="text" placeholder="Auto" disabled style="opacity:.5">
                        <?php elseif (strpos($col['Type'], 'text') !== false || strpos($col['Type'], 'json') !== false): ?>
                            <textarea name="val[]" rows="2" placeholder="<?= $col['Null'] === 'YES' ? 'NULL (để trống)' : 'Bắt buộc' ?>"></textarea>
                            <input type="hidden" name="col[]" value="<?= $col['Field'] ?>">
                        <?php elseif (strpos($col['Type'], 'enum') !== false): ?>
                            <?php preg_match_all("/'([^']+)'/", $col['Type'], $enumMatches); ?>
                            <select name="val[]">
                                <?php if ($col['Null'] === 'YES'): ?><option value="">-- NULL --</option><?php endif; ?>
                                <?php foreach ($enumMatches[1] as $ev): ?>
                                    <option value="<?= $ev ?>" <?= $col['Default'] === $ev ? 'selected' : '' ?>><?= $ev ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="col[]" value="<?= $col['Field'] ?>">
                        <?php else: ?>
                            <input type="text" name="val[]" placeholder="<?= $col['Default'] !== null ? 'Default: '.$col['Default'] : ($col['Null'] === 'YES' ? 'NULL' : 'Bắt buộc') ?>">
                            <input type="hidden" name="col[]" value="<?= $col['Field'] ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="form-btns">
                    <button type="submit" class="save">➕ Thêm</button>
                    <a href="?table=<?= urlencode($table) ?>" class="cancel" style="padding:8px 20px;text-decoration:none;display:inline-block;border-radius:8px">Hủy</a>
                </div>
            </form>
            <?php endif; ?>

            <!-- Column structure -->
            <details style="margin-bottom:12px">
                <summary style="cursor:pointer;color:#8b949e;font-size:13px;font-weight:600;padding:6px 0">📐 Cấu trúc bảng (<?= count($tableColumns) ?> cột)</summary>
                <div style="overflow-x:auto;margin-top:8px">
                <table>
                    <thead><tr><th>Cột</th><th>Kiểu</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr></thead>
                    <tbody>
                        <?php foreach ($tableColumns as $col): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($col['Field']) ?></strong></td>
                            <td><span class="col-type"><?= htmlspecialchars($col['Type']) ?></span></td>
                            <td><?= $col['Null'] === 'YES' ? '<span class="col-null">✓</span>' : '<span class="col-no">✗</span>' ?></td>
                            <td><?= $col['Key'] === 'PRI' ? '<span class="col-pk">PK</span>' : htmlspecialchars($col['Key']) ?></td>
                            <td><?= htmlspecialchars($col['Default'] ?? 'NULL') ?></td>
                            <td><?= htmlspecialchars($col['Extra']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </details>

            <!-- Data table -->
            <?php if ($tableData && count($tableData) > 0): ?>
            <div style="overflow-x:auto">
                <table>
                    <thead><tr>
                        <th style="width:80px">Thao tác</th>
                        <?php foreach (array_keys($tableData[0]) as $col): ?><th><?= htmlspecialchars($col) ?></th><?php endforeach; ?>
                    </tr></thead>
                    <tbody>
                        <?php foreach ($tableData as $row): ?>
                        <tr>
                            <td>
                                <div class="actions">
                                    <a href="?table=<?= urlencode($table) ?>&action=edit&id=<?= urlencode($row[$tablePK]) ?>" class="edit-btn" title="Sửa">✏️</a>
                                    <form method="POST" action="?table=<?= urlencode($table) ?>&action=delete_row" style="display:inline" onsubmit="return confirm('Xóa hàng <?= $tablePK ?>=<?= $row[$tablePK] ?>?')">
                                        <input type="hidden" name="row_id" value="<?= htmlspecialchars($row[$tablePK]) ?>">
                                        <input type="hidden" name="pk_col" value="<?= $tablePK ?>">
                                        <button type="submit" class="del-btn" title="Xóa">🗑️</button>
                                    </form>
                                </div>
                            </td>
                            <?php foreach ($row as $k => $v): ?>
                                <td title="<?= htmlspecialchars($v ?? '') ?>">
                                    <?php if ($v === null): ?>
                                        <span class="null">NULL</span>
                                    <?php elseif ($k === $tablePK): ?>
                                        <span class="pk"><?= htmlspecialchars($v) ?></span>
                                    <?php else: ?>
                                        <?= htmlspecialchars(mb_substr($v, 0, 80)) ?><?= mb_strlen($v) > 80 ? '…' : '' ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="pager">
                <span>Trang <?= $page ?>/<?= $totalPages ?> (<?= number_format($totalRows) ?> hàng)</span>
                <div class="pager-links">
                    <?php if ($page > 1): ?><a href="?table=<?= urlencode($table) ?>&page=<?= $page-1 ?>">‹</a><?php endif; ?>
                    <?php for ($p = max(1, $page-3); $p <= min($totalPages, $page+3); $p++): ?>
                        <a href="?table=<?= urlencode($table) ?>&page=<?= $p ?>" class="<?= $p === $page ? 'curr' : '' ?>"><?= $p ?></a>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?><a href="?table=<?= urlencode($table) ?>&page=<?= $page+1 ?>">›</a><?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php elseif ($tableData !== null): ?>
                <div class="alert alert-success">Bảng trống, chưa có dữ liệu. <a href="?table=<?= urlencode($table) ?>&action=insert_form" style="color:#58a6ff">Thêm hàng mới →</a></div>
            <?php endif; ?>

        <?php elseif (!$table && !$customQuery): ?>
            <!-- Welcome -->
            <div style="text-align:center;padding:80px 20px;color:#484f58">
                <div style="font-size:48px;margin-bottom:16px">⚡</div>
                <h2 style="color:#e6edf3;font-size:24px;margin-bottom:8px">TLO DB Manager</h2>
                <p style="font-size:14px">Chọn bảng ở sidebar hoặc nhập SQL query để bắt đầu.</p>
                <div style="margin-top:24px;display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
                    <span class="btn-sm">📋 <?= count($tables_list) ?> bảng</span>
                    <a href="?action=export" class="btn-sm btn-blue">📥 Export DB</a>
                    <span class="btn-sm" onclick="document.getElementById('importModal').classList.add('show')" style="cursor:pointer">📤 Import SQL</span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Import Modal -->
<div class="modal-bg" id="importModal">
    <div class="modal">
        <h3>📤 Import SQL</h3>
        <form method="POST" action="?action=import" enctype="multipart/form-data">
            <div style="margin-bottom:12px">
                <label style="display:block;margin-bottom:6px;font-size:13px;color:#8b949e;font-weight:600">Chọn file .sql:</label>
                <input type="file" name="sqlfile" accept=".sql" style="color:#c9d1d9;font-size:13px">
            </div>
            <label style="display:block;margin-bottom:6px;font-size:13px;color:#8b949e;font-weight:600">Hoặc paste SQL:</label>
            <textarea name="sql_content" placeholder="SET FOREIGN_KEY_CHECKS=0;&#10;DROP TABLE IF EXISTS...&#10;CREATE TABLE...&#10;INSERT INTO..."></textarea>
            <div class="modal-btns">
                <button type="button" class="btn-sm" onclick="document.getElementById('importModal').classList.remove('show')">Hủy</button>
                <button type="submit" class="btn-sm btn-green">🚀 Import</button>
            </div>
        </form>
    </div>
</div>

<script>
// Quick fill query
document.querySelectorAll('.sb-list a').forEach(a => {
    a.addEventListener('dblclick', e => {
        e.preventDefault();
        const t = a.textContent.trim().split(/\s/)[0];
        document.querySelector('.toolbar textarea').value = 'SELECT * FROM `' + t + '` LIMIT 100';
    });
});
</script>
</body>
</html>
