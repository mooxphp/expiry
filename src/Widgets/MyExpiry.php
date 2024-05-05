<?php

namespace Moox\Expiry\Widgets;

use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Moox\Expiry\Models\Expiry;

class MyExpiry extends BaseWidget
{
    protected int|string|array $columnSpan = [
        'sm' => 3,
        'md' => 6,
        'xl' => 12,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Expiry::query()->where('notified_to', auth()->id())->where('done_at', null),
            )

            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->toggleable()
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('expired_at')
                    ->toggleable()
                    ->sortable()
                    ->since(),
                Tables\Columns\TextColumn::make('escalated_at')
                    ->label('Escalate at')
                    ->toggleable()
                    ->sortable()
                    ->since(),
                Tables\Columns\TextColumn::make('escalated_to')
                    ->label('Escalate to')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('expiry_job')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->limit(50),
            ])
            ->filters([
                SelectFilter::make('expiry_job')
                    ->label('Expiry Job'),
            ])
            ->actions([
                Action::make('View')->url(fn ($record): string => "{$record->link}"),
                Action::make('Edit')->url(fn ($record): string => "/wp/wp-admin/post.php?post={$record->item_id}&action=edit"),
            ])
            ->bulkActions([DeleteBulkAction::make()]);
    }
}
