$(function () {
    var webStorage = function () {
        if (sessionStorage.getItem('access')) {
            // 2回目以降の訪問時の処理
            $(".logo_fadein").addClass('is-active');
        } else {
            sessionStorage.setItem('access', 'true'); // sessionStorageにデータを保存
            setTimeout(function(){
                $('.logo_fadein p').fadeIn(1000); // ロゴをフェードインする時間を1000ミリ秒（1秒）に変更
            }, 500); // 0.5秒後にロゴをフェードイン！

            setTimeout(function(){
                $('.logo_fadein').fadeOut(1000, function() {
                    // フェードアウト完了後に要素を非表示にする
                    $(".logo_fadein").addClass('is-active');
                });
            }, 2500); // 2.5秒後にロゴ含め真っ白背景をフェードアウト！
        }
    }
    webStorage();
});
