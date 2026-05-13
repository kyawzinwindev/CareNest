<?php

namespace App\Filament\Resources\Schedules\RelationManagers;

use App\Enums\TimeSlotStatus;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TimeSlotsRelationManager extends RelationManager
{
    protected static string $relationship = 'time_slots';
    protected static ?string $title = 'Schedule Time Slots';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TimePicker::make('start_time')
                    ->required(),
                TimePicker::make('end_time')
                    ->required(),
                Select::make('status')
                    ->options(TimeSlotStatus::class)
                    ->default('available')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('start_time')
            ->columns([
                TextColumn::make('start_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                SelectColumn::make('status')
                    ->options(function ($record) {

                        if ($record->status === TimeSlotStatus::BOOKED) {
                            return [
                                TimeSlotStatus::BOOKED->value => 'Booked',
                            ];
                        }

                        return [
                            TimeSlotStatus::AVAILABLE->value => 'Available',
                            TimeSlotStatus::UNAVAILABLE->value => 'Unavailable',
                        ];
                    })
                    ->disabled(fn($record) => $record->status === TimeSlotStatus::BOOKED),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                // EditAction::make(),
                // DissociateAction::make(),
                // DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
