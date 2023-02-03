$(function() {
    $('.manager-btn').on('click', function(){
        $('.links-area').toggleClass('active');
    });
    $('.links-back').on('click', function(){
        $('.links-area').toggleClass('active');
    });

    // DBの削除ボタン
	$('.trash-btn').on('click', function(){
		if(confirm('本当に削除しますか？')){
			location.href = 'ex-delete';
		}else{
			return false;
		}
	});

    // カテゴリー追加ボタン
	$('.submit-confirm').on('click', function(){
		if(confirm('追加してよろしいですか？')){
			location.href = 'category-add';
		}else{
			return false;
		}
	});

    // ログアウト
    $('#logout-btn').on('click', function(){
        if(confirm('ログアウトしますか？')){
			location.href = 'login';
		}else{
			return false;
		}
    });
});