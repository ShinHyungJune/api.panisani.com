@component('mail::message')

    <div id="wrap">
        <!-- //main -->

        <main id="main" class="E-mail">
            <div class="E-mail-box" style="padding: 32px 20px;background-color: #e7edf5;width: 100%;max-width: 678px;">
                <div class="content">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>이메일</th>
                            <td>{{$item->email}}</td>
                        </tr>
                        <tr>
                            <th>연락처</th>
                            <td>{{$item->contact}}</td>
                        </tr>
                        <tr>
                            <th>성함</th>
                            <td>{{$item->name}}</td>
                        </tr>
                        <tr>
                            <th>빌라</th>
                            <td>{{$item->villa}}</td>
                        </tr>
                        <tr>
                            <th>층호수</th>
                            <td>{{$item->floor}}</td>
                        </tr>
                        <tr>
                            <th>엘리베이터</th>
                            <td>{{$item->elevator}}</td>
                        </tr>
                        <tr>
                            <th>주소</th>
                            <td>{{$item->address}} {{$item->address_detail}}</td>
                        </tr>
                        <tr>
                            <th>제목</th>
                            <td>{{$item->title}}</td>
                        </tr>
                        <tr>
                            <th>내용</th>
                            <td>{!! $item->description !!}</td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="btns">
                        <a href="{{$item->getAdminUrl()}}" class="btn">확인하러 가기</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endcomponent
