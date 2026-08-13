<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        $technologies = [
            [
                'title' => 'Low-Cost Water Purification Membrane using Graphene Oxide',
                'description' => 'A novel graphene oxide composite membrane that removes heavy metals, microplastics, and biological contaminants from water at a fraction of the cost of conventional systems. Validated at pilot scale for both household and community use.',
                'industry_sector' => 'Water & Sanitation',
                'development_stage' => 'filed',
                'benefits' => [
                    '80% lower production cost vs. conventional membranes',
                    'Removes 99.7% of heavy metals and pathogens',
                    'Operates without electricity — gravity-fed design',
                ],
                'licensing_available' => true,
                'contact_email' => 'tto@northsouth.edu',
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'AI-Based Crop Disease Detection System',
                'description' => 'A lightweight convolutional neural network model deployable on low-end Android smartphones that identifies 28 crop diseases from leaf images with 94.3% accuracy, supporting both Bangla and English interfaces for rural farmers.',
                'industry_sector' => 'Agriculture Technology',
                'development_stage' => 'early_stage',
                'benefits' => [
                    '94.3% disease detection accuracy on field images',
                    'Works offline — no internet required after install',
                    'Supports Bangla voice guidance for low-literacy users',
                ],
                'licensing_available' => false,
                'contact_email' => 'tto@northsouth.edu',
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Biodegradable Packaging from Jute Fiber Composite',
                'description' => 'A bio-composite packaging material made from Bangladeshi jute fibers and natural binders, providing tensile strength comparable to conventional plastic packaging while fully biodegrading within 90 days under standard soil conditions.',
                'industry_sector' => 'Textile & Materials',
                'development_stage' => 'early_stage',
                'benefits' => [
                    'Fully biodegrades in 90 days — zero microplastic residue',
                    'Tensile strength within 15% of conventional plastic',
                    'Uses locally sourced jute — supports rural livelihoods',
                ],
                'licensing_available' => false,
                'contact_email' => 'tto@northsouth.edu',
                'is_published' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Solar-Powered IoT Irrigation Controller',
                'description' => 'An autonomous irrigation management system using soil moisture sensors, weather APIs, and solar power to optimize water usage for smallholder farms. Reduces water consumption by up to 40% with a payback period of under two growing seasons.',
                'industry_sector' => 'Renewable Energy / AgriTech',
                'development_stage' => 'early_stage',
                'benefits' => [
                    'Up to 40% reduction in irrigation water usage',
                    'Solar-powered — zero operating electricity cost',
                    'LoRa-based connectivity covers 5 km without cellular',
                ],
                'licensing_available' => false,
                'contact_email' => 'tto@northsouth.edu',
                'is_published' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($technologies as $tech) {
            Technology::create($tech);
        }
    }
}
