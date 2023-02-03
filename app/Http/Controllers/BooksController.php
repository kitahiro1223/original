<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use App\Lib\My_function;

use App\Models\Main_category;
use App\Models\Sub_category;
use App\Models\Icon;

use Illuminate\Support\Facades\Hash;

class BooksController extends Controller
{
    // ホーム、ログイン
    // public function index(Request $request) {        
    //     // ユーザーidがなければログイン画面へ
    //     $user_id = $request->session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
    //     if (!isset($user_id)){ return redirect('login'); exit;}

    //     return view('home', compact('user_id'));
    // }

    // ユーザー一覧
    public function user_list(Request $request) {        
        // ユーザーidがなければ, role = 0 ならばログイン画面へ
        $user_id = $request->session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
        $user_role = $request->session()->get('user_role');
        if (!isset($user_id)){return redirect('login'); exit;} 
        elseif ($user_role != 1) {return redirect('/'); exit;}

        $page_top['icon'] = 'box-icon fa-solid fa-user';
        $page_top['title'] = 'ユーザー一覧';

        $all_data = User::query()
            ->select('id', 'name', 'email')
            ->get();

        foreach ($all_data as $data) {
            $user['id']     = $data['id'];
            $user['name']   = $data['name'];
            $user['email']  = $data['email'];
            $users[] = $user;
        }

        return view('user_list', compact('user_id', 'page_top', 'users'));
    }

    // 設定
    public function setting(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        $request->session()->forget([
            'main_category',
            'sub_category',
            'main_cates',
            'sub_cates',
            'sub_cate',
            'to_sub',
            'main',
            'ex_sources',
            'in_to',
            'in_tos',
            'add_data',
            'add_main_categories',
            'add_sub_categories',
            'kind',
            'error'
        ]);
        
        return view("setting.setting"); 
    }
    public function account(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        $ob_all = User::query()
            ->select('name', 'email')
            ->where('id', '=', $user_id)
            ->get();
        
        foreach ($ob_all as $ob) {
            $account['name'] = $ob['name'];
            $account['email'] = $ob['email'];
        }
        
        return view("setting.account", compact('account')); 
    }
    public function category_kinds(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        $request->session()->forget([
            'main_category',
            'sub_category',
            'main_cates',
            'sub_cates',
            'sub_cate',
            'to_sub',
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
            'kind',
            'error'
        ]);

        return view("setting.category_kinds");         
    }

    public function categories(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        echo url()->previous(); exit;

        $request->session()->forget("add_cate");
        $request->session()->forget("error");

        // 機能の情報
        if(!empty($request->get("kind_id"))){
            $kind['id'] = $request->get("kind_id");
            $kind['name'] = $request->get("kind_name");
            $request->session()->put("kind", $kind);

            return redirect('categories'); exit;
        }

        // メインカテゴリーの情報
        if(!empty($request->get("main_cate_id"))){
            $to_sub['id'] = $request->get("main_cate_id");
            $to_sub['name'] = $request->get("main_cate_name");
            $request->session()->put("to_sub", $to_sub);

            return redirect('categories'); exit;
        }

        $kind = $request->session()->get("kind");

        // kind の全カテゴリーのicon, name
        // ユーザーの且つ既定の各機能のmainカテゴリ("id", "name")を取得
        $main_cate_obs = Main_category::query()
            ->select("id", "user_id", "name", "icon_id")
            ->where("kind_id", "=", $kind['id'])
            ->where("del_flg", "=", 0)
            ->where(function($query) use ($user_id) {
                $query
                    ->where("user_id", "=", 0)
                    ->orwhere("user_id", "=", $user_id);})
            ->get();        

            foreach ($main_cate_obs as $main_cate_ob) {
                $main_cate['id']   = $main_cate_ob['id'];
                $main_cate['user_id'] = $main_cate_ob['user_id'];
                $main_cate['name'] = $main_cate_ob['name'];
                $main_cate['icon_id'] = $main_cate_ob['icon_id'];
                $main_cate['icon'] = $main_cate_ob->icon->code;
                $main_cates[] = $main_cate;
            } 
            $request->session()->put("main_cates", $main_cates);
        // end カテゴリ        

        $to_sub = $request->session()->get("to_sub");

        if(!empty($to_sub)) {
            // sub の全カテゴリーのicon, name
            // ユーザーの且つ既定の各機能のmainカテゴリ("id", "name")を取得
            $sub_cate_obs = Sub_category::query()
                ->select("id", "user_id", "name", "main_category_id AS icon_id")
                ->where("main_category_id", "=", $to_sub['id'])
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
            return view("setting.categories", compact('kind','main_cates','sub_cates','to_sub'));
            exit; 
        }
        $request->session()->forget("sub_cates");
        
        return view("setting.categories", compact('kind','main_cates','to_sub')); 
    }
    
    public function category_add(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // 
        if(!empty($request->get("add_cate_kind"))){
            $add_cate['kind'] = $request->get("add_cate_kind");
            $add_cate['main'] = $request->get("add_cate_main");
            $request->session()->put("add_cate", $add_cate);
            return redirect('category-add'); exit;
        }
        $add_cate = $request->session()->get("add_cate");

        // 追加を押した時
        if(!empty($request->get("add_submit"))){
            $add_cate = $request->session()->get("add_cate");
            $add_cate['name'] = $request->get("add_cate_name");

            // 名前をバリデーション
            $add_cate['name']    = My_function::xss($add_cate['name']);

            // エラーの確認
            $error = NULL;
            $request->session()->forget('error');
            // エラーがあればエラーをsessionに入れる
            // エラーがなければ正常値をsessionに入れる
            if($add_cate['name'] == '') {
                $error['name'] = '名前を入力して下さい';
                $request->session()->put("error.name", $error['name']);
                $request->session()->forget('add_cate.name');
            } else {
                $request->session()->put("add_cate.name", $add_cate['name']);
                $request->session()->forget('error.name');
            }
            $request->session()->put("add_cate", $add_cate);

            if (!isset($error)) {
                // エラーがない場合登録
                $request->session()->forget('error');

                $kind = $request->session()->get("kind");
                $to_sub = $request->session()->get("to_sub");
                $add_cate = $request->session()->get("add_cate");
                
                if(empty($add_cate['main'])){
                    $main_category = new Main_category();
                    // プロパティに値を代入
                    $main_category->user_id = $user_id;
                    $main_category->kind_id = $add_cate['kind'];
                    $main_category->icon_id = 0;
                    $main_category->name    = $add_cate['name'];
                    // データベースに保存
                    $main_category->save();
                }
                elseif(!empty($add_cate['main'])){
                    $sub_category = new Sub_category();
                    // プロパティに値を代入
                    $sub_category->user_id           = $user_id;
                    $sub_category->main_category_id  = $add_cate['main'];
                    $sub_category->name              = $add_cate['name'];
                    // データベースに保存
                    $sub_category->save();
                }

                $request->session()->forget("add_cate");
                return redirect("categories");
                exit;
            } else {
                // エラーがある場合
                return redirect("category-add");
                exit;
            }
            return redirect('categories'); exit;
        }
        $error = $request->session()->get("error");
        
        return view("setting.category_add", compact('error')); 
    }

    public function category_delete(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}
        
        $delete_id = $request->get("id");
        $delete_sub_id = $request->get("sub_cate_id");

        if(!empty($delete_id)){
            $main_category = Main_category::query()
                ->where("id", "=", $delete_id)
                ->first();
            // 0:表示、1:非表示 の[del_flg]カラム値を1にして非表示
            $main_category->del_flg = 1;
            // データベースに保存
            $main_category->save();
        }
        
        if(!empty($delete_sub_id)){
            $sub_category = Sub_category::query()
                ->where("id", "=", $delete_sub_id)
                ->first();
            // 0:表示、1:非表示 の[del_flg]カラム値を1にして非表示
            $sub_category->del_flg = 1;
            // データベースに保存
            $sub_category->save();
        }

        return redirect('categories');
    }
}
