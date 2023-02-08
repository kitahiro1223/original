<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
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

        $user_id = $request->session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
        $request->session()->put('user_id', $user_id);

        $all_data = User::query()
            ->where('id', '=', $user_id)
            ->get();

        foreach($all_data as $data) {
            $user_role = $data['role'];
        }
        $request->session()->put('user_role', $user_role);

        return view('home', compact('user_role'));
    }
}
