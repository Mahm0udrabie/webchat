<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\WebSocketTrait;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class MessagesController extends Controller
{
    use WebSocketTrait;
    protected $model;
    public function __construct(Message $model)
    {
        $this->model = $model;
    }
    public function send(Request $request) {
        $v = Validator::make(request()->all() , [
            'message' => 'required',
            'to_user'  => 'required'
        ]);
        if($v->fails()) {
            return response()->json([
                'status' => false, 
                "error" => $v->errors()
            ]);
        }
        $to_user = User::find($request->to_user);
        if(!$to_user) {
            return response()->json([
                'status' => false, 
                'error' => 'user not fount'
            ]);
        }
        $save = $this->model->create([
            'user_id' => auth()->guard('api') -> user() ->id,
            'to_user' => $request->to_user,
            'message' => $request->message
        ]);
        if($save) {
            $this->sendWSMessage($to_user->channel, auth()->guard('api') -> user() ->channel,new MessageResource($save));
            return response() -> json([
                'status' => true, 
                'data' => $save
            ]);
        }
    }
    public function messages($id) {
        $to_user = User::find($id);
        if(!$to_user) {
            return response() -> json(["status" => false, "error" => "error_user"]);
        }
        $messages = $this->model->where('user_id', auth()->guard('api')-> id())
                                ->where('to_user', $to_user->id)
                                ->orWhere('user_id', $to_user->id)
                                ->where('to_user', auth()->guard('api')->id())
                                ->get();
        return response() ->json([
            'status' => true,
            "data"   => MessageResource::collection($messages)
        ]);
    }
}
