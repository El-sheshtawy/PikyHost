<?php

namespace App\Filament\Pages;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use Filament\Actions\Action;
use Filament\Forms\Components;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class TasksBoardBoardPage extends KanbanBoardPage
{
    protected static ?string $navigationIcon = 'heroicon-o-view-columns';
    protected static ?string $navigationLabel = 'Tasks Board';
    protected static ?string $title = 'Task Board';
    protected static ?string $model = Task::class;

    public $project_id;

    public function mount(): void
    {
        $this->project_id = request()->query('project_id');
        session(['current_project_id' => $this->project_id]);

        $this
            ->titleField('title')
            ->descriptionField('description')
            ->orderField('order_column')
            ->columnField('status')
            ->columns(TaskStatus::options())
            ->columnColors([
                TaskStatus::Pending->value => 'gray',
                TaskStatus::InProgress->value => 'yellow',
                TaskStatus::Review->value => 'blue',
                TaskStatus::Completed->value => 'green',
                TaskStatus::Cancelled->value => 'red',
            ])
            ->cardLabel('Task')
            ->pluralCardLabel('Tasks')
            ->cardView('filament.pages.custom-task-card') // Add custom card view
            ->cardAttributes([
                'priority' => 'Priority',
                'due_at' => 'Due Date',
            ])
            ->cardAttributeIcons([
                'priority' => 'heroicon-o-flag',
                'due_at' => 'heroicon-o-calendar',
                'description' => 'heroicon-o-document-text',
            ]);
    }

    public function getSubject(): Builder
    {
        $query = Task::query()->with(['users']);
        $projectId = session('current_project_id') ?? $this->project_id;

        if ($projectId) {
            $query->where('project_id', $projectId);
        }
        return $query;
    }

    // Create custom card view
    public function getCardViewData($record)
    {
        return [
            'record' => $record,
            'title' => $record->title,
            'description' => $record->description,
            'priority' => $record->priority,
            'due_at' => $record->due_at,
            'assignees' => $record->users,
        ];
    }

    public function createAction(Action $action): Action
    {
        return $action
            ->iconButton()
            ->icon('heroicon-o-plus')
            ->modalHeading('Create New Task')
            ->modalWidth('xl')
            ->form([
                Components\Select::make('users')
                    ->label('Assignees')
                    ->multiple()
                    ->relationship('users', 'name')
                    ->searchable()
                    ->preload(),
                Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Components\Textarea::make('description')
                    ->columnSpanFull(),
                Components\Select::make('priority')
                    ->options(TaskPriority::options())
                    ->required()
                    ->native(false),
                Components\DateTimePicker::make('due_at'),
            ])
            ->mutateFormDataUsing(function (array $data): array {
                $data['project_id'] = session('current_project_id') ?? $this->project_id;
                return $data;
            });
    }

    public function editAction(Action $action): Action
    {
        return $action
            ->modalHeading('Edit Task')
            ->modalWidth('xl')
            ->form([
                Components\Select::make('users')
                    ->label('Assignees')
                    ->multiple()
                    ->relationship('users', 'name')
                    ->searchable()
                    ->preload(),
                Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Components\Textarea::make('description')
                    ->columnSpanFull(),
                Components\Select::make('priority')
                    ->options(TaskPriority::options())
                    ->required()
                    ->native(false),
                Components\DateTimePicker::make('due_at'),
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
