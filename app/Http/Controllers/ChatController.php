<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // public function index()
    // {
    //     return view('chat');
    // }

    public function saveData(Request $request)
    {
        $img = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;
            $img = $file->move('attachments/',$filename);
        }

        if($img == null){
            $request->validate([
                'msg' => 'required'
            ]);
        }

        $adminId = User::where('role', 'admin')->first();

        $message = new Messages;
        $message->msg = $request->msg;
        $message->attachment = $img;
        $message->sender = Auth::user()->id;
        $message->receiver = $adminId->id;
        $message->save();
    }

    public function getChat()
    {
        $user_id = Auth::user()->id;

        $data =  Messages::where('sender', $user_id)
        ->orWhere('receiver', $user_id)
        ->get();

        return $data;
    }

    public function getImage($id)
    {
        $img = Messages::find($id)->attachment;

        return view('image',compact('img'));
    }

}
