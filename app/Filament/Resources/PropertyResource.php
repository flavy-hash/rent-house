<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\Property;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'My Properties';

    protected static ?string $modelLabel = 'property';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->placeholder('e.g. Spacious 3-bedroom house in Njiro')
                            ->required()
                            ->maxLength(150)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('region')
                            ->options(array_combine(Property::REGIONS, Property::REGIONS))
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('area')
                            ->label('Area / Neighbourhood')
                            ->placeholder('e.g. Njiro')
                            ->maxLength(100),

                        Forms\Components\Select::make('type')
                            ->options(array_combine(Property::TYPES, Property::TYPES))
                            ->required(),

                        Forms\Components\TextInput::make('price')
                            ->label('Monthly rent')
                            ->prefix('TZS')
                            ->numeric()
                            ->minValue(10000)
                            ->required(),

                        Forms\Components\TextInput::make('bedrooms')
                            ->numeric()->minValue(0)->maxValue(20)->required()->default(1),

                        Forms\Components\TextInput::make('bathrooms')
                            ->numeric()->minValue(0)->maxValue(20)->required()->default(1),
                    ]),

                Forms\Components\Section::make('Description & amenities')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->rows(4)
                            ->maxLength(2000)
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('amenities')
                            ->placeholder('Add an amenity and press Enter')
                            ->suggestions(['Parking', 'Water tank', 'Fenced compound', 'Security guard', 'Backup generator', 'Modern kitchen', 'Balcony', 'Master en-suite', 'Garden', 'Solar water heater', 'DSTV ready'])
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Photos & video')
                    ->description('The cover photo appears on cards and search results. Add more photos for the gallery and an optional video tour.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Cover photo')
                            ->image()
                            ->disk('public')
                            ->directory('properties')
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->fetchFileInformation(false)
                            ->getUploadedFileUsing(static::resolveExistingFile())
                            ->helperText('The current photo shows below with an ✕ to remove it — or drop a new image on top to replace it.'),

                        Forms\Components\FileUpload::make('photos')
                            ->label('Gallery photos')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->openable()
                            ->downloadable()
                            ->disk('public')
                            ->directory('properties')
                            ->maxFiles(8)
                            ->fetchFileInformation(false)
                            ->getUploadedFileUsing(static::resolveExistingFile())
                            ->helperText('Add more photos, drag to reorder, or remove any with the ✕.'),

                        Forms\Components\FileUpload::make('video')
                            ->label('Video tour (optional)')
                            ->disk('public')
                            ->directory('property-videos')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'])
                            ->maxSize(51200) // 50 MB
                            ->openable()
                            ->downloadable()
                            ->helperText('MP4, WebM, OGG or MOV, up to 50 MB. Upload a new file to replace the current tour, or remove it with the ✕.')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Location on map')
                    ->description('Coordinates place the property on the map. Leave blank to use the region centre.')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->numeric()->minValue(-90)->maxValue(90)
                            ->placeholder('-3.3869'),
                        Forms\Components\TextInput::make('longitude')
                            ->numeric()->minValue(-180)->maxValue(180)
                            ->placeholder('36.6830'),
                    ]),

                Forms\Components\Section::make('Contact & status')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('landlord_name')
                            ->label('Contact name')
                            ->required()
                            ->maxLength(100)
                            ->default(fn () => Auth::user()?->name),

                        Forms\Components\TextInput::make('phone')
                            ->label('Phone (call / WhatsApp)')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->default(fn () => Auth::user()?->phone),

                        Forms\Components\Select::make('user_id')
                            ->label('Owner')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->visible(fn () => Auth::user()?->isAdmin())
                            ->required(fn () => Auth::user()?->isAdmin()),

                        Forms\Components\Toggle::make('is_available')
                            ->label('Available for rent')
                            ->default(true),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured on homepage')
                            ->helperText('Only admins can feature listings.')
                            ->visible(fn () => Auth::user()?->isAdmin()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('')
                    ->getStateUsing(fn (Property $record) => $record->image_url)
                    ->square(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->description(fn (Property $record) => trim(($record->area ? $record->area.', ' : '').$record->region, ', ')),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Owner')
                    ->visible(fn () => Auth::user()?->isAdmin())
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Rent / month')
                    ->formatStateUsing(fn ($state) => 'TZS '.number_format($state))
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Available')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('region')
                    ->options(array_combine(Property::REGIONS, Property::REGIONS)),
                Tables\Filters\SelectFilter::make('type')
                    ->options(array_combine(Property::TYPES, Property::TYPES)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Property $record) => route('properties.show', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }

    /**
     * Show existing files as removable preview cards — including external image
     * URLs from the seed data, which Filament would otherwise skip because they
     * don't live on the disk. This makes every current photo/video appear with
     * an ✕ so it can be removed and replaced.
     */
    protected static function resolveExistingFile(): Closure
    {
        return function (BaseFileUpload $component, string $file, string|array|null $storedFileNames): ?array {
            if (Str::startsWith($file, ['http://', 'https://'])) {
                return [
                    'name' => basename((string) parse_url($file, PHP_URL_PATH)) ?: 'photo',
                    'size' => 0,
                    'type' => null,
                    'url' => $file,
                ];
            }

            $storage = $component->getDisk();

            try {
                if (! $storage->exists($file)) {
                    return null;
                }
            } catch (\Throwable $e) {
                return null;
            }

            return [
                'name' => ($component->isMultiple() ? ($storedFileNames[$file] ?? null) : $storedFileNames) ?? basename($file),
                'size' => $storage->size($file),
                'type' => $storage->mimeType($file),
                'url' => $storage->url($file),
            ];
        };
    }

    /**
     * Landlords only ever see and manage their own listings; admins see everything.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user && ! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    // --- Only admins and approved landlords may manage listings ---------

    public static function canViewAny(): bool
    {
        return (bool) Auth::user()?->canManageListings();
    }

    public static function canCreate(): bool
    {
        return (bool) Auth::user()?->canManageListings();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return (bool) Auth::user()?->canManageListings();
    }
}
