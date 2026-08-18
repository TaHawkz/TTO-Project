<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Commercialization;
use App\Models\Disclosure;
use App\Models\Patent;
use App\Models\RevenueDistribution;
use App\Models\RevenueRecord;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('portal.reports.index');
    }

    public function disclosures(Request $request): View
    {
        $byStatus = Disclosure::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $bySector = Disclosure::selectRaw('industry_sector, count(*) as count')
            ->groupBy('industry_sector')
            ->pluck('count', 'industry_sector');

        $monthly = Disclosure::selectRaw('MONTH(created_at) as month, count(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month');

        $list = Disclosure::with('submitter', 'inventors')->latest()->get();

        return view('portal.reports.disclosures', compact('byStatus', 'bySector', 'monthly', 'list'));
    }

    public function patents(Request $request): View
    {
        $byStatus = Patent::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $byJurisdiction = Patent::selectRaw('jurisdiction, count(*) as count')
            ->groupBy('jurisdiction')
            ->pluck('count', 'jurisdiction');

        $expiringSoon = Patent::where('status', 'granted')
            ->where('expiry_date', '<=', now()->addDays(90))
            ->get();

        return view('portal.reports.patents', compact('byStatus', 'byJurisdiction', 'expiringSoon'));
    }

    public function revenue(Request $request): View
    {
        $totalGross = RevenueRecord::sum('gross_amount');
        $totalNet   = RevenueRecord::sum('net_amount');

        $byType = RevenueRecord::selectRaw('source_type, sum(net_amount) as total')
            ->groupBy('source_type')
            ->pluck('total', 'source_type');

        $pending = RevenueDistribution::where('payment_status', 'pending')->sum('amount');
        $records = RevenueRecord::with('distributions')->latest('received_date')->get();

        return view('portal.reports.revenue', compact('totalGross', 'totalNet', 'byType', 'pending', 'records'));
    }

    public function commercialization(Request $request): View
    {
        $byStatus = Commercialization::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $byType = Commercialization::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        $records = Commercialization::with('patent', 'disclosure')->latest()->get();

        return view('portal.reports.commercialization', compact('byStatus', 'byType', 'records'));
    }

    public function export(string $type): StreamedResponse
    {
        $filename = "{$type}-report-" . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($type) {
            $handle = fopen('php://output', 'w');

            switch ($type) {
                case 'disclosures':
                    fputcsv($handle, ['ID', 'Title', 'Status', 'Industry Sector', 'Submitted By', 'Submitted At']);
                    foreach (Disclosure::with('submitter')->get() as $d) {
                        fputcsv($handle, [
                            $d->disclosure_id,
                            $d->title,
                            $d->status,
                            $d->industry_sector,
                            $d->submitter?->name,
                            $d->submitted_at?->format('Y-m-d'),
                        ]);
                    }
                    break;

                case 'patents':
                    fputcsv($handle, ['Title', 'Patent Number', 'Status', 'Jurisdiction', 'Filing Date', 'Grant Date', 'Expiry Date']);
                    foreach (Patent::all() as $p) {
                        fputcsv($handle, [
                            $p->title,
                            $p->patent_number,
                            $p->status,
                            $p->jurisdiction,
                            $p->filing_date?->format('Y-m-d'),
                            $p->grant_date?->format('Y-m-d'),
                            $p->expiry_date?->format('Y-m-d'),
                        ]);
                    }
                    break;

                case 'revenue':
                    fputcsv($handle, ['Source Type', 'Gross Amount', 'Deductions', 'Net Amount', 'Currency', 'Received Date', 'Recorded By']);
                    foreach (RevenueRecord::with('recorder')->latest('received_date')->get() as $r) {
                        fputcsv($handle, [
                            $r->source_type,
                            $r->gross_amount,
                            $r->deductions,
                            $r->net_amount,
                            $r->currency,
                            $r->received_date?->format('Y-m-d'),
                            $r->recorder?->name,
                        ]);
                    }
                    break;

                case 'commercialization':
                    fputcsv($handle, ['Title', 'Type', 'Status', 'Partner', 'Partner Email', 'Manager']);
                    foreach (Commercialization::with('manager')->latest()->get() as $c) {
                        fputcsv($handle, [
                            $c->title,
                            $c->type,
                            $c->status,
                            $c->partner_name,
                            $c->partner_email,
                            $c->manager?->name,
                        ]);
                    }
                    break;

                default:
                    fputcsv($handle, ['Invalid export type']);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
