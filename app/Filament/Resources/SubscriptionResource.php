<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Models\Subscription;
use App\Models\TechnicianProfile;
use App\Scopes\ActiveSubscriptionScope;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

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
                ->required()
                ->reactive() // MUHIMU: Hii inamruhusu Admin kuchagua na inafanya form iwe hai
                ->afterStateUpdated(fn ($set) => $set('receipt_display', null)),

            Forms\Components\Select::make('plan_type')
                ->label('Plan')
                ->options([
                    'basic'    => 'Basic (TZS 15,000 / 30 days)',
                    'standard' => 'Standard (TZS 35,000 / 90 days)',
                    'premium'  => 'Premium (TZS 100,000 / year)',
                ])
                ->required(),

            // Section hii inaonekana TU wakati wa EDIT, kuzuia error kwenye CREATE
            Forms\Components\Section::make('Current Receipt')
                ->visible(fn ($record) => $record && $record->payment_receipt)
                ->schema([
                    Forms\Components\Placeholder::make('receipt_display')
                        ->label('')
                        ->content(function ($record) {
                            $url = asset('storage/' . $record->payment_receipt);
                            return new \Illuminate\Support\HtmlString('
                                <div style="display: flex; gap: 10px;">
                                    <a href="'.$url.'" target="_blank" style="padding: 8px 16px; background: #3b82f6; color: white; border-radius: 6px; text-decoration: none;">View</a>
                                    <a href="'.$url.'" download style="padding: 8px 16px; background: #10b981; color: white; border-radius: 6px; text-decoration: none;">Download</a>
                                </div>
                            ');
                        }),
                ]),

            Forms\Components\Select::make('payment_method')
                ->label('Payment Method')
                ->options([
                    'mpesa'    => 'M-Pesa',
                    'tigopesa' => 'Tigo Pesa',
                    'airtel'   => 'Airtel Money',
                ])
                ->required(),


                Forms\Components\FileUpload::make('payment_receipt')
    ->label('Payment Receipt')
    ->image()
    ->disk('public')
    ->directory('subscriptions/receipts')
    ->required()
    ->columnSpanFull(),

            Forms\Components\Section::make('Technician Documents')
                ->description('Review the documents before approving the subscription.')
                ->visible(fn ($record) => $record !== null) // Hii inazuia kosa la null kwenye ukurasa wa Create
                ->schema([
                    Forms\Components\Placeholder::make('certificate_display')
                        ->label('Certificate')
                        ->content(function ($record) {
                            if (!$record || !$record->user_id) return 'No technician selected';
                            
                            $profile = TechnicianProfile::withoutGlobalScope(ActiveSubscriptionScope::class)
                                ->where('user_id', $record->user_id)->first();
                                
                            return ($profile && $profile->certificate_path) 
                                ? new \Illuminate\Support\HtmlString('<div style="display: flex; gap: 10px;"><a href="'.asset('storage/'.$profile->certificate_path).'" target="_blank" style="padding: 8px 16px; background: #3b82f6; color: white; border-radius: 6px; text-decoration: none;">View</a><a href="'.asset('storage/'.$profile->certificate_path).'" download style="padding: 8px 16px; background: #10b981; color: white; border-radius: 6px; text-decoration: none;">Download</a></div>')
                                : 'No certificate uploaded';
                        }),
                    Forms\Components\Placeholder::make('residency_letter_display')
                        ->label('Residency Letter')
                        ->content(function ($record) {
                            if (!$record || !$record->user_id) return 'No technician selected';

                            $profile = TechnicianProfile::withoutGlobalScope(ActiveSubscriptionScope::class)
                                ->where('user_id', $record->user_id)->first();
                            return ($profile && $profile->residency_letter_path) 
                                ? new \Illuminate\Support\HtmlString('<div style="display: flex; gap: 10px;"><a href="'.asset('storage/'.$profile->residency_letter_path).'" target="_blank" style="padding: 8px 16px; background: #3b82f6; color: white; border-radius: 6px; text-decoration: none;">View</a><a href="'.asset('storage/'.$profile->residency_letter_path).'" download style="padding: 8px 16px; background: #10b981; color: white; border-radius: 6px; text-decoration: none;">Download</a></div>')
                                : 'No residency letter uploaded';
                        }),
                ])->collapsible(),

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
                    'queued'           => 'Queued (Waiting)',
                ])
                ->default('pending_approval')
                ->required(),

            Forms\Components\Textarea::make('admin_notes')
                ->label('Admin Notes')
                ->columnSpanFull(),
        ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user.technicianProfile' => function ($query) {
            $query->withoutGlobalScope(ActiveSubscriptionScope::class);
        }]);
    }

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('user.name')->label('Technician')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('plan_type')->label('Plan')->badge(),
            // Nimeondoa ImageColumn hapa ili kuondoa ile loading
            Tables\Columns\TextColumn::make('payment_method')->label('Method')->badge(),
            Tables\Columns\TextColumn::make('amount_paid')->label('Amount')->money('TZS')->sortable(),
            Tables\Columns\TextColumn::make('status')->label('Status')->badge(),
        ])
        ->actions([
            // Hizi ndizo Action za View na Download (Sawa na zile za kwenye Documents)
            

           Tables\Actions\Action::make('approve')
    ->label('Approve')
    ->icon('heroicon-o-check-circle')
    ->color('success')
    // Sasa inaonekana kama ni 'pending_approval' AU 'queued'
    ->visible(fn (Subscription $record) => in_array($record->status, ['pending_approval', 'queued']))
    ->requiresConfirmation()
    ->action(function (Subscription $record) {
        $user = $record->user;

        // 1. Angalia kama kuna subscription nyingine iliyo active
        $hasActive = \App\Models\Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->exists();

        if ($hasActive) {
            // Kama ipo, hii mpya iwe 'queued' tu
            $record->update(['status' => 'queued']);
            \Filament\Notifications\Notification::make()
                ->title('Subscription Queued')
                ->body('user has already an active subscription (queued) its planned to start later.')
                ->success()
                ->send();
        } else {
            // Kama hakuna, i-approve moja kwa moja
            $record->approve(auth()->user());
            \Filament\Notifications\Notification::make()
                ->title('Subscription Approved')
                ->body('Subscription sasa inafanya kazi (Active).')
                ->success()
                ->send();
        }
    }),
        ]);
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
}