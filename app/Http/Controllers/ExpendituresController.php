<?php

namespace App\Http\Controllers;

use App\Models\Expenditure;
use Illuminate\Http\Request;

use App\Lib\My_function;
use App\Models\Possession;
use App\Models\Main_category;
use App\Models\Sub_category;
use App\Models\Icon;

class ExpendituresController extends Controller
{
    public function expenditure(Request $request) { 
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        $request->session()->forget([
            'main_category',
            'sub_category',
            'sub_cate',
            'main',
            'ex_sources',
            'in_to',
            'in_tos',
            'add_data',
            'add_main_categories',
            'add_sub_categories',
            'edit_data',
            'error'
        ]);        
        
        // 既定カテゴリ
        // ユーザーの且つ既定mainの収入カテゴリと各合計の取得（$common_all_data）
        $common_all_data = Expenditure::query()
            ->select("main_category_id")
            ->selectRaw('SUM(amount) AS main_category_sum')
            ->where("user_id", "=", $user_id)
            ->where("main_category_id", "<=", 9)
            ->where("del_flg", "=", 0)
            ->groupby("main_category_id")
            ->get();
        
        // 既定カテゴリごとの合計値を$common_sum へ
        foreach ($common_all_data as $common_data) {
            if($common_data['main_category_id'] == 7) {
                $common_sum['food'] = $common_data['main_category_sum'];
            }
            if($common_data['main_category_id'] == 8) {
                $common_sum['daily'] = $common_data['main_category_sum'];
            }
            if($common_data['main_category_id'] == 9) {
                $common_sum['house'] = $common_data['main_category_sum'];
            }
        }
        // 値がなければ 0 を代入
        if(empty($common_sum['food'])) {$common_sum['food'] = 0;}
        if(empty($common_sum['daily'])) {$common_sum['daily'] = 0;}
        if(empty($common_sum['house'])) {$common_sum['house'] = 0;}

        // 既定カテゴリの総計を計算
        $common_sum['sum'] = array_sum($common_sum);
        
        // 追加カテゴリ
        // ユーザーが追加したカテゴリと各合計の取得（$incomes）
        $expenditures = Expenditure::query()
            ->select("main_category_id", "icon_id")
            ->selectRaw('SUM(amount) AS sub_category_sum')
            ->where("user_id", "=", $user_id)
            ->where("main_category_id", ">", 9)
            ->where("del_flg", "=", 0)
            ->groupby("main_category_id", "icon_id")
            ->get();
        
        // 追加カテゴリごとの合計（$amounts[]）
        foreach ($expenditures as $expenditure) {
            $amounts[] = $expenditure->sub_category_sum;
        }
        if(empty($amounts)) {
            // 追加カテゴリの値がなければ 0 を代入
            $amounts = 0;
            $sum = $common_sum['sum'];
        } else {
            // 追加カテゴリの値があれば合計（$sub_sum）
            $sub_sum = array_sum($amounts);
            // 既定カテゴリと足す（$sum）
            $sum = $common_sum['sum'] + $sub_sum;
        }

        // 支出(kind_id = 3)の全main_categoryを得てsessionに入れる
        $all_ex_main_category = Main_category::query()
            ->where(function($query) {
                $query->where("kind_id", "=", 3);})
            ->where(function($query) use ($user_id) {
                $query
                    ->where("user_id", "=", 0)
                    ->orwhere("user_id", "=", $user_id);})
            ->select('id', 'name')
            ->get();
        
            foreach ($all_ex_main_category as $ex_main_category) {
                $add_main_category['id']    = $ex_main_category->id;
                $add_main_category['name']  = $ex_main_category->name;
                $add_main_categories[] = $add_main_category;
            }
            $request->session()->put("add_main_categories", $add_main_categories);

        // [支出元]の選択肢を得てsessionに入れる("kind_id", "=", 1)
        $all_po_category = Main_category::query()
            ->where(function($query) {
                $query->where("kind_id", "=", 1);})
            ->where(function($query) use ($user_id) {
                $query
                    ->where("user_id", "=", 0)
                    ->orwhere("user_id", "=", $user_id);})
            ->select('id', 'name')
            ->get();

            foreach ($all_po_category as $po_category) {
                $ex_source['id']    = $po_category->id;
                $ex_source['name']  = $po_category->name;
                $ex_sources[] = $ex_source;
            }
            $request->session()->put("ex_sources", $ex_sources);        
        
        // 
        return view("expenditure.expenditure", compact('sum', 'expenditures', 'common_sum')); 
    }

    public function ex_sub_category(Request $request) { 
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        if(!empty($request->get("main_category_id"))){
            // POST値があればsessionに入れてリダイレクト
            $main_category['id'] = $request->get("main_category_id");
            $main_category['name'] = $request->get("main_category_name");
            $main_category['icon'] = $request->get("icon");
            $request->session()->put("main_category", $main_category);
            return redirect('ex-sub-category', 302, [], true); 
        }
        
        $main_category = $request->session()->get("main_category");
        $main['title'] = $main_category['name'];
        $main['icon'] = $main_category['icon'];
        $request->session()->put("main", $main);
        
        $all_ob_sub = Sub_category::query()
            ->where("user_id", "=", 0)
            ->where("main_category_id", "=", $main_category['id'])
            ->select("id", "name")
            ->get();
        
        foreach ($all_ob_sub as $ob_sub) {
            $common_cate['name'] = $ob_sub['name'];
            $common_cates[] = $common_cate;
        }

        $all_data = Expenditure::query()
            ->select("sub_category_id")
            ->selectRaw('SUM(amount) AS by_category_sum')
            ->where(function($query) use ($main_category) {
                $query
                    ->where("main_category_id", "=", $main_category['id'])
                    ->where("del_flg", "=", 0);})
            ->where(function($query) use ($user_id) {
                $query
                    ->where("user_id", "=", 0)
                    ->orwhere("user_id", "=", $user_id);})
            ->groupby("sub_category_id")
            ->get();

        foreach ($all_data as $data) {
            $ex_sub_cate['sub_cate_id']     = $data->sub_category_id;
            $ex_sub_cate['sub_cate_name']   = $data->sub_category->name;
            $ex_sub_cate['by_cate_sum']     = $data->by_category_sum;
            $ex_sub_cates[] = $ex_sub_cate;
        }
        foreach ($common_cates as $common_cate) {
            foreach ($ex_sub_cates as $ex_sub_cate) {
                if($common_cate['name'] == $ex_sub_cate['sub_cate_name']){
                    $common_cate['sub_cate_id'] = $ex_sub_cate['sub_cate_id'];
                    $common_cate['amount'] = $ex_sub_cate['by_cate_sum'];
                    $common[] = $common_cate;
                }
            }
        }
        $amounts = 0;
        foreach ($common as $common_cate) {
            $amounts += $common_cate['amount'];
        }

        return view("expenditure.ex_sub_category", compact('amounts', 'common', 'main')); 
    }

    public function ex_by_category(Request $request) { 

        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}      

        // ex-add画面から戻ってきた場合、入力内容削除
        $request->session()->forget('add_data');
        $request->session()->forget('edit_data');
        $request->session()->forget('error');

        if(!empty($request->get("sub_cate_id"))){
            $sub_cate['id'] = $request->get("sub_cate_id");
            $sub_cate['name'] = $request->get("sub_cate_name");
            $request->session()->put("sub_cate", $sub_cate);
            
            return redirect('ex-by-category', 302, [], true); 
        }
        $sub_cate = $request->session()->get("sub_cate");
        $main = $request->session()->get("main");
        $main['title'] = $sub_cate['name'];

        // ユーザーの且つmain_cateのデータ取得
        $all_data = Expenditure::query()
            ->where("user_id", "=", $user_id)
            ->where("sub_category_id", "=", $sub_cate['id'])
            ->where("del_flg", "=", 0)
            ->orderBy('date', 'DESC')
            ->get();

        foreach ($all_data as $data) {
            $by_cate['id']       = $data->id;
            $by_cate['date']     = $data->date;
            $by_cate['amount']   = $data->amount;
            $by_cate['name']     = $data->name;
            $by_cates[] = $by_cate;

            $amounts[] = $data->amount;
        }
        if(empty($amounts)) { $amounts[] = 0; }
        if(empty($by_cates)) { 
            return view("expenditure.ex_by_category", compact('amounts', 'main')); 
            exit;
        }

        return view("expenditure.ex_by_category", compact('amounts', 'main', 'by_cates')); 
    }

    public function ex_add(Request $request) {

        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // 最初に読み込まれた時
            // 前ページから[カテゴリー]情報を受け取る
            $add_data['main_category_id']   = $request->session()->get("main_category.id");
            $add_data['main_category_name'] = $request->session()->get("main_category.name");
            $add_data['sub_category_id']    = $request->session()->get("sub_category.id");
            $add_data['sub_category_name']  = $request->session()->get("sub_category.name");

            $add_data['icon']  = $request->session()->get("main.icon");

            $add_main_categories = $request->session()->get("add_main_categories");

            // [サブカテゴリー]の選択肢を得てsessionに入れる
            $all_ex_sub_category = Sub_category::query()
                ->where("user_id", "=", 0)
                ->where("main_category_id", "=", $add_data['main_category_id'])
                ->select('id', 'name')
                ->get();
            
            foreach ($all_ex_sub_category as $ex_sub_category) {
                $add_sub_category['id']    = $ex_sub_category->id;
                $add_sub_category['name']  = $ex_sub_category->name;
                $add_sub_categories[] = $add_sub_category;
            }
            $request->session()->put("add_sub_categories", $add_sub_categories);
                
            $ex_sources = $request->session()->get("ex_sources");
        // end 最初に読み込まれた時

        // 入力後「追加」をクリックしたとき
            if(!empty($request->get("add_submit"))) {
                // POSTをSESSIONに入れる
                $add_data = $request->all();                
                $request->session()->put("add_data", $add_data);
                
                // コメントをバリデーション
                $add_data['name']       = My_function::xss($add_data['name']);
                $add_data['comment']    = My_function::xss($add_data['comment']);

                // エラーの確認
                $error = NULL;
                // エラーがあればエラーをsessionに入れる
                // エラーがなければ正常値をsessionに入れる
                if($add_data['amount'] == '') {
                    $error['amount'] = '金額を入力して下さい';
                    $request->session()->put("error.amount", $error['amount']);
                    $request->session()->forget('add_data.amount');
                } else {
                    $request->session()->put("add_data.amount", $add_data['amount']);
                    $request->session()->forget('error.amount');
                }
                if($add_data['ex_source'] == "") {
                    $error['ex_source'] = '支出元を選択して下さい';
                    $request->session()->put("error.ex_source", $error['ex_source']);
                    $request->session()->forget('add_data.ex_source');
                } else {
                    $request->session()->put("add_data.ex_source", $add_data['ex_source']);
                    $request->session()->forget('error.ex_source');
                }
                if (!empty($add_data['main_category'])) {
                    $request->session()->put("add_data.main_category", $add_data['main_category']);
                }
                if (mb_strlen($add_data['name']) > 20) {
                    $error['name'] = '名前は20文字以内で入力して下さい。';
                    $request->session()->put("error.name", $error['name']);
                    $request->session()->forget('add_data.name');
                } else {
                    $request->session()->put("add_data.name", $add_data['name']);
                    $request->session()->forget('error.name');
                }
                if (mb_strlen($add_data['comment']) > 100) {
                    $error['comment'] = 'メモは100文字以内で入力して下さい。';
                    $request->session()->put("error.comment", $error['comment']);
                    $request->session()->forget('add_data.comment');
                } else {
                    $request->session()->put("add_data.comment", $add_data['comment']);
                    $request->session()->forget('error.comment');
                }
                // 
                $request->session()->put("add_data.icon", $add_data['icon']);
                $request->session()->put("add_data.sub_category", $add_data['sub_category']);
                $request->session()->put("add_data.date", $add_data['date']);
                $request->session()->put("add_data.ex_source", $add_data['ex_source']);
                $request->session()->put("add_data.radio", $add_data['radio']);
                $request->session()->put("add_data.comment", $add_data['comment']);

                // エラーがない場合確認画面へ
                if (!isset($error)) {
                    $request->session()->forget('error');
                    return redirect("ex-add-confirm");
                    exit;
                } else {
                    return redirect("ex-add");
                    exit;
                }
            }
        // end 入力後「追加」をクリックしたとき

        $add_data['amount']     = $request->session()->get("add_data.amount");
        $add_data['main_category']   = $request->session()->get("add_data.main_category");
        $add_data['sub_category']   = $request->session()->get("add_data.sub_category");
        $add_data['date']       = $request->session()->get("add_data.date");
        $add_data['ex_source']  = $request->session()->get("add_data.ex_source");
        $add_data['radio']      = $request->session()->get("add_data.radio");
        $add_data['name']       = $request->session()->get("add_data.name");
        $add_data['comment']    = $request->session()->get("add_data.comment");
        
        $error = $request->session()->get("error");
        
        return view("expenditure.ex_add", compact('add_data', 'add_main_categories', 'add_sub_categories', 'ex_sources', 'error'));
    }

    public function ex_add_confirm(Request $request) {
        
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // 前ページから情報を受け取る        
            $add_data = $request->session()->get("add_data");

            if($add_data['radio'] == 0) {
                $add_data['notice'] = "OFF";
            } elseif ($add_data['radio'] == 1) {
                $add_data['notice'] = "ON";
            }
            $request->session()->put("add_data", $add_data);

        return view("expenditure.ex_add_confirm", compact('add_data'));
    }

    public function ex_add_comp(Request $request) {

        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;
        }

        // POST から リダイレクト
        if(!empty($request->get("add_submit"))){            
            return redirect('ex-add-comp', 302, [], true); 
        }

        // データの受け取り
        $add_data = $request->session()->get("add_data");

        // 送られているのは「name」なのでDBに登録できるように
        // id と name の照合
        $add_main_categories    = $request->session()->get("add_main_categories");
        $add_sub_categories     = $request->session()->get("add_sub_categories");
        $ex_sources             = $request->session()->get("ex_sources");

        foreach ($add_main_categories as $add_main_category) {
            if($add_main_category['name'] == $add_data['main_category']) {
                $add_data['main_category_id'] = $add_main_category['id'];
        }}
        foreach ($add_sub_categories as $add_sub_category) {
            if($add_sub_category['name'] == $add_data['sub_category']) {
                $add_data['sub_category_id'] = $add_sub_category['id'];
        }}
        foreach ($ex_sources as $ex_source) {
            if($ex_source['name'] == $add_data['ex_source']) {
                $add_data['ex_source'] = $ex_source['id'];
        }}

        $expenditure = new Expenditure();
        // プロパティに値を代入
        $expenditure->user_id           = $user_id;
        $expenditure->main_category_id  = $add_data['main_category_id'];
        $expenditure->sub_category_id   = $add_data['sub_category_id'];
        $expenditure->icon_id           = $add_data['main_category_id'];
        $expenditure->date              = $add_data['date'];
        $expenditure->possession_id     = $add_data['ex_source'];
        $expenditure->amount            = $add_data['amount'];
        $expenditure->name              = $add_data['name'];
        $expenditure->notice            = $add_data['radio'];
        $expenditure->comment           = $add_data['comment'];
        // データベースに保存
        $expenditure->save();

        return view("expenditure.ex_add_comp");
    }

    public function ex_detail(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        if(!empty($request->get("id"))){
            $data['id'] = $request->get("id");
            $request->session()->put("data.id", $data['id']);
            return redirect('ex-detail'); exit;
        }

        $data['id'] = $request->session()->get("data.id");
        if(empty($data['id'])){ return redirect('expenditure'); exit; }
        
        $data_obs = Expenditure::query()
            ->where("id", "=", $data['id'])
            ->get();
        
        foreach ($data_obs as $data_ob) {
            $data_one['id'] = $data_ob['id'];
            $data_one['main_category_name'] = $data_ob->main_category->name;
            $data_one['sub_category_name']  = $data_ob->sub_category->name;
            $data_one['icon']               = $data_ob->icon->code;
            $data_one['date']               = $data_ob['date'];
            $data_one['ex_source']          = $data_ob['possession_id'];
            $data_one['amount']             = $data_ob['amount'];
            $data_one['name']               = $data_ob['name'];
            $data_one['notice']             = $data_ob['notice'];
            $data_one['comment']            = $data_ob['comment'];
        }
        
        $main_cates_ob = Main_category::query()
            ->select('id', 'name')
            ->where("id", "=", $data_one['ex_source'])
            ->get();
        
        foreach ($main_cates_ob as $main_cate_ob) {
            if($main_cate_ob['id'] == $data_one['ex_source']){
                $data_one['ex_source'] = $main_cate_ob['name'];
        }}
        if($data_one['notice'] == 0 || empty($data_one['notice'])) {
            $data_one['notice'] = "OFF";
        } elseif ($data_one['notice'] == 1) {
            $data_one['notice'] = "ON";
        }
        
        return view("expenditure.ex_detail", compact('data_one'));
    }

    public function ex_edit(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // POSTされたデータのidをsessionへ
        if(!empty($request->get("id"))){
            $data['id'] = $request->get("id");
            $request->session()->put("data.id", $data['id']);
            return redirect('ex-edit'); exit;
        }        

        // 入力後「確認」をクリックしたとき
            if(!empty($request->get("edit_submit"))) {
                // POSTをSESSIONに入れる
                $edit_data = $request->all();                
                $request->session()->put("edit_data", $edit_data);
                
                // データのidを受け取る
                $data['id'] = $request->session()->get("data.id");

                // コメントをバリデーション
                $edit_data['name']       = My_function::xss($edit_data['name']);
                $edit_data['comment']    = My_function::xss($edit_data['comment']);

                // エラーの確認
                $error = NULL;
                // エラーがあればエラーをsessionに入れる
                // エラーがなければ正常値をsessionに入れる
                if($edit_data['amount'] == '') {
                    $error['amount'] = '金額を入力して下さい';
                    $request->session()->put("error.amount", $error['amount']);
                    $request->session()->forget('edit_data.amount');
                } else {
                    $request->session()->put("edit_data.amount", $edit_data['amount']);
                    $request->session()->forget('error.amount');
                }
                if($edit_data['ex_source'] == "") {
                    $error['ex_source'] = '支出元を選択して下さい';
                    $request->session()->put("error.ex_source", $error['ex_source']);
                    $request->session()->forget('edit_data.ex_source');
                } else {
                    $request->session()->put("edit_data.ex_source", $edit_data['ex_source']);
                    $request->session()->forget('error.ex_source');
                }
                if (!empty($edit_data['main_category'])) {
                    $request->session()->put("edit_data.main_category", $edit_data['main_category']);
                }
                if (mb_strlen($edit_data['name']) > 20) {
                    $error['name'] = '名前は20文字以内で入力して下さい。';
                    $request->session()->put("error.name", $error['name']);
                    $request->session()->forget('edit_data.name');
                } else {
                    $request->session()->put("edit_data.name", $edit_data['name']);
                    $request->session()->forget('error.name');
                }
                if (mb_strlen($edit_data['comment']) > 100) {
                    $error['comment'] = 'メモは100文字以内で入力して下さい。';
                    $request->session()->put("error.comment", $error['comment']);
                    $request->session()->forget('edit_data.comment');
                } else {
                    $request->session()->put("edit_data.comment", $edit_data['comment']);
                    $request->session()->forget('error.comment');
                }
                // 
                $request->session()->put("edit_data.id", $data['id']);
                $request->session()->put("edit_data.icon", $edit_data['icon']);
                $request->session()->put("edit_data.main_category", $edit_data['main_category']);
                $request->session()->put("edit_data.sub_category", $edit_data['sub_category']);
                $request->session()->put("edit_data.date", $edit_data['date']);
                $request->session()->put("edit_data.ex_source", $edit_data['ex_source']);
                $request->session()->put("edit_data.radio", $edit_data['radio']);
                $request->session()->put("edit_data.comment", $edit_data['comment']);

                // エラーがない場合確認画面へ
                if (!isset($error)) {
                    $request->session()->forget('error');
                    return redirect("ex-edit-confirm");
                    exit;
                } else {
                    return redirect("ex-edit");
                    exit;
                }
            }
        // end 入力後「確認」をクリックしたとき

        // データのidを受け取る
        $data['id'] = $request->session()->get("data.id");
        if(empty($data['id'])){ return redirect('expenditure'); exit; }
        // データを受け取る
        $edit_data = $request->session()->get("edit_data");

        // id から情報を取得
            $data_obs = Expenditure::query()
                ->where("id", "=", $data['id'])
                ->get();

            foreach ($data_obs as $data_ob) {
                $edit_data['id'] = $data_ob['id'];
                $edit_data['main_category_id']   = $data_ob->main_category_id;
                $edit_data['main_category_name'] = $data_ob->main_category->name;
                $edit_data['sub_category_id']    = $data_ob->sub_category_id;
                $edit_data['sub_category_name']  = $data_ob->sub_category->name;
                $edit_data['icon_id']            = $data_ob->icon_id;
                $edit_data['icon']               = $data_ob->icon->code;
                $edit_data['date']               = $data_ob['date'];
                $edit_data['ex_source']          = $data_ob['possession_id'];
                $edit_data['amount']             = $data_ob['amount'];
                $edit_data['name']               = $data_ob['name'];
                $edit_data['notice']             = $data_ob['notice'];
                $edit_data['comment']            = $data_ob['comment'];
            }

            // 支出元とラジオの値を変換
            $main_cates_ob = Main_category::query()
                ->select('id', 'name')
                ->where("id", "=", $edit_data['ex_source'])
                ->get();
            
            foreach ($main_cates_ob as $main_cate_ob) {
                if($main_cate_ob['id'] == $edit_data['ex_source']){
                    $edit_data['ex_source'] = $main_cate_ob['name'];
            }}
            if($edit_data['notice'] == 0 || empty($edit_data['notice'])) {
                    $edit_data['notice'] = "OFF";
                } elseif ($edit_data['notice'] == 1) {
                    $edit_data['notice'] = "ON";
            }

            $request->session()->put("edit_data", $edit_data);

        // [メインカテゴリー]の選択肢を得る
        $add_main_categories = $request->session()->get("add_main_categories");

        // [サブカテゴリー]の選択肢を得てsessionに入れる
        $all_ex_sub_category = Sub_category::query()
            ->where("user_id", "=", 0)
            ->where("main_category_id", "=", $edit_data['main_category_id'])
            ->select('id', 'name')
            ->get();
        
        foreach ($all_ex_sub_category as $ex_sub_category) {
            $add_sub_category['id']    = $ex_sub_category->id;
            $add_sub_category['name']  = $ex_sub_category->name;
            $add_sub_categories[] = $add_sub_category;
        }
        $request->session()->put("add_sub_categories", $add_sub_categories);    

        $ex_sources = $request->session()->get("ex_sources");

        $error = $request->session()->get("error");
        
        return view("expenditure.ex_edit", compact('edit_data', 'add_main_categories', 'add_sub_categories', 'ex_sources', 'error'));
    }

    public function ex_edit_confirm(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // 前ページから情報を受け取る        
            $edit_data = $request->session()->get("edit_data");

            if($edit_data['radio'] == 0) {
                $edit_data['notice'] = "OFF";
            } elseif ($edit_data['radio'] == 1) {
                $edit_data['notice'] = "ON";
            }

        return view("expenditure.ex_edit_confirm", compact('edit_data'));
    }

    public function ex_edit_comp(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        if(!empty($request->get("edit_submit"))){            
            return redirect('ex-edit-comp', 302, [], true);}

        $edit_data = $request->session()->get("edit_data");

        // 送られているのは「name」なのでDBに登録できるように
        // id と name の照合
        $edit_main_categories   = $request->session()->get("add_main_categories");
        $edit_sub_categories    = $request->session()->get("add_sub_categories");
        $ex_sources             = $request->session()->get("ex_sources");

        foreach ($edit_main_categories as $edit_main_category) {
            if($edit_main_category['name'] == $edit_data['main_category']) {
                $edit_data['main_category_id'] = $edit_main_category['id'];
        }}
        foreach ($edit_sub_categories as $edit_sub_category) {
            if($edit_sub_category['name'] == $edit_data['sub_category']) {
                $edit_data['sub_category_id'] = $edit_sub_category['id'];
        }}
        foreach ($ex_sources as $ex_source) {
            if($ex_source['name'] == $edit_data['ex_source']) {
                $edit_data['ex_source'] = $ex_source['id'];
        }}

        $expenditure = Expenditure::query()
            ->where("id", "=", $edit_data['id'])
            ->first();
        
        $expenditure->user_id           = $user_id;
        $expenditure->main_category_id  = $edit_data['main_category_id'];
        $expenditure->sub_category_id   = $edit_data['sub_category_id'];
        $expenditure->icon_id           = $edit_data['main_category_id'];
        $expenditure->date              = $edit_data['date'];
        $expenditure->possession_id     = $edit_data['ex_source'];
        $expenditure->amount            = $edit_data['amount'];
        $expenditure->name              = $edit_data['name'];
        $expenditure->notice            = $edit_data['radio'];
        $expenditure->comment           = $edit_data['comment'];
        // データベースに保存
        $expenditure->save();

        return view("expenditure.ex_edit_comp");
    }

    public function ex_delete(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}
        
        $delete_id = $request->get("id");
        
        $expenditure = Expenditure::query()
            ->where("id", "=", $delete_id)
            ->first();

        // 0:表示、1:非表示 の[del_flg]カラム値を1にして非表示
        $expenditure->del_flg = 1;
        // データベースに保存
        $expenditure->save();

        return redirect('ex_by_category');
    }

}
