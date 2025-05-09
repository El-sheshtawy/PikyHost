<?php

namespace App\Filament\Resources;

use App\Enums\ProjectStatus;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers\TasksRelationManager;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                            ->collection('feature_project_image') // If you want a second image in same collection
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
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('feature_project_image')
                    ->circular()
                    ->simpleLightbox()
                    ->placeholder('-')
                    ->collection('feature_project_image')
                    ->label(__('Image')),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Owner')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ProjectStatus $state): string => $state->label())
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),

                Tables\Columns\TextColumn::make('progress')
                    ->numeric()
                    ->suffix('%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(ProjectStatus::options()),

                Tables\Filters\Filter::make('is_featured')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true))
                    ->label('Featured Only'),

                Tables\Filters\Filter::make('active_projects')
                    ->query(fn (Builder $query): Builder => $query->where('status', ProjectStatus::ACTIVE))
                    ->label('Active Projects Only'),
            ],Tables\Enums\FiltersLayout::Modal)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(function ($record) {
               return route('filament.admin.pages.tasks-board-board-page', [
                   'project_id' => $record->id
               ]);
            })
            ->defaultSort('created_at', 'desc');
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
        ];
    }
}
