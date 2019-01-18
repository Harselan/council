@component('mail::message')
# One last step

We just need you to confirm your email to prove that you're a human. You get it, right? cool.

@component('mail::button', ['url' => route( 'register.confirm', [ 'token' => $user->confirmation_token ] )])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
