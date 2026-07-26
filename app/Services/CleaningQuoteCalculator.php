<?php

namespace App\Services;

/**
 * Produces the indicative estimate stored on a booking and shown in the
 * internal notification and the quote PDF.
 *
 * The public site advertises starting rates and a free quote rather than a
 * live calculator, so this figure is a starting point for Tina's own quote,
 * not a price promised to the customer.
 */
class CleaningQuoteCalculator
{
    /** The four service families from the company presentation. */
    public const SERVICE_BASE_PRICES = [
        'hotelier' => 90,
        'immeubles' => 80,
        'bureaux' => 70,
        'specifiques' => 110,
    ];

    public const PROPERTY_MULTIPLIERS = [
        'apartment' => 1.0,
        'house' => 1.15,
        'office' => 1.3,
    ];

    public const EXTRA_PRICES = [
        'vitres' => 40,
        'apres_travaux' => 80,
        'desinfection' => 60,
        'lingerie' => 30,
    ];

    public const PRICE_PER_BEDROOM = 15;

    public const PRICE_PER_BATHROOM = 10;

    /**
     * @param  array{property_type?: string, service_type: string, bedrooms?: int|null, bathrooms?: int|null, extras?: array<int, string>|null}  $data
     */
    public function estimate(array $data): float
    {
        $base = self::SERVICE_BASE_PRICES[$data['service_type']] ?? 0;
        $multiplier = self::PROPERTY_MULTIPLIERS[$data['property_type'] ?? ''] ?? 1.0;

        $rooms = ((int) ($data['bedrooms'] ?? 0)) * self::PRICE_PER_BEDROOM
            + ((int) ($data['bathrooms'] ?? 0)) * self::PRICE_PER_BATHROOM;

        $extras = collect($data['extras'] ?? [])
            ->sum(fn (string $extra) => self::EXTRA_PRICES[$extra] ?? 0);

        return round(($base + $rooms) * $multiplier + $extras, 2);
    }
}
