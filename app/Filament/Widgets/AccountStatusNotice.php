<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class AccountStatusNotice extends Widget
{
    protected static string $view = 'filament.widgets.account-status-notice';

    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    /**
     * Only shown to signed-in landlords who are still awaiting approval.
     */
    public static function canView(): bool
    {
        $user = Auth::user();

        return $user && ! $user->isAdmin() && ! $user->is_approved;
    }
}
