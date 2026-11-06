<?php

namespace Modules\Pendaftaran\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = ['title', 'author', 'publisher', 'published_year', 'stock'];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
