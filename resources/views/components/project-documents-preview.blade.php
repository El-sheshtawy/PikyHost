<div class="project-documents">
    @foreach ($project->getProjectDocuments() as $media)
        <div class="mb-4">
            <h6>{{ $media->name }}</h6>

            @if(Str::endsWith($media->file_name, ['.pdf']))
                <iframe src="{{ $media->getUrl() }}" width="100%" height="500px" style="border: none;">
                    This browser does not support inline PDFs. <a href="{{ $media->getUrl() }}">Download</a>
                </iframe>
            @elseif(Str::startsWith($media->mime_type, 'image/'))
                <img src="{{ $media->getUrl() }}" alt="{{ $media->name }}" style="max-width: 100%; height: auto;" />
            @else
                <p>
                    <a href="{{ $media->getUrl() }}" target="_blank" class="underline text-blue-600">
                        Download {{ strtoupper($media->mime_type) }} File
                    </a>
                </p>
            @endif
        </div>
    @endforeach
</div>
