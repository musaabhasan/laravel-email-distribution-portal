<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliverabilitySnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'spf_pass',
        'dkim_pass',
        'dmarc_pass',
        'score',
        'findings',
        'checked_at',
    ];

    protected function casts(): array
    {
        return [
            'spf_pass' => 'boolean',
            'dkim_pass' => 'boolean',
            'dmarc_pass' => 'boolean',
            'findings' => 'array',
            'checked_at' => 'datetime',
        ];
    }
}
