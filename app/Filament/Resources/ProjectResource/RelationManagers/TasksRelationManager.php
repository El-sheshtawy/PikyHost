<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options(TaskStatus::options())
                    ->required(),
                Forms\Components\Select::make('priority')
                    ->options(TaskPriority::options())
                    ->required(),
                Forms\Components\TextInput::make('progress')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                Forms\Components\DateTimePicker::make('due_at'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (TaskStatus $state): string => match ($state) {
                        TaskStatus::Pending => 'gray',
                        TaskStatus::InProgress => 'blue',
                        TaskStatus::Review => 'orange',
                        TaskStatus::Completed => 'green',
                        TaskStatus::Cancelled => 'red',
                    }),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (TaskPriority $state): string => match ($state) {
                        TaskPriority::LOW => 'gray',
                        TaskPriority::MEDIUM => 'blue',
                        TaskPriority::HIGH => 'orange',
                        TaskPriority::CRITICAL => 'red',
                    }),
                Tables\Columns\TextColumn::make('progress')
                    ->numeric()
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('due_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(TaskStatus::options()),
                Tables\Filters\SelectFilter::make('priority')
                    ->options(TaskPriority::options()),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
