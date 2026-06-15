<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Job Requests';
    protected static ?string $navigationGroup = 'Management';
    protected static ?int    $navigationSort  = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Booking Information')
                ->schema([
                    Forms\Components\TextInput::make('booking_code')
                        ->label('Booking Number')
                        ->disabled(),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending'     => 'Pending',
                            'accepted'    => 'Accepted',
                            'rejected'    => 'Rejected',
                            'in_progress' => 'In Progress',
                            'completed'   => 'Completed',
                            'cancelled'   => 'Cancelled',
                        ]),
                ]),

            Forms\Components\Section::make('Financials & Notes')
                ->schema([
                    Forms\Components\TextInput::make('agreed_price')
                        ->label('Agreed Price (TZS)')
                        ->numeric()
                        ->prefix('TZS')
                        ->placeholder('0.00'),

                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('technician_notes')
                        ->label('Technician Notes')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_code')
                    ->label('Booking Number')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('technician.name')
                    ->label('Technician')
                    ->searchable(),

                Tables\Columns\TextColumn::make('agreed_price')
                    ->label('Price')
                    ->money('TZS')
                    ->sortable(),

                
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed'   => 'success',
                        'accepted'    => 'info',
                        'in_progress' => 'info',
                        'pending'     => 'warning',
                        'rejected'    => 'danger',
                        'cancelled'   => 'danger',
                        default       => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'     => 'Pending',
                        'accepted'    => 'Accepted',
                        'completed'   => 'Completed',
                        'rejected'    => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('complete_job')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('technician_notes')
                            ->label('Technician Notes')
                            ->required(),
                    ])
                    ->action(function (Booking $record, array $data): void {
                        $record->update([
                            'status' => 'completed',
                            'technician_notes' => $data['technician_notes'],
                        ]);

                        Notification::make()
                            ->title('Job completed successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Booking $record) => $record->status !== 'completed'),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBookings::route('/'),
            'view'   => Pages\ViewBooking::route('/{record}'),
            'edit'   => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}