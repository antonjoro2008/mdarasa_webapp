<div class="mt-12 card">
    <div class="card-header">Notes</div>
    <div class="card-body">
        @if(!is_null($notes))
        @foreach($notes as $notesRecord)
        <div class="row">
            <div class="col-md-12"><span class="title-color">#</span> {{ $notesRecord->notesNumber }}.
                {{ $notesRecord->notesTitle }}</div>
        </div>
        <div class="separator"></div>
        <div class="row pt-8 pb-8">
            <div class="col-md-10">
                @if($notesRecord->fileDomiciledAt == "Local")
                <a download href="{{url('/topics/')}}/{{$notesRecord->subtopicNotes}}" class="download-link"
                    title="{{ $notesRecord->notesTitle }}">
                    <img src="{{ url('/images/pdf.png') }}" width="100" alt="" />
                </a>
                @else
                <a download href="{{url('https://')}}/{{$notesRecord->subtopicNotes}}" class="download-link"
                    title="{{ $notesRecord->notesTitle }}">
                    <img src="{{ url('/images/pdf.png') }}" width="100" alt="" />
                </a>
                @endif
            </div>
            <div class="col-md-2">
                <form method="post" action="{{ url('/subtopic/notes/remove') }}" class="inline-view">
                    @csrf
                    <input type="hidden" name="subtopicNotesId" id="subtopicNotesId"
                        value="{{ $notesRecord->subtopicNotesId }}">
                    <button type="submit" class="unpublish-btn">Remove</button>
                </form>
            </div>
        </div>
        <div class="separator"></div>
        @endforeach
        @else
        You have not uploaded any subtopic notes yet
        @endif
    </div>
</div>
