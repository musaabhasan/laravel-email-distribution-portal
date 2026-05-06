<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RecipientGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'criteria',
        'is_dynamic',
    ];

    protected function casts(): array
    {
        return [
            'criteria' => 'array',
            'is_dynamic' => 'boolean',
        ];
    }

    public function recipients(): BelongsToMany
    {
        return $this->belongsToMany(Recipient::class, 'recipient_group_recipient')->withTimestamps();
    }
}
