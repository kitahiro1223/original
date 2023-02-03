<!-- 検証コード -->
<?php
$result = ["a" => "1", "b" => "2", "c" => "3"];

// 結果をまとめたら[print_r()]で表示してみます
// [print_r()]だけだと見にくいので[<pre></pre>]で囲むと改行されます
echo url()->previous(); exit;
echo "<pre>"; print_r($result); echo "</pre>"; exit;
echo 'ここ'; exit;
if(isset($value)) { echo 'isset : true'."<br/>"; }else{echo 'isset : false'."<br/>";}
if(empty($value)) { echo 'empty : true'."<br/>"; }else{echo 'empty : false'."<br/>";}
if(is_null($value)) { echo 'is_null : true'."<br/>"; }else{echo 'is_null : false'."<br/>";}

// リファラ管理
Route::get ("file", function (Request $request) {
    $referer = $_SERVER['HTTP_REFERER'];
    if(preg_match('/file/',$referer) === 0) {
        $request->session()->forget('error');
    }
    $error = $request->session()->get('error');
    return view('', compact('error'));
});
// end リファラ管理

// どこの処理が行われたかわかるように[echo]するのも良いです
if($i >= 10) {
    // 処理1 の記述
    echo "処理1が実行されてるよ!";
}else{
    // 処理2 の記述
    echo "処理2が実行されてるよ!";
    exit;   // [exit;] : 処理をここで終了させられます
}
// 処理3 の記述
echo "処理3が実行されてるよ!";
    // 処理1 の場合、処理は続き 処理3は実行されます
    // 処理2 の場合、処理は[exit;]で止まるため 処理3は実行されません
?>

<div style="color: red">あああ</div>

<!-- 使用するアイコン -->
<div>
    <!-- ログアウト状態 -->
        <!-- ログイン -->
        <i class="icon fa-solid fa-arrow-right-to-bracket"></i>
        <!-- 新規作成 -->
        <i class="icon fa-solid fa-user-plus"></i>
        <!-- 鍵 -->
        <i class="icon fa-solid fa-key"></i>
    <!-- ホーム -->
        <!-- ベル・線 -->
        <i class="icon fa-regular fa-bell"></i>
        <!-- ベル・塗りつぶし -->
        <i class="icon fa-solid fa-bell"></i>
        <!-- 本 -->
        <i class="fa-solid fa-book-open"></i>
        <i class="fa-solid fa-book-open-reader"></i>
        <!-- ぶたの貯金箱 -->
        <i class="icon fa-solid fa-piggy-bank"></i>"f4d3"
        <!-- 財布 -->
        <i class="icon fa-solid fa-wallet"></i>
        <!-- 追加・プラス -->
        <i class="icon fa-solid fa-plus"></i>

    <!-- 所持金 -->
        <!-- 銀行 -->
        <i class="icon fa-solid fa-building-columns"></i>
        <!-- 電子マネー・スマホ -->
        <i class="icon fa-solid fa-mobile-screen-button"></i>
        <!-- カード -->
        <i class="icon fa-regular fa-credit-card"></i>
    <!-- 収入 -->
        <!-- 給料・会社 -->
        <i class="icon fa-solid fa-building"></i>
        <!-- 副業 -->
        <!-- 通勤バッグ -->
        <i class="icon fa-solid fa-briefcase"></i>
        <!-- 家と人 -->
        <i class="icon box-icon fa-solid fa-house-chimney-user"></i>
        <!-- 臨時収入 -->
        <i class="icon fa-solid fa-yen-sign"></i>
    <!-- 支出 -->
        <!-- 食事 -->
        <i class="icon fa-solid fa-utensils"></i>
        <!-- 日用品・ペン・カート -->
        <i class="icon fa-solid fa-pencil"></i>
        <i class="fa-solid fa-cart-shopping"></i>
        <!-- 住まい・家 -->
        <i class="fa-solid fa-house"></i>
    <!-- 編集 -->
        <!-- 詳細・リスト -->
        <i class="icon fa-solid fa-list-ul"></i>
        <!-- 詳細・iマーク -->
        <i class="icon fa-solid fa-circle-info"></i>
        <!-- 編集・ペン -->
        <i class="icon fa-solid fa-pen"></i>
        <!-- 削除・ゴミ箱 -->
        <i class="icon fa-solid fa-trash"></i>

    <!-- 設定 -->
        <!-- 歯車 -->
        <i class="icon box-icon fa-solid fa-gear"></i>
        <!-- 人型 -->
        <i class="icon fa-solid fa-user"></i>
        <!-- ログアウト -->
        <i class="icon fa-solid fa-arrow-right-from-bracket"></i>
    <!-- その他 -->
        <!-- カテゴリー・BOX -->
        <i class="fa-solid fa-box-archive"></i>
        <!-- カテゴリー・ファイル -->
        <i class="fa-solid fa-folder-open"></i>
        <!-- カレンダー -->
        <i class="fa-regular fa-calendar"></i>
</div>

<!-- フォーム -->
<div class="form-area">
    <h2>フォームタイトル</h2>
    <form action="" method="post">
        <div class="form-summary">
            <p>アカウントを新しく作ります。</p>
            <p>メールアドレスとパスワードを入力して下さい。</p>
        </div>
        {{ csrf_field() }}
        <div><input type="" class="" name="" placeholder=""></div>
        <div><input type="submit" class="submit" name="submit" value="送信"></div>
    </form>    
</div><!-- form-area -->

<!-- テキストリンク -->
<div class="text-links">
    <div><a href=""></a></div>
</div>

<style>
.sample {
    filter: 
    invert(100%) /* 反転 */
    sepia(95%) /* セピア */ 
    saturate(6932%) /* 彩度 */ 
    hue-rotate(40deg) /* 色相回転 */ 
    brightness(95%) /* 明度 */ 
    contrast(112%) /* コントラスト */
    ;
}
/* オレンジ */
.orange {
    filter: invert(72%) sepia(26%) saturate(6428%) hue-rotate(1deg) brightness(105%) contrast(102%);
}

/* カレンダー */
.data-individual .date-edit::before{
    content: "";
    width: 24px;
    height: 24px;
    position: absolute;
    top: 14px;
    right: 6px;
    background: url(/img/icon/calendar-gray.png) no-repeat center center /80%;
}

</style>

<!-- 230122 po-edit -->
<!-- <main class="data-individual content-center">
    <div class="form-area login-form">
        <h2>編集</h2>
        <form action="" method="post">
            {{ csrf_field() }}
            <ul>
                <li>
                    <label for="amount">¥</label>
                    <input type="number" name="amount" id="amount" placeholder="0">
                </li>
                <li>
                    <label for="category">カテゴリー</label>
                    <input type="text" name="category" id="category" value="">
                </li>
                <li>
                    <label for="date">日付</label>
                    <label class="date-edit"><input type="date" name="date" id="date" value="2023-01-13"></label>
                </li>
                <li>
                    <label for="notion">通知</label>
                    <div class="radio">
                        <label><input type="radio" name="radio">ON</label>
                        <label><input type="radio" name="radio" checked>OFF</label>
                    </div>
                </li>
                <textarea name="memo" rows="3" placeholder="メモ"></textarea>
            </ul>

            <div><input type="submit" class="submit" name="submit" value="編集"></div>
        </form>
    </div>
</main>
-->
<!-- 230122 detail -->
<!-- <main class="data-individual content-center">
    <div class="form-area login-form">
        <h2>詳細</h2>
        <form action="" method="post">
            {{ csrf_field() }}
            <ul>
                <li>
                    <label for="amount">¥</label>
                    <input type="number" name="amount" id="amount" placeholder="0">
                </li>
                <li>
                    <label for="category">カテゴリー</label>
                    <input type="text" name="category" id="category" value="">
                </li>
                <li>
                    <label for="date">日付</label>
                    <label class="date-edit"><input type="date" name="date" id="date" value="2023-01-13"></label>
                </li>
                <li>
                    <label for="notion">通知</label>
                    <div class="radio">
                        <label><input type="radio" name="radio">ON</label>
                        <label><input type="radio" name="radio" checked>OFF</label>
                    </div>
                </li>
                <textarea name="memo" rows="3" placeholder="メモ"></textarea>
            </ul>

            <div><input type="submit" class="submit" name="submit" value="編集"></div>
        </form>
    </div>
</main> -->

<!-- 支出元コード -->
<a href="">
                <div class="box box-link by-category-box">
                    <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                        <div class="icon-area color3"><!-- アイコンを収める -->
                            <i class="icon box-icon fa-solid fa-utensils"></i>
                        </div>
                    </div>
                    <h3 class="box-name">食事</h3>
                    <p class="box-amount">¥40,000</p>
                </div>
            </a>
            <a href="">
                <div class="box box-link by-category-box">
                    <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                        <div class="icon-area color5"><!-- アイコンを収める -->
                            <i class="icon box-icon fa-solid fa-cart-shopping"></i>
                        </div>
                    </div>
                    <h3 class="box-name">日用品</h3>
                    <p class="box-amount">¥13,000</p>
                </div>
            </a>
            <a href="">
                <div class="box box-link by-category-box">
                    <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                        <div class="icon-area color1"><!-- アイコンを収める -->
                            <i class="icon box-icon fa-solid fa-house"></i>
                        </div>
                    </div>
                    <h3 class="box-name">住まい</h3>
                    <p class="box-amount">¥70,000</p>
                </div>
            </a>

<!-- 追加ボタン -->
    <form action="ex-add" method="POST" class="data-btn-form">
        {{ csrf_field() }}
        <div class="box add-btn box-link">
            <div class="icon-area"><!-- アイコンを収める -->
                <i class="icon fa-solid fa-plus"></i>
            </div>
            <h3>追加</h3>
        </div>
        <input type="submit" class="data-btn-submit" value="" >
    </form>

<!-- add POST 確認 -->
<?php 
    'amount';
    'add_category_id';
    'date';
    'ex_source';
    'radio';
    'comment';
    'add_submit';
    print_r($add_submit);
    print_r($add_submit['amount']);
    print_r($add_submit['add_category_id']);
    print_r($add_submit['date']);
    print_r($add_submit['ex_source']);
    print_r($add_submit['radio']);
    print_r($add_submit['comment']);
    print_r($add_submit['add_submit']);
?>

<!-- POSTで送られてくる全データはこちら -->
$add_data
(
    [_token] => tQqs5KyfJ5ChghtKw8ORgh4uPIiaWh8woF7S5oh7
    [amount] => 100
    [add_category_id] => 1
    [date] => 2023-01-26
    [ex_source] => 1
    [radio] => 0
    [comment] => あああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ
    [add_submit] => 追加
)

{
    <main class="data-individual content-center">
    <div class="form-area login-form">
        <h2>追加の確認</h2>
        <form action="ex-add" method="post">
            {{ csrf_field() }}
            <ul>
                <li>
                    <label for="amount">¥</label>
                    <div class="">{{ $add_data['amount'] }}</div>
                    <input type="hidden" name="amount" id="amount" value="{{ $add_data['amount'] }}">
                </li>
                <li>
                    <label for="category"><i class="fa-solid fa-folder-open" title="カテゴリー"></i></label>
                    <div class="add_category_area">
                        <div class="icon-area color5" style="margin: 0 6px 0 0;"><!-- アイコンを収める -->
                            <i class="{{ $add_category['icon'] }}"></i>
                        </div>
                        <p class="add_category_name">{{ $add_category['name'] }}</p>
                    </div>
                    <input type="hidden" name="add_category_id" value="{{ $add_data['add_category_id'] }}">
                </li>
                <li>
                    <label for="date" title="日付"><i class="fa-regular fa-calendar"></i></label>
                    <div class="">{{ $add_data['date'] }}</div>
                    <input type="hidden" name="date" id="date" value="{{ $add_data['date'] }}">
                </li>
                <li>
                    <label for="ex_source" title="支出元"><i class="fa-solid fa-wallet"></i></label>
                    <select name="ex_source" id="ex-source">
                        <option value="">支出元を選択</option>
                        <?php foreach ($ex_sources as $ex_source): ?>
                            <option value="{{ $ex_source['main_category_id'] }}">{{ $ex_source['main_category_name'] }}</option>
                        <?php endforeach; ?>
                    </select>
                </li>
                <li>
                    <label for="notion" title="通知"><i class="fa-solid fa-bell"></i></label>
                    <div class="radio">
                        <label><input type="hidden" name="radio" value="1">ON</label>
                        <label><input type="hidden" name="radio" value="0" checked>OFF</label>
                    </div>
                </li>
                <textarea name="comment" rows="3" placeholder="メモ"></textarea>
            </ul>

            <div><input type="submit" class="submit" name="add_submit" value="追加"></div>
        </form>
    </div><!-- form-area -->
</main>
<div class="content-space"></div>
}

<!-- ex-add の 編集前のカテゴリーアイコン廃止 -->
<div class="add_category_area">
    <div class="icon-area color5" style="margin: 0 6px 0 0;"><!-- アイコンを収める -->
        <i class="{{ $add_data['icon'] }}"></i>
    </div>
    <p class="add_category_name">{{ $add_category['name'] }}</p>
</div>

// Laravelのログイン機能
login
register
verify

// Laravelのパスワード機能
confirm
email
reset

ygfp tmev opou kzps

Route::get('/mail/send', [MailController::class, 'send']);
Route::get('/mail/sendMail', [MailController::class, 'sendMail']);

use App\Http\Controllers\MailController;

// 「パスワードを忘れた」ビューからのフォーム送信リクエストを処理するルート
    Route::post('password/email', function (Request $request) {
        // echo "読み込まれています。";
        // $request->validate(['email' => 'required|email']);

        // echo $request->only('email');
        // exit;

        // $to = "kitahiro1223f@gmail.com";
        // $subject = "TEST";
        // $message = "This is TEST.\r\nHow are you?";
        // $headers = "From: from@example.com";
        // mail($to, $subject, $message, $headers);
        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        // return $status === Password::RESET_LINK_SENT
        //             ? back()->with(['status' => __($status)])
        //             : back()->withErrors(['email' => __($status)]);
    })->middleware('guest')->name('password.email');


    echo 'if($email) { else';
    exit;
    $error =  'メールアドレスを入力して下さい。';
    $request->session()->put('error.email', $error);
    exit;

<!-- BooksController -->
// public function verify()    { return view("auth.verify"); }

// Laravelのパスワード機能
// public function confirm()   { return view("auth.passwords.confirm"); }
// public function password_email(Request $request) {return view("auth.passwords.email");}
// public function reset()     { return view("auth.passwords.reset"); }

// 新規登録
// public function signup()             { return view("signup.signup"); }
// public function signup_confirm()     { return view("signup.signup-confirm"); }
// public function signup_complete()    { return view("signup.signup-complete"); }

// パスワードリセット
// public function pass(Request $request) { 
//     if (!empty($request)) {
//         // 入力されたメールアドレスをバリデーション
//         if (empty($request)) {
//             $error =  'メールアドレスを入力して下さい。';
//             $request->session()->put('error.email', $error);
//         }
//         $request->validate(['email' => 'required|email']);
//         $user_email = $request->only('email');

//         // 入力されたメールアドレスをもとにDBのユーザ情報を絞り込み
//         $user_info = User::query()
//             ->select("email")
//             ->where("email", "=", $user_email)
//             ->get();

//         echo "<pre>";
//         print_r($user_info);
//         echo "</pre>";
//         echo "<pre>";
//         isset($user_info);
//         echo "</pre>";
//         echo "<pre>";
//         empty($user_info);
//         echo "</pre>";
//         exit;
//     }

//     $error['email'] = $request->session()->get('error.email');

//     return view("password-reset.pass"); 
// }
// public function pass_confirm()   { return view("password-reset.pass-confirm"); }

// 支出
    // public function ex_category()    { return view("expenditure.ex-category"); }
    // public function ex_detail()      { return view("expenditure.ex-detail"); }
    // public function ex_edit()        { return view("expenditure.ex-edit"); }

// ログアウト
    // public function logout() {
    //     // ユーザーidがなければログイン画面へ
    //     $user_id = $request->session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
    //     if (!isset($user_id)){ return redirect('login'); exit;}
    //     return view("setting.logout"); 
    // }

    // public function logout(Request $request) {
    //     // ユーザーidがなければログイン画面へ
    //     $user_id = $request->session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
    //     if (!isset($user_id)){ return redirect('login'); exit;}

    //     $request->session()->flush();

    //     return redirect("login"); 
    //     exit;
    // }

<!-- web.php -->

// Route::get("verify",    [BooksController::class, 'verify' ]);
// パスワードリセット
// Route::get ("password-email",   function () {return view('auth.passwords.email');exit;});
// Route::post("password-email",   function () {return view('auth.passwords.email');});
// Route::get ("reset",            [BooksController::class, 'reset' ]);
// Route::get ("confirm",          [BooksController::class, 'confirm' ]);

Route::get('/linelogin',    [LineLoginController::class, 'lineLogin'])->name('linelogin');
Route::get('/callback',     [LineLoginController::class, 'callback' ])->name('callback');

// LINE メッセージ受信
Route::post('/line/webhook', [LineMessengerController::class, 'webhook'])->name('line.webhook'); 
// LINE メッセージ送信用
Route::get('/line/message',  [LineMessengerController::class, 'message']);


// Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


<!-- アイコンありログアウトボタン -->
<!-- <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" id="logout-btn">
    <a class="dropdown-item" href="logout_check"
        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
        <div class="icon-area header-icon color2">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </div>
        <div class="h-item-name">{{ __('ログアウト') }}</div>
    </a>

    <form id="logout-form" action="logout_check" method="POST" class="d-none">
        @csrf
    </form>
</div> -->

// Route::get('welcome', function () {return view('welcome');});
// Laravelのログイン機能
// Route::get("login-email",     [BooksController::class, 'login' ]);
// Route::get("register",  [BooksController::class, 'register' ]);

// Route::get("home",  [BooksController::class, 'index' ]);

// 新規登録
// Route::get("signup",            [BooksController::class, 'signup'            ]);
// Route::get("signup-confirm",    [BooksController::class, 'signup_confirm'    ]);
// Route::get("signup-complete",   [BooksController::class, 'signup_complete'   ]);

// パスワードリセット
// Route::get ("pass", function (Request $request) {
//     $request->session()->forget('token');
//     $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
//     if(
//         preg_match('/pass/',$referer) === 0 ||
//         preg_match('/pass-/',$referer) === 1) {
//         $request->session()->forget('error');
//     }
//     $error = $request->session()->get('error');
//     return view('password-reset.pass', compact('error'));
// });
// Route::post("pass", function (Request $request) {
//     // 入力されたメールアドレスをバリデーション
//     $request->validate(['email' => 'required|email']);
//     $email = $request->get('email');

//     if($email) { 
//         // DBのユーザ情報入手
//         $all_data = User::query()
//             ->select("email")
//             ->get();

//         foreach ($all_data as $data) {
//             $user_emails[] = $data['email'];
//         }

//         if(in_array($email , $user_emails)){
//             $request->session()->put('token', 'reset-token');
//             $request->session()->put('email', $email);
//             return redirect('pass-reset');
//             exit;
//         } else {
//             $error =  'ユーザー情報を確認できませんでした。登録が済んでいない場合は新規登録をお願いします。';
//             $request->session()->put('error.email', $error);            
//             return redirect('pass');
//             exit;
//         }
//     }
        
//     return view('password-reset.pass', compact('error'));
// });
// Route::get ("pass-reset",       [BooksController::class, 'pass_reset'       ]);
// Route::post("pass-reset",       [BooksController::class, 'pass_confirm'     ]);
// Route::get ("pass-complete",     [BooksController::class, 'pass_complete'   ]);

<!-- BooksCon -->
// Laravelのログイン機能
    public function login(Request $request) { 
        $request->session()->flush();
        $error = $request->session()->get('error');
        return view("login", compact('error')); 
    }
    public function register()  { return view("auth.register"); }

    public function pass_reset(Request $request) { 
        $token = $request->session()->get('token');
        if ($token != 'reset-token'){
            return redirect('pass');
            exit;
        }
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
        if(preg_match('/pass-reset/',$referer) === 0) {
            $request->session()->forget('error');
        }
        $error = $request->session()->get('error');
        return view("password-reset.pass-reset", compact('error')); 
    }
    public function pass_confirm(Request $request) { 
        $pass       = My_function::xss($request->get('pass'));
        $pass_conf  = My_function::xss($request->get('pass-conf'));

        $request->session()->forget('error');
        if(!$pass || !$pass_conf) {
            $error = 'パスワードを入力してください。';
            $request->session()->put('error.pass_input', $error);
            return redirect('pass-reset');
            exit;
        } elseif ($pass === $pass_conf) {
            // 入力値が等しければsessionに入れてpass-completeへ
            $request->session()->put('reset-pass', $pass);
            return redirect('pass-complete');
            exit;
        } elseif ($pass !== $pass_conf) {
            $error = 'パスワードが一致していません。';
            $request->session()->put('error.pass_match', $error);
            return redirect('pass-reset');
            exit;
        }
        $error = $request->session()->get('error');
        return view("password-reset.pass-reset", compact('error')); 
    }
    public function pass_complete(Request $request)  {
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
        if(preg_match('/pass-reset/',$referer) === 0) {
            return redirect('pass');
            exit;
        }
        $request->session()->forget('token');
        $email          = $request->session()->pull('email');
        $new_password   = $request->session()->pull('reset-pass');
        $new_password   = Hash::make($new_password);

        User::query()
            ->where('email', $email)
            ->update(['password' => $new_password]);

        return view("password-reset.pass-complete"); 
    }

    <!-- expenditureController -->
    foreach ($expenditures as $expenditure) {
            $sub_category_sum[] = $expenditure->sub_category_sum;   
        }
        // foreach ($expenditures as $expenditure){
        //     echo $expenditure['icon']."<br/>";
        // }
        // $expenditure['main_category_id']    = $expenditure->main_category_id;   
        // $expenditure['main_category_name']  = $expenditure->main_category->name;   
        // $expenditure['icon']                = $expenditure->icon->code;   
        // $expenditure['sub_category_sum']    = $expenditure->sub_category_sum;   
        

        if(empty($expenditures)) { 
            $expenditures['amounts'] = 0;
        }else{
            $expenditures['amounts'] = number_format(array_sum($sub_category_sum));
        }


    @if(!empty($expenditure))

    <!-- sub_category -->
    // $all_data = Expenditure::query()
        //     ->where("user_id", "=", $user_id)
        //     ->where("main_category_id", "=", $main_category['id'])
        //     ->select("main_category_id", "sub_category_id","main_category_id AS icon_id")
        //     ->selectRaw('SUM(amount) AS by_category_sum')
        //     ->groupby("main_category_id", "sub_category_id")
        //     ->get();


// $data_one['main_category_id']   = $data_ob->main_category_id;
// $data_one['sub_category_id']    = $data_ob->sub_category_id;
// $data_one['icon_id']            = $data_ob->icon_id;

<!--  -->
// foreach ($all_data as $data) {
//     echo "<pre>";
//     print_r($data);
//     echo "</pre>";
//     $ex_sub_category['sub_category_id']     = $data->sub_category_id;
//     $ex_sub_category['sub_category_name']   = $data->sub_category->name;
//     $ex_sub_category['icon']                = $data->icon->code;
//     $ex_sub_category['by_category_sum']     = $data->by_category_sum;
//     $ex_sub_categories[] = $ex_sub_category;
// }
// exit;

<!--  -->
            <?php foreach ($possessions as $possession): ?>
                <a href="po-category">
                    <div class="box box-link by-category-box">
                        <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                            <div class="icon-area"><!-- アイコンを収める -->
                                <i class="box-icon {{ $possession->icon->code }}"></i>    
                            </div>
                        </div>
                        <h3 class="box-name">{{ $possession->main_category->name }}</h3>
                        <p class="box-amount">{{ "¥".number_format($possession->amount) }}</p>
                    </div>
                </a>
            <?php endforeach; ?>
<!--  -->

// 値がなければ 0 を代入
    if(empty($common_sum['food'])) {$common_sum['food'] = 0;}
    if(empty($common_sum['daily'])) {$common_sum['daily'] = 0;}
    if(empty($common_sum['house'])) {$common_sum['house'] = 0;}        


    <div class="add_category_area">
        @if (!empty($add_data['icon']) && !empty($add_data['main_category_name']))
            <div class="icon-area color5" style="margin: 0 6px 0 0;"><!-- アイコンを収める -->
                <i class="{{ $add_data['icon'] }}"></i>
                <input type="hidden" name="icon" value="{{ $add_data['icon'] }}">
            </div>
            <p class="add_category_name">{{ $add_data['main_category_name'] }}</p>
            <input type="hidden" name="main_category" value="{{ $add_data['main_category_name'] }}">
        @else
            <select name="main_category" id="add_main_category" style="width: auto; border-bottom: 1px solid var(--accent-color)">
                @foreach ($add_main_categories as $add_main_category)
                    <option value="{{ $add_main_category['name'] }}">{{ $add_main_category["name"] }}</option>
                @endforeach
            </select>
        @endif
        <p style="padding: 0 24px;">></p>
        <select name="sub_category" id="add_sub_category" style="width: auto;">
            @if (!empty($add_data['sub_category_id'])) 
                <option value="{{ $add_data['sub_category_name'] }}">{{ $add_data['sub_category_name'] }}</option>
            @endif
            @foreach ($add_sub_categories as $add_sub_category)
                @if (!empty($add_data['sub_category_id'])) 
                    @if ($add_data['sub_category_name'] != $add_sub_category['name']) 
                        <option value="{{ $add_sub_category['name'] }}">{{ $add_sub_category["name"] }}</option>
                    @endif
                @else
                    <option value="{{ $add_sub_category['name'] }}">{{ $add_sub_category["name"] }}</option>
                @endif    
            @endforeach
        </select>
    </div>

    <label for="category"><i class="fa-solid fa-folder-open" title="カテゴリー"></i></label>
    <div class="add_category_area">
        <select name="sub_category" id="add_sub_category" style="width: auto;">
            <option value="">振替元を選択して下さい</option>
            @foreach ($add_main_categories as $add_main_category)
                <optgroup label="{{ $add_main_category['name'] }}">
                    @foreach ($add_sub_categories as $add_sub_category)
                        @if (!empty($add_data['sub_category_id'])) 
                            @if ($add_data['sub_category_name'] != $add_sub_category['name']) 
                                <option value="{{ $add_sub_category['name'] }}">{{ $add_sub_category["name"] }}</option>
                            @endif
                        @elseif ($add_sub_category['main_category_id'] == $add_main_category['id'])
                            <option value="{{ $add_sub_category['name'] }}">{{ $add_sub_category["name"] }}</option>
                        @endif    
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </div>


    if($kind['id'] != 3){
        return redirect('categories'); exit;
    }elseif($kind['id'] == 3){
        return redirect('category-kinds'); exit;
    }

        $kind = $request->session()->get("kind");

        if(empty($kind)){
            return view("setting.category_kinds"); 
        }elseif($kind['id'] == 3){
            // ユーザーの且つ既定の各機能のsubカテゴリ("id", "name")を取得
            $sub_cate_obs = Sub_category::query()
                ->select("id", "user_id", "name", "icon_id")
                ->where("kind_id", "=", $kind['id'])
                ->where("del_flg", "=", 0)
                ->where(function($query) use ($user_id) {
                    $query
                        ->where("user_id", "=", 0)
                        ->orwhere("user_id", "=", $user_id);})
                ->get();        

                foreach ($sub_cate_obs as $sub_cate_ob) {
                    $sub_cate['id']   = $sub_cate_ob['id'];
                    $sub_cate['user_id'] = $sub_cate_ob['user_id'];
                    $sub_cate['name'] = $sub_cate_ob['name'];
                    $sub_cate['icon_id'] = $sub_cate_ob['icon_id'];
                    $sub_cate['icon'] = $sub_cate_ob->icon->code;
                    $sub_cates[] = $sub_cate;
                } 
                $request->session()->put("sub_cates", $sub_cates);
            // end カテゴリ     
            return view("setting.category_kinds", compact('kind', 'sub_cates'));


    @elseif(!empty($kind) && $kind['id'] == 3)
    <form action="category-kinds" method="post">
        <div class="by-category-inner">
            <div class="box-list">
                @foreach($sub_cates as $sub_cate)
                <div>
                    <form action="category-kinds" method="POST" class="box box-link by-category-box main_category_form">
                        @csrf
                        <div class="box box-link by-category-box" style="position: relative;">
                            <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                                <div class="icon-area"><!-- アイコンを収める -->
                                    <i class="box-icon"></i>
                                </div>
                            </div>
                            <h3 class="box-name box-2-items">所持金</h3>
                            <input type="hidden" name="kind_id" value="1">
                            <input type="hidden" name="kind_name" value="所持金">
                            <input type="submit" name="kind_submit" class="main_category_btn" value="">
                        </div>
                    </form>
                </div>
                @endforeach
            </div><!-- .box-list -->
        </div><!-- .by-category-inner -->
    </form>
    @endif


    <!-- 日時指定と振替 -->
    <div class="box-list date-add-area">
        <div class="box date-box">
            <h3>日付指定</h3>
        </div>
        <a href="po-add" class="add-btn-form box-link">
            <div class="box add-btn">
                <div class="icon-area"><!-- アイコンを収める -->
                    <i class="icon fa-solid fa-plus"></i>
                </div>
                <h3>振替</h3>
            </div>
        </a>
    </div>