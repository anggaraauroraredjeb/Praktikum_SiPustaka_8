<?php

namespace Modules\Pendaftaran\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $fillable = [
        'user_id', 'book_id', 'borrower_name', 'school', 'study_program', 'email', 'phone',
        'loan_date', 'return_date', 'status', 'document_path',
    ];

    protected function casts(): array
    {
        return ['loan_date' => 'date', 'return_date' => 'date'];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
