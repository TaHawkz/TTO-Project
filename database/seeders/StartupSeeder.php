<?php

namespace Database\Seeders;

use App\Models\Startup;
use Illuminate\Database\Seeder;

class StartupSeeder extends Seeder
{
    public function run(): void
    {
        $startups = [
            [
                'name' => 'AgroVision Analytics Ltd.',
                'founders' => ['Dr. Ayesha Rahman', 'Md. Tanvir Islam'],
                'faculty_advisor' => 'Prof. Dr. Imran Chowdhury',
                'technology_used' => 'AI-Based Crop Disease Detection System (PAT-2024-0052)',
                'incorporation_date' => '2025-03-15',
                'funding_status' => 'Seed Funded',
                'funding_amount' => 'BDT 1.2 Crore',
                'website' => null,
                'description' => 'AgroVision Analytics develops AI-powered mobile tools for smallholder farmers across Bangladesh and South Asia. Its flagship product, CropGuard, uses the NSU patented neural network to detect 28 crop diseases in real time, offering advice in Bangla voice guidance. The company has reached 3,200 farmers in three districts in its pilot phase.',
                'industry_sector' => 'Agriculture Technology',
                'is_published' => true,
            ],
            [
                'name' => 'GreenFlow IoT Solutions Ltd.',
                'founders' => ['Dr. Ayesha Rahman', 'Farhana Akter'],
                'faculty_advisor' => 'Prof. Dr. Imran Chowdhury',
                'technology_used' => 'Solar-Powered IoT Irrigation Controller (PAT-2024-0067)',
                'incorporation_date' => '2025-06-01',
                'funding_status' => 'Pre-Seed',
                'funding_amount' => null,
                'website' => null,
                'description' => 'GreenFlow IoT Solutions commercializes the NSU solar-powered irrigation controller technology, targeting rice and vegetable farmers in water-stressed regions. The company is currently conducting a 200-farm pilot in Rajshahi and Khulna, with a commercial launch planned for Q1 2026.',
                'industry_sector' => 'Renewable Energy / AgriTech',
                'is_published' => true,
            ],
        ];

        foreach ($startups as $startup) {
            Startup::create($startup);
        }
    }
}
