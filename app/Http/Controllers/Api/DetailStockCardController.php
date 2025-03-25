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