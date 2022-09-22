<?php

namespace App\Http\Controllers\Api;

use App\{Models\NewsletterSignup, Models\StoreLocation, Models\WPStoreLocation};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Newsletter;

class NewsletterController extends Controller
{
    public function __construct()
    {
        // $this->middleware('recaptcha')->only('register');
    }

    public function register(Request $request)
    {
        $locationId = null;
        $location = '';

        $list = 'subscribers';
        $audienceId = env('MAILCHIMP_LIST_ID');

        if ($request->get('LOCATION_CODE')) {
            $storeLocation = StoreLocation::where(
                'code',
                '=',
                $request->get('LOCATION_CODE'),
            )->first();
            if ($storeLocation) {
                $locationId = $storeLocation->id;
                $location = $storeLocation->locale;

                if ($storeLocation->status === StoreLocation::STATUS_COMING_SOON) {
                    $list = 'comingSoon';
                    $audienceId = env('MAILCHIMP_COMING_SOON_LIST_ID');
                }
            }
        }

        if ($request->get('SOURCE') === 'wcl') {
            $list = 'wcl';
            $audienceId = env('MAILCHIMP_WCL_LIST_ID');
        }

        $data = $request->only(['FNAME', 'LNAME']);
        $data['SLOCATION'] = $location;
        $data = array_filter($data, 'strlen');

        $user = Newsletter::subscribeOrUpdate($request->get('EMAIL'), $data, $list);
        $success = is_array($user) && array_key_exists('id', $user);
        $status = 200;

        if (!$success) {
            $body = Newsletter::getApi()->getLastResponse()['body'];

            if ($body) {
                $response = json_decode($body);
                $status = $response->status;

                // 400 response means the user has unsubscribed
                // resubscribe them by setting their status to pending
                if ($status === 400) {
                    Newsletter::subscribeOrUpdate($request->get('EMAIL'), $data, $list, [
                        'status' => 'pending',
                    ]);
                    $error = Newsletter::getLastError();
                    if (!$error) {
                        $status = 201;
                        $success = true;

                        Log::info('Resubscribed ' . $request->get('EMAIL'));
                    }
                }
            }

            Log::info(
                'Newsletter sign up !success: ' .
                    print_r($user, true) .
                    ' ' .
                    $body .
                    ' ' .
                    $request->get('EMAIL'),
            );
        }

        if ($success) {
            NewsletterSignup::updateOrCreate(
                [
                    'email' => $request->get('EMAIL'),
                    'audience_id' => $audienceId,
                ],
                [
                    'first_name' => $request->get('FNAME'),
                    'last_name' => $request->get('LNAME'),
                    'store_location_id' => $locationId,
                    'source' => $request->get('SOURCE'),
                ],
            );
        }

        return ['success' => $success, 'status' => $status];
    }
}
