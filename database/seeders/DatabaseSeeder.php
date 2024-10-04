<?php

namespace Database\Seeders;

use App\Actions\ParseAndUpdateAd;
use App\Models\Ad;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Creating fake users.');

        \App\Models\User::factory(4)->create();

        $urls = [
            'aima-arenda-bolt-glovo-IDVk39R',
            'malenkie-domovyata-IDV0Bwo',
            'arki-vesln-prezidumi-fotozoni-rostov-kvti-oformlennya-IDJrzKs',
            'amortizator-peredny-sachs-land-rover-freelander-lend-rover-frlender-IDU7DAD',
            'styki-peredn-v-sbor-z-pruzhinami-land-rover-freelander-1-IDUW9tl',
        ];

        foreach($urls as $url) {
            $this->command->info('Creating fake ad with url: ' . $url);

            $ad = Ad::create([
                'url' => $url,
            ]);

            $this->command->info('Parsing fake ad with url: ' . $url);
            ParseAndUpdateAd::run($ad);

            $user = User::whereNotNull('email_verified_at')->first();
            if ($user) {
                $this->command->info('Creating fake ad subscription for user: ' . $user->email . ' and ad url: ' . $ad->url);

                $ad->subscriptions()->create([
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
