<?php

namespace App\Filament\Widgets;

use App\Models\Property;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PropertyStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    /**
     * Hide the stats until the account can actually manage listings.
     */
    public static function canView(): bool
    {
        return (bool) Auth::user()?->canManageListings();
    }

    protected function getStats(): array
    {
        $user = Auth::user();

        return $user->isAdmin()
            ? $this->adminStats()
            : $this->landlordStats($user);
    }

    protected function adminStats(): array
    {
        $total = Property::count();
        $available = Property::where('is_available', true)->count();
        $landlords = User::where('role', 'landlord')->count();
        $pending = User::where('role', 'landlord')->where('is_approved', false)->count();

        return [
            Stat::make('Total properties', number_format($total))
                ->description($available.' available now')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('primary'),
            Stat::make('Landlords', number_format($landlords))
                ->description('Registered on the platform')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Pending approvals', number_format($pending))
                ->description($pending > 0 ? 'Needs your review' : 'All caught up')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pending > 0 ? 'warning' : 'gray'),
        ];
    }

    protected function landlordStats(User $user): array
    {
        $mine = Property::where('user_id', $user->id);
        $count = (clone $mine)->count();
        $available = (clone $mine)->where('is_available', true)->count();
        $avg = (int) round((clone $mine)->avg('price') ?? 0);

        return [
            Stat::make('My listings', number_format($count))
                ->description($available.' available now')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('primary'),
            Stat::make('Featured', number_format((clone $mine)->where('is_featured', true)->count()))
                ->description('Shown on the homepage')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
            Stat::make('Average rent', $count ? 'TZS '.number_format($avg) : '—')
                ->description('Across your listings')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
