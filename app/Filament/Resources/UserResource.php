<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Landlords & users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Select::make('role')
                            ->options(['landlord' => 'Landlord', 'admin' => 'Admin'])
                            ->default('landlord')
                            ->required(),
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Approved (can list properties)')
                            ->default(false),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation) => $operation === 'create')
                            ->helperText('Leave blank to keep the current password when editing.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->description(fn (User $record) => $record->email),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state) => $state === 'admin' ? 'warning' : 'gray'),
                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean(),
                Tables\Columns\TextColumn::make('properties_count')
                    ->label('Listings')
                    ->counts('properties')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Approval status')
                    ->trueLabel('Approved')
                    ->falseLabel('Pending'),
                Tables\Filters\SelectFilter::make('role')
                    ->options(['landlord' => 'Landlord', 'admin' => 'Admin']),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => ! $record->is_approved)
                    ->action(function (User $record) {
                        $record->update(['is_approved' => true]);
                        Notification::make()
                            ->title("{$record->name} approved")
                            ->body('They can now list and manage properties.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('revoke')
                    ->label('Revoke')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => $record->is_approved && ! $record->isAdmin())
                    ->action(fn (User $record) => $record->update(['is_approved' => false])),
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    /**
     * Only admins can see and manage user accounts.
     */
    public static function canViewAny(): bool
    {
        return (bool) Auth::user()?->isAdmin();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return (bool) Auth::user()?->isAdmin();
    }
}
