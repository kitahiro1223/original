<?php
 
namespace App\Http\Controllers;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use App\Models\User;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
 
use Illuminate\Http\Request;
 
class LineMessengerController extends Controller
{
    public function webhook(Request $request) {
        // LINEから送られた内容を$inputsに代入
        $inputs=$request->all();
 
        // そこからtypeをとりだし、$message_typeに代入
        $message_type=$inputs['events'][0]['type'];
 
        // メッセージが送られた場合、$message_typeは'message'となる。その場合処理実行。
        if($message_type=='message') {
            
            // replyTokenを取得
            $reply_token=$inputs['events'][0]['replyToken'];
 
            // LINEBOTSDKの設定
            $http_client = new CurlHTTPClient(config('services.line.channel_token'));
            $bot = new LINEBot($http_client, ['channelSecret' => config('services.line.messenger_secret')]);
 
            // 送信するメッセージの設定
            $reply_message='メッセージありがとうございます';
 
            // ユーザーにメッセージを返す
            $reply=$bot->replyText($reply_token, $reply_message);
            
            // LINEユーザーがデータベースになければ、ユーザーを追加する
            // LINEのユーザーIDをuserIdに代入
            $userId=$request['events'][0]['source']['userId'];
 
            // userIdがあるユーザーを検索
            $user=User::where('line_id', $userId)->first();
 
            // もし見つからない場合は、データベースに保存
            if($user==NULL) {
                $profile=$bot->getProfile($userId)->getJSONDecodedBody();
 
                $user=new User();
                $user->provider='line';
                $user->line_id=$userId;
                $user->name=$profile['displayName'];
                $user->save();
            }

            return 'ok';
        }
    }

    // 個別メッセージ送信用
    public function message() {
 
        // LINEBOTSDKの設定
        $http_client = new CurlHTTPClient(config('services.line.channel_token'));
        $bot = new LINEBot($http_client, ['channelSecret' => config('services.line.messenger_secret')]);
 
        // LINEユーザーID指定
        $userId = "kita_kita1223";
 
        // メッセージ設定
        $message = "こんにちは！";
 
        // メッセージ送信
        $textMessageBuilder = new TextMessageBuilder($message);
        $response    = $bot->pushMessage($userId, $textMessageBuilder);
 
    }
}