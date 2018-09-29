<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('users');
    }

    public function allusers(Request $request)
    {
        $columns = ['id', 'name', 'email'];
        $totalData = User::count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if (empty($request->input('search.value')))
            $users = User::offset($start)->limit($limit)->orderBy($order,$dir)->get();
        else {
            $search = $request->input('search.value'); 
            $users =  User::where('id','LIKE',"%{$search}%")->orWhere('name', 'LIKE',"%{$search}%")->offset($start)->limit($limit)->orderBy($order,$dir)->get();

            $totalFiltered = User::where('id','LIKE',"%{$search}%")->orWhere('name', 'LIKE',"%{$search}%")->count();
        }

        $users = $users->map(function($set) {
            $set['email'] = "<a href=''>".$set->email."</a>";
            return $set;
        });

        echo json_encode([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $users->toArray()
        ]);
    }
}
