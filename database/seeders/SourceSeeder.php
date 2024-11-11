<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Source::firstOrCreate(
            ['name' => 'NewsAPI'],
            [
                'url' => "https://newsapi.org/v2/top-headlines?sortBy=popularity&apiKey=[api_key]&category=[category]",
                'api_key' => env('NEWS_ORG_API_KEY')
            ]
        );

        Source::firstOrCreate(
            ['name' => 'The Guardian'],
            [
                'name' => 'The Guardian',
                'url' => "https://content.guardianapis.com/search?api-key=[api_key]&show-fields=headline,trailText,thumbnail,publication,sectionName&q=[category]",
                'api_key' => env('THE_GUARDIAN_API_KEY')
            ]
        );

        Source::firstOrCreate(
            ['name' => 'The New York Times'],
            [
                'url' => "https://api.nytimes.com/svc/search/v2/articlesearch.json?facet_fields=section_name&fl=web_url,lead_paragraph,headline,multimedia&fq=[category]&api-key=[api_key]",
                'api_key' => env('THE_NEW_YORK_TIMES_API_KEY')
            ]
        );
    }
}
