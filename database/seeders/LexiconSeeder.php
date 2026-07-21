<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LexiconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positiveWords = [
            'surge', 'profit', 'growth', 'gain', 'positive', 'agreement', 'stable', 'boost',
            'success', 'recover', 'expansion', 'favorable', 'optimism', 'breakthrough', 'safe',
            'efficient', 'smooth', 'stabilize', 'upgrade', 'promising', 'untouched', 'improved'
        ];

        $negativeWords = [
            'crisis', 'war', 'conflict', 'delay', 'shortage', 'strike', 'inflation', 'sanction',
            'risk', 'loss', 'decline', 'disrupt', 'threat', 'negative', 'concern', 'storm',
            'congested', 'blockade', 'crash', 'damage', 'warning', 'hazard', 'collapse', 'halt'
        ];

        foreach ($positiveWords as $word) {
            DB::table('positive_words')->updateOrInsert(['word' => $word], ['created_at' => now(), 'updated_at' => now()]);
        }

        foreach ($negativeWords as $word) {
            DB::table('negative_words')->updateOrInsert(['word' => $word], ['created_at' => now(), 'updated_at' => now()]);
        }
    }
}
