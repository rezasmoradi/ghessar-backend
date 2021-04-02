<?php


namespace App\Services;


use App\Http\Requests\CreateCommentRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CommentService
{
    public static function create(CreateCommentRequest $request)
    {
        try {
            $filename = null;
            if ($request->has('media')) {
                $filename = $request->user()->id . '_' . md5($request->file('media')->getClientOriginalName());
                Storage::disk('media')->putFileAs($request->user()->id, $request->file('media'), $filename);
            }
            $twit = $request->route('twit');
            $twit->comments()->create([
                'user_id' => $request->user()->id,
                'body' => $request->post('body'),
                'media' => $filename,
                'reply_to' => $request->has('reply_to') ? $request->post('reply_to') : null,
            ]);
            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }
}
