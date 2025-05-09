<?php

namespace App\Filament\Resources;

use App\Enums\ProjectStatus;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers\TasksRelationManager;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\View;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $modelLabel = 'Project';

    protected static ?string $navigationLabel = 'Projects';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('owner_id')
                            ->relationship('owner', 'name')
                            ->required()
                            ->label('Project Owner'),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Project Name'),

                        Forms\Components\TextInput::make('slug')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('feature_project_image')
                            ->collection('feature_project_image')
                            ->image()
                            ->maxSize(2048)
                            ->label('Main Feature Image')
                            ->imageEditor(),

                        Forms\Components\SpatieMediaLibraryFileUpload::make('second_feature_image')
                            ->collection('second_feature_image') // If you want a second image in same collection
                            ->image()
                            ->maxSize(2048)
                            ->label('Secondary Feature Image')
                            ->imageEditor(),

                        Forms\Components\SpatieMediaLibraryFileUpload::make('project_gallery')
                            ->collection('project_gallery')
                            ->multiple()
                            ->directory('projects/gallery')
                            ->maxFiles(10)
                            ->maxSize(5120)
                            ->label('Gallery Images/Videos')
                            ->image()
                            ->preserveFilenames(),

                        Forms\Components\SpatieMediaLibraryFileUpload::make('project_documents')
                            ->collection('project_documents')
                            ->multiple()
                            ->directory('projects/documents')
                            ->maxFiles(20)
                            ->maxSize(10240)
                            ->label('Project Documents')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Details')
                    ->schema([
                        Forms\Components\Textarea::make('summary')
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('description')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status')
                            ->options(ProjectStatus::options())
                            ->required()
                            ->native(false),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Project'),
                    ]),

                Forms\Components\Section::make('Timeline')
                    ->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Start Date'),

                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label('End Date'),

                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Completion Date'),
                    ])->columns(3),

                Forms\Components\Section::make('Progress')
                    ->schema([
                        Forms\Components\TextInput::make('budget')
                            ->maxLength(null)
                            ->numeric(),

                        Forms\Components\TextInput::make('progress')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(fn($record) => route('filament.admin.pages.tasks-board-board-page', ['project_id' => $record->id]), true)
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\SpatieMediaLibraryImageColumn::make('feature_project_image')
                        ->circular()
                        ->placeholder('-')
                        ->collection('feature_project_image')
                        ->height('100%')
                        ->width('100%'),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable(),

                        Tables\Columns\TextColumn::make('owner.name')
                            ->label(__('Owner'))
                            ->color('gray')
                            ->limit(30)
                            ->sortable(),
                    ]),
                ])->space(3),
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\TextColumn::make('status')
                        ->badge()
                        ->formatStateUsing(fn (ProjectStatus $state): string => $state->label())
                        ->sortable(),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('description')
                            ->color('gray')
                            ->limit(100)
                            ->wrap(),
                    ])->space(4),
                    Tables\Columns\TextColumn::make('progress')
                        ->numeric()
                        ->suffix('%')
                        ->sortable()
                        ->formatStateUsing(fn ($state): string => 'Progress: '.number_format($state, 1)),
                ])->collapsible(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(ProjectStatus::options())
                    ->label(__('Status'))
                    ->multiple(),
                Tables\Filters\Filter::make('is_featured')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true))
                    ->label(__('Featured Only')),
                Tables\Filters\Filter::make('active_projects')
                    ->query(fn (Builder $query): Builder => $query->where('status', ProjectStatus::ACTIVE))
                    ->label(__('Active Projects Only')),
            ], Tables\Enums\FiltersLayout::Modal)
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
                'xl' => 3,
            ])
            ->paginated([
                12,
                24,
                48,
                'all',
            ])
            ->actions([
                Tables\Actions\ViewAction::make('visit')
                    ->label(__('View'))
                    ->icon('heroicon-m-arrow-top-right-on-square'),
                Tables\Actions\EditAction::make()
                    ->label(__('Edit'))
                    ->icon('heroicon-m-pencil-square'),
                Tables\Actions\DeleteAction::make()
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $record = $infolist->getRecord();

        return $infolist
            ->schema([
                Section::make('Project Overview')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Group::make([
                                    TextEntry::make('name')
                                        ->label('Project Name'),
                                    TextEntry::make('status')
                                        ->label('Status')
                                        ->badge(),
                                    TextEntry::make('owner.name')
                                        ->label('Project Owner'),
                                ]),
                                Group::make([
                                    TextEntry::make('starts_at')
                                        ->label('Start Date')
                                        ->date(),
                                    TextEntry::make('ends_at')
                                        ->label('End Date')
                                        ->date(),
                                    TextEntry::make('progress')
                                        ->label('Progress')
                                        ->suffix('%'),
                                ]),
                                Group::make([
                                    TextEntry::make('budget')
                                        ->label('Budget')
                                        ->money(),
                                    TextEntry::make('is_featured')
                                        ->label('Featured')
                                        ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No'),
                                    TextEntry::make('completed_at')
                                        ->label('Completed At')
                                        ->date(),
                                ]),
                            ]),
                    ]),

                Section::make('Description')
                    ->schema([
                        TextEntry::make('description')
                            ->label('')
                            ->html()
                            ->columnSpanFull(),
                    ]),

                Section::make('Media')
                    ->schema([
                        ImageEntry::make('feature_project_image')
                            ->label('Featured Image')
                            ->getStateUsing(fn () => $record->getFeatureProjectImageUrl()),
                        ImageEntry::make('second_feature_image')
                            ->label('Secondary Image')
                            ->getStateUsing(fn () => $record->getSecondFeatureImageUrl()),
                    ])
                    ->columns(2),

                Section::make('Tasks')
                    ->schema([
                        RepeatableEntry::make('tasks')
                            ->schema([
                                TextEntry::make('title')
                                    ->label('Task Title'),

                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge(),

                                TextEntry::make('priority')
                                    ->label('Priority')
                                    ->badge(),

                                TextEntry::make('progress')
                                    ->label('Progress')
                                    ->suffix('%'),

                                TextEntry::make('due_at')
                                    ->label('Due Date')
                                    ->date(),

                                // Assigned users with roles and assignment time
                                RepeatableEntry::make('users')
                                    ->label('Assigned Team')
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Name'),

                                        TextEntry::make('pivot.role')
                                            ->label('Role')
                                            ->badge(),

                                        TextEntry::make('pivot.created_at')
                                            ->label('Assigned On')
                                            ->date(),
                                    ])
                                    ->columns(3)
                                    ->columnSpanFull(),
                            ])
                            ->columns(5),
                    ]),

                Section::make('Project Documents')
                    ->collapsible()
                    ->visible(fn () => $record->hasMedia('project_documents'))
                    ->schema([
                        View::make('components.project-documents-preview')
                            ->hiddenLabel()
                            ->viewData([
                                'project' => $record,
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TasksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'view' => Pages\ViewProject::route('/{record}'),
        ];
    }
}
