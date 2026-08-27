<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BorrowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'book_id' => ['required', Rule::exists('books', 'id')],
            'member_id' => ['required', Rule::exists('members', 'id')],
        ];
    }
}
