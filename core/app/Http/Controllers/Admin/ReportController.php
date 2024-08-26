<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingActionHistory;
use App\Models\NotificationLog;
use App\Models\PaymentLog;
use App\Models\UserLogin;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function loginHistory(Request $request)
    {
        $pageTitle = 'User Login History';
        $loginLogs = UserLogin::orderBy('id', 'desc')->searchable(['user:username'])->dateFilter()->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::where('user_ip', $ip)->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs', 'ip'));
    }

    public function notificationHistory(Request $request)
    {
        $pageTitle = 'Notification History';
        $logs = NotificationLog::orderBy('id', 'desc')->searchable(['user:username'])->dateFilter()->with('user')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id)
    {
        $pageTitle = 'Email Details';
        $email = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle', 'email'));
    }


    public function bookingSituationHistory()
    {
        $pageTitle  = 'Booking Situation Report';
        $remarks    = BookingActionHistory::groupBy('remark')->orderBy('remark')->get('remark');
        $query      = BookingActionHistory::searchable(['booking:booking_number'])->filter(['remark']);
        $bookingLog = $query->with('booking', 'admin')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.reports.booking_actions', compact('pageTitle', 'bookingLog', 'remarks'));
    }

    public function paymentsReceived()
    {
        return $this->getPaymentData('RECEIVED', 'Received Payments History');
    }

    public function paymentReturned()
    {
        return $this->getPaymentData('RETURNED', 'Returned Payments History');
    }

    protected function getPaymentData($type, $pageTitle)
    {
        $paymentLog = PaymentLog::where('type', $type)->searchable(['booking:booking_number', 'booking.user:username'])->with('booking.user', 'admin')->orderBy('id', 'desc')->paginate(getPaginate());

        return view('admin.reports.payment_history', compact('pageTitle', 'paymentLog', 'pageTitle'));
    }
}
