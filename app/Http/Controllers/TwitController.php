<?php

namespace App\Http\Controllers;

use App\Events\TwitLiked;
use App\Http\Requests\BookmarkRequest;
use App\Http\Requests\CreateRetwitRequest;
use App\Http\Requests\CreateTwitRequest;
use App\Http\Requests\DeleteTwitRequest;
use App\Http\Requests\LikeTwitRequest;
use App\Http\Requests\ReportTwitRequest;
use App\Http\Requests\UnlikeTwitRequest;
use App\Http\Requests\UnmarkRequest;
use App\Http\Requests\UnretwitRequest;
use App\Http\Resources\TwitCollection;
use App\Http\Resources\TwitResource;
use App\Models\Bookmark;
use App\Models\Like;
use App\Models\Report;
use App\Models\ReportType;
use App\Models\Retwit;
use App\Models\Twit;
use App\Services\TwitService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TwitController extends Controller
{
    public function index(Request $request)
    {
        return response(TwitResource::collection(TwitService::index($request)), Response::HTTP_OK);
    }

    public function show(Request $request)
    {
        $twit = $request->route('twit');
        return response(['twit' => $twit], Response::HTTP_OK);
    }

    public function search(Request $request)
    {
        $twits = Twit::with('user')->where('body', 'like', '%' . $request->search . '%')->paginate(10);

        return response(TwitResource::collection($twits)->response()->getData(true), Response::HTTP_OK);
    }

    public function create(CreateTwitRequest $request)
    {
        if (TwitService::create($request)) {
            return response(['message' => 'توییت با موفقیت ساخته شد.'], Response::HTTP_CREATED);
        }
        return response(['message' => 'در ایجاد توییت خطایی رخ داده است.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function delete(DeleteTwitRequest $request)
    {
        try {
            Twit::query()->find($request->route('twit')->id)->delete();
            return response(null, Response::HTTP_OK);
        } catch (\Exception $exception) {
            Log::error($exception);
            return response(['message' => 'توییت یافت نشد و یا حذف شده است.'], Response::HTTP_NOT_FOUND);
        }
    }

    public function like(LikeTwitRequest $request)
    {
        $twit = $request->route('twit');
        $likable = new Like(['user_id' => $request->user()->id]);
        $twit->likes()->save($likable);

        event(new TwitLiked($twit));

        return response(new TwitResource(Twit::query()->find($twit->id)), Response::HTTP_CREATED);
    }

    public function unlike(UnlikeTwitRequest $request)
    {
        $twit = $request->route('twit');
        $twit->likes()->where('user_id', $request->user()->id)->delete();

        return response(new TwitResource(Twit::query()->find($twit->id)), Response::HTTP_OK);
    }

    public function retwit(CreateRetwitRequest $request)
    {
        $twit = $request->route('twit');
        $retwitable = new Retwit(['user_id' => $request->user()->id]);
        $twit->retwits()->save($retwitable);
        return response(new TwitResource(Twit::query()->find($twit->id)), Response::HTTP_CREATED);
    }

    public function unretwit(UnretwitRequest $request)
    {
        try {
            $twit = $request->route('twit');
            $twit->retwits()->where('user_id', $request->user()->id)->delete();

            return response(new TwitResource(Twit::query()->find($twit->id)), Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e);
            return response(['message' => 'توییت یافت نشد.'], Response::HTTP_NOT_FOUND);
        }
    }

    public function reportTypes(Request $request)
    {
        $reports = ReportType::query()->where('type', '=', 'post')->get(['id', 'title']);
        return response(['reports' => $reports], Response::HTTP_OK);
    }

    public function report(ReportTwitRequest $request)
    {
        $reportable = $request->route('twit');
        $report = new Report([
            'reporter_id' => $request->user()->id,
            'reason_id' => $request->post('reason')
        ]);
        $reportable->reports()->save($report);
        return response(['message' => 'گزارش با موفقیت ثبت شد.'], Response::HTTP_CREATED);
    }

    public function bookmark(BookmarkRequest $request)
    {
        try {
            Bookmark::query()->create([
                'user_id' => auth()->id(),
                'twit_id' => $request->route('twit')->id,
            ]);
            return response(['twit' => new TwitResource($request->twit)], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            Log::error($exception);
            return response(['message' => 'در ایجاد نشان خطایی رخ داده است.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function unmark(UnmarkRequest $request)
    {
        try {
            Bookmark::query()->where([
                'user_id' => auth()->id(),
                'twit_id' => $request->route('twit')->id,
            ])->delete();
            return response(null, Response::HTTP_OK);
        } catch (\Exception $exception) {
            Log::error($exception);
            return response(['message' => 'در حذف نشان خطایی رخ داده است.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
