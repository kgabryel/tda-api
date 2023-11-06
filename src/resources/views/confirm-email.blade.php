@extends('email')
@section('content')
    <p>
        {{__('translations.confirmEmailHeader', [], $lang)}}
    </p>
    <a href="{{env('FRONT_URL')}}/settings/confirm-email?code={{$code}}" class='action-button'>
        {{__('translations.confirmEmail', [], $lang)}}
    </a>
    <p>
        {{__('translations.confirmEmailInformation', [], $lang)}}
    </p>
@endsection
