<?php


namespace App\Services;


use App\Http\Requests\CreateTwitRequest;
use App\Models\Twit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TwitService
{
    public static function create(CreateTwitRequest $request)
    {
        try {
            $filename = null;
            if ($request->has('media')) {
                $filename = $request->user()->id . '_' . md5($request->file('media')->getClientOriginalName());
                Storage::disk('media')->putFileAs($request->user()->id, $request->file('media'), $filename);
            }
            Twit::query()->create([
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

    public static function index(Request $request)
    {
        $user = $request->user();
        $followings = $user->followings()->get();
        $followingIds = [];
        foreach ($followings as $following) {
            $followingIds[] = $following->id;
        }
        $blocks = json_decode($user->getBlocks());
        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                $followingIds = array_filter($followingIds, function ($item) use ($block) {
                    return $item !== $block;
                });
            }
        }
        return Twit::query()->whereIn('user_id', $followingIds)->orWhere('user_id', $user->id)->paginate(10);
    }
}
