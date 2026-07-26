<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'property_type', 'service_type',
        'bedrooms', 'bathrooms', 'extras', 'preferred_date', 'end_date', 'address',
        'notes', 'estimated_price', 'status',
    ];

    protected function casts(): array
    {
        return [
            'extras' => 'array',
            'preferred_date' => 'date',
            'end_date' => 'date',
            'estimated_price' => 'decimal:2',
        ];
    }

    /**
     * Last day of the job. A single-day booking leaves `end_date` null, so the
     * start doubles as the end.
     */
    public function endsOn(): ?CarbonImmutable
    {
        $end = $this->end_date ?? $this->preferred_date;

        return $end ? CarbonImmutable::parse($end) : null;
    }

    public function spansMultipleDays(): bool
    {
        return $this->end_date !== null
            && $this->preferred_date !== null
            && ! $this->end_date->isSameDay($this->preferred_date);
    }

    /** Number of days the job occupies, inclusive of both ends. */
    public function dayCount(): int
    {
        if (! $this->preferred_date) {
            return 0;
        }

        return CarbonImmutable::parse($this->preferred_date)
            ->diffInDays($this->endsOn()) + 1;
    }
}
