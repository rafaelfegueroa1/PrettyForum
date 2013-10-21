@extends('layouts.layout')


@section('title')
{{{ $category->title }}} - {{{ Config::get('settings.appSettings.defaultTitle') }}}
@endsection


@section('content')


    <ul class="breadcrumb">
        <li><a class="forum-link" href="/">{{{ Config::get('settings.appSettings.forumName') }}}</a></li>
        <li><a class="forum-link" href="/#{{{ $category->parent_section }}}">{{{ $category->section->title }}}</a></li>
        <li class="active">{{{ $category->title }}}</li>
    </ul>


<table class="table-container">

    <thead class="table-head">
    <tr>
        <th class="table-head-title col-md-6">
            {{{ $category->title }}}
        </th>
        <th class="table-head-info col-md-1">Replies</th>
        <th class="table-head-info col-md-4">Latest post</th>
    </tr>


    </thead>

    <tbody>
    @foreach($topics as $topic)
    <tr class="table-body-container">


        <td class="col-md-6">
            <p class="category-title"><a class="forum-link pull-left" href="/topic/show/{{{ $topic->id }}}"> {{{ $topic->title }}} </a>
            <div class="extra-small-text">
                <?php
                $total = count($topic->replies);
                $per_page = 20;
                $pager = new Pager($total, $per_page, '/topic/show/'.$topic->id);
                echo $pager->links();
                ?>
            </div>
            </p>
            <span class="clearfix"></span>
            <p class='category-description'><a class="forum-link" href="/user/show/{{{ $topic->user_id }}}">{{{ $topic->user->username }}}</a></p>
        </td>

        <td class="category-info col-md-2">
            {{{ $topic->replies()->where('deleted', '=', '0')->count() }}}
        </td>
        <td class="category-info col-md-4">

            {{-- Check if topic has replies --}}
            @if( count($topic->replies) == 0)

            None

            @else
            {{-- Replies found, echo it's info --}}
            <?php   $reply = $topic->replies()->where('deleted', '=', '0')->orderBy('id', 'DESC')->first();
            $user = $reply->user;
            ?>

            <span class="small-text">{{ Helper::getDateFormatted($reply->created_at) }}</span>
            by <a class="forum-link" href="/user/profile/{{{ $user->id }}}">{{{ Helper::shorten($user->username, 25) }}}</a>

            @endif


            <div>

            </div>
        </td>

    </tr>

    @endforeach
    </tbody>

</table>
{{ $topics->links() }}


@endsection