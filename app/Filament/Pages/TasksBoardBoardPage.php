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

    public $project_id;

    public function mount(): void
    {
        $this->project_id = request()->query('project_id');

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

    public function getSubject(): Builder
    {
        $query = Task::query();

        if ($this->project_id) {
            $query->where('project_id', $this->project_id);
        }

        return $query;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false; // Hide from main navigation since we access it through projects
    }
}
