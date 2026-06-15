<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Management';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('technician_id')
                ->relationship('technician', 'name')
                ->label('Technician')
                ->required()
                ->disabled(),

            Forms\Components\Select::make('customer_id')
                ->relationship('customer', 'name')
                ->label('Customer')
                ->required()
                ->disabled(),

            Forms\Components\TextInput::make('rating')
                ->label('Rating')
                ->numeric()
                ->required()
                ->minValue(1)
                ->maxValue(5),

            Forms\Components\Textarea::make('comment')
                ->label('Private Customer Feedback')
                ->columnSpanFull(),

            // ONGEZA SEHEMU HII HAPA:
            Forms\Components\Textarea::make('bookings.technician_notes')
                ->label('Technician Note')
                ->columnSpanFull(),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('technician.name')
                    ->label('Technician')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1', '2' => 'danger',
                        '3' => 'warning',
                        '4', '5' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Private Feedback')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('bookings.technician_notes')
              ->label('Technician Note')
              ->searchable()
                 ->limit(50), // Inaongeza hii ili kuonyesha sehemu ya maoni ya fundi kwenye orodha


            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

                
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}