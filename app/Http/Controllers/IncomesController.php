<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;

use App\Lib\My_function;
use App\Models\Possession;
use App\Models\Main_category;
use App\Models\Icon;

class IncomesController extends Controller
{
    public function income(Request $request) {
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
        $common_all_data = Income::query()
            ->select("main_category_id")
            ->selectRaw('SUM(amount) AS main_category_sum')
            ->where("user_id", "=", $user_id)
            ->where("main_category_id", "<=", 6)
            ->where("del_flg", "=", 0)
            ->groupby("main_category_id")
            ->get();
        
        // 既定カテゴリごとの合計値を$common_sum へ
        foreach ($common_all_data as $common_data) {
            if($common_data['main_category_id'] == 4) {
                $common_sum['salary'] = $common_data['main_category_sum'];
            }
            if($common_data['main_category_id'] == 5) {
                $common_sum['side_job'] = $common_data['main_category_sum'];
            }
            if($common_data['main_category_id'] == 6) {
                $common_sum['extra'] = $common_data['main_category_sum'];
            }
        }
        // 値がなければ 0 を代入
        if(empty($common_sum['salary'])) {$common_sum['salary'] = 0;}
        if(empty($common_sum['side_job'])) {$common_sum['side_job'] = 0;}
        if(empty($common_sum['extra'])) {$common_sum['extra'] = 0;}

        // 既定カテゴリの総計を計算
        $common_sum['sum'] = array_sum($common_sum);
        
        // 追加カテゴリ
        // ユーザーが追加したカテゴリと各合計の取得（$incomes）
        $incomes = income::query()
            ->select("main_category_id", "icon_id")
            ->selectRaw('SUM(amount) AS sub_category_sum')
            ->where("user_id", "=", $user_id)
            ->where("main_category_id", ">", 6)
            ->where("del_flg", "=", 0)
            ->groupby("main_category_id", "icon_id")
            ->get();

        // 追加カテゴリごとの合計（$amounts[]）
        foreach ($incomes as $income) {
            $amounts[] = $income->sub_category_sum;
        }
        if(empty($amounts)) {
            // 追加カテゴリの値がなければ 0 を代入
            $amounts = 0;
            $sum = $common_sum['sum'] + $amounts;
        } else {
            // 追加カテゴリの値があれば合計（$sub_sum）
            $sub_sum = array_sum($amounts);
            // 既定カテゴリと足す（$sum）
            $sum = $common_sum['sum'] + $sub_sum;
        }
        
        // 収入(kind_id = 2)の全main_categoryを得てsessionに入れる
        $all_in_main_category = Main_category::query()
            ->where(function($query) {
                $query->where("kind_id", "=", 2);})
            ->where(function($query) use ($user_id) {
                $query
                    ->where("user_id", "=", 0)
                    ->orwhere("user_id", "=", $user_id);})
            ->select('id', 'name')
            ->get();
    
            foreach ($all_in_main_category as $in_main_category) {
                $add_main_category['id']    = $in_main_category->id;
                $add_main_category['name']  = $in_main_category->name;
                $add_main_categories[] = $add_main_category;
            }
            $request->session()->put("add_main_categories", $add_main_categories);

        // [収入先](kind_id = 1)の選択肢を得てsessionに入れる
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
                $in_to['id']    = $po_category->id;
                $in_to['name']  = $po_category->name;
                $in_tos[] = $in_to;
            }
            $request->session()->put("in_tos", $in_tos);           

        return view("income.income", compact('sum', 'incomes', 'common_sum')); 
    }

    public function in_category(Request $request) { 
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        if(!empty($request->get("main_category_id"))){
            // POST値があればsessionに入れてリダイレクト
            $main_category['id'] = $request->get("main_category_id");
            $main_category['name'] = $request->get("main_category_name");
            $main_category['icon'] = $request->get("icon");
            $request->session()->put("main_category", $main_category);
            return redirect('in-category', 302, [], true); 
        }
        $main_cate = $request->session()->get("main_category");
        $main['title'] = $main_cate['name'];
        $main['icon'] = $main_cate['icon'];
        $request->session()->put("main", $main);

        // 追加、編集画面から戻ってきた場合、入力内容削除
        $request->session()->forget('add_data');
        $request->session()->forget('edit_data');
        $request->session()->forget('error');

        // ユーザーの且つmain_cateのデータ取得
        $all_data = Income::query()
            ->where("user_id", "=", $user_id)
            ->where("main_category_id", "=", $main_cate['id'])
            ->where("del_flg", "=", 0)
            ->orderBy('date', 'DESC')
            ->get();

        foreach ($all_data as $data) {
            $by_cate['id']       = $data->id;
            $by_cate['date']     = $data->date;
            $by_cate['amount']   = $data->amount;
            $by_cates[] = $by_cate;

            $amounts[] = $data->amount;
        }
        if(empty($amounts)) { $amounts[] = 0; }
        if(empty($by_cates)) { 
            return view("income.in_category", compact('amounts', 'main')); 
            exit;
        }

        return view("income.in_category", compact('amounts', 'main', 'by_cates'));             
    }

    public function in_add(Request $request) {

        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // 最初に読み込まれた時
            // 前ページから[カテゴリー]情報を受け取る
            $add_data['main_category_id']   = $request->session()->get("main_category.id");
            $add_data['main_category_name'] = $request->session()->get("main_category.name");

            $add_data['icon']  = $request->session()->get("main.icon");

            $add_main_categories = $request->session()->get("add_main_categories");
                    
            $in_tos = $request->session()->get("in_tos");
        // end 最初に読み込まれた時

        // 入力後「追加」をクリックしたとき
            if(!empty($request->get("add_submit"))) {
                // POSTをSESSIONに入れる
                $add_data = $request->all();                
                $request->session()->put("add_data", $add_data);
                
                // 入力値をバリデーション
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
                if($add_data['in_to'] == "") {
                    $error['in_to'] = '収入先を選択して下さい';
                    $request->session()->put("error.in_to", $error['in_to']);
                    $request->session()->forget('add_data.in_to');
                } else {
                    $request->session()->put("add_data.in_to", $add_data['in_to']);
                    $request->session()->forget('error.in_to');
                }
                if (!empty($add_data['main_category'])) {
                    $request->session()->put("add_data.main_category", $add_data['main_category']);
                }
                if (mb_strlen($add_data['comment']) > 100) {
                    $error['comment'] = 'メモは100文字以内で入力して下さい。';
                    $request->session()->put("error.comment", $error['comment']);
                    $request->session()->forget('add_data.comment');
                } else {
                    $request->session()->put("add_data.comment", $add_data['comment']);
                    $request->session()->forget('error.comment');
                }
                $request->session()->put("add_data.date", $add_data['date']);
                $request->session()->put("add_data.in_to", $add_data['in_to']);
                $request->session()->put("add_data.comment", $add_data['comment']);

                // エラーがない場合確認画面へ
                if (!isset($error)) {
                    $request->session()->forget('error');
                    return redirect("in-add-confirm");
                    exit;
                } else {
                    return redirect("in-add");
                    exit;
                }
            }
        // end 入力後「追加」をクリックしたとき

        $add_data['amount']     = $request->session()->get("add_data.amount");
        $add_data['main_category']   = $request->session()->get("add_data.main_category");
        $add_data['date']       = $request->session()->get("add_data.date");
        $add_data['in_to']      = $request->session()->get("add_data.in_to");
        $add_data['comment']    = $request->session()->get("add_data.comment");
        
        $error = $request->session()->get("error");
        
        return view("income.in_add", compact('add_data', 'add_main_categories', 'in_tos', 'error'));
    }

    public function in_add_confirm(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}        

        // 前ページから情報を受け取る        
        $add_data = $request->session()->get("add_data");

        return view("income.in_add_confirm", compact('add_data'));
    }

    public function in_add_comp(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;
        }
        // POST から リダイレクト
        if(!empty($request->get("add_submit"))){            
            return redirect('in-add-comp', 302, [], true); 
        }        

        // データの受け取り
        $add_data = $request->session()->get("add_data");

        // 送られているのは「name」なのでDBに登録できるように
        // id と name の照合
        $add_main_categories    = $request->session()->get("add_main_categories");
        $in_tos                 = $request->session()->get("in_tos");

        foreach ($add_main_categories as $add_main_category) {
            if($add_main_category['name'] == $add_data['main_category']) {
                $add_data['main_category_id'] = $add_main_category['id'];
        }}
        foreach ($in_tos as $in_to) {
            if($in_to['name'] == $add_data['in_to']) {
                $add_data['in_to'] = $in_to['id'];
        }}

        $income = new Income();
        // プロパティに値を代入
        $income->user_id           = $user_id;
        $income->main_category_id  = $add_data['main_category_id'];
        $income->icon_id           = $add_data['main_category_id'];
        $income->date              = $add_data['date'];
        $income->possession_id     = $add_data['in_to'];
        $income->amount            = $add_data['amount'];
        $income->comment           = $add_data['comment'];
        // データベースに保存
        $income->save();

        return view("income.in_add_comp");
    }

    public function in_detail(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        if(!empty($request->get("id"))){
            $data['id'] = $request->get("id");
            $request->session()->put("data.id", $data['id']);
            return redirect('in-detail'); exit;
        }        

        $data['id'] = $request->session()->get("data.id");
        if(empty($data['id'])){ return redirect('income'); exit; }
        
        $data_obs = Income::query()
            ->where("id", "=", $data['id'])
            ->get();
        
        foreach ($data_obs as $data_ob) {
            $data_one['id'] = $data_ob['id'];
            $data_one['main_category_name'] = $data_ob->main_category->name;
            $data_one['icon']               = $data_ob->icon->code;
            $data_one['date']               = $data_ob['date'];
            $data_one['in_to']              = $data_ob['possession_id'];
            $data_one['amount']             = $data_ob['amount'];
            $data_one['name']               = $data_ob['name'];
            $data_one['comment']            = $data_ob['comment'];
        }
        
        $main_cates_ob = Main_category::query()
            ->select('id', 'name')
            ->where("id", "=", $data_one['in_to'])
            ->get();
        
        foreach ($main_cates_ob as $main_cate_ob) {
            if($main_cate_ob['id'] == $data_one['in_to']){
                $data_one['in_to'] = $main_cate_ob['name'];
        }}
        
        return view("income.in_detail", compact('data_one'));
    }

    public function in_edit(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // POSTされたデータのidをsessionへ
        if(!empty($request->get("id"))){
            $data['id'] = $request->get("id");
            $request->session()->put("data.id", $data['id']);
            return redirect('in-edit'); exit;
        }        

        // 入力後「確認」をクリックしたとき
            if(!empty($request->get("edit_submit"))) {
                // POSTを受け取る
                $edit_data = $request->all();                
                // $request->session()->put("edit_data", $edit_data);
                
                // データのidを受け取る
                $data['id'] = $request->session()->get("data.id");

                // コメントをバリデーション
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
                if($edit_data['in_to'] == "") {
                    $error['in_to'] = '収入先を選択して下さい';
                    $request->session()->put("error.in_to", $error['in_to']);
                    $request->session()->forget('edit_data.in_to');
                } else {
                    $request->session()->put("edit_data.in_to", $edit_data['in_to']);
                    $request->session()->forget('error.in_to');
                }
                if (!empty($edit_data['main_category'])) {
                    $request->session()->put("edit_data.main_category", $edit_data['main_category']);
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
                $request->session()->put("edit_data.date", $edit_data['date']);
                $request->session()->put("edit_data.in_to", $edit_data['in_to']);
                $request->session()->put("edit_data.comment", $edit_data['comment']);

                // エラーがない場合確認画面へ
                if (!isset($error)) {
                    $request->session()->forget('error');
                    return redirect("in-edit-confirm");
                    exit;
                } else {
                    return redirect("in-edit");
                    exit;
                }
            }
        // end 入力後「確認」をクリックしたとき

        // データのidを受け取る
        $data['id'] = $request->session()->get("data.id");
        if(empty($data['id'])){ return redirect('income'); exit; }
        // データを受け取る
        $edit_data = $request->session()->get("edit_data");

        // id から情報を取得
            $data_obs = Income::query()
                ->where("id", "=", $data['id'])
                ->get();

            foreach ($data_obs as $data_ob) {
                $edit_data['id'] = $data_ob['id'];
                $edit_data['main_category_id']   = $data_ob->main_category_id;
                $edit_data['main_category_name'] = $data_ob->main_category->name;
                $edit_data['icon_id']            = $data_ob->icon_id;
                $edit_data['icon']               = $data_ob->icon->code;
                $edit_data['date']               = $data_ob['date'];
                $edit_data['in_to']              = $data_ob['possession_id'];
                $edit_data['amount']             = $data_ob['amount'];
                $edit_data['comment']            = $data_ob['comment'];
            }

            // 収入先とラジオの値を変換
            $main_cates_ob = Main_category::query()
                ->select('id', 'name')
                ->where("id", "=", $edit_data['in_to'])
                ->get();
            
            foreach ($main_cates_ob as $main_cate_ob) {
                if($main_cate_ob['id'] == $edit_data['in_to']){
                    $edit_data['in_to'] = $main_cate_ob['name'];
            }}

            $request->session()->put("edit_data", $edit_data);

        // [メインカテゴリー]の選択肢を得る
        $add_main_categories = $request->session()->get("add_main_categories");

        $in_tos = $request->session()->get("in_tos");

        $error = $request->session()->get("error");
        
        return view("income.in_edit", compact('edit_data', 'add_main_categories', 'in_tos', 'error'));
    }

    public function in_edit_confirm(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // 前ページから情報を受け取る        
            $edit_data = $request->session()->get("edit_data");

        return view("income.in_edit_confirm", compact('edit_data'));
    }

    public function in_edit_comp(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        if(!empty($request->get("edit_submit"))){            
            return redirect('in-edit-comp', 302, [], true);}

        $edit_data = $request->session()->get("edit_data");

        // 送られているのは「name」なのでDBに登録できるように
        // id と name の照合
        $edit_main_categories   = $request->session()->get("add_main_categories");
        $in_tos             = $request->session()->get("in_tos");

        foreach ($edit_main_categories as $edit_main_category) {
            if($edit_main_category['name'] == $edit_data['main_category']) {
                $edit_data['main_category_id'] = $edit_main_category['id'];
        }}
        foreach ($in_tos as $in_to) {
            if($in_to['name'] == $edit_data['in_to']) {
                $edit_data['in_to'] = $in_to['id'];
        }}

        $income = Income::query()
            ->where("id", "=", $edit_data['id'])
            ->first();
        
        $income->user_id           = $user_id;
        $income->main_category_id  = $edit_data['main_category_id'];
        $income->icon_id           = $edit_data['main_category_id'];
        $income->date              = $edit_data['date'];
        $income->possession_id     = $edit_data['in_to'];
        $income->amount            = $edit_data['amount'];
        $income->comment           = $edit_data['comment'];
        // データベースに保存
        $income->save();

        return view("income.in_edit_comp");
    }

    public function in_delete(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}
        
        $delete_id = $request->get("id");
        
        $income = Income::query()
            ->where("id", "=", $delete_id)
            ->first();

        // 0:表示、1:非表示 の[del_flg]カラム値を1にして非表示
        $income->del_flg = 1;
        // データベースに保存
        $income->save();

        return redirect('in-category');
    }

}