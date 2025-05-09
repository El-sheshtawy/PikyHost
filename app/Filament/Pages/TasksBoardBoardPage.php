<?php

namespace App\Filament\Pages;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use Filament\Actions\Action;
use Filament\Forms\Components;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Relaticle\Flowforge\Filament\Pages\KanbanBoardPage;

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

        $cardAttributes = [
            'priority' => 'Priority',
            'due_at' => 'Due Date',
            'assignees' => 'Assignees',
        ];

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
            ->cardAttributes($cardAttributes)
            ->cardAttributeIcons([
                'priority' => 'heroicon-o-flag',
                'due_at' => 'heroicon-o-calendar',
                'description' => 'heroicon-o-document-text',
                'assignees' => 'heroicon-o-users',
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

    public function createAction(Action $action): Action
    {
        return $action
            ->iconButton()
            ->icon('heroicon-o-plus')
            ->modalHeading('Create New Task')
            ->modalWidth('md')
            ->form([
                Components\Select::make('users')
                    ->label('Assignees')
                    ->multiple()
                    ->relationship('users', 'name')
                    ->searchable()
                    ->preload()
                    ->translateLabel(),
                Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->translateLabel(),
                Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->translateLabel(),
                Components\Select::make('status')
                    ->options(TaskStatus::options())
                    ->required()
                    ->native(false)
                    ->translateLabel(),
                Components\Select::make('priority')
                    ->options(TaskPriority::options())
                    ->required()
                    ->native(false)
                    ->translateLabel(),
                Components\DateTimePicker::make('due_at')
                    ->translateLabel(),
            ])
            ->mutateFormDataUsing(function (array $data): array {
                $data['project_id'] = session('current_project_id') ?? $this->project_id;

                Log::info('mutateFormDataUsing: project_id = ' . ($data['project_id'] ?? 'null'));

                if (empty($data['project_id'])) {
                    throw new \Exception('Project ID is required to create a task.');
                }

                return $data;
            });
    }

    public function editAction(Action $action): Action
    {
        return $action
            ->modalHeading('Edit Task')
            ->modalWidth('md')
            ->form([
                Components\Select::make('users')
                    ->label('Assignees')
                    ->multiple()
                    ->relationship('users', 'name')
                    ->searchable()
                    ->preload()
                    ->translateLabel(),
                Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->translateLabel(),
                Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->translateLabel(),
                Components\Select::make('status')
                    ->options(TaskStatus::options())
                    ->required()
                    ->native(false)
                    ->translateLabel(),
                Components\Select::make('priority')
                    ->options(TaskPriority::options())
                    ->required()
                    ->native(false)
                    ->translateLabel(),
                Components\DateTimePicker::make('due_at')
                    ->translateLabel(),
            ]);
    }

    public function viewAction(Action $action): Action
    {
        return $action
            ->modalHeading('Task Details')
            ->modalWidth('md')
            ->modalContent(view('filament.pages.task-view', [
                'record' => $action->getRecord(),
            ]));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
