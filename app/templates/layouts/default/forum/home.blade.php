@extends('layouts.layout')


@section('content')

<ul class="breadcrumb">
    <li><a class="forum-link" href="/">{{{ Config::get('settings.appSettings.forumName') }}}</a></li>
</ul>

@foreach(Section::with('categories.topics', 'categories.replies')->get() as $section)

<table class="table-container" id="{{{ $section->id }}}">

    <thead class="table-head">
        <tr>
            <th class="table-head-title col-md-6">
                {{{ $section->title }}}
            </th>
            <th class="table-head-info col-md-1">Topics</th>
            <th class="table-head-info col-md-1">Posts</th>
            <th class="table-head-info col-md-4">Latest post</th>
        </tr>


    </thead>

    <tbody>
    @foreach($section->categories as $category)
    <tr class="table-body-container">


        <td class="col-md-6">
            <p class="category-title"><a class="forum-link" href="/forum/show/{{{ $category->id }}}"> {{{ $category->title }}} </a></p>
            <p class='category-description'>{{{ $category->description }}}</p>
        </td>
        <td class="category-info col-md-1">
            {{{ $topicCount = $category->topics()->where('deleted', '=', '0')->count() }}}
        </td>
        <td class="category-info col-md-1">
            {{{ $topicCount + $category->replies()->where('deleted', '=', '0')->count() }}}
        </td>
        <td class="category-info col-md-4">
            {{-- Check if category has topics/replies --}}
            @if( $category->replies()->where('deleted', '=', '0')->count() == 0)

                {{-- If category has no active topics with replies check for topics --}}
                @if( count($category->topics) == 0)
                    {{-- No topics found, show "none" --}}
                    None
                @else
                    {{-- Topic found, echo it's info --}}
                    <?php
                    $topic = $category->topics()->where('deleted', '=', '0')->orderBy('id', 'DESC')->first();
                    $user = $topic->user;
                    ?>

                    <a class="forum-link" href="/topic/show/{{{ $topic->id }}}"> {{{ Helper::shorten($topic->title, 25) }}} </a>
                    by <a class="forum-link" href="/user/profile/{{{ $user->id }}}">{{{ Helper::shorten($user->username, 25) }}}</a>
                @endif


            @else
            {{-- Active topic found, echo it's info --}}
            <?php   $reply = $category->replies()->where('deleted', '=', '0')->orderBy('id', 'DESC')->first();
            $topic = $reply->topic()->first();
            $user = $reply->user;
            ?>

               <a class="forum-link" href="/topic/show/{{{ $topic->id }}}"> {{{ Helper::shorten($topic->title, 25) }}} </a>
               by <a class="forum-link" href="/user/profile/{{{ $user->id }}}">{{{ Helper::shorten($user->username, 25) }}}</a>

            @endif


            <div>

            </div>
        </td>

    </tr>

    @endforeach
    </tbody>
</table>

@endforeach

@endsection

