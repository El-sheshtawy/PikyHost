<div class="document-viewer">
    @if ($project->getMedia('project_documents')->count())
        <h6>Project Documents</h6>
        <br>

        @foreach ($project->getMedia('project_documents') as $index => $document)
            <div class="document-container mb-4">
                <div class="document-header">
                    <h5>{{ $document->name }}</h5>
                    <small>
                        {{ $this->getFileTypeName($document->mime_type) }} •
                        {{ number_format($document->size / 1024, 2) }} KB •
                        Uploaded {{ $document->created_at->diffForHumans() }}
                    </small>
                </div>

                <div class="document-preview mt-3">
                    @if (Str::startsWith($document->mime_type, 'image/'))
                        <img
                            src="{{ $document->getUrl() }}"
                            alt="{{ $document->name }}"
                            class="img-fluid rounded"
                            style="max-height: 500px;">

                    @elseif ($document->mime_type === 'application/pdf')
                        <iframe
                            src="{{ route('projects.show.document', ['id' => $project->id, 'documentIndex' => $index]) }}"
                            width="100%"
                            height="500px"
                            style="border: 1px solid #eee; background: #f5f5f5;">
                            Your browser doesn't support PDF preview.
                            <a href="{{ route('projects.show.document', ['id' => $project->id, 'documentIndex' => $index]) }}">
                                Download PDF
                            </a>
                        </iframe>

                    @elseif (in_array($document->mime_type, [
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    ]))
                        <div class="excel-preview-placeholder bg-light p-4 text-center rounded">
                            <i class="fas fa-file-excel fa-4x text-success mb-3"></i>
                            <p class="mb-2">Excel document preview not available</p>
                            <p class="text-muted small">Download to view the spreadsheet</p>
                        </div>

                    @elseif (in_array($document->mime_type, [
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    ]))
                        <div class="word-preview-placeholder bg-light p-4 text-center rounded">
                            <i class="fas fa-file-word fa-4x text-primary mb-3"></i>
                            <p class="mb-2">Word document preview not available</p>
                            <p class="text-muted small">Download to view the document</p>
                        </div>

                    @else
                        <div class="generic-preview-placeholder bg-light p-4 text-center rounded">
                            <i class="fas fa-file fa-4x text-secondary mb-3"></i>
                            <p class="mb-2">Preview not available for this file type</p>
                            <p class="text-muted small">Download to view the file</p>
                        </div>
                    @endif
                </div>

                <div class="document-actions mt-3 d-flex justify-content-between align-items-center">
                    <div class="file-info">
                        <span class="badge bg-secondary">
                            {{ strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION)) }}
                        </span>
                    </div>
                    <div>
                        <a
                            href="{{ route('projects.show.document', ['id' => $project->id, 'documentIndex' => $index]) }}"
                            class="btn btn-sm btn-primary"
                            download="{{ $document->file_name }}">
                            <i class="fas fa-download me-1"></i> Download
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info">
            No documents uploaded for this project.
        </div>
    @endif
</div>
