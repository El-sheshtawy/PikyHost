<?php

namespace App\Filament\Pages;

use App\Models\Task;
use App\Enums\TaskStatus;
use Filament\Actions\Action;
use Filament\Forms\Components;
use Illuminate\Database\Eloquent\Builder;
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
            ->cardAttributes([
                'priority' => 'Priority',
                'due_at' => 'Due Date',
                'owner.name' => 'Assigned To',
            ])
            ->cardAttributeColors([
                'priority' => fn ($state) => match($state) {
                    'low' => 'gray',
                    'medium' => 'blue',
                    'high' => 'orange',
                    'critical' => 'red',
                    default => 'gray',
                },
                'due_at' => 'red',
            ])
            ->cardAttributeIcons([
                'priority' => 'heroicon-o-flag',
                'due_at' => 'heroicon-o-calendar',
                'owner.name' => 'heroicon-o-user',
            ])
            ->initialCardsCount(15)
            ->cardsIncrement(10);
    }

    public function getSubject(): Builder
    {
        $query = Task::query()->with(['owner']);

        if ($this->project_id) {
            $query->where('project_id', $this->project_id);
        }

        return $query;
    }

    public function createAction(Action $action): Action
    {
        return $action
            ->iconButton()
            ->icon('heroicon-o-plus')
            ->modalHeading('Create New Task')
            ->modalWidth('xl')
            ->form([
                Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Components\Select::make('status')
                    ->options(TaskStatus::options())
                    ->required()
                    ->native(false),
                Components\Select::make('priority')
                    ->options(TaskPriority::options())
                    ->required()
                    ->native(false),
                Components\Textarea::make('description')
                    ->columnSpanFull(),
                Components\DateTimePicker::make('due_at'),
                Components\Select::make('owner_id')
                    ->relationship('owner', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->using(function (array $data) {
                if ($this->project_id) {
                    $data['project_id'] = $this->project_id;
                }
                return Task::create($data);
            });
    }

    public function editAction(Action $action): Action
    {
        return $action
            ->modalHeading('Edit Task')
            ->modalWidth('xl')
            ->form([
                Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Components\Select::make('status')
                    ->options(TaskStatus::options())
                    ->required()
                    ->native(false),
                Components\Select::make('priority')
                    ->options(TaskPriority::options())
                    ->required()
                    ->native(false),
                Components\Textarea::make('description')
                    ->columnSpanFull(),
                Components\DateTimePicker::make('due_at'),
                Components\Select::make('owner_id')
                    ->relationship('owner', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public function viewAction(Action $action): Action
    {
        return $action
            ->modalHeading('Task Details')
            ->modalWidth('xl')
            ->modalContent(view('filament.pages.task-view', [
                'record' => $action->getRecord(),
            ]));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
