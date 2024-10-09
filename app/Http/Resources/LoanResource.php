<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user->name,
            'book_name' => $this->book->name,
            'author_name' => $this->book->author,
            'loan_date' => $this->loan_date ? $this->loan_date->format('Y-m-d') : null,
            'return_date' => $this->return_date ? $this->return_date->format('Y-m-d') : null,
        ];
    }
}
