<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\Create;
use App\Http\Requests\Banner\Update;

use App\Services\BannerService;
use App\Services\MessageService;
use App\Services\UploadFileService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(
        protected MessageService $message,
    ) {}
    public function getMessage($id) {
        $checkGroupChat = $this->message->getChatGroup($id);
        return $this->responseSuccess($checkGroupChat, "Thành công!");
    }
    public function createMessage(Request $request) {
        $message = $this->message->createMessage($request->chat_with, $request->message);
        return $this->responseSuccess($message, "Thành công!");
    }

}
