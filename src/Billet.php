<?php

namespace DevAjMeireles\PagHiper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billet extends Model
{
    use HasFactory;

    protected $fillable = [
        'billable_id',
        'billable_type',
        'transaction',
        'status',
        'url',
        'pdf',
        'digitable',
        'duedate_at',
    ];
}