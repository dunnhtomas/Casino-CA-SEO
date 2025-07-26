<?php

namespace App\Controllers;

use App\Services\CasinoService;
use App\Utils\ResponseFormatter;

/**
 * Casino Controller - Handles all casino-related requests
 * Part of the Enterprise Casino Portal MVP implementation
 */
class CasinoController
{
    private CasinoService $casinoService;

    public function __construct(CasinoService $casinoService)
    {
        $this->casinoService = $casinoService;
    }

    /**
     * Get top Canadian casinos for homepage
     */
    public function getTopCasinos(): array
    {
        $casinos = [
            [
                'id' => 1,
                'name' => 'Jackpot City Casino',
                'slug' => 'jackpot-city',
                'logo' => '/images/casinos/jackpot-city-logo.png',
                'rating' => 4.7,
                'established' => 1998,
                'rtp' => 97.39,
                'payout_speed' => '1-3 days',
                'games_count' => '1,350+',
                'bonus' => '100% up to $4,000 + 210 Free Spins',
                'min_deposit' => 5,
                'affiliate_url' => 'https://casino.ca/play/jackpot-city',
                'pros' => [
                    'Exemplary mobile casino app',
                    'Long standing reputation',
                    'Pays out in 1-3 business days',
                    'Deposit from as little as $1'
                ],
                'cons' => [
                    'Focused on slot fans',
                    'Heavily features Microgaming slots',
                    'Withdrawal limits of $4,000 per week'
                ],
                'payment_methods' => ['Visa', 'Mastercard', 'Interac', 'Neteller', 'Skrill'],
                'license' => 'Malta Gaming Authority'
            ],
            [
                'id' => 2,
                'name' => 'Spin Palace',
                'slug' => 'spin-palace',
                'logo' => '/images/casinos/spin-palace-logo.png',
                'rating' => 4.7,
                'established' => 2001,
                'rtp' => 97.45,
                'payout_speed' => '1-3 days',
                'games_count' => '1,000+',
                'bonus' => '100% up to $1,000 + 345 Bonus Spins',
                'min_deposit' => 10,
                'affiliate_url' => 'https://casino.ca/play/spin-palace',
                'pros' => [
                    'Fast payouts within 1–3 days',
                    'Well-curated selection of over 1,000 games',
                    'Excellent six-tier loyalty program',
                    '24/7 live chat support available'
                ],
                'cons' => [
                    'No dedicated mobile app',
                    'Missing traditional poker tables',
                    '35x bonus playthrough rate could be lower'
                ],
                'payment_methods' => ['Visa', 'Mastercard', 'Interac', 'PayPal', 'Bitcoin'],
                'license' => 'Malta Gaming Authority'
            ],
            [
                'id' => 3,
                'name' => 'Lucky Ones',
                'slug' => 'lucky-ones',
                'logo' => '/images/casinos/lucky-ones-logo.png',
                'rating' => 4.7,
                'established' => 2023,
                'rtp' => 98.27,
                'payout_speed' => '0-2 days',
                'games_count' => '14,000+',
                'bonus' => '100% up to $20,000 + 500 Free Spins',
                'min_deposit' => 20,
                'affiliate_url' => 'https://casino.ca/play/lucky-ones',
                'pros' => [
                    'Immense library of 14,000 games',
                    'Fantastic collection of progressive jackpots',
                    'Large selection of game providers',
                    'Fast payouts'
                ],
                'cons' => [
                    'No dedicated app',
                    'Poor selection of table and live games',
                    'No telephone support',
                    'High minimum deposit'
                ],
                'payment_methods' => ['Visa', 'Mastercard', 'Bitcoin', 'Interac', 'Neteller'],
                'license' => 'Curacao eGaming'
            ]
        ];

        return ResponseFormatter::success($casinos, 'Top casinos retrieved successfully');
    }

    /**
     * Get casino by slug
     */
    public function getCasinoBySlug(string $slug): array
    {
        // This would normally query the database
        $casinos = $this->getTopCasinos()['data'];
        
        foreach ($casinos as $casino) {
            if ($casino['slug'] === $slug) {
                return ResponseFormatter::success($casino, 'Casino found');
            }
        }

        return ResponseFormatter::error('Casino not found', 404);
    }

    /**
     * Get casino categories for filtering
     */
    public function getCategories(): array
    {
        $categories = [
            [
                'id' => 'real-money',
                'name' => 'Best real money casino',
                'icon' => '💸',
                'top_casino' => 'Jackpot City',
                'rtp' => '97.39%',
                'games' => '1,350+',
                'rating' => '4.7/5'
            ],
            [
                'id' => 'slots',
                'name' => 'Best for online slots',
                'icon' => '🎰',
                'top_casino' => 'Spin Palace',
                'rtp' => '97.45%',
                'games' => '1,000+',
                'rating' => '4.7/5'
            ],
            [
                'id' => 'bonus',
                'name' => 'Best welcome bonus',
                'icon' => '💰',
                'top_casino' => 'Lucky Ones',
                'rtp' => '98.27%',
                'games' => '8,000+',
                'rating' => '4.4/5'
            ],
            [
                'id' => 'payments',
                'name' => 'Best payment options',
                'icon' => '💳',
                'top_casino' => 'Pistolo',
                'rtp' => '97.21%',
                'games' => '11,000+',
                'rating' => '4.6/5'
            ],
            [
                'id' => 'live',
                'name' => 'Best live casino',
                'icon' => '🎲',
                'top_casino' => 'Magius',
                'rtp' => '98.13%',
                'games' => '7,400+',
                'rating' => '4.6/5'
            ]
        ];

        return ResponseFormatter::success($categories, 'Categories retrieved successfully');
    }

    /**
     * Search casinos by name or features
     */
    public function searchCasinos(string $query): array
    {
        $casinos = $this->getTopCasinos()['data'];
        $results = array_filter($casinos, function($casino) use ($query) {
            return stripos($casino['name'], $query) !== false ||
                   stripos(implode(' ', $casino['pros']), $query) !== false;
        });

        return ResponseFormatter::success(array_values($results), 'Search results');
    }

    /**
     * Get casino comparison data
     */
    public function getComparison(): array
    {
        $casinos = $this->getTopCasinos()['data'];
        
        $comparison = array_map(function($casino) {
            return [
                'name' => $casino['name'],
                'rating' => $casino['rating'],
                'rtp' => $casino['rtp'],
                'games' => $casino['games_count'],
                'payout_speed' => $casino['payout_speed'],
                'min_deposit' => $casino['min_deposit'],
                'bonus' => $casino['bonus']
            ];
        }, $casinos);

        return ResponseFormatter::success($comparison, 'Casino comparison data');
    }
}
