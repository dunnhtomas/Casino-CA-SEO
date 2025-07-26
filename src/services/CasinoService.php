<?php

namespace App\Services;

/**
 * Casino Service - Business logic for casino operations
 * Handles data processing, validation, and external API integration
 */
class CasinoService
{
    private array $casinoData;

    public function __construct()
    {
        $this->initializeCasinoData();
    }

    /**
     * Initialize casino data (would be from database in production)
     */
    private function initializeCasinoData(): void
    {
        $this->casinoData = [
            // Casino data would be loaded from database
            // This is a placeholder for the enterprise implementation
        ];
    }

    /**
     * Get filtered casinos by criteria
     */
    public function getFilteredCasinos(array $filters = []): array
    {
        // Implement advanced filtering logic
        // Rating, RTP, Games count, Payout speed, etc.
        return [];
    }

    /**
     * Calculate casino ranking score
     */
    public function calculateRankingScore(array $casino): float
    {
        $score = 0;
        
        // Rating weight (40%)
        $score += ($casino['rating'] / 5) * 40;
        
        // RTP weight (30%)
        $score += ($casino['rtp'] / 100) * 30;
        
        // Games count weight (20%)
        $gamesScore = min($casino['games_count'] / 15000, 1) * 20;
        $score += $gamesScore;
        
        // Payout speed weight (10%)
        $payoutScore = $this->getPayoutSpeedScore($casino['payout_speed']);
        $score += $payoutScore * 10;
        
        return round($score, 2);
    }

    /**
     * Get payout speed score (0-1)
     */
    private function getPayoutSpeedScore(string $payoutSpeed): float
    {
        $speeds = [
            '0-1 day' => 1.0,
            '0-2 days' => 0.9,
            '1-2 days' => 0.8,
            '1-3 days' => 0.7,
            '2-3 days' => 0.6,
            '3-5 days' => 0.4
        ];

        return $speeds[$payoutSpeed] ?? 0.3;
    }

    /**
     * Validate casino data
     */
    public function validateCasinoData(array $data): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = 'Casino name is required';
        }

        if (!isset($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            $errors[] = 'Rating must be between 1 and 5';
        }

        if (!isset($data['rtp']) || $data['rtp'] < 80 || $data['rtp'] > 99) {
            $errors[] = 'RTP must be between 80% and 99%';
        }

        return $errors;
    }

    /**
     * Format bonus display text
     */
    public function formatBonus(array $bonusData): string
    {
        $parts = [];
        
        if (isset($bonusData['percentage'])) {
            $parts[] = $bonusData['percentage'] . '%';
        }
        
        if (isset($bonusData['max_amount'])) {
            $parts[] = 'up to $' . number_format($bonusData['max_amount'], 0);
        }
        
        if (isset($bonusData['free_spins']) && $bonusData['free_spins'] > 0) {
            $parts[] = $bonusData['free_spins'] . ' Free Spins';
        }

        return implode(' ', $parts);
    }

    /**
     * Get SEO-optimized casino data
     */
    public function getSEOCasinoData(string $slug): array
    {
        // This would query the database for SEO metadata
        return [
            'title' => 'Best Casino Reviews | Top Online Casinos Canada 2025',
            'description' => 'Discover Canada\'s top-rated online casinos. Expert reviews, exclusive bonuses, and trusted recommendations for Canadian players.',
            'keywords' => 'online casino, casino reviews, Canadian casinos, casino bonuses',
            'canonical' => 'https://casinoauthority.ca/' . $slug,
            'structured_data' => $this->generateStructuredData()
        ];
    }

    /**
     * Generate structured data for SEO
     */
    private function generateStructuredData(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'Casino Authority',
            'description' => 'Canada\'s premier casino review and comparison platform',
            'url' => 'https://casinoauthority.ca',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => 'https://casinoauthority.ca/search?q={search_term_string}',
                'query-input' => 'required name=search_term_string'
            ]
        ];
    }
}
