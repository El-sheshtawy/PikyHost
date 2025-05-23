<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

   public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
   {
       return false;
   }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options(TaskStatus::options())
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('priority')
                    ->options(TaskPriority::options())
                    ->required()
                    ->native(false),
                Forms\Components\TextInput::make('progress')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%'),
                Forms\Components\DateTimePicker::make('due_at'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (TaskStatus $state): string => $state->label())
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->formatStateUsing(fn (TaskPriority $state): string => $state->label())
                    ->sortable(),
                Tables\Columns\TextColumn::make('progress')
                    ->numeric()
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(TaskStatus::options()),
                Tables\Filters\SelectFilter::make('priority')
                    ->options(TaskPriority::options()),
            ], Tables\Enums\FiltersLayout::Modal)
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
            ])
            ->defaultSort('created_at', 'desc');
    }
}
