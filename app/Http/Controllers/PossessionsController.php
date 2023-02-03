<?php

namespace App\Http\Controllers;

use App\Models\Possession;
use Illuminate\Http\Request;

use App\Lib\My_function;
use App\Models\Expenditure;
use App\Models\Income;
use App\Models\Main_category;
use App\Models\Sub_category;
use App\Models\Icon;

class PossessionsController extends Controller
{
    public function possession(Request $request) { 
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
            'po_cates',
            'po_by_cates',
            'cate_results',
            'main_cates',
            'error'
        ]);

        // カテゴリ
        // ユーザーの且つ既定の所持金のmainカテゴリ("id", "name")を取得（$common_category_obs）
        $common_category_obs = Main_category::query()
            ->select("id", "name", "icon_id", "del_flg")
            ->where("kind_id", "=", 1)
            ->where("del_flg", "=", 0)
            ->where(function($query) use ($user_id) {
                $query
                    ->where("user_id", "=", 0)
                    ->orwhere("user_id", "=", $user_id);})
            ->get();

            foreach ($common_category_obs as $common_category_ob) {
                $po_cate['id']   = $common_category_ob['id'];
                $po_cate['name'] = $common_category_ob['name'];
                $po_cate['icon_id'] = $common_category_ob['icon_id'];
                $po_cate['icon'] = $common_category_ob->icon->code;
                $po_cates[] = $po_cate;
            } 
            $request->session()->put("po_cates", $po_cates);
        // end カテゴリ
        
        // 所持金の計算
        foreach ($po_cates as $po_cate) {
            // ユーザーの且つmain_cateのデータ取得
            $incomes = Income::query()
                ->select('id','user_id','kind_id','icon_id','date','amount')
                ->where("user_id", "=", $user_id)
                ->where("possession_id", "=", $po_cate['id'])
                ->where("del_flg", "=", 0);

            $result_obs = Expenditure::query()
                ->select('id','user_id','kind_id','icon_id','date','amount')
                ->where("user_id", "=", $user_id)
                ->where("possession_id", "=", $po_cate['id'])
                ->where("del_flg", "=", 0)
                ->union($incomes)
                ->orderby('date', 'DESC')
                ->get();

            foreach ($result_obs as $result_ob) {
                $result['id']            = $result_ob->id;
                $result['user_id']       = $result_ob->user_id;
                $result['kind_id']       = $result_ob->kind_id;
                $result['icon_id']       = $result_ob->icon_id;
                $result['icon']          = $result_ob->icon->code;
                $result['date']          = $result_ob->date;
                $result['amount']        = $result_ob->amount;
                // 機能が支出だった場合, 符号を反転（+を-にする）
                if($result['kind_id'] == 3) {
                    $result['amount'] = -$result['amount'];
                }
                $results[] = $result;
                $amounts[] = $result['amount'];
            }

            if(empty($amounts)) { 
                $amounts[] = 0; 
            }else {
                $amounts_sum = array_sum($amounts);
            }
            if(empty($results)) { }
            
            $cate_results[$po_cate['id']] = $results;
            $po_cate['sum'] = $amounts_sum;
            $po_by_cates[$po_cate['id']] = $po_cate;
            $results = [];
            $amounts = [];
        }

        // mainカテゴリーのname, icon、 mainカテゴリーごとのsum
        // possession に表示
        $request->session()->put('po_by_cates', $po_by_cates);
        // mainカテゴリーごとの全データ
        // by_category に表示
        $request->session()->put('cate_results', $cate_results);

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
                    
        // [サブカテゴリー]の選択肢を得てsessionに入れる
        $all_ex_sub_category = Sub_category::query()
            ->where("user_id", "=", 0)
            ->select('id', 'name', "main_category_id")
            ->get();
        
            foreach ($all_ex_sub_category as $ex_sub_category) {
                $add_sub_category['id']    = $ex_sub_category->id;
                $add_sub_category['name']  = $ex_sub_category->name;
                $add_sub_category['main_category_id']  = $ex_sub_category->main_category_id;
                $add_sub_categories[] = $add_sub_category;
            }
            $request->session()->put("add_sub_categories", $add_sub_categories);        

        // [支出元]の選択肢を得てsessionに入れる("kind_id", "=", 1)
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
                $ex_source['id']    = $po_category->id;
                $ex_source['name']  = $po_category->name;
                $ex_sources[] = $ex_source;
            }
            $request->session()->put("ex_sources", $ex_sources);      

        // 
        return view("possession.possession", compact("po_by_cates", 'ex_source', 'add_main_categories')); 
    }

    public function po_category(Request $request) { 
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // POST値があればsessionに入れてリダイレクト
        if(!empty($request->get("main_category_id"))){
            $main_category['id'] = $request->get("main_category_id");
            $main_category['name'] = $request->get("main_category_name");
            $main_category['icon_id'] = $request->get("icon_id");
            $main_category['icon'] = $request->get("icon");
            $request->session()->put("main_category", $main_category);
            return redirect('po-category', 302, [], true); 
        }
        $main_cate = $request->session()->get("main_category");
        $main['title'] = $main_cate['name'];
        $main['icon'] = $main_cate['icon'];
        $request->session()->put("main", $main);

        // 追加、編集画面から戻ってきた場合、入力内容削除
        $request->session()->forget('add_data');
        $request->session()->forget('edit_data');
        $request->session()->forget('data');
        $request->session()->forget('error');

        $cate_results = $request->session()->get('cate_results');
        $results = $cate_results[$main_cate['id']];
        // echo "<pre>"; print_r($results); echo "</pre>"; exit;

        if(empty($results)) { 
            return view("possession.po_category", compact('main')); 
            exit;
        }

        return view("possession.po_category", compact('main', 'results'));             
    }

    public function po_add(Request $request) { 
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // 入力後「追加」をクリックしたとき
        if(!empty($request->get("add_submit"))) {
            // POSTをSESSIONに入れる
            $add_data = $request->all();
            $request->session()->put("add_data", $add_data);
            
            // コメントをバリデーション
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
                $error['ex_source'] = '振替元を選択して下さい';
                $request->session()->put("error.ex_source", $error['ex_source']);
                $request->session()->forget('add_data.ex_source');
            } else {
                $request->session()->put("add_data.ex_source", $add_data['ex_source']);
                $request->session()->forget('error.ex_source');
            }
            if($add_data['in_to'] == "") {
                $error['in_to'] = '振替先を選択して下さい';
                $request->session()->put("error.in_to", $error['in_to']);
                $request->session()->forget('add_data.in_to');
            } else {
                $request->session()->put("add_data.in_to", $add_data['in_to']);
                $request->session()->forget('error.in_to');
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
            $request->session()->put("add_data.date", $add_data['date']);
            $request->session()->put("add_data.ex_source", $add_data['ex_source']);
            $request->session()->put("add_data.in_to", $add_data['in_to']);
            $request->session()->put("add_data.comment", $add_data['comment']);

            // エラーがない場合確認画面へ
            if (!isset($error)) {
                $request->session()->forget('error');
                return redirect("po-add-confirm");
                exit;
            } else {
                return redirect("po-add");
                exit;
            }
        }
        // end 入力後「追加」をクリックしたとき        
        
        $add_main_categories = $request->session()->get('add_main_categories');
        $add_sub_categories = $request->session()->get('add_sub_categories');
        $ex_sources = $request->session()->get("ex_sources");
        $in_tos = $ex_sources;

        $error = $request->session()->get("error");

        return view("possession.po_add", compact('add_main_categories','add_sub_categories','ex_sources','in_tos','error'));
    }

    public function po_add_confirm(Request $request) {
        
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // 前ページから情報を受け取る        
            $add_data = $request->session()->get("add_data");

            $request->session()->put("add_data", $add_data);

        return view("possession.po_add_confirm", compact('add_data'));
    }

    public function po_add_comp(Request $request) {

        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;
        }

        // POST から リダイレクト
        if(!empty($request->get("add_submit"))){    
            return redirect('po-add-comp', 302, [], true); 
        }
    
        // データの受け取り
        $add_data = $request->session()->get("add_data");

        // 送られているのは「name」なのでDBに登録できるように
        // id と name の照合
        $ex_sources = $request->session()->get("ex_sources");
        $in_tos = $ex_sources;

        foreach ($ex_sources as $ex_source) {
            if($ex_source['name'] == $add_data['ex_source']) {
                $add_data['ex_source'] = $ex_source['id'];
        }}
        foreach ($in_tos as $in_to) {
            if($in_to['name'] == $add_data['in_to']) {
                $add_data['in_to'] = $in_to['id'];
        }}
        
        $expenditure = new Expenditure();
        // プロパティに値を代入
        $expenditure->user_id           = $user_id;
        $expenditure->date              = $add_data['date'];
        $expenditure->possession_id     = $add_data['ex_source'];
        $expenditure->amount            = $add_data['amount'];
        $expenditure->comment           = $add_data['comment'];
        // データベースに保存
        $expenditure->save();

        $income = new Income();
        // プロパティに値を代入
        $income->user_id           = $user_id;
        $income->date              = $add_data['date'];
        $income->possession_id     = $add_data['in_to'];
        $income->amount            = $add_data['amount'];
        $income->comment           = $add_data['comment'];
        // データベースに保存
        $income->save();

        return view("possession.po_add_comp");
    }
}
