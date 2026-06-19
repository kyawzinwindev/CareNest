<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\Role;
use App\Enums\Specialization;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')->label("ID"),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('role')
                    ->formatStateUsing(fn($state) => $state->label()),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options(Role::optionsForFilter()),
                SelectFilter::make('specialization')
                    ->label('Doctor Specialization')
                    ->options(Specialization::class)
                    ->query(fn(Builder $query, array $data) => $query->when(
                        $data['value'],
                        fn($q) => $q->whereHas('doctor', fn($dq) => $dq->where('specialization', $data['value']))
                    ))
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
                                ->title('Cannot delete user')
                                ->body('Please delete related records first.')
                                ->danger()
                                ->send();
                        }
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
