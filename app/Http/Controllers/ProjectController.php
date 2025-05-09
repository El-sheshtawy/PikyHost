<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectController extends Controller
{
    public function showDocument($id, $documentIndex)
    {
        $project = Project::findOrFail($id);
        $mediaItems = $project->getMedia('project_documents');

        if (!isset($mediaItems[$documentIndex])) {
            abort(404, 'Document not found.');
        }

        $media = $mediaItems[$documentIndex];
        $path = $media->getPath();

        if (!file_exists($path)) {
            abort(404, 'Document file not found.');
        }

        $mimeType = $media->mime_type;

        // For viewable types (PDF, images)
        if (in_array($mimeType, [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
        ])) {
            return response()->file($path);
        }

        // For Excel files - offer download but could implement preview later
        if (in_array($mimeType, [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])) {
            return response()->download($path, $media->file_name);
        }

        // For Word documents
        if (in_array($mimeType, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])) {
            return response()->download($path, $media->file_name);
        }

        // Default download for other types
        return response()->download($path, $media->file_name);
    }
}
