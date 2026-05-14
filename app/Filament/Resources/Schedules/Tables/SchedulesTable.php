<?php

namespace App\Filament\Resources\Schedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\QueryException;

class SchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("id")->label("ID"),
                TextColumn::make("doctor.user.name"),
                TextColumn::make("date")->date(),
                TextColumn::make("start_time")->label("Start Time"),
                TextColumn::make("end_time")->label("End Time"),
                TextColumn::make("slot_duration_minutes")
                    ->label("Duration"),
                TextColumn::make("created_at")->dateTime()
            ])
            ->filters([
                SelectFilter::make("doctor")
                    ->relationship(
                        'doctor',
                        'id',
                        fn($query) => $query->with('user')
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn($record) => $record->user->name
                    )
                    ->searchable()
                    ->preload(),

                Filter::make('date')
                    ->form([
                        DatePicker::make('date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['date'],
                                fn($query, $date) => $query->whereDate('date', $date)
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
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
                                ->title('Cannot delete schedule')
                                ->body('There have appointments on this schedule. Please delete them first!')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
