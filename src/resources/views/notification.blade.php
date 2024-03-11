@extends('email')
@section('content')
    <h1>
        {{$name}} ({{$date}})
    </h1>
    @if($content !== null)
        <div class='text-left'>
            {!! $content !!}
        </div>
    @endif
    @if(!empty($notifications))
        <p class='text-center'>
            {{__('translations.nextNotifications', [], $lang)}}
        </p>
        @foreach ($notifications as $notification)
            <p class='text-left'>
                {{$notification['time']}} ({{$notification['types']}})
            </p>
        @endforeach
    @endif
    @if($alarmDeactivationCode === null)
        <a href="{{env('FRONT_URL')}}/alarms/{{$alarmId}}" class='action-button'>
            {{__('translations.goToAlarm', [], $lang)}}
        </a>
    @else
        <div>
            <a href="{{env('FRONT_URL')}}/alarms/{{$alarmId}}" class='action-button left'>
                {{__('translations.goToAlarm', [], $lang)}}
            </a>
            <a
                href="{{env('FRONT_URL')}}/alarms/deactivate-single?code={{$alarmDeactivationCode}}"
                class='action-button right'>
                {{__('translations.deactivateAlarm', [], $lang)}}
            </a>
        </div>
    @endif
    @if($taskId !== null || $groupId !== null)
        <p class='text-center'>
            {{__('translations.connected', [], $lang)}}
        </p>
    @endif
    @if($taskId !== null && $groupId !== null)
        <div>
            <a href="{{env('FRONT_URL')}}/tasks/{{$taskId}}" class='action-button left'>
                {{__('translations.goToTask', [], $lang)}}
            </a>
            <a href="{{env('FRONT_URL')}}/alarms/{{$groupId}}" class='action-button right'>
                {{__('translations.goToAlarm', [], $lang)}}
            </a>
        </div>
    @else
        @if($taskId !== null && $groupId === null)
            <a href="{{env('FRONT_URL')}}/tasks/{{$taskId}}" class='action-button'>
                {{__('translations.goToTask', [], $lang)}}
            </a>
        @endif
        @if($taskId === null && $groupId !== null)
            <a href="{{env('FRONT_URL')}}/alarms/{{$groupId}}" class='action-button'>
                {{__('translations.goToAlarm', [], $lang)}}
            </a>
        @endif
    @endif
@endsection
