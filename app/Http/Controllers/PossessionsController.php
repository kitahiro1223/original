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
        // 所持金のカテゴリーごとの合計
            // ユーザーの収入取得
            $income_obs = Income::query()
            ->select('possession_id')
            ->selectRaw('SUM(amount) AS sum ')
            ->where("user_id", "=", $user_id)
            ->where("del_flg", "=", 0)
            ->groupBy("possession_id")
            ->get();

            if(!empty($income_obs)) {
                foreach ($income_obs as $income_ob) {
                    $income['id'] = $income_ob->possession_id;
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

            $expenditure_obs = Expenditure::query()
            ->select('possession_id')
            ->selectRaw('SUM(amount) AS sum ')
            ->where("user_id", "=", $user_id)
            ->where("del_flg", "=", 0)
            ->groupBy("possession_id")
            ->get();
            if(!empty($expenditure_obs)) {
                foreach ($expenditure_obs as $expenditure_ob) {
                    $expenditure['id'] = $expenditure_ob->possession_id;
                    $expenditure['sum'] = -$expenditure_ob->sum;
                    $expenditures[] = $expenditure;
                }
                // echo "<pre>"; print_r($expenditures); echo "</pre>"; exit;
            } else {
                $expenditures = array(
                    ["id" => "1", "sum" => "0"],
                    ["id" => "2", "sum" => "0"],
                    ["id" => "3", "sum" => "0"]
                );
            }

            if(!empty($incomes)) {
                foreach ($incomes as $income) {
                    foreach ($expenditures as $expenditure) {
                        if($income['id'] == $expenditure['id']){
                            $possession['id'] = $income['id'];
                            $possession['sum'] = $income['sum'] + $expenditure['sum'];
                            $possessions[] = $possession;
                        }
                    }
                }
                
                foreach ($po_cates as $po_cate) {
                    foreach ($possessions as $possession) {
                        if($po_cate['id'] == $possession['id']){
                            $po_cate['sum'] = $possession['sum'];
                            $po_by_cates[] = $po_cate;
                        }
                    }
                    if(empty($po_cate['sum'])){
                        $po_cate['sum'] = 0;
                        $po_by_cates[] = $po_cate;
                    }
                }
            }else{
                $possessions_sum = 0;
                return view("possession.possession", compact("possessions_sum")); 
            }
        // 所持金総額
            $possessions_sum = array_sum(array_column($possessions, 'sum'));

        // 
        return view("possession.possession", compact("possessions_sum","po_by_cates")); 
    }

    public function po_category(Request $request) { 
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // 選択されたカテゴリーPOST値をsessionに入れてリダイレクト
        if(!empty($request->get("main_category_id"))){
            $main_category['id'] = $request->get("main_category_id");
            $main_category['name'] = $request->get("main_category_name");
            $main_category['icon_id'] = $request->get("icon_id");
            $main_category['icon'] = $request->get("icon");
            $request->session()->put("main_category", $main_category);
            return redirect('po-category', 302, [], true); 
        }

        // アクセス制御
            $referer = url()->previous();
            // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
            $referer_check = 
                preg_match('/possession/',$referer) +
                preg_match('/po-category/',$referer) +
                preg_match('/in-detail/',$referer) +
                preg_match('/in-edit/',$referer) +
                preg_match('/ex-detail/',$referer) +
                preg_match('/ex-edit/',$referer)
            ;
            if($referer_check === 0) {
                return redirect('possession');
            }

        // 選択されたカテゴリーを取得
        $main_cate = $request->session()->get("main_category");
        if(empty($main_cate)) {
            return redirect('possession');
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

        // ユーザーの収入取得
        $incomes = Income::query()
            ->select('id','kind_id','icon_id','date','amount')
            ->where("user_id", "=", $user_id)
            ->where("possession_id", "=", $main_cate['id'])
            ->where("del_flg", "=", 0);

        $result_obs = Expenditure::query()
            ->select('id','kind_id','icon_id','date','amount')
            ->where("user_id", "=", $user_id)
            ->where("possession_id", "=", $main_cate['id'])
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
        }
        
        if(!empty($results)){
            $po_by_cate_sum = array_sum(array_column($results,'amount'));        
        }else{
            $po_by_cate_sum = 0;
            return view("possession.po_category", compact('main','po_by_cate_sum')); 
            exit;
        }

        return view("possession.po_category", compact('main','results','po_by_cate_sum'));             
    }
}
