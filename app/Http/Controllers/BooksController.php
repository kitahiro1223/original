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

        $user_role = $request->session()->get('user_role');

        return view('user_list', compact('user_id','user_role','page_top','users'));
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
            // 'main_cates',
            // 'sub_cates',
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
            
            // 'po_by_cates',
            // 'in_to',
            // 'to_sub',
            // 'cate_results'
        ]);

        return view("setting.category_kinds");         
    }

    public function categories(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // 機能のPOST情報リダイレクト
        if(!empty($request->get("kind_id"))){
            $kind['id'] = $request->get("kind_id");
            $kind['name'] = $request->get("kind_name");
            $request->session()->put("kind", $kind);
            return redirect('categories'); exit;
        }

        // アクセス制御
            $referer = url()->previous();
            // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
            $referer_check = 
                preg_match('/category-kinds/',$referer) +
                preg_match('/categories/',$referer) +
                preg_match('/sub_categories/',$referer) +
                preg_match('/category-add/',$referer)
            ;
            if($referer_check === 0) {return redirect('category-kinds');}
            
        // 機能の情報取得
            $kind = $request->session()->get("kind");
            if(empty($kind)) {return redirect('category-kinds');}

        // 戻ってきたらセッション削除
            $request->session()->forget("main_cate");
            $request->session()->forget("sub_cate");
            $request->session()->forget("sub_cates");
            $request->session()->forget("add_cate");
            $request->session()->forget("error");
            
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
        // end カテゴリ        
        if(!empty($main_cates)) { 
            $request->session()->put("main_cates", $main_cates);
        }else{
            return view("setting.categories", compact('kind')); 
            exit;
        }
        
        return view("setting.categories", compact('kind','main_cates')); 
    }
    
    public function sub_categories(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}

        // メインカテゴリーの情報、POSTリダイレクト
        if(!empty($request->get("main_cate_id"))){
            $main_cate['id'] = $request->get("main_cate_id");
            $main_cate['name'] = $request->get("main_cate_name");
            $main_cate['icon_id'] = $request->get("icon_id");
            $main_cate['icon'] = $request->get("icon");
            $request->session()->put("main_cate", $main_cate);   
            return redirect('sub-categories'); exit;
        }

        // アクセス制御
            $referer = url()->previous();
            // echo "<pre>"; print_r($referer); echo "</pre>"; exit;
            $referer_check = 
                preg_match('/categories/',$referer) +
                preg_match('/sub_categories/',$referer) +
                preg_match('/category-add/',$referer)
            ;
            if($referer_check === 0) {return redirect('category-kinds');}

        // 機能の情報
            $kind = $request->session()->get("kind");
            $main_cate = $request->session()->get("main_cate");
            if(empty($kind)) {return redirect('category-kinds');}
            if(empty($main_cate)) {return redirect('category-kinds');}

        // 追加から戻ってきた場合、セッション削除
            $request->session()->forget("add_cate");
            $request->session()->forget("error");

            // sub の全カテゴリーのicon, name
            // ユーザーの且つ既定の各機能のmainカテゴリ("id", "name")を取得
            $sub_cate_obs = Sub_category::query()
                ->select("id", "user_id", "name", "main_category_id AS icon_id")
                ->where("main_category_id", "=", $main_cate['id'])
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
                    $sub_cates[] = $sub_cate;
                } 
                if(!empty($sub_cates)){
                    $request->session()->put("sub_cates", $sub_cates);
                }else{
                    return view("setting.sub_categories", compact('kind','main_cate'));
                    exit;
                }
            // end カテゴリ
        
        return view("setting.sub_categories", compact('kind','main_cate','sub_cates')); 
    }
    
    public function category_add(Request $request) {
        // ユーザーidがなければログイン画面へ
        $user_id = $request->session()->get('user_id');
        if (!isset($user_id)){ return redirect('login'); exit;}
        
        // POST
        if(!empty($request->get("add_cate_kind"))){
            $add_cate['kind'] = $request->get("add_cate_kind");
            $add_cate['main'] = $request->get("add_cate_main");
            $request->session()->put("add_cate", $add_cate);
            return redirect('category-add'); exit;
        }
        
        $add_cate = $request->session()->get("add_cate");
        if(empty($add_cate)) {return redirect('category-kinds');}

        // 追加を押した時
            if(!empty($request->get("add_submit"))){
                $name = $request->get("add_cate_name");
                
                // 名前をバリデーション
                $add_cate = $request->session()->get("add_cate");
                $add_cate['name']    = My_function::xss($name);

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

                if (!isset($error)) {
                    // エラーがない場合登録
                    $request->session()->forget('error');

                    $add_cate = $request->session()->get("add_cate");

                    if(!empty($add_cate['main'])){
                        $sub_category = new Sub_category();
                        // プロパティに値を代入
                        $sub_category->user_id           = $user_id;
                        $sub_category->main_category_id  = $add_cate['main'];
                        $sub_category->name              = $add_cate['name'];
                        // データベースに保存
                        $sub_category->save();

                    }elseif(!empty($add_cate['kind'])){
                        $main_category = new Main_category();
                        // プロパティに値を代入
                        $main_category->user_id = $user_id;
                        $main_category->kind_id = $add_cate['kind'];
                        $main_category->icon_id = 0;
                        $main_category->name    = $add_cate['name'];
                        // データベースに保存
                        $main_category->save();
                    }

                    $request->session()->forget("add_cate");
                    return redirect("categories");
                    exit;
                } else {
                    // エラーがある場合
                    return redirect("category-add");exit;
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
