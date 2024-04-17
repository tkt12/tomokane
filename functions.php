<?php

declare(strict_types=1);

/**
 * PDOインスタンスを取得する関数
 */
function connect(): PDO {
    // $pdo = new PDO('mysql:host=mysql57.studio4-design.sakura.ne.jp; dbname=studio4-design_tomokane; charset=utf8mb4', 'studio4-design', 'studio4-tomokane');
    $pdo = new PDO('mysql:host=localhost:8889; dbname=tomokane; charset=utf8mb4', 'tk', '12345');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $pdo;
}

/**
 * HTMLエスケープする関数
 */
function escape(?string $value){
    return htmlspecialchars(strval($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * プルダウン選択肢取得
 */
function getPulldownData($pdo, $table) {
    $sql = "SELECT * FROM $table";

    $result = $pdo->query($sql);

    if ($result === false) {
        // クエリの実行に失敗した場合の処理
        echo "クエリの実行に失敗しました: " . $pdo->errorInfo()[2];
        return array(); // 空の配列を返す
    }

    $data = $result->fetchAll(PDO::FETCH_ASSOC); // 結果セット全体を配列に取得

    return $data;
}

/**
* タイムライン表示関数
*/
function getData($pdo, $condition) {
    $sql = "SELECT datas.id,datas.genre,datas.amoment,datas.name,names.color,datas.relation,datas.category,datas.ymd,datas.memo FROM datas INNER JOIN names ON datas.name = names.name";

    // 条件が指定されている場合はクエリに追加
    if (!empty($condition)) {
        $sql .= " WHERE " . $condition; // .=で$sqlに連結
    }

    $sql .= " ORDER BY ymd DESC";

    $result = $pdo->query($sql);

    if ($result === false) {
        // クエリの実行に失敗した場合の処理
        echo "クエリの実行に失敗しました: " . $pdo->errorInfo()[2];
        return array(); // 空の配列を返す
    }

    $data = $result->fetchAll(PDO::FETCH_ASSOC); // 結果セット全体を配列に取得

    return $data;
}

/**
 * タイムライン更新
 */
function timelineEdit($pdo, $id, $genre, $amoment, $name, $relation, $category, $ymd, $memo) {
    $sql = "UPDATE datas SET genre = '$genre', amoment = $amoment, name = '$name', relation = '$relation', category = '$category', ymd = '$ymd', memo = '$memo' WHERE id = $id";

    $result = $pdo->query($sql); 

    if ($result === false) {
        // クエリの実行に失敗した場合の処理
        echo "クエリの実行に失敗しました: " . $pdo->errorInfo()[2];
        return array(); // 空の配列を返す
    }

    $data = $result->fetchAll(PDO::FETCH_ASSOC); // 結果セット全体を配列に取得

    return $data;
}

/**
 * タイムライン削除
 */
function deleteTimeline($pdo, $id) {
    try {
        $sql = "DELETE FROM datas WHERE id = '$id'";

        $result = $pdo->exec($sql); // exec() を使って実行し、結果セットを取得しない
    
        if ($result === false) {
            // クエリの実行に失敗した場合の処理
            echo "クエリの実行に失敗しました: " . $pdo->errorInfo()[2];
            return false; // false を返す
        }
        return true; // 成功したことを示す true を返す    
    } catch(PDOException $e) {
        return false;
    }
}

/**
 * 編集表示関数
 */
function getEditData($pdo, $table, $column, $condition) {
    $sql = "SELECT * FROM $table";
    // 条件が指定されている場合はクエリに追加
    if (!empty($condition)) {
        $sql .= " WHERE $column = '$condition'"; // .=で$sqlに連結
    }

    $result = $pdo->query($sql);

    if ($result === false) {
        // クエリの実行に失敗した場合の処理
        echo "クエリの実行に失敗しました: " . $pdo->errorInfo()[2];
        return array(); // 空の配列を返す
    }

    $data = $result->fetchAll(PDO::FETCH_ASSOC); // 結果セット全体を配列に取得

    return $data;
}
/**
 * 更新関数
 */
function updateData($pdo, $table, $column, $newValue, $condition) {
    try {
        // トランザクションを開始
        $pdo->beginTransaction();

        // 外部キー制約を一時的に無効にする
        $pdo->exec('SET FOREIGN_KEY_CHECKS=0');

        // 指定された行の情報を取得
        $result = $pdo->prepare("SELECT * FROM $table WHERE $column = :value");
        $result->bindParam(':value', $condition);
        $result->execute();
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);

        // 指定された行の情報を更新
        $result = $pdo->prepare("UPDATE $table SET $column = :newValue WHERE $column = :value");
        $result->bindParam(':newValue', $newValue);
        $result->bindParam(':value', $condition);
        $result->execute();

        // 関連する行を更新
        if($column != "color"){
            foreach ($rows as $row) {
                $result = $pdo->prepare("UPDATE datas SET $column = :newValue WHERE $column = :value");
                $result->bindParam(':newValue', $newValue);
                $result->bindParam(':value', $row[$column]);
                $result->execute();
            }
        }

        // 外部キー制約を再度有効にする
        $pdo->exec('SET FOREIGN_KEY_CHECKS=1');

        // トランザクションをコミット
        $pdo->commit();
    } catch (PDOException $e) {
        // エラーが発生した場合はロールバック
        $pdo->rollBack();
        throw $e;
    }
}

/**
 * 削除関数
 */
function deleteData($pdo, $table, $column, $condition) {
    try {
        $sql = "DELETE FROM $table WHERE $column = '$condition'";

        $result = $pdo->exec($sql); // exec() を使って実行し、結果セットを取得しない
    
        if ($result === false) {
            // クエリの実行に失敗した場合の処理
            echo "クエリの実行に失敗しました: " . $pdo->errorInfo()[2];
            return false; // false を返す
        }
        return true; // 成功したことを示す true を返す    
    } catch(PDOException $e) {
        return false;
    }
}
/**
 * メモ表示関数
 */ 
function truncateMemo($memo) {
    $maxLength = 8;
    if (mb_strlen($memo) > $maxLength) {
        return mb_substr($memo, 0, $maxLength - 1) . '...';
    } else {
        return $memo;
    }
}
/**
 * 設定→名前
 */
function getName($pdo) {
    $sql = "SELECT name from names";

    $result = $pdo->query($sql);

    if ($result === false) {
        // クエリの実行に失敗した場合の処理
        echo "クエリの実行に失敗しました: " . $pdo->errorInfo()[2];
        return array(); // 空の配列を返す
    }

    $data = $result->fetchAll(PDO::FETCH_ASSOC); // 結果セット全体を配列に取得

    return $data;
}

/**
 * 設定→関係性
 */
function getRelation($pdo) {
    $sql = "SELECT relation from relations";

    $result = $pdo->query($sql);

    if ($result === false) {
        // クエリの実行に失敗した場合の処理
        echo "クエリの実行に失敗しました: " . $pdo->errorInfo()[2];
        return array(); // 空の配列を返す
    }

    $data = $result->fetchAll(PDO::FETCH_ASSOC); // 結果セット全体を配列に取得

    return $data;
}

/**
 * 設定→カテゴリー
 */
function getCategory($pdo) {
    $sql = "SELECT category from categorys";

    $result = $pdo->query($sql);

    if ($result === false) {
        // クエリの実行に失敗した場合の処理
        echo "クエリの実行に失敗しました: " . $pdo->errorInfo()[2];
        return array(); // 空の配列を返す
    }

    $data = $result->fetchAll(PDO::FETCH_ASSOC); // 結果セット全体を配列に取得

    return $data;
}

/**
 * 名前追加
 */
 function insertN($pdo, $table, $column1, $column2, $value1, $value2) {
    // INSERT文を生成する
    $sql = "INSERT INTO $table ($column1, $column2) VALUES ('$value1', '$value2')";
    $result = $pdo->query($sql);
    return $result;
}

/**
 * 関係性、カテゴリー追加
 */
function insertR_C($pdo, $table, $column, $value) {
    // INSERT文を生成する
    $sql = "INSERT INTO $table ($columnName) VALUES ('$value')";
    $result = $pdo->query($sql);
    return $result;
}

?>