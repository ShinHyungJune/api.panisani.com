@component('mail::message')
    <p style="font-size:24px; font-weight:500; line-height:1.3; color:#202020; margin:0 0 52px;">
        {{config("app.name")}}에서 발송된<br>
        <b style="font-size:24px; font-family: bold; line-height:1.3; color:#202020;">비밀번호 초기화 메일</b>입니다.
    </p>

    <div style="text-align: center;">
        <a href="{{$passwordReset->resetUrl()}}" class="btn type01" style="display:inline-block; padding:16px 40px; margin:0 auto; border-radius:32px; color:#fff; background-color:#9747FF; text-align: center;">비밀번호 초기화</a>
    </div>

@endcomponent
