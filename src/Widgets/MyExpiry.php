<?php

namespace Moox\Expiry\Widgets;

use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Moox\Expiry\Models\Expiry;

class MyExpiry extends BaseWidget
{
    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 3,
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
}
