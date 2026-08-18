<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Disclosure;
use App\Models\Patent;
use App\Models\PatentDeadline;
use App\Models\RevenueRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $stats = [];

        if ($user->canReviewDisclosures()) {
            $stats['total_disclosures'] = Disclosure::count();
            $stats['pending_review']    = Disclosure::byStatus('submitted')->count();
            $stats['active_patents']    = Patent::whereIn('status', ['filed', 'examination', 'granted'])->count();
            $stats['total_revenue']     = RevenueRecord::sum('net_amount');
        } else {
            $stats['my_disclosures'] = Disclosure::where('submitted_by', $user->id)->count();
            $stats['my_drafts']      = Disclosure::where('submitted_by', $user->id)->byStatus('draft')->count();
        }

        $recentDisclosures = Disclosure::query()
            ->forUser($user)
            ->with('submitter', 'inventors')
            ->latest()
            ->limit(5)
            ->get();

        $upcomingDeadlines = $user->isTTOStaff()
            ? PatentDeadline::with('patent')
                ->where('is_completed', false)
                ->where('due_date', '<=', now()->addDays(90))
                ->orderBy('due_date')
                ->limit(5)
                ->get()
            : collect();

        return view('portal.dashboard', compact('stats', 'recentDisclosures', 'upcomingDeadlines', 'user'));
    }
}
