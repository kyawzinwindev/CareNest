<?php

namespace App\Filament\Resources\Services\Tables;

use App\Enums\Specialization;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\QueryException;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
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
                TextColumn::make("specialization")
                    ->label("Specialization")
                    ->formatStateUsing(fn($state) => $state?->label() ?? '')
                    ->searchable(),
                TextColumn::make("created_at")->dateTime()
            ])
            ->filters([
                SelectFilter::make('specialization')
                    ->options(Specialization::class)
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                ->successNotification(null)
                    ->action(function ($record) {
                        try {
                            $record->delete();

                            Notification::make()
                                ->title('Deleted successfully')
                                ->success()
                                ->send();
                        } catch (QueryException $e) {

                            Notification::make()
                                ->title('Cannot delete service')
                                ->body('There have appointments on this service. Please delete them first!')
                                ->danger()
                                ->send();
                        }
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
