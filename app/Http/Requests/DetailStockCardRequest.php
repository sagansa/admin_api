<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetailStockCardRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'stock_card_id' => 'required|exists:stock_cards,id',
            'quantity' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ];
    }
}