<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ChatAdminController extends Controller
{
    public function chat(){
        return view('admin.chat');
    }

    public function viewChat($userId)
    {
        $user = User::findOrFail($userId);

        $userMsg = Messages::where('sender', $userId)
        ->where('is_seen', 0);

        $userMsg->update(['is_seen' => 1]);

        return view('admin.viewChat',compact('user'));
    }

    public function storeChat(Request $request, $userId)
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

        $message = new Messages;
        $message->msg = $request->msg;
        $message->attachment = $img;
        $message->sender = Auth::user()->id;
        $message->receiver = $userId;
        $message->save();
    }


    public function getChat($userId)
    {
        $data =  Messages::where('sender', $userId)
        ->orWhere('receiver', $userId)
        ->get();

        $this->viewChat($userId); // to update unseen to 1 if i stop in userChat

        return $data;
    }

    public function fetchNewMessages()
    {
        $userIds = Messages::pluck('sender');
        $users = User::whereIn('id', $userIds)->get();

        // for navbar blade
        $unSeenUsersCount = DB::table('messages')
        ->join('users', 'users.id', '=', 'messages.sender')
        ->where('users.role', '=', 'user')
        ->where('is_seen', 0)
        ->distinct('sender')
        ->count('sender');

        $latestSenders = DB::table('users')
        ->select('users.*', 'messages.msg', 'messages.created_at')
        ->join('messages', function ($join) {
            $join->on('users.id', '=', 'messages.sender')
                ->whereRaw('messages.id = (
                    SELECT MAX(id) FROM messages WHERE sender = users.id
                )'); // to get last message for this user and not repeat him
        })
        ->where('users.role', '=', 'user')
        ->where('messages.is_seen', '=', 0)
        ->orderBy('messages.created_at', 'desc')
        ->distinct('')
        ->take(3)
        ->get();


        // for sidebar
        $usersWithUnseenMessages = DB::table('users')
        ->where('role', 'user')
        ->join('messages', function ($join) {
            $join->on('users.id', '=', 'messages.sender')
                ->orWhere('users.id', '=', 'messages.receiver');
        })
        ->select('users.id', 'users.name', DB::raw('SUM(CASE WHEN messages.is_seen = 0 THEN 1 ELSE 0 END) as unseen_count'))
        ->groupBy('users.id', 'users.name')
        ->orderByRaw('(SELECT MAX(created_at) FROM messages WHERE sender = users.id OR receiver = users.id) DESC')
        ->get();
        
        return response()->json([
            'users' => $users,
            'unSeenUsersCount' => $unSeenUsersCount,
            'latestSenders' => $latestSenders,
            'data' => $usersWithUnseenMessages,
        ]);
    }

}   
