@extends('layouts.layout')


@section('script')

<script>
    $(document).ready( function()
    {

        $('.quoteBtn').click( function()
        {
            $.get('{{{ action("TopicController@getReplyText") }}}/'+$(this).data('reply-id'), function(data)
            {
                var reply = JSON.parse(data);
                $('#replyArea').append('[quote='+reply.username+']'+reply.body+'[/quote]');
            });
        })
    });
</script>

@endsection

@section('content')
<?php $bbcode = new Bbcode; ?>

<ul class="breadcrumb">
    <li><a class="forum-link" href="/">{{{ Config::get('settings.appSettings.forumName') }}}</a></li>
    <li><a class="forum-link" href="/#{{{ $topic->category->parent_section }}}">{{{ $topic->category->section->title }}}</a></li>
    <li><a class="forum-link" href="/forum/show/{{{ $topic->category->id }}}">{{{ $topic->category->title }}}</a></li>

    {{-- Show pages breadcrumb if current page is not 1 --}}
    @if($replies->getCurrentPage() == 1)
        <li class="active">{{{ Helper::shorten($topic->title, 60) }}} </li>
    @else
        <li><a class="forum-link" href="/topic/show/{{{ $topic->id }}}">{{{ Helper::shorten($topic->title, 60) }}}</a></li>
        <li class="active">Page {{{ $replies->getCurrentPage() }}} of {{{ $replies->getLastPage() }}}</li>
    @endif

</ul>


{{-- Only show the topic post on page 1 --}}
@if($replies->getCurrentPage() == 1)
    <div class="post-container">
        <div class="row post-head">
            <div class="col-md-12">
                <div class="post-title">
                    {{{ Helper::shorten($topic->title, 70) }}}
                </div>

                <div class="small-text post-date-info">
                    posted {{ Helper::getDateFormatted($topic->created_at) }}

                    @if($topic->created_at != $topic->updated_at)
                        <span class="small-text">
                            modified {{ Helper::getDateFormatted($topic->updated_at) }}
                        </span>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="user-info row">
                <div class="col-md-6">
                    <div class="pull-left">
                    <img src="{{{ $topic->user->avatar}}}" class="avatar" />
                    </div>


                    <h5><a class="forum-link" href="/user/profile/{{{$topic->user->id}}}">{{{ $topic->user->username }}}</a></h5>
                    <span>{{{ $topic->user->user_title }}}</span>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-6">
                    <div class="user-info-posts pull-right">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Posts: </td>
                                    <td>{{{ $topic->user->getPostCount() }}}</td>
                                </tr>
                                <tr>
                                    <td>Joined:</td>
                                    <td>{{{ date('M Y', strtotime($topic->user->created_at)) }}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>

        <div class="post-body">
            {{ $bbcode->toHTML($topic->body) }}
            <br/>
        </div>
        @if(!empty($topic->user->signature))
        <div class="post-signature">
            {{ $bbcode->toHTML($topic->user->signature) }}
        </div>
        @endif

        {{-- Only show actions if user is logged in --}}
        @if(!Auth::guest())
            @if($topic->user_id == Auth::user()->id || Auth::user()->canModifyPost($topic) )
                <div class="row post-actions-container">
                        <div class="col-md-12">
                            <div class="pull-right post-actions">
                                <?php // TODO: Write report buttons + backend ?>



                                    <a class="btn btn-primary btn-sm" href="/topic/edit/{{{$topic->id}}}">edit</a>
                                    <a class="btn btn-primary btn-sm" href="/topic/delete/{{{$topic->id}}}">delete</a>

                            </div>
                        </div>
                </div>
            @endif
        @endif
    </div>

@endif

@foreach($replies as $reply)

<div class="post-container" id="{{{ $reply->id }}}">
    <div class="row post-head">
        <div class="col-md-12">
            <div class="post-title">
                Reply by {{{ $reply->user->username }}}
            </div>

            <div class="small-text post-date-info">
                posted {{ Helper::getDateFormatted($reply->created_at) }}

                @if($reply->created_at != $reply->updated_at)
                        <span class="small-text">
                            modified {{ Helper::getDateFormatted($reply->updated_at) }}
                        </span>
                @endif
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="user-info row">
        <div class="col-md-6">
            <div class="pull-left">
                <img src="{{{ $reply->user->avatar}}}" class="avatar" />
            </div>


            <h5><a class="forum-link" href="/user/profile/{{{$reply->user->id}}}">{{{ $reply->user->username }}}</a></h5>
            <span>{{{ $reply->user->user_title }}}</span>
            <div class="clearfix"></div>
        </div>
        <div class="col-md-6">
            <div class="user-info-posts pull-right">
                <table>
                    <tbody>
                    <tr>
                        <td>Posts: </td>
                        <td>{{{ $reply->user->getPostCount() }}}</td>
                    </tr>
                    <tr>
                        <td>Joined:</td>
                        <td>{{{ date('M Y', strtotime($reply->user->created_at)) }}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="post-body">
        {{ $bbcode->toHTML($reply->body) }}
        <br/>
    </div>
    @if(!empty($reply->user->signature))
    <div class="post-signature">
        {{ $bbcode->toHTML($reply->user->signature) }}
    </div>
    @endif

    {{-- Only show actions if user is logged in --}}
    @if(!Auth::guest())
        <div class="row post-actions-container">

            <div class="col-md-12">
                <div class="pull-right post-actions">
                    <?php // TODO: Write report buttons + backend ?>

                    @if(!Auth::guest())
                    @if($reply->user_id == Auth::user()->id || Auth::user()->canModifyPost($reply) )
                    <a class="btn btn-primary btn-sm" href="/reply/edit/{{{$reply->id}}}">edit</a>
                    <a class="btn btn-primary btn-sm" href="/reply/delete/{{{$reply->id}}}">delete</a>
                    @endif
                    @endif
                    <a class="btn btn-primary btn-sm quoteBtn" data-reply-id="{{{$reply->id}}}">quote</a>
                </div>
            </div>
        </div>
    @endif
</div>

@endforeach

{{ $replies->links() }}


<div>

    {{ Form::open(array('action' => array('TopicController@postReply', $topic->id))) }}



    {{-- The textarea gets its markup and actions from jquery.bbcode.js, located in the assets/js/ folder --}}

    {{ Form::textarea('replyBody', (Session::has('postReplyBody')) ? Session::get('postReplyBody') : '', array('class' => 'form-control', 'id' => 'replyArea')) }}
    <script>$('#replyArea').bbcode();</script>
    <br/>
    @if(Session::has('postReplyError'))

    <p class="alert alert-danger">
        {{{ Session::get('postReplyError') }}}
    </p>
    @endif

    {{ Form::submit('Post reply', array('id' => 'postBtn', 'class' => 'btn btn-primary')) }}
    {{ Form::close() }}
</div>




@endsection