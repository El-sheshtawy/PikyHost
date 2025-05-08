<?php

namespace App\Filament\Pages;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Relaticle\Flowforge\Filament\Pages\KanbanBoardPage;

class TasksBoardBoardPage extends KanbanBoardPage
{
    protected static ?string $navigationIcon = 'heroicon-o-view-columns';
    protected static ?string $navigationLabel = 'Tasks Board';
    protected static ?string $title = 'Task Board';

    public function getSubject(): Builder
    {
        return Task::query();
    }

    public function mount(): void
    {
        $this
            ->titleField('title')
            ->orderField('order_column')
            ->columnField('status')
            ->columns(TaskStatus::options()) // Uses enum labels with translations
            ->columnColors([
                TaskStatus::Pending->value => 'gray',
                TaskStatus::InProgress->value => 'yellow',
                TaskStatus::Review->value => 'blue',
                TaskStatus::Completed->value => 'green',
                TaskStatus::Cancelled->value => 'red',
            ]);
    }
}
