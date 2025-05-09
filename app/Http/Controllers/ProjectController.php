<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProjectController extends Controller
{
    public function showMedia(Project $project, Media $media)
    {
        abort_unless($project->id === $media->model_id, 403);
        return response()->file($media->getPath());
    }

}
