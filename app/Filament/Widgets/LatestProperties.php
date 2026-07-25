<?php

namespace App\Filament\Widgets;

use App\Models\Property;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LatestProperties extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest listings';

    public static function canView(): bool
    {
        return (bool) Auth::user()?->canManageListings();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->baseQuery())
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('')
                    ->getStateUsing(fn (Property $record) => $record->image_url)
                    ->square(),
                Tables\Columns\TextColumn::make('title')
                    ->limit(30)
                    ->description(fn (Property $record) => trim(($record->area ? $record->area.', ' : '').$record->region, ', ')),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Owner')
                    ->visible(fn () => Auth::user()?->isAdmin()),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Rent / month')
                    ->formatStateUsing(fn ($state) => 'TZS '.number_format($state)),
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Available')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->label('View')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (Property $record) => route('properties.show', $record))
                    ->openUrlInNewTab(),
            ])
            ->paginated([5, 10]);
    }

    protected function baseQuery(): Builder
    {
        $query = Property::query()->latest();
        $user = Auth::user();

        if ($user && ! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }
}
