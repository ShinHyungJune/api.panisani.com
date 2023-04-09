@component('mail::message')
    <p style="font-size:24px; font-weight:500; line-height:1.3; color:#202020; margin:0 0 52px;">
        {{config("app.name")}}에서 발송된<br>
        <b style="font-size:24px; font-weight:bold; line-height:1.3; color:#202020;">인증번호 안내 메일</b>입니다.
    </p>
    <strong style="display:block; font-size:120px; font-weight:bold; line-height:1.3; color:#202020;">{{$item->number}}</strong>
    <span style="display:block; font-size:18px; line-height:1.3; font-weight:bold; color:#202020; margin:0 0 6px;">발급시간 : {{$item->created_at}}</span>
    <!--            <time style="display:block; font-size:14px; line-height:1.3; color:#202020;">인증시간은 발송 후 5분 내에만 유효합니다.</time>-->

@endcomponent
