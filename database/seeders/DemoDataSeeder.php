<?php

namespace Database\Seeders;

use App\Models\Agreement;
use App\Models\Commercialization;
use App\Models\Disclosure;
use App\Models\DisclosureInventor;
use App\Models\IpAssignment;
use App\Models\Patent;
use App\Models\PatentDeadline;
use App\Models\RevenueDistribution;
use App\Models\RevenueRecord;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $faculty = User::where('email', 'faculty@northsouth.edu')->firstOrFail();
            $staff   = User::where('email', 'staff@northsouth.edu')->firstOrFail();
            $officer = User::where('email', 'tto.officer@tto.northsouth.edu')->firstOrFail();

            // ── Disclosure 1: Full lifecycle (approved) ───────────────────
            $disc1 = Disclosure::updateOrCreate(
                ['disclosure_id' => 'DISC-2026-0001'],
                [
                    'title'                  => 'AI-Powered Flood Early Warning System Using IoT Sensor Networks',
                    'abstract'               => 'A real-time flood prediction platform combining IoT water-level sensors with LSTM neural networks to issue early warnings up to 6 hours in advance with 94% accuracy, enabling timely evacuation and disaster response.',
                    'description'            => 'The system deploys a network of low-cost IoT sensor nodes along riverbanks and drainage channels. Sensor data streams to a cloud inference engine running a trained LSTM model that predicts flood events and triggers automated SMS and app alerts for downstream communities and emergency responders. An edge-AI preprocessing layer on the sensor nodes reduces bandwidth by 80%.',
                    'technical_field'        => 'Artificial Intelligence & IoT',
                    'problem_solved'         => 'Existing flood warning systems in Bangladesh rely on manual gauge readings with 24–48 hour lag times, providing inadequate evacuation windows. The proposed system reduces warning latency to under 2 hours.',
                    'novel_features'         => 'Edge-AI preprocessing on sensor nodes reduces bandwidth requirements by 80%. Multi-sensor fusion (rainfall, water-level, soil-saturation) outperforms single-sensor models by 18% in F1 score. Fully solar-powered nodes with 6-month autonomous operation.',
                    'potential_applications' => 'Municipal disaster management systems, agricultural loss prevention, insurance risk modelling, national flood monitoring networks, and international humanitarian aid logistics.',
                    'industry_sector'        => 'Climate Technology & Disaster Management',
                    'existing_alternatives'  => 'BWDB manual gauge stations (24–48 hr latency); satellite-based remote sensing (12–24 hr latency); conventional telemetry systems (single-sensor, no ML).',
                    'funding_source'         => 'HEAT Sub-Project PIN-15012',
                    'sponsor_info'           => 'Higher Education Acceleration & Transformation (HEAT) Project — World Bank funded via University Grants Commission of Bangladesh.',
                    'project_reference'      => 'NSU-HEAT-2025-CSE-04',
                    'status'                 => 'approved',
                    'submitted_by'           => $faculty->id,
                    'assigned_to'            => $officer->id,
                    'reviewer_notes'         => 'Novel application of LSTM networks with multi-sensor fusion. Edge inference architecture significantly differentiates from prior art in the BWDB patent registry. Strong commercial potential in South and South-East Asia. Recommend immediate patent filing and parallel licensing discussions.',
                    'submitted_at'           => now()->subMonths(5),
                ]
            );

            DisclosureInventor::updateOrCreate(
                ['disclosure_id' => $disc1->id, 'email' => $faculty->email],
                [
                    'user_id'     => $faculty->id,
                    'name'        => $faculty->name,
                    'email'       => $faculty->email,
                    'department'  => $faculty->department,
                    'designation' => $faculty->designation,
                    'is_primary'  => true,
                ]
            );
            DisclosureInventor::updateOrCreate(
                ['disclosure_id' => $disc1->id, 'email' => $staff->email],
                [
                    'user_id'     => $staff->id,
                    'name'        => $staff->name,
                    'email'       => $staff->email,
                    'department'  => $staff->department,
                    'designation' => $staff->designation,
                    'is_primary'  => false,
                ]
            );

            // ── Disclosure 2: Awaiting TTO review (submitted) ─────────────
            $disc2 = Disclosure::updateOrCreate(
                ['disclosure_id' => 'DISC-2026-0002'],
                [
                    'title'                  => 'Biodegradable Nanocomposite Polymer for Targeted Cancer Drug Delivery',
                    'abstract'               => 'A chitosan–graphene oxide nanocomposite that encapsulates chemotherapy agents and releases them selectively in tumour microenvironments, reducing systemic toxicity by up to 70% in preclinical trials.',
                    'description'            => 'The composite is synthesised via a one-step sonochemical co-precipitation process. pH-responsive release is achieved through carboxymethyl modification of the chitosan backbone, triggering degradation at the acidic pH characteristic of tumour tissue (pH 5.5–6.5) while remaining stable at physiological pH (7.4).',
                    'technical_field'        => 'Biomedical Engineering & Nanotechnology',
                    'problem_solved'         => 'Systemic toxicity of conventional chemotherapy causes severe side effects including immunosuppression, nausea, and hair loss. Targeted delivery improves the therapeutic index and patient quality of life.',
                    'novel_features'         => 'One-step sonochemical synthesis reduces production cost by 60% versus multi-step methods. Dual pH- and glutathione-responsive release mechanism. Fully biodegradable carrier leaves no cytotoxic residues.',
                    'potential_applications' => 'Oncology drug delivery, wound healing scaffolds, antimicrobial coatings, and controlled-release agricultural inputs.',
                    'industry_sector'        => 'Pharmaceutical & Biomedical',
                    'status'                 => 'submitted',
                    'submitted_by'           => $faculty->id,
                    'submitted_at'           => now()->subWeeks(2),
                ]
            );
            DisclosureInventor::updateOrCreate(
                ['disclosure_id' => $disc2->id, 'email' => $faculty->email],
                [
                    'user_id'     => $faculty->id,
                    'name'        => $faculty->name,
                    'email'       => $faculty->email,
                    'department'  => $faculty->department,
                    'designation' => $faculty->designation,
                    'is_primary'  => true,
                ]
            );

            // ── Disclosure 3: Draft (not yet submitted) ───────────────────
            Disclosure::updateOrCreate(
                ['submitted_by' => $staff->id, 'title' => 'Smart Campus Energy Management with Predictive Load Balancing'],
                [
                    'title'                  => 'Smart Campus Energy Management with Predictive Load Balancing',
                    'abstract'               => 'An ML-driven energy management platform that forecasts electricity demand across campus buildings and optimises HVAC and lighting schedules, targeting a 35% reduction in energy consumption.',
                    'description'            => 'Building energy meters feed 15-minute interval consumption data into a gradient-boosted regression model. The output scheduling layer integrates with the campus BMS (Building Management System) via BACnet protocol, automatically adjusting HVAC setpoints and lighting scenes based on predicted occupancy and weather data.',
                    'technical_field'        => 'Smart Systems & Energy Management',
                    'problem_solved'         => 'NSU campus energy bills exceed BDT 2 crore per month. Uncoordinated HVAC and lighting scheduling wastes an estimated 30–40% of consumed energy. Manual override culture prevents sustained savings.',
                    'novel_features'         => 'Real-time BMS integration via BACnet. Occupancy-aware demand forecasting using anonymised Wi-Fi probe data. Penalty-aware optimisation that avoids peak-demand tariff windows defined by BPDB tariff schedules.',
                    'potential_applications' => 'University campuses, hospitals, commercial office buildings, industrial facilities, and smart city municipal buildings.',
                    'industry_sector'        => 'Smart Buildings & Sustainable Energy',
                    'status'                 => 'draft',
                    'submitted_by'           => $staff->id,
                ]
            );

            // ── Patent ────────────────────────────────────────────────────
            $patent = Patent::updateOrCreate(
                ['title' => 'AI-Powered Flood Early Warning System Using IoT Sensor Networks'],
                [
                    'disclosure_id'    => $disc1->id,
                    'patent_number'    => 'BD/BIPO/2026-A-01284',
                    'status'           => 'filed',
                    'jurisdiction'     => 'BD',
                    'filing_date'      => '2026-02-10',
                    'applicant'        => 'North South University',
                    'attorney_firm'    => 'Rahman & Associates IP Law',
                    'attorney_contact' => 'ip@rahmanassociates.com.bd',
                    'notes'            => 'Patent application filed with Bangladesh Intellectual Property Organisation (BIPO) on 10 February 2026. Application number BD/BIPO/2026-A-01284. Examination report expected within 12–18 months per BIPO SLA.',
                    'managed_by'       => $officer->id,
                ]
            );

            PatentDeadline::updateOrCreate(
                ['patent_id' => $patent->id, 'deadline_type' => 'Response to First Examination Report'],
                [
                    'due_date'     => '2026-11-10',
                    'is_completed' => false,
                    'notes'        => 'Must file detailed written response addressing all examiner objections within the statutory 9-month period from examination report date.',
                ]
            );
            PatentDeadline::updateOrCreate(
                ['patent_id' => $patent->id, 'deadline_type' => 'Annual Renewal Fee (Year 1)'],
                [
                    'due_date'     => '2027-02-09',
                    'is_completed' => false,
                    'notes'        => 'Annual maintenance fee payable to BIPO to keep the application alive. Late payment attracts 50% surcharge.',
                ]
            );

            // ── IP Assignment ─────────────────────────────────────────────
            IpAssignment::updateOrCreate(
                ['disclosure_id' => $disc1->id],
                [
                    'outcome'            => 'university',
                    'determination_date' => '2026-01-28',
                    'determined_by'      => $officer->id,
                    'notes'              => 'Invention developed using HEAT project funding (World Bank). Per NSU Intellectual Property Policy Section 4.2, inventions arising from sponsored research vest in the University. Inventors are entitled to revenue sharing per Schedule B of the IP Policy (40% to inventors).',
                ]
            );

            // ── Agreement ─────────────────────────────────────────────────
            $agreement = Agreement::updateOrCreate(
                ['title' => 'Technology Licensing Agreement — DataSense Technologies Ltd.'],
                [
                    'type'          => 'licensing',
                    'disclosure_id' => $disc1->id,
                    'patent_id'     => $patent->id,
                    'parties'       => ['North South University', 'DataSense Technologies Ltd.'],
                    'signed_date'   => '2026-03-15',
                    'expiry_date'   => '2029-03-14',
                    'status'        => 'signed',
                    'managed_by'    => $officer->id,
                ]
            );

            // ── Commercialization ─────────────────────────────────────────
            Commercialization::updateOrCreate(
                ['title' => 'Commercialization — AI Flood Warning System (DataSense Technologies)'],
                [
                    'patent_id'       => $patent->id,
                    'disclosure_id'   => $disc1->id,
                    'type'            => 'licensing',
                    'status'          => 'active',
                    'partner_name'    => 'DataSense Technologies Ltd.',
                    'partner_contact' => 'Md. Rafiqul Islam, CEO',
                    'partner_email'   => 'licensing@datasense.com.bd',
                    'description'     => 'Exclusive 3-year licence granted to DataSense Technologies Ltd. to commercialise the flood early warning system across Bangladesh and Myanmar. Sub-licensing to government entities permitted with prior written consent from NSU TTO.',
                    'notes'           => 'Initial pilot deployment planned for Sylhet and Sunamganj districts in Q3 2026. DataSense to provide quarterly commercialization progress reports.',
                    'managed_by'      => $officer->id,
                ]
            );

            // ── Revenue ───────────────────────────────────────────────────
            $gross      = 500000.00;
            $deductions =  50000.00;
            $net        = $gross - $deductions;

            $revenue = RevenueRecord::updateOrCreate(
                ['agreement_id' => $agreement->id, 'received_date' => '2026-04-01'],
                [
                    'source_type'   => 'licensing',
                    'disclosure_id' => $disc1->id,
                    'patent_id'     => $patent->id,
                    'gross_amount'  => $gross,
                    'deductions'    => $deductions,
                    'net_amount'    => $net,
                    'currency'      => 'BDT',
                    'notes'         => 'First annual licence fee payment per Agreement Schedule A, Clause 3.1. Deduction: 10% TTO administrative overhead per NSU Finance Policy FP-2024-07.',
                    'recorded_by'   => $officer->id,
                ]
            );

            $distributions = [
                [
                    'recipient_type'    => 'university',
                    'recipient_name'    => 'North South University',
                    'recipient_user_id' => null,
                    'percentage'        => 40.00,
                    'amount'            => $net * 0.40,
                    'payment_status'    => 'paid',
                    'paid_at'           => '2026-04-15',
                ],
                [
                    'recipient_type'    => 'inventor',
                    'recipient_name'    => $faculty->name,
                    'recipient_user_id' => $faculty->id,
                    'percentage'        => 40.00,
                    'amount'            => $net * 0.40,
                    'payment_status'    => 'paid',
                    'paid_at'           => '2026-04-15',
                ],
                [
                    'recipient_type'    => 'department',
                    'recipient_name'    => 'Dept. of Computer Science & Engineering',
                    'recipient_user_id' => null,
                    'percentage'        => 20.00,
                    'amount'            => $net * 0.20,
                    'payment_status'    => 'pending',
                    'paid_at'           => null,
                ],
            ];

            foreach ($distributions as $d) {
                RevenueDistribution::updateOrCreate(
                    [
                        'revenue_record_id' => $revenue->id,
                        'recipient_type'    => $d['recipient_type'],
                        'recipient_name'    => $d['recipient_name'],
                    ],
                    array_merge($d, ['revenue_record_id' => $revenue->id])
                );
            }
        });

        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════════════════════════════════');
        $this->command->info('  TTMS Demo Data Seeded — Ready for Client Audit');
        $this->command->info('═══════════════════════════════════════════════════════════════════');
        $this->command->table(
            ['Item', 'Detail'],
            [
                ['Disclosure 1', 'DISC-2026-0001 — AI Flood Warning System        [approved]'],
                ['Disclosure 2', 'DISC-2026-0002 — Biodegradable Nanocomposite    [submitted]'],
                ['Disclosure 3', '(no ID yet)   — Smart Campus Energy Mgmt        [draft]'],
                ['Patent',       'BD/BIPO/2026-A-01284  filed 2026-02-10  (2 deadlines)'],
                ['IP Assignment','University ownership determined — DISC-2026-0001'],
                ['Agreement',    'Licensing — DataSense Technologies Ltd.         [signed]'],
                ['Commercial.',  'Active licensing track — DataSense Technologies'],
                ['Revenue',      'BDT 500,000 gross / 450,000 net — 3 distributions'],
            ]
        );
        $this->command->newLine();
    }
}
