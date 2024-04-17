<?php
// POST データが送信されてきたかを確認し、それぞれのデータを変数に格納する
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // データ1
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    
    // データ2
    $relation = isset($_POST['relation']) ? $_POST['relation'] : '';
    
    // データ3
    $category = isset($_POST['category']) ? $_POST['category'] : '';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>友は金なり</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h2 class="title">タイムライン</h2>
        <a class="filter" href="filter.php"><img src="icon/search.svg"></a>
    </header>
    <main>
    <?php
        require_once dirname(__FILE__) . '/functions.php';
        $pdo = connect();
        // 条件文を組み立てる
        $condition = "";

        // データ1の条件を追加する
        if (!empty($name)) {
            $condition .= "datas.name = '" . $name . "'";
        }

        // データ2の条件を追加する
        if (!empty($relation)) {
            // $condition が既に条件を含んでいる場合、AND で結合する
            if (!empty($condition)) {
                $condition .= " AND ";
            }
            $condition .= "datas.relation = '" . $relation . "'";
        }

        // データ3の条件を追加する
        if (!empty($category)) {
            // $condition が既に条件を含んでいる場合、AND で結合する
            if (!empty($condition)) {
                $condition .= " AND ";
            }
            $condition .= "datas.category = '" . $category . "'";
        }
        $datas = getData($pdo, $condition);

        $grouped_data = []; // 日付ごとにグループ化されたデータを格納する配列

        // 日付をキーとしてグループ化
        foreach ($datas as $row) {
            $date = date('Y年m月d日', strtotime($row["ymd"]));
            $groupedData[$date][] = $row;
        }
        
        // グループごとにデータを表示
        foreach ($groupedData as $date => $items) {
            echo "<h3 class='tlDate'>$date</h3>"; // 日付をタイトルとして表示
            foreach ($items as $item) {
                echo "<ul class='tlBox' onclick='redirectToAnotherPage(\"" . $item["id"] . "\", \"" . $item["genre"] . "\", \"" . $item["amoment"] . "\", \"" . $item["name"] . "\", \"" . $item["relation"] . "\", \"" . $item["category"] . "\", \"" . $date . "\", \"" . $item["memo"] . "\")'>";
                $id = $item["id"];
                if ($item["genre"] == "貸付"){
                    $genreColor = "#32BB2F";
                } else {
                    $genreColor = "#E51B1B";
                }
                echo "<li class='tlColor' style='background-color:" . $item["color"] . ";'></li>";
                echo "<li class='tlAmoment' style='color:" . $genreColor . ";'>" . $item["amoment"] . "円</li>";
                echo "<li class='tlName'>" . $item["name"] . "</li>";
                echo "<li class='tlRelation'>" . $item["relation"] . "</li>";
                echo "<li class='tlCategory'>" . $item["category"] . "</li>";
                if (!empty($item["memo"])) {
                    echo "<li class='tlMemo'>（" . truncateMemo($item["memo"]) . "）</li>";
                }
                echo "</ul>";
            }
        }
    ?>
    </main>
    <footer>
        <ul class="menu">
            <li><a href="index.php" class="menuF"><img src="icon/pen_g.svg" alt="入力アイコン">入力</a></li>
            <li><a href="timeline.php" class="menuT"><img src="icon/timeline_b.svg" alt="タイムラインアイコン">タイムライン</a></li>
            <li><a href="setting.php" class="menuF"><img src="icon/setting_g.svg" alt="設定アイコン">設定</a></li>
        </ul>
    </footer>
    <script>
        function redirectToAnotherPage(id, genre, amoment, name, relation, category, date, memo) {
            // memoが存在しない場合は空文字列を代入
            memo = memo || '';

            // 日付のフォーマットを変更する
            var formattedDate = date.replace(/(\d{4})年(\d{1,2})月(\d{1,2})日/, '$1-$2-$3');
            
            // クエリパラメータを正しく構築する
            var url = "timeline_edit.php?id=" + encodeURIComponent(id) + "&genre=" + encodeURIComponent(genre) + "&amoment=" + encodeURIComponent(amoment) + "&name=" + encodeURIComponent(name) + "&relation=" + encodeURIComponent(relation) + "&category=" + encodeURIComponent(category) + "&date=" + encodeURIComponent(formattedDate) + "&memo=" + encodeURIComponent(memo);
            window.location.href = url;
        }
</script>
</body>
</html>
