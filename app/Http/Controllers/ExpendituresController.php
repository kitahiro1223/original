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
                ->where("kind_id", "=", 3)
                ->where("del_flg", "=", 0)
                ->where(function($query) use ($user_id) {
                    $query
                        ->where("user_id", "=", 0)
                        ->orwhere("user_id", "=", $user_id);})
                ->get();

            foreach ($common_category_obs as $common_category_ob) {
                $ex_cate['id']   = $common_category_ob['id'];
                $ex_cate['name'] = $common_category_ob['name'];
                $ex_cate['icon_id'] = $common_category_ob['icon_id'];
                $ex_cate['icon'] = $common_category_ob->icon->code;
                $ex_cates[] = $ex_cate;
            } 
            $request->session()->put("ex_cates", $ex_cates);
        // end カテゴリ
        // 支出の計算
        // 支出のカテゴリーごとの合計
            // ユーザーの支出取得
            $expenditure_obs = expenditure::query()
                ->select('main_category_id')
                ->selectRaw('SUM(amount) AS sum ')
                ->where("user_id", "=", $user_id)
                ->where("del_flg", "=", 0)
                ->groupBy("main_category_id")
                ->get();

            if(!empty($expenditure_obs)) {
                foreach ($expenditure_obs as $expenditure_ob) {
                    $expenditure['id'] = $expenditure_ob->main_category_id;
                    $expenditure['sum'] = $expenditure_ob->sum;
                    $expenditures[] = $expenditure;
                }
            } else {
                $expenditures = array(
                    ["id" => "1", "sum" => "0"],
                    ["id" => "2", "sum" => "0"],
                    ["id" => "3", "sum" => "0"]
                );
            }
            if(!empty($expenditures)) {
                foreach ($ex_cates as $ex_cate) {
                    foreach ($expenditures as $expenditure) {
                        if($ex_cate['id'] == $expenditure['id']){
                            $ex_cate['sum'] = $expenditure['sum'];
                            $ex_main_cates[] = $ex_cate;
                        }
                    }
                    if(empty($ex_cate['sum'])){
                        $ex_cate['sum'] = 0;
                        $ex_main_cates[] = $ex_cate;
                    }
                }
            }else{
                $expenditures_sum = 0;
                return view("expenditure.expenditure", compact('expenditures_sum')); 
            }
        // 所持金総額
            $expenditures_sum = array_sum(array_column($expenditures, 'sum'));

        return view("expenditure.expenditure", compact('expenditures_sum', 'ex_main_cates')); 
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

        // アクセス制御
            $referer = url()->previous();
            // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
            $referer_check = 
                preg_match('/expenditure/',$referer) +
                preg_match('/ex-sub-category/',$referer) +
                preg_match('/ex-by-category/',$referer) 
            ;
            if($referer_check === 0) {return redirect('expenditure');}
        
        $main_category = $request->session()->get("main_category");
        if(empty($main_category)) {return redirect('expenditure');}
        $main['title'] = $main_category['name'];
        $main['icon'] = $main_category['icon'];
        $request->session()->put("main", $main);

        // サブカテゴリーから戻ってきたとき削除
        $request->session()->forget('sub_cate');
        
        // カテゴリ
            // 既定&ユーザー設定カテゴリーを取得（$common_category_obs）
            $common_category_obs = Sub_category::query()
                ->select("id", "name", "main_category_id as icon_id")
                ->where("del_flg", "=", 0)
                ->where("main_category_id", "=", $main_category['id'])
                ->where(function($query) use ($user_id) {
                    $query
                        ->where("user_id", "=", 0)
                        ->orwhere("user_id", "=", $user_id);})
                ->get();
            
                foreach ($common_category_obs as $common_category_ob) {
                    $ex_cate['id']   = $common_category_ob['id'];
                    $ex_cate['name'] = $common_category_ob['name'];
                    if($common_category_ob['icon_id'] >= 12){
                        $common_category_ob['icon_id'] = 0;
                    }
                    $ex_cate['icon_id'] = $common_category_ob['icon_id'];
                    $ex_cate['icon'] = $common_category_ob->icon->code;
                    $ex_cates[] = $ex_cate;
                } 
            if(!empty($ex_cates)) {
                $request->session()->put("ex_cates", $ex_cates);
            }else{
                return redirect('ex-sub-error');
            }
        // end カテゴリ
                
        $expenditure_obs = Expenditure::query()
            ->select("sub_category_id")
            ->selectRaw('SUM(amount) AS sum')
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

        if(!empty($expenditure_obs)) {
            foreach ($expenditure_obs as $expenditure_ob) {
                $expenditure['id'] = $expenditure_ob->sub_category_id;
                $expenditure['sum'] = $expenditure_ob->sum;
                $expenditures[] = $expenditure;
            }
        } else {
            $expenditures = array(
                ["id" => "1", "sum" => "0"],
                ["id" => "2", "sum" => "0"],
                ["id" => "3", "sum" => "0"]
            );
        }
        foreach ($ex_cates as $ex_cate) {
            foreach ($expenditures as $expenditure) {
                if($ex_cate['id'] == $expenditure['id']){
                    $ex_cate['sum'] = $expenditure['sum'];
                    $ex_sub_cates[] = $ex_cate;
                }
            }
            if(empty($ex_cate['sum'])){
                $ex_cate['sum'] = 0;
                $ex_sub_cates[] = $ex_cate;
            }
        }
        // 所持金総額
        $expenditures_sum = array_sum(array_column($expenditures, 'sum'));

        return view("expenditure.ex_sub_category", compact('expenditures_sum', 'ex_sub_cates', 'main')); 
    }

    public function ex_by_category(Request $request) { 

        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}      

        if(!empty($request->get("sub_cate_id"))){
            $sub_cate['id'] = $request->get("sub_cate_id");
            $sub_cate['name'] = $request->get("sub_cate_name");
            $request->session()->put("sub_cate", $sub_cate);
            return redirect('ex-by-category', 302, [], true); 
        }
        // アクセス制御
        $referer = url()->previous();
        // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
        $referer_check = 
            preg_match('/ex-sub-category/',$referer) +
            preg_match('/ex-by-category/',$referer) +
            preg_match('/ex-detail/',$referer) +
            preg_match('/ex-edit/',$referer) +
            preg_match('/ex-add/',$referer)
        ;
        if($referer_check === 0) {return redirect('expenditure');}

        
        // 選択されたカテゴリーを取得
        $sub_cate = $request->session()->get("sub_cate");
        $main = $request->session()->get("main");
        $main['title'] = $sub_cate['name'];
        if(empty($sub_cate)) {return redirect('expenditure');}
        if(empty($main)) {return redirect('expenditure');}
        
        // 追加、編集画面から戻ってきた場合、入力内容削除
            $request->session()->forget('data');
            $request->session()->forget('add_data');
            $request->session()->forget('edit_data');
            $request->session()->forget('add_main_categories');
            $request->session()->forget('ex_sources');            
            $request->session()->forget('error');

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
        }
        if(!empty($by_cates)) { 
            $ex_by_cate_sum = array_sum(array_column($by_cates,'amount'));        
        }else{
            $ex_by_cate_sum = 0;
            return view("expenditure.ex_by_category", compact('main','ex_by_cate_sum')); 
            exit;
        }

        return view("expenditure.ex_by_category", compact('main','by_cates','ex_by_cate_sum')); 
    }

    public function ex_add(Request $request) {

        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // 最初に読み込まれた時
            // アクセス制御
                $referer = url()->previous();
                // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
                $referer_check = 
                    preg_match('/ex-by-category/',$referer) +
                    preg_match('/ex-add/',$referer) +
                    preg_match('/ex-add-confirm/',$referer)
                ;
                if($referer_check === 0) {
                    return redirect('expenditure');
                }
            // 前ページから[カテゴリー]情報を受け取る
                $add_data['main_category_id']   = $request->session()->get("main_category.id");
                $add_data['main_category_name'] = $request->session()->get("main_category.name");
                if(empty($add_data['main_category_id'])) {return redirect('expenditure');}
                if(empty($add_data['main_category_name'])) {return redirect('expenditure');}

                $add_data['sub_category_id']    = $request->session()->get("sub_cate.id");
                $add_data['sub_category_name']  = $request->session()->get("sub_cate.name");
                if(empty($add_data['sub_category_id'])) {return redirect('expenditure');}
                if(empty($add_data['sub_category_name'])) {return redirect('expenditure');}

                $add_data['icon']  = $request->session()->get("main.icon");
                if(empty($add_data['icon'])) {return redirect('expenditure');}
                
            // 支出(kind_id = 3)の全main_categoryを得てsessionに入れる
                $all_in_main_category = Main_category::query()
                ->where(function($query) {
                    $query
                    ->where("del_flg", "=", 0)
                    ->where("kind_id", "=", 3);})
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

            // [サブカテゴリー]の選択肢を得てsessionに入れる
                $all_ex_sub_category = Sub_category::query()
                    ->where("user_id", "=", 0)
                    ->where("del_flg", "=", 0)
                    ->where("main_category_id", "=", $add_data['main_category_id'])
                    ->select('id', 'name')
                    ->get();
                
                foreach ($all_ex_sub_category as $ex_sub_category) {
                    $add_sub_category['id']    = $ex_sub_category->id;
                    $add_sub_category['name']  = $ex_sub_category->name;
                    $add_sub_categories[] = $add_sub_category;
                }
                $request->session()->put("add_sub_categories", $add_sub_categories);
             
            // [支出元]の選択肢を得てsessionに入れる("kind_id", "=", 1)
                $all_po_category = Main_category::query()
                ->where(function($query) {
                    $query
                        ->where("kind_id", "=", 1)
                        ->where("del_flg", "=", 0);})
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
                    // $request->session()->put("add_data.radio", $add_data['radio']);
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

        // アクセス制御
            $referer = url()->previous();
            // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
            $referer_check = 
                preg_match('/ex-add/',$referer) +
                preg_match('/ex-add-confirm/',$referer)
            ;
            if($referer_check === 0) {return redirect('expenditure');}        

        // 前ページから情報を受け取る        
            $add_data = $request->session()->get("add_data");
            if(empty($add_data)) {return redirect('expenditure');}
            // if($add_data['radio'] == 0) {
            //     $add_data['notice'] = "OFF";
            // } elseif ($add_data['radio'] == 1) {
            //     $add_data['notice'] = "ON";
            // }
            $add_data['comment'] = html_entity_decode($add_data['comment']);
            $request->session()->put("add_data", $add_data);

        return view("expenditure.ex_add_confirm", compact('add_data'));
    }

    public function ex_add_comp(Request $request) {

        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;
        }

        // アクセス制御
        $referer = url()->previous();
        // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
        $referer_check = 
            preg_match('/ex-add-confirm/',$referer)+
            preg_match('/ex-add-comp/',$referer)
        ;
        if($referer_check === 0) {return redirect('expenditure');}        

        // POST から リダイレクト
        if(!empty($request->get("add_submit"))){            
            return redirect('ex-add-comp', 302, [], true); 
        }

        // データの受け取り
        $add_data = $request->session()->get("add_data");
        if(empty($add_data)) {return redirect('expenditure');}
        // 送られているのは「name」なのでDBに登録できるように
        // id と name の照合
        $add_main_categories    = $request->session()->get("add_main_categories");
        $add_sub_categories     = $request->session()->get("add_sub_categories");
        $ex_sources             = $request->session()->get("ex_sources");
        if(empty($add_main_categories)) {return redirect('expenditure');}
        if(empty($add_sub_categories)) {return redirect('expenditure');}
        if(empty($ex_sources)) {return redirect('expenditure');}        
        
        foreach ($add_main_categories as $add_main_category) {
            if($add_data['main_category'] == $add_main_category['name']) {
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

        // echo "<pre>"; print_r($add_data); echo "</pre>"; exit;

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
        // $expenditure->notice            = $add_data['radio'];
        $expenditure->comment           = $add_data['comment'];
        // データベースに保存
        $expenditure->save();

        return view("expenditure.ex_add_comp");
    }

    public function ex_detail(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // アクセス制御
        $referer = url()->previous();
        // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
        $referer_check = 
            preg_match('/po-category/',$referer) +
            preg_match('/ex-by-category/',$referer) +
            preg_match('/ex-detail/',$referer)
        ;
        if($referer_check === 0) {return redirect('expenditure');}

        if(!empty($request->get("id"))){
            $data['id'] = $request->get("id");
            $data['kind_id'] = $request->get("kind_id");
            $request->session()->put("data", $data);
            return redirect('ex-detail'); exit;
        }

        $data = $request->session()->get("data");
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
            // $data_one['notice']             = $data_ob['notice'];
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

        if(empty($data_one)){
            return redirect('expenditure'); exit;
        }

        // if($data_one['notice'] == 0 || empty($data_one['notice'])) {
        //     $data_one['notice'] = "OFF";
        // } elseif ($data_one['notice'] == 1) {
        //     $data_one['notice'] = "ON";
        // }
        
        return view("expenditure.ex_detail", compact('data','data_one'));
    }

    public function ex_edit(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // アクセス制御
        $referer = url()->previous();
        // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
        $referer_check = 
            preg_match('/po-category/',$referer) +
            preg_match('/ex-by-category/',$referer) +
            preg_match('/ex-detail/',$referer) +
            preg_match('/ex-edit/',$referer) +
            preg_match('/ex-edit-confirm/',$referer)
        ;
        if($referer_check === 0) {return redirect('expenditure');}

        // POSTされたデータのidをsessionへ
        if(!empty($request->get("id"))){
            $data['id'] = $request->get("id");
            $data['kind_id'] = $request->get("kind_id");
            $request->session()->put("data", $data);
            return redirect('ex-edit'); exit;
        }        

        // 入力後「確認」をクリックしたとき
            if(!empty($request->get("edit_submit"))) {
                // POSTをSESSIONに入れる
                $edit_data = $request->all();                
                // $request->session()->put("edit_data", $edit_data);
                
                // データのidを受け取る
                $data = $request->session()->get("data");

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
                        $edit_data['comment'] = html_entity_decode($edit_data['comment']);
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
                    // $request->session()->put("edit_data.radio", $edit_data['radio']);

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
        $data = $request->session()->get("data");
        if(empty($data['id'])){ return redirect('expenditure'); exit; }

        // id から情報を取得
            $data_obs = Expenditure::query()
                ->where("id", "=", $data['id'])
                ->get();

            if(empty($data_obs)) {return redirect('expenditure');}

            foreach ($data_obs as $data_ob) {
                $edit_data['id'] = $data_ob['id'];
                $edit_data['main_category_id']   = $data_ob->main_category_id;
                $edit_data['main_category_name'] = $data_ob->main_category->name;
                $edit_data['sub_category_id']    = $data_ob->sub_category_id;
                $edit_data['sub_category_name']  = $data_ob->sub_category->name;
                $edit_data['icon_id']            = $data_ob->icon_id;
                $edit_data['icon']               = $data_ob->icon->code;
                $edit_data['date']               = $data_ob->date;
                $edit_data['ex_source']          = $data_ob->possession_id;
                $edit_data['amount']             = $data_ob->amount;
                $edit_data['name']               = $data_ob->name;
                $edit_data['notice']             = $data_ob->notice;
                $edit_data['comment']            = $data_ob->comment;
            }        
        // 支出元の値を変換
            $main_cates_ob = Main_category::query()
                ->select('id', 'name')
                ->where("user_id", "=", $user_id)
                ->where("del_flg", "=", 0)
                ->where("id", "=", $edit_data['ex_source'])
                ->get();
            
            foreach ($main_cates_ob as $main_cate_ob) {
                if($main_cate_ob['id'] == $edit_data['ex_source']){
                    $edit_data['ex_source'] = $main_cate_ob['name'];
            }}
            // if($edit_data['notice'] == 0 || empty($edit_data['notice'])) {
            //         $edit_data['notice'] = "OFF";
            //     } elseif ($edit_data['notice'] == 1) {
            //         $edit_data['notice'] = "ON";
            // }
            // セッションに入れる
            $request->session()->put("edit_data", $edit_data);

        // [メインカテゴリー]の選択肢を得る
        // 支出(kind_id = 3)の全main_categoryを得てsessionに入れる
            $all_in_main_category = Main_category::query()
            ->where(function($query) {
                $query
                ->where("del_flg", "=", 0)
                ->where("kind_id", "=", 3);})
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

        // [サブカテゴリー]の選択肢を得てsessionに入れる
            $all_ex_sub_category = Sub_category::query()
                ->where("user_id", "=", 0)
                ->where("del_flg", "=", 0)
                ->where("main_category_id", "=", $edit_data['main_category_id'])
                ->select('id', 'name')
                ->get();
            
            foreach ($all_ex_sub_category as $ex_sub_category) {
                $add_sub_category['id']    = $ex_sub_category->id;
                $add_sub_category['name']  = $ex_sub_category->name;
                $add_sub_categories[] = $add_sub_category;
            }
            $request->session()->put("add_sub_categories", $add_sub_categories);    

        // [支出元]の選択肢を得てsessionに入れる("kind_id", "=", 1)
            $all_po_category = Main_category::query()
            ->where(function($query) {
                $query
                    ->where("kind_id", "=", 1)
                    ->where("del_flg", "=", 0);})
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

        $error = $request->session()->get("error");
        
        return view("expenditure.ex_edit", compact('data','edit_data', 'add_main_categories', 'add_sub_categories', 'ex_sources', 'error'));
    }

    public function ex_edit_confirm(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // アクセス制御
            $referer = url()->previous();
            // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
            $referer_check = 
                preg_match('/ex-edit/',$referer) +
                preg_match('/ex-edit-confirm/',$referer)
            ;
            if($referer_check === 0) {return redirect('expenditure');}

        // 前ページから情報を受け取る        
            $edit_data = $request->session()->get("edit_data");
            if(empty($edit_data)) {return redirect('expenditure');}
            $edit_data['comment'] = html_entity_decode($edit_data['comment']);
            $request->session()->put("edit_data", $edit_data);            

            // if($edit_data['radio'] == 0) {
            //     $edit_data['notice'] = "OFF";
            // } elseif ($edit_data['radio'] == 1) {
            //     $edit_data['notice'] = "ON";
            // }
        echo "<pre>"; print_r($edit_data); echo "</pre>"; exit;

        return view("expenditure.ex_edit_confirm", compact('edit_data'));
    }

    public function ex_edit_comp(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // アクセス制御
        $referer = url()->previous();
        // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
        $referer_check = 
        preg_match('/ex-edit-confirm/',$referer)+
        preg_match('/ex-edit-comp/',$referer)
        ;
        if($referer_check === 0) {return redirect('expenditure');}        

        // POST から リダイレクト
        if(!empty($request->get("edit_submit"))){            
            return redirect('ex-edit-comp', 302, [], true);
        }

        // データの受け取り
        $edit_data = $request->session()->get("edit_data");
        if(empty($edit_data)) {return redirect('expenditure');}

        // 送られているのは「name」なのでDBに登録できるように
        // id と name の照合
        $edit_main_categories   = $request->session()->get("add_main_categories");
        $edit_sub_categories    = $request->session()->get("add_sub_categories");
        $ex_sources             = $request->session()->get("ex_sources");
        if(empty($edit_main_categories)) {return redirect('expenditure');}
        if(empty($edit_sub_categories)) {return redirect('expenditure');}
        if(empty($ex_sources)) {return redirect('expenditure');}

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
        // $expenditure->notice            = $edit_data['radio'];
        $expenditure->comment           = $edit_data['comment'];
        // データベースに保存
        $expenditure->save();

        $data = $request->session()->get("data");

        return view("expenditure.ex_edit_comp", compact('data'));
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

        if(!empty($request->get('kind_id'))){
            $kind_id = $request->get('kind_id');
            if($kind_id == 1){
                return redirect('po-category');
            }
        }
        return redirect('ex-by-category');
    }

    public function ex_sub_error(Request $request) {
        return view("expenditure.ex_sub_error");
    }
}
