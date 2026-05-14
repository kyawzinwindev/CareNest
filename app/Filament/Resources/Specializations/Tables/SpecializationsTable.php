<?php

namespace App\Filament\Resources\Specializations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\QueryException;

class SpecializationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make("id")->label("ID"),
                TextColumn::make("name")->searchable(),
                TextColumn::make("description")
                    ->limit(50)
                    ->tooltip(fn ($state): string => $state)
                    ->searchable(),
                TextColumn::make('created_at')->dateTime()

            ])
            ->filters([
                //
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
                                ->title('Cannot delete specialization')
                                ->body('Please delete related records first.')
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
