<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BillingAddress;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserStatsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'year');

        $totalUsers = User::count();
        $newUsersThisWeek = User::where('created_at', '>=', Carbon::now()->startOfWeek())->count();

        $countries = BillingAddress::select('country')
            ->whereNotNull('country')
            ->groupBy('country')
            ->selectRaw('country, COUNT(DISTINCT user_id) as count')
            ->get();

        if ($period === 'week') {
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
            $usersByDay = User::whereBetween('created_at', [$start, $end])
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as date, COUNT(*) as count")
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            $usersByMonth = collect();
            $userEvolution = [];
        } elseif ($period === 'month') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $usersByDay = User::whereBetween('created_at', [$start, $end])
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as date, COUNT(*) as count")
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            $usersByMonth = collect();
            $userEvolution = [];
            $daysInMonth = Carbon::now()->daysInMonth;
            for ($i = 0; $i < $daysInMonth; $i++) {
                $date = $start->copy()->addDays($i)->endOfDay();
                $userEvolution[] = [
                    'month' => $date->format('Y-m-d'),
                    'count' => User::where('created_at', '<=', $date)->count()
                ];
            }
        } else { // year
            $usersByDay = collect();
            $usersByMonth = User::whereYear('created_at', Carbon::now()->year)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            $userEvolution = [];
            $start = Carbon::now()->startOfYear();
            $end = Carbon::now()->endOfYear();
            $current = $start->copy();
            while ($current->lte($end) && $current->year == Carbon::now()->year) {
                $dateStr = $current->format('Y-m');
                $lastDayOfMonth = $current->copy()->endOfMonth();
                $count = User::where('created_at', '<=', $lastDayOfMonth)->count();
                $userEvolution[] = [
                    'month' => $dateStr,
                    'count' => $count
                ];
                $current->addMonth();
            }
        }

        $usersWithPhone = User::whereNotNull('phone')->where('phone', '!=', '')->count();
        $usersWithBillingAddress = BillingAddress::distinct('user_id')->count('user_id');

        return view('users.stats', [
            'totalUsers' => $totalUsers,
            'newUsersThisWeek' => $newUsersThisWeek,
            'countries' => $countries,
            'usersByDay' => $usersByDay,
            'usersByMonth' => $usersByMonth,
            'userEvolution' => $userEvolution,
            'usersWithPhone' => $usersWithPhone,
            'usersWithBillingAddress' => $usersWithBillingAddress,
            'period' => $period,
        ]);
    }
}
