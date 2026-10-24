<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_name',
        'receiver_phone',
        'province',
        'ward',
        'street_address',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->street_address}, {$this->ward}, {$this->province}";
    }

    public function getRecipientNameAttribute(): string
    {
        return $this->receiver_name;
    }

    public function getRecipientPhoneAttribute(): string
    {
        return $this->receiver_phone;
    }

    public function getDistrictAttribute(): string
    {
        return $this->ward;
    }

    public function getAddressDetailAttribute(): string
    {
        return $this->street_address;
    }
}
