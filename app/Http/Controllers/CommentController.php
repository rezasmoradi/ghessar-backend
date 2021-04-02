<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentUnretwitRequest;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\CreateCommentRetwitRequest;
use App\Http\Requests\LikeCommentRequest;
use App\Http\Requests\ReportCommentRequest;
use App\Http\Requests\UnlikeCommentRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Report;
use App\Models\Retwit;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $twit = $request->route('twit');
//        $comments = $twit->comments->groupBy('reply_to');
        $comments = new CommentCollection($twit->comments);
        return response(['comments' => $comments], Response::HTTP_OK);
    }

    public function create(CreateCommentRequest $request)
    {
        if (CommentService::create($request)) {
            $comment = Comment::query()->orderBy('created_at', 'desc')->first();
            return response(['comment' => new CommentResource($comment)], Response::HTTP_CREATED);
        }
        return response(['message' => 'در ایجاد دیدگاه خطایی رخ داده است.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function like(LikeCommentRequest $request)
    {
        $comment = $request->route('comment');
        $like = new Like(['user_id' => $request->user()->id]);
        $comment->likes()->save($like);
        return response(null, Response::HTTP_CREATED);
    }

    public function unlike(UnlikeCommentRequest $request)
    {
        $comment = $request->route('comment');
        $comment->likes()->where('user_id', $request->user()->id)->delete();
        return response(null, Response::HTTP_OK);
    }

    public function retwit(CreateCommentRetwitRequest $request)
    {
        $comment = $request->route('comment');
        $retwit = new Retwit(['user_id' => $request->user()->id]);
        $comment->retwits()->save($retwit);
        return response(null, Response::HTTP_CREATED);
    }

    public function unretwit(CommentUnretwitRequest $request)
    {
        $comment = $request->route('comment');
        $comment->retwits()->where('user_id', $request->user()->id)->delete();
        return response(null, Response::HTTP_OK);
    }

    public function report(ReportCommentRequest $request)
    {
        $comment = $request->route('comment');
        $report = new Report([
            'user_id' => $request->user()->id,
            'reason_id' => $request->post('reason'),
        ]);
        $comment->reports()->save($report);

        return response(['message' => 'گزارش با موفقیت ثبت شد.'], Response::HTTP_CREATED);
    }
}
