<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DetailStockCardRequest;
use App\Http\Resources\DetailStockCardResource;
use App\Models\DetailStockCard;
use Illuminate\Http\Request;

class DetailStockCardController extends Controller
{
    public function index(Request $request)
    {
        $query = DetailStockCard::with(['product', 'stockCard']);

        if ($request->has('stock_card_id')) {
            $query->where('stock_card_id', $request->stock_card_id);
        }

        $detailStockCards = $query->latest()->get();

        return DetailStockCardResource::collection($detailStockCards);
    }

    public function dailyStockCard(Request $request)
    {
        $query = DetailStockCard::with(['product', 'stockCard'])
            ->whereHas('stockCard', function ($query) {
                $query->where('date', '>=', now()->subDays(3)->startOfDay())
                    ->where('date', '<=', now()->endOfDay());
            });

        if ($request->has('stock_card_id')) {
            $query->where('stock_card_id', $request->stock_card_id);
        }

        $detailStockCards = $query->latest()->get();

        return DetailStockCardResource::collection($detailStockCards);
    }

    public function monthlyStockCard(Request $request)
    {
        $query = DetailStockCard::with(['product', 'stockCard'])
            ->whereHas('stockCard', function ($query) {
                $query->whereRaw('DATE(date) = LAST_DAY(date)');
            });

        if ($request->has('stock_card_id')) {
            $query->where('stock_card_id', $request->stock_card_id);
        }

        $detailStockCards = $query->latest()->get();

        return DetailStockCardResource::collection($detailStockCards);
    }

    public function yearlyStockCard(Request $request)
    {
        $query = DetailStockCard::with(['product', 'stockCard'])
            ->whereHas('stockCard', function ($query) {
                $query->whereRaw('DATE(date) = LAST_DAY(DATE_FORMAT(date, "%Y-12-01"))');
            });

        if ($request->has('stock_card_id')) {
            $query->where('stock_card_id', $request->stock_card_id);
        }

        $detailStockCards = $query->latest()->get();

        return DetailStockCardResource::collection($detailStockCards);
    }

    public function store(DetailStockCardRequest $request)
    {
        $detailStockCard = DetailStockCard::create($request->validated());

        return new DetailStockCardResource($detailStockCard);
    }

    public function show(DetailStockCard $detailStockCard)
    {
        return new DetailStockCardResource($detailStockCard->load(['product', 'stockCard']));
    }

    public function update(DetailStockCardRequest $request, DetailStockCard $detailStockCard)
    {
        $detailStockCard->update($request->validated());

        return new DetailStockCardResource($detailStockCard);
    }

    public function destroy(DetailStockCard $detailStockCard)
    {
        $detailStockCard->delete();

        return response()->noContent();
    }
}