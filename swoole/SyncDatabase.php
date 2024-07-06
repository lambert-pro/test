<?php

$onlineDb = new PDO('mysql:host=18.171.80.129;dbname=acquired', 'acquired', 'KHFECKyr01iCwv7T');
$localDb = new PDO('mysql:host=database;dbname=acquired_test_2', 'acquired', 'KHFECKyr01iCwv7T', [
    PDO::ATTR_TIMEOUT => 60,
    PDO::ATTR_PERSISTENT => true
]);

function reconnect($db) {
    $db = null;
    $db = new PDO('mysql:host=database;dbname=acquired_test_2', 'acquired', 'KHFECKyr01iCwv7T');
    return $db;
}

function getTableStructure($db, $tableName)
{
    $structure = [];
    $stmt = $db->query("SHOW CREATE TABLE `$tableName`");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $structure['create_table'] = $row['Create Table'];

    $stmt = $db->query("SHOW INDEX FROM `$tableName`");
    $structure['indexes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $structure;
}

function createTableInLocalDb($localDb, $tableStructure)
{
    $createTableSql = $tableStructure['create_table'];
    $createTableSql = preg_replace('/,\s*CONSTRAINT.*?FOREIGN\s*KEY.*?REFERENCES.*?\)/i', '', $createTableSql);
    $createTableSql = preg_replace('/,\s*FOREIGN\s*KEY.*?REFERENCES.*?\)/i', '', $createTableSql);
    $createTableSql = preg_replace('/\bON DELETE CASCADE\b|\bON UPDATE CASCADE\b/i', '', $createTableSql);

    echo "Executing SQL for table creation:\n$createTableSql\n"; // 输出SQL语句
    $localDb->exec($createTableSql);
}

function createIndexesInLocalDb($localDb, $tableStructure)
{
    foreach ($tableStructure['indexes'] as $index) {
        if ($index['Key_name'] == 'PRIMARY') {
            continue; // 主键已经在CREATE TABLE中创建
        }
        $indexSql = 'CREATE ';
        if ($index['Non_unique'] == 0) {
            $indexSql .= 'UNIQUE ';
        }
        $indexSql .= "INDEX `{$index['Key_name']}` ON `{$index['Table']}` (`{$index['Column_name']}`)";
        echo "Executing SQL for index creation:\n$indexSql\n"; // 输出SQL语句
        $localDb->exec($indexSql);
    }
}

function addForeignKeys($localDb, $tableStructure, $tableName)
{
    $createTableSql = $tableStructure['create_table'];
    preg_match_all('/,\s*(CONSTRAINT.*?FOREIGN\s*KEY.*?REFERENCES.*?\)\s*)/i', $createTableSql, $matches);
    foreach ($matches[1] as $foreignKeySql) {
        // 需要把前面的逗号和空格去掉
        $foreignKeySql = trim($foreignKeySql, ', ');
        echo "Executing SQL for adding foreign key:\nALTER TABLE `$tableName` ADD $foreignKeySql\n"; // 输出SQL语句
        $localDb->exec("ALTER TABLE `$tableName` ADD $foreignKeySql");
    }
}

function logToConsole($message)
{
    echo date('Y-m-d H:i:s')." - $message\n";
}

function insertBatch($db, $table, $batch)
{
    if (empty($batch)) {
        return;
    }

    $columns = array_keys($batch[0]);
    $columnsList = implode(', ', $columns);
    $placeholders = implode(', ', array_fill(0, count($columns), '?'));

    $sql = "INSERT INTO `$table` ($columnsList) VALUES ($placeholders)";
    $stmt = $db->prepare($sql);

    foreach ($batch as $row) {
        $values = array_values($row);
        $stmt->execute($values);
    }
}

function fetchAndInsertData($onlineDb, $localDb, $table, $batchSize)
{
    $offset = 0;
    while (true) {
        $stmt = $onlineDb->prepare("SELECT * FROM `$table` LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $batchSize, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $batch = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($batch)) {
            break; // No more rows to fetch
        }

        insertBatch($localDb, $table, $batch);
        logToConsole('Inserted batch of '.count($batch)." rows into table $table");

        $offset += $batchSize;
    }
}

Swoole\Coroutine\run(function () use ($onlineDb, $localDb) {
    echo "====== Start Database Sync ======\n";
    $tables = $onlineDb->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    echo 'Found '.count($tables)." tables in online database.\n";
    $tableStructures = [];

    // 创建表结构
    foreach ($tables as $table) {
        $tableStructure = getTableStructure($onlineDb, $table);
        createTableInLocalDb($localDb, $tableStructure);
        logToConsole("Created table structure for $table");
        $tableStructures[$table] = $tableStructure;
    }

    // 插入数据
    $batchSize = 1000;
    foreach ($tables as $table) {
        go(function () use ($onlineDb, $localDb, $table, $batchSize) {
            fetchAndInsertData($onlineDb, $localDb, $table, $batchSize);
            logToConsole("Completed syncing table $table.");
        });
    }

    // 等待数据插入完成
    Swoole\Coroutine::sleep(5);

    // 创建索引
    foreach ($tables as $table) {
        createIndexesInLocalDb($localDb, $tableStructures[$table]);
        logToConsole("Created indexes for $table");
    }

    // 添加外键约束
    foreach ($tables as $table) {
        addForeignKeys($localDb, $tableStructures[$table], $table);
        logToConsole("Added foreign keys for $table");
    }
});