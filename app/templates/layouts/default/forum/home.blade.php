@extends('layouts.layout')


@section('content')


@foreach($sections as $section)

    <div class="row section-container">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 section-title">
                    {{{ $section->title }}}
                </div>
                <div class="col-md-2 section-info">Topics</div>
                <div class="col-md-2 section-info">Posts</div>
                <div class="col-md-2 section-info">Latest post</div>
            </div>
            <div class="category-container">
                @foreach($section->getCategories()->get() as $category)
                    <div class="row">
                        <div class="col-md-6">
                            <span class="category-title">{{{ $category->title }}}</span>
                            <br/>
                            <span class='category-description'>{{{ $category->description }}}</span>
                        </div>
                        <div class="col-md-2 category-info">
                            {{{ $topicCount = $category->getTopics()->count() }}}
                        </div>
                        <div class="col-md-2 category-info">
                            {{{ $topicCount + $category->getReplies()->count() }}}
                        </div>
                        <div class="col-md-2 category-info">
                            <?php
                                $topic = $category->getReplies()->first()->getTopic()->first();
                            ?>
                            <a href="/topic/{{{$topic->id}}}">{{{ $topic->title }}} </a> by {{{ $topic->getUser()->first()->username }}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endforeach
@endsection



@endsection