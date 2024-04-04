<?php

namespace Moox\Expiry\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Moox\Expiry\Models\Expiry;
use Moox\Expiry\Resources\ExpiryResource\Pages;

class ExpiryResource extends Resource
{
    protected static ?string $model = Expiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationGroup = 'Moox Expiry';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 0])->schema([
                    TextInput::make('title')
                        ->rules(['max:255', 'string'])
                        ->required()
                        ->placeholder('Title')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    TextInput::make('slug')
                        ->rules(['max:255', 'string'])
                        ->required()
                        ->placeholder('Slug')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    TextInput::make('item')
                        ->rules(['max:255', 'string'])
                        ->required()
                        ->placeholder('Item')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    TextInput::make('link')
                        ->rules(['max:255', 'string'])
                        ->required()
                        ->placeholder('Link')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    DateTimePicker::make('expired_at')
                        ->rules(['date'])
                        ->required()
                        ->placeholder('Expired At')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    DateTimePicker::make('notified_at')
                        ->rules(['date'])
                        ->placeholder('Notified At')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    TextInput::make('notified_to')
                        ->rules(['max:255', 'string'])
                        ->placeholder('Notified To')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    DateTimePicker::make('escalated_at')
                        ->rules(['date'])
                        ->placeholder('Escalated At')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    TextInput::make('escalated_to')
                        ->rules(['max:255', 'string'])
                        ->placeholder('Escalated To')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    TextInput::make('handled_by')
                        ->rules(['max:255', 'string'])
                        ->placeholder('Handled By')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    DateTimePicker::make('done_at')
                        ->rules(['date'])
                        ->placeholder('Done At')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    Select::make('expiry_monitor_id')
                        ->rules(['exists:expiry_monitors,id'])
                        ->required()
                        ->relationship('expiryMonitor', 'title')
                        ->searchable()
                        ->placeholder('Expiry Monitor')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->toggleable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('slug')
                    ->toggleable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('link')
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('expired_at')
                    ->toggleable()
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('notified_at')
                    ->label('Notify at')
                    ->toggleable()
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('notified_to')
                    ->label('Notify to')
                    ->toggleable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('escalated_at')
                    ->label('Escalate at')
                    ->toggleable()
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('escalated_to')
                    ->label('Escalate to')
                    ->toggleable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('expiryMonitor.title')
                    ->toggleable()
                    ->limit(50),
            ])
            ->filters([
                SelectFilter::make('expiry_monitor_id')
                    ->relationship('expiryMonitor', 'title')
                    ->indicator('ExpiryMonitor')
                    ->multiple()
                    ->label('ExpiryMonitor'),
            ])
            ->actions([
                Action::make('Edit')->url(fn ($record): string => "/wp/wp-admin/post.php?post={$record->item_id}&action=edit"),
                ViewAction::make(),
                EditAction::make()])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpiries::route('/'),
            'create' => Pages\CreateExpiry::route('/create'),
            'view' => Pages\ViewExpiry::route('/{record}'),
            'edit' => Pages\EditExpiry::route('/{record}/edit'),
        ];
    }
}
