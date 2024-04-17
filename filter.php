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
        <h2 class="title">絞り込み機能</h2>
        <p class="backBtn"><a href="timeline.php">閉じる</a></p>
    </header>
    <main>
        <form action="timeline.php" method="POST">
            <table class="marginTop150">
                <tr class="box">
                    <th>名前</th>
                    <td>
                        <?php
                            require_once dirname(__FILE__) . '/functions.php';

                            $pdo = connect();
                            $table = 'names';
                            $pulldownData = getPulldownData($pdo, $table);

                            // 初期値として "なし" を追加するための配列を作成
                            $options = ['なし'];

                            // プルダウンメニューのデータが取得できた場合、取得したデータを追加する
                            if (!empty($pulldownData)) {
                                foreach ($pulldownData as $row) {
                                    $options[] = htmlspecialchars($row['name']);
                                }
                            }

                            // プルダウンメニューの生成
                            echo '<select name="name">';
                            foreach ($options as $option) {
                                // データベースから取得した情報を元に、プルダウンメニューのオプションを生成する
                                if ($option === 'なし') {
                                    echo '<option value="">なし</option>';
                                } else {
                                    echo '<option value="' . htmlspecialchars($option) . '">' . htmlspecialchars($option) . '</option>';
                                }
                            }
                            echo '</select>';
                        ?>
                    </td>
                </tr>
                <tr class="box">
                    <th>関係性</th>
                    <td>
                        <?php
                            require_once dirname(__FILE__) . '/functions.php';

                            $pdo = connect();
                            $table = 'relations';
                            $pulldownData = getPulldownData($pdo, $table);

                            // 初期値として "なし" を追加するための配列を作成
                            $options = ['なし'];

                            // プルダウンメニューのデータが取得できた場合、取得したデータを追加する
                            if (!empty($pulldownData)) {
                                foreach ($pulldownData as $row) {
                                    $options[] = htmlspecialchars($row['relation']);
                                }
                            }

                            // プルダウンメニューの生成
                            echo '<select name="relation">';
                            foreach ($options as $option) {
                                // データベースから取得した情報を元に、プルダウンメニューのオプションを生成する
                                if ($option === 'なし') {
                                    echo '<option value="">なし</option>';
                                } else {
                                    echo '<option value="' . htmlspecialchars($option) . '">' . htmlspecialchars($option) . '</option>';
                                }
                            }
                            echo '</select>';
                        ?>
                    </td>
                </tr>
                <tr class="box">
                    <th>カテゴリー</th>
                    <td>
                        <?php
                            require_once dirname(__FILE__) . '/functions.php';

                            $pdo = connect();
                            $table = 'categorys';
                            $pulldownData = getPulldownData($pdo, $table);

                            // 初期値として "なし" を追加するための配列を作成
                            $options = ['なし'];

                            // プルダウンメニューのデータが取得できた場合、取得したデータを追加する
                            if (!empty($pulldownData)) {
                                foreach ($pulldownData as $row) {
                                    $options[] = htmlspecialchars($row['category']);
                                }
                            }

                            // プルダウンメニューの生成
                            echo '<select name="category">';
                            foreach ($options as $option) {
                                // データベースから取得した情報を元に、プルダウンメニューのオプションを生成する
                                if ($option === 'なし') {
                                    echo '<option value="">なし</option>';
                                } else {
                                    echo '<option value="' . htmlspecialchars($option) . '">' . htmlspecialchars($option) . '</option>';
                                }
                            }
                            echo '</select>';
                        ?>
                    </td>
                </tr>
            </table>
            <input class="submit" type="submit" value="保存">
        </form>
    </main>
</body>
</html>