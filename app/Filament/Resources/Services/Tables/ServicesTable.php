<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("id")
                    ->label("ID"),
                TextColumn::make("name")
                    ->searchable(),
                TextColumn::make("description")
                    ->limit(50)
                    ->tooltip(fn($state): string => $state)
                    ->searchable(),
                TextColumn::make("price"),
                IconColumn::make('required_prepayment')
                    ->label("Required Prepayment")
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make("specialization.name")
                    ->label("Specialization")
                    ->searchable(),
                TextColumn::make("created_at")->dateTime()
            ])
            ->filters([
                SelectFilter::make('specialization')
                    ->relationship('specialization', 'name')
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
