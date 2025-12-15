<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $sessions = \DB::table('sessions')->orderBy('last_activity','desc')->paginate(20);
        return view('sessions.index', compact('sessions'));
    }

    public function destroy($id)
    {
        \DB::table('sessions')->where('id',$id)->delete();
        return back();
    }
}
