<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Models\Subscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;
    protected static ?string $navigationIcon  = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Subscription Payments';
    protected static ?string $pluralModelLabel = 'Subscription Payments';
    protected static ?string $modelLabel = 'Subscription Payment';
    protected static ?string $navigationGroup = 'Management';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->label('Technician')
                ->required(),

            Forms\Components\Select::make('plan_type')
                ->label('Plan')
                ->options([
                    'basic'    => 'Basic (TZS 15,000 / 30 days)',
                    'standard' => 'Standard (TZS 35,000 / 90 days)',
                    'premium'  => 'Premium (TZS 100,000 / year)',
                ])
                ->required(),

            Forms\Components\TextInput::make('mpesa_reference')
                ->label('Transaction Number (M-Pesa / Tigo / Airtel)')
                ->required(),

            Forms\Components\Select::make('payment_method')
                ->label('Payment Method')
                ->options([
                    'mpesa'    => 'M-Pesa',
                    'tigopesa' => 'Tigo Pesa',
                    'airtel'   => 'Airtel Money',
                ])
                ->required(),

            Forms\Components\TextInput::make('amount_paid')
                ->label('Amount Paid')
                ->numeric()
                ->prefix('TZS')
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'pending_approval' => 'Pending Approval',
                    'active'           => 'Active (Approved)',
                    'rejected'         => 'Rejected',
                    'expired'          => 'Expired',
                ])
                ->required(),

            Forms\Components\Textarea::make('admin_notes')
                ->label('Admin Notes')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Technician')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('plan_type')
                    ->label('Plan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'premium'  => 'success',
                        'standard' => 'info',
                        default    => 'gray',
                    }),

                Tables\Columns\TextColumn::make('mpesa_reference')
                    ->label('Payment Ref.')
                    ->copyable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge(),

                Tables\Columns\TextColumn::make('amount_paid')
                    ->label('Amount')
                    ->money('TZS')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active'           => 'success',
                        'pending_approval' => 'warning',
                        'rejected'         => 'danger',
                        'expired'          => 'danger',
                        default            => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active'           => 'Active',
                        'pending_approval' => 'Pending',
                        'rejected'         => 'Rejected',
                        'expired'          => 'Expired',
                        default            => $state,
                    }),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires At')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending_approval' => 'Pending Approval',
                        'active'           => 'Active',
                        'rejected'         => 'Rejected',
                        'expired'          => 'Expired',
                    ]),

                Tables\Filters\SelectFilter::make('plan_type')
                    ->label('Plan')
                    ->options([
                        'basic'    => 'Basic',
                        'standard' => 'Standard',
                        'premium'  => 'Premium',
                    ]),
            ])

            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Subscription $record) => $record->isPendingApproval())
                    ->requiresConfirmation()
                    ->modalHeading('Approve Payment')
                    ->modalDescription('Are you sure you want to approve this payment?')
                    ->action(function (Subscription $record) {
                        $record->approve(auth()->user());

                        Notification::make()
                            ->title('Payment approved!')
                            ->body("Technician {$record->user->name} can now receive customers.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Subscription $record) => $record->isPendingApproval())
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Reason for Rejection')
                            ->required()
                            ->placeholder('Write the reason here...'),
                    ])
                    ->action(function (Subscription $record, array $data) {
                        $record->reject(auth()->user(), $data['reason']);

                        Notification::make()
                            ->title('Payment rejected.')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\ViewAction::make()->label('View'),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit'   => Pages\EditSubscription::route('/{record}/edit'),
            'view'   => Pages\ViewSubscription::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'pending_approval')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}