<div class="mt-12 card">
    <div class="card-header">Videos</div>
    <div class="card-body">
        @if(!is_null($videos))
        @foreach($videos as $video)
        <div class="row">
            <div class="col-md-12"><span class="title-color">#</span> {{ $video->videoNumber }}.
                {{ $video->videoTitle }}</div>
        </div>
        <div class="separator"></div>
        <div class="row pt-8 pb-8">
            <div class="col-md-10">
                <div id="container">
                    @if(isset($video->thirdPartyUrl) && !is_null($video->thirdPartyUrl) && strpos($video->thirdPartyUrl,
                    'youtube') !== false)
                    <iframe title="" width="100%" height="315" src="{{ $video->thirdPartyUrl }}"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                    @else
                    <video class='plyr-video' controls crossorigin playsinline @if($video->videoThumbnail)
                        data-poster="{{ $video->videoThumbnail }}"
                        @else data-poster="{{ url('/images/videos-placeholder-lecturer.jpg') }}"
                        @endif id="player">
                        <!-- Video files -->

                        @if(isset($video->thirdPartyUrl) && !is_null($video->thirdPartyUrl))
                        <source src="{{ $video->thirdPartyUrl }}">
                        @else
                        @if($video->fileDomiciledAt == "Local")
                        <source src="/topic/{{ $video->subtopicVideo }}">
                        @else
                        <source
                            src="{{ strpos($video->subtopicVideo, 'https://') !== false?$video->subtopicVideo:'https://'.$video->subtopicVideo }}">
                        @endif
                        @endif

                        <track kind="captions" label="English" srclang="en"
                            src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.en.vtt" default />
                        <track kind="captions" label="Français" srclang="fr"
                            src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.fr.vtt" />
                    </video>
                    @endif
                </div>
            </div>
            <div class="col-md-2">
                <form method="post" action="{{ url('/subtopic/video/remove') }}" class="inline-view">
                    @csrf
                    <input type="hidden" name="subtopicVideoId" id="subtopicVideoId"
                        value="{{ $video->subtopicVideoId }}">
                    <button type="submit" class="unpublish-btn">Remove</button>
                </form>
            </div>
        </div>
        <div class="separator"></div>
        @endforeach
        @else
        You have not uploaded any subtopic videos yet
        @endif
    </div>
</div>