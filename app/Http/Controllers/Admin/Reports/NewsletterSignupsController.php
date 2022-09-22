<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSignup;
use App\Traits\BuildsReports;
use App\Traits\ParsesDates;
use Illuminate\Http\Request;

class NewsletterSignupsController extends Controller
{
    use BuildsReports, ParsesDates;

    public function signupsByStore(Request $request, $audience = null)
    {
        $select = [
            'count(*) as total',
            "CONCAT_WS(' ', store_locations.city, store_locations.location, store_locations.state) AS store_location",
        ];
        $query = NewsletterSignup::leftJoin(
            'store_locations',
            'newsletter_signups.store_location_id',
            '=',
            'store_locations.id',
        )
            ->groupBy('newsletter_signups.store_location_id')
            ->orderBy('total', 'desc')
            ->selectRaw(implode(',', $select));

        $audience == 'wcl'
            ? $query->where('audience_id', env('MAILCHIMP_WCL_LIST_ID'))
            : $query->whereIn('audience_id', [
                env('MAILCHIMP_LIST_ID'),
                env('MAILCHIMP_COMING_SOON_LIST_ID'),
            ]);

        $this->bindQueryTimeframe(
            $request,
            $query,
            $this->getDefaultSalesTrendsRange(),
            'newsletter_signups.created_at',
        );

        $params = $this->reportInputs($request, $this->getDefaultSalesTrendsRange());
        $params['signupsByStore'] = $query->get();
        $params['audience'] = $audience;

        return view('admin.reports.newsletter-signups.signups-by-store', $params);
    }

    private function getDefaultSalesTrendsRange()
    {
        return [$this->startOfMonthGlobalDate(), $this->todayGlobalDate()];
    }
}
