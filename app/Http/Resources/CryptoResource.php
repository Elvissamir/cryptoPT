<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CryptoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cg_id' => $this->cg_id,
            'amount' => $this->whenPivotLoaded('crypto_portfolio', function () {
                return $this->pivot->amount;
            }),
            'created_at' => $this->whenPivotLoaded('crypto_portfolio', function () {
                return $this->pivot->created_at->diffForHumans();
            })
        ];
    }
}
