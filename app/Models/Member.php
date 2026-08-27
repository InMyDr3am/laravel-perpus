<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected $fillable = ['code', 'name', 'email', 'phone', 'address'];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
