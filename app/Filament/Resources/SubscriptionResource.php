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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Section;


class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;
    protected static ?string $navigationIcon  = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Subscription Payments and Document Approvals';
    protected static ?string $pluralModelLabel = 'Subscription Payments and Document Approvals';
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

            Forms\Components\Section::make('Technician Documents')
    ->description('Review the documents before approving the subscription.')
    ->schema([
        // Certificate Placeholder
        Forms\Components\Placeholder::make('certificate_display')
            ->label('Certificate')
            ->extraAttributes([
                'style' => 'background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px; padding: 15px;'
            ])
            ->content(fn ($record) => $record->user->technicianProfile?->certificate_path 
                ? new \Illuminate\Support\HtmlString('
                    <div style="display: flex; gap: 10px;">
                        <a href="'.asset('storage/'.$record->user->technicianProfile->certificate_path).'" target="_blank" 
                           style="padding: 8px 16px; background: #3b82f6; color: white; border-radius: 6px; text-decoration: none; font-weight: 500;">View</a>
                        <a href="'.asset('storage/'.$record->user->technicianProfile->certificate_path).'" download 
                           style="padding: 8px 16px; background: #10b981; color: white; border-radius: 6px; text-decoration: none; font-weight: 500;">Download</a>
                    </div>')
                : 'No certificate uploaded'),

        // Residency Letter Placeholder
        Forms\Components\Placeholder::make('residency_letter_display')
            ->label('Residency Letter')
            ->extraAttributes([
                'style' => 'background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px; padding: 15px;'
            ])
            ->content(fn ($record) => $record->user->technicianProfile?->residency_letter_path 
                ? new \Illuminate\Support\HtmlString('
                    <div style="display: flex; gap: 10px;">
                        <a href="'.asset('storage/'.$record->user->technicianProfile->residency_letter_path).'" target="_blank" 
                           style="padding: 8px 16px; background: #3b82f6; color: white; border-radius: 6px; text-decoration: none; font-weight: 500;">View</a>
                        <a href="'.asset('storage/'.$record->user->technicianProfile->residency_letter_path).'" download 
                           style="padding: 8px 16px; background: #10b981; color: white; border-radius: 6px; text-decoration: none; font-weight: 500;">Download</a>
                    </div>')
                : 'No residency letter uploaded'),
    ])
    ->collapsible(),
    

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