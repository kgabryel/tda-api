@extends('email')
@section('content')
    <h1>
        {{__('translations.resetPasswordHeader', [], $lang)}}
    </h1>
    <p>
        {{__('translations.resetPasswordInformation', [], $lang)}}
    </p>
    <a href="{{env('FRONT_URL')}}/change-password?token={{$token}}" class='action-button'>
        {{__('translations.resetPassword', [], $lang)}}
    </a>
@endsection
