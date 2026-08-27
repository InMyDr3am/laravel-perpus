<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $fillable = [
        'book_id', 'member_id', 'borrowed_at', 'due_at', 'returned_at', 'fine',
    ];

    protected $casts = [
        'borrowed_at' => 'date',
        'due_at' => 'date',
        'returned_at' => 'date',
        'fine' => 'integer',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /** Pinjaman yang belum dikembalikan. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('returned_at');
    }

    /** Pinjaman aktif yang sudah melewati jatuh tempo. */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->active()->whereDate('due_at', '<', now());
    }

    public function isReturned(): bool
    {
        return $this->returned_at !== null;
    }
}
