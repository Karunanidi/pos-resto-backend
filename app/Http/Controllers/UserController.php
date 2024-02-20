<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller{

    public function index(Request $request) {
        //get all users with pagination
        $users = DB::table('users')
        ->when($request->input('name'), function($query, $name){
            $query->where('name', 'like', '%'.$name.'%')
            ->orWhere('email', 'like', '%'.$name.'%');
        })
        ->paginate(10);
        return view('pages.user.index', compact('users'));
    }

    //create
    public function create(Request $request) {

        return view('pages.user.create');
    }

    //store
    public function store(Request $request) {

    //validate request
    $request->validate([
        'name' => 'required',
        'email' => 'required',
        'password' => 'required|min:8',
        'roles' => 'required|in:admin,staff,user',
    ]);

    //store req..
    $user = new User;
    $user -> name = $request->name;
    $user -> email = $request->email;
    $user -> password = Hash::make($request->password);
    $user -> roles = $request->roles;
    $user -> save();

    return redirect()->route('user.index')->with('success', 'User created successfully');
    }

    //update
    public function update(Request $request, $id)
    {
        //validate request
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'roles' => 'required|in:admin,staff,user',
        ]);

        // update the request...
        $user = User::find($id);
        $user -> name = $request->name;
        $user -> email = $request->email;
        $user -> roles = $request->roles;
        $user -> save();

        // if password is not empty
        if  ($request->password){
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('user.index')->with('success', 'User update successfully');

    }

    //show
    public function show($id) {
        return view('pages.user.show');
    }

    //edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.user.edit', compact('user'));
    }

    //destroy
    public function destroy($id)
    {
        //delete req..
        $user = User::find($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User deleted successfully');
    }
}
