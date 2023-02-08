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
            'main_cates',
            'sub_cates',
            'sub_cate',
            'main',
            
            'ex_sources',
            'in_tos',
            
            'po_cates',
            'in_cates',
            'ex_cates',
            
            'data',
            'kind',
            'add_data',
            'add_main_categories',
            'add_sub_categories',
            'edit_data',
            'error',
            
            'add_cate'
            // 'po_by_cates',
            // 'in_to',
            // 'to_sub',
            // 'cate_results'
        ]);
        // カテゴリ
            // 既定&ユーザー設定カテゴリーを取得（$common_category_obs）
            $common_category_obs = Main_category::query()
                ->select("id", "name", "icon_id")
                ->where("kind_id", "=", 2)
                ->where("del_flg", "=", 0)
                ->where(function($query) use ($user_id) {
                    $query
                        ->where("user_id", "=", 0)
                        ->orwhere("user_id", "=", $user_id);})
                ->get();

            foreach ($common_category_obs as $common_category_ob) {
                $in_cate['id']   = $common_category_ob['id'];
                $in_cate['name'] = $common_category_ob['name'];
                $in_cate['icon_id'] = $common_category_ob['icon_id'];
                $in_cate['icon'] = $common_category_ob->icon->code;
                $in_cates[] = $in_cate;
            } 
            $request->session()->put("in_cates", $in_cates);
        // end カテゴリ
        // 収入の計算
        // 収入のカテゴリーごとの合計
            // ユーザーの収入取得
            $income_obs = Income::query()
            ->select('main_category_id')
            ->selectRaw('SUM(amount) AS sum ')
            ->where("user_id", "=", $user_id)
            ->where("del_flg", "=", 0)
            ->groupBy("main_category_id")
            ->get();

            if(!empty($income_obs)) {
                foreach ($income_obs as $income_ob) {
                    $income['id'] = $income_ob->main_category_id;
                    $income['sum'] = $income_ob->sum;
                    $incomes[] = $income;
                }
            } else {
                $incomes = array(
                    ["id" => "1", "sum" => "0"],
                    ["id" => "2", "sum" => "0"],
                    ["id" => "3", "sum" => "0"]
                );
            }
            if(!empty($incomes)) {
                foreach ($in_cates as $in_cate) {
                    foreach ($incomes as $income) {
                        if($in_cate['id'] == $income['id']){
                            $in_cate['sum'] = $income['sum'];
                            $in_by_cates[] = $in_cate;
                        }
                    }
                    if(empty($in_cate['sum'])){
                        $in_cate['sum'] = 0;
                        $in_by_cates[] = $in_cate;
                    }
                }
            }else{
                $incomes_sum = 0;
                return view("income.income", compact('incomes_sum'));         
            }
        // 所持金総額
            $incomes_sum = array_sum(array_column($incomes, 'sum'));

        return view("income.income", compact('incomes_sum', 'in_by_cates')); 
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

        // アクセス制御
        $referer = url()->previous();
        // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
        $referer_check = 
            preg_match('/income/',$referer) +
            preg_match('/in-category/',$referer) +
            preg_match('/in-detail/',$referer) +
            preg_match('/in-edit/',$referer) +
            preg_match('/in-add/',$referer)
        ;
        if($referer_check === 0) {
            return redirect('income');
        }

        // 選択されたカテゴリーを取得
            $main_cate = $request->session()->get("main_category");
            if(empty($main_cate)) {
                return redirect('income');
            }
            $main['title'] = $main_cate['name'];
            $main['icon'] = $main_cate['icon'];
            $request->session()->put("main", $main);

        // 追加、編集画面から戻ってきた場合、入力内容削除
            $request->session()->forget('data');
            $request->session()->forget('add_data');
            $request->session()->forget('edit_data');
            $request->session()->forget('add_main_categories');
            $request->session()->forget('ex_sources');
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
        }
        if(!empty($by_cates)) { 
            $in_by_cate_sum = array_sum(array_column($by_cates,'amount'));        
        }else{
            $in_by_cate_sum = 0;
            return view("income.in_category", compact('main','in_by_cate_sum')); 
            exit;
        }

        return view("income.in_category", compact('main','by_cates','in_by_cate_sum'));             
    }

    public function in_add(Request $request) {

        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // 最初に読み込まれた時
            // アクセス制御
                $referer = url()->previous();
                // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
                $referer_check = 
                    preg_match('/in-category/',$referer) +
                    preg_match('/in-add/',$referer) +
                    preg_match('/in-add-confirm/',$referer)
                ;
                if($referer_check === 0) {
                    return redirect('income');
                }
            // 前ページから[カテゴリー]情報を受け取る
                $add_data['main_category_id']   = $request->session()->get("main_category.id");
                $add_data['main_category_name'] = $request->session()->get("main_category.name");
                $add_data['icon']  = $request->session()->get("main.icon");
                if(empty($add_data['main_category_id'])) {return redirect('income');}
                if(empty($add_data['main_category_name'])) {return redirect('income');}
                if(empty($add_data['icon'])) {return redirect('income');}

            // 収入(kind_id = 2)の全main_categoryを得てsessionに入れる
                $all_in_main_category = Main_category::query()
                ->where(function($query) {
                    $query
                    ->where("del_flg", "=", 0)
                    ->where("kind_id", "=", 2);})
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
                    $query
                    ->where("del_flg", "=", 0)
                    ->where("kind_id", "=", 1);})
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
        
        // アクセス制御
            $referer = url()->previous();
            // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
            $referer_check = 
                preg_match('/in-add/',$referer) +
                preg_match('/in-add-confirm/',$referer)
            ;
            if($referer_check === 0) {
                return redirect('income');
            }

        // 前ページから情報を受け取る        
        $add_data = $request->session()->get("add_data");
        if(empty($add_data)) {return redirect('income');}
        $add_data['comment'] = html_entity_decode($add_data['comment']);
        $request->session()->put("add_data", $add_data);

        return view("income.in_add_confirm", compact('add_data'));
    }

    public function in_add_comp(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // アクセス制御
            $referer = url()->previous();
            // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
            $referer_check = 
                preg_match('/in-add-confirm/',$referer)+
                preg_match('/in-add-comp/',$referer)
            ;
            if($referer_check === 0) {
                return redirect('income');
            }

        // POST から リダイレクト
        if(!empty($request->get("add_submit"))){            
            return redirect('in-add-comp', 302, [], true); 
        }

        // データの受け取り
        $add_data = $request->session()->get("add_data");
        if(empty($add_data)) {return redirect('income');}
        // 送られているのは「name」なのでDBに登録できるように
        // id と name の照合
        $add_main_categories    = $request->session()->get("add_main_categories");
        $in_tos                 = $request->session()->get("in_tos");
        if(empty($add_main_categories)) {return redirect('income');}
        if(empty($in_tos)) {return redirect('income');}

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

        // アクセス制御
            $referer = url()->previous();
            // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
            $referer_check = 
                preg_match('/po-category/',$referer) +
                preg_match('/in-category/',$referer) +
                preg_match('/in-detail/',$referer)
            ;
            if($referer_check === 0) {return redirect('income');}

        if(!empty($request->get("id"))){
            $data['id'] = $request->get("id");
            $data['kind_id'] = $request->get("kind_id");
            $request->session()->put("data", $data);
            return redirect('in-detail'); exit;
        }        

        $data = $request->session()->get("data");
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

        if(empty($data_one)){
            return redirect('income'); exit;
        }        
        
        return view("income.in_detail", compact('data','data_one'));
    }

    public function in_edit(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // アクセス制御
            $referer = url()->previous();
            // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
            $referer_check = 
                preg_match('/po-category/',$referer) +
                preg_match('/in-category/',$referer) +
                preg_match('/in-detail/',$referer) +
                preg_match('/in-edit/',$referer) +
                preg_match('/in-edit-confirm/',$referer)
            ;
            if($referer_check === 0) {return redirect('income');}

        // POSTされたデータのidをsessionへ
        if(!empty($request->get("id"))){
            $data['id'] = $request->get("id");
            $data['kind_id'] = $request->get("kind_id");
            $request->session()->put("data", $data);
            return redirect('in-edit'); exit;
        }        

        // 入力後「確認」をクリックしたとき
            if(!empty($request->get("edit_submit"))) {
                // POSTを受け取る
                $edit_data = $request->all();                
                $request->session()->put("edit_data", $edit_data);
                
                // データのidを受け取る
                $data = $request->session()->get("data");

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
        $data = $request->session()->get("data");
        if(empty($data['id'])){ return redirect('income'); exit; }

        // id から情報を取得
            $data_obs = Income::query()
                ->where("id", "=", $data['id'])
                ->get();

            if(empty($data_obs)) {return redirect('income');}

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
        // 収入先の値を変換
            $main_cates_ob = Main_category::query()
                ->select('id', 'name')
                ->where("user_id", "=", $user_id)
                ->where("del_flg", "=", 0)
                ->where("id", "=", $edit_data['in_to'])
                ->get();
            
            foreach ($main_cates_ob as $main_cate_ob) {
                if($main_cate_ob['id'] == $edit_data['in_to']){
                    $edit_data['in_to'] = $main_cate_ob['name'];
            }}
            // セッションに入れる
            $request->session()->put("edit_data", $edit_data);

        // [メインカテゴリー]の選択肢を得る
            $all_in_main_category = Main_category::query()
            ->where(function($query) {
                $query
                    ->where("del_flg", "=", 0)
                    ->where("kind_id", "=", 2);})
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
                $query
                    ->where("del_flg", "=", 0)
                    ->where("kind_id", "=", 1);})
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

        $error = $request->session()->get("error");
        
        return view("income.in_edit", compact('data','edit_data', 'add_main_categories', 'in_tos', 'error'));
    }

    public function in_edit_confirm(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}
        
        // アクセス制御
            $referer = url()->previous();
            // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
            $referer_check = 
                preg_match('/in-edit/',$referer) +
                preg_match('/in-edit-confirm/',$referer)
            ;
            if($referer_check === 0) {return redirect('income');}

        // 前ページから情報を受け取る        
            $edit_data = $request->session()->get("edit_data");
            if(empty($edit_data)) {return redirect('income');}
            $edit_data['comment'] = html_entity_decode($edit_data['comment']);
            $request->session()->put("edit_data", $edit_data);            

        // echo "<pre>"; print_r($edit_data); echo "</pre>"; exit;
        return view("income.in_edit_confirm", compact('edit_data'));
    }

    public function in_edit_comp(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // アクセス制御
            $referer = url()->previous();
            // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
            $referer_check = 
            preg_match('/in-edit-confirm/',$referer)+
            preg_match('/in-edit-comp/',$referer)
            ;
            if($referer_check === 0) {return redirect('income');}        

        // POST から リダイレクト
        if(!empty($request->get("edit_submit"))){            
            return redirect('in-edit-comp', 302, [], true);
        }
       
        // データの受け取り
        $edit_data = $request->session()->get("edit_data");
        if(empty($edit_data)) {return redirect('income');}

        // 送られているのは「name」なのでDBに登録できるように
        // id と name の照合
        $edit_main_categories   = $request->session()->get("add_main_categories");
        $in_tos                 = $request->session()->get("in_tos");
        if(empty($edit_main_categories)) {return redirect('income');}
        if(empty($in_tos)) {return redirect('income');}

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

        $data = $request->session()->get("data");

        return view("income.in_edit_comp", compact('data'));
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

        if(!empty($request->get('kind_id'))){
            $kind_id = $request->get('kind_id');
            if($kind_id == 1){
                return redirect('po-category');
            }
        }
        return redirect('in-category');
    }

}