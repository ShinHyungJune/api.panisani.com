@component('mail::message')
    <div id="wrap">
        <!-- //main -->

        <main id="main" class="E-mail">
            <div class="E-mail-box" style="padding: 32px 20px;background-color: #e7edf5;width: 100%;max-width: 678px;">
                <div class="content">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>문의자 아이디</th>
                            <td>{{$item->user ? $item->user->ids : ""}}</td>
                        </tr>
                        <tr>
                            <th>문의자 성함</th>
                            <td>{{$item->user ? $item->user->name : ""}}</td>
                        </tr>
                        <tr>
                            <th>문의자 연락처</th>
                            <td>{{$item->user ? $item->user->contact : ""}}</td>
                        </tr>

                        <tr>
                            <th>카테고리</th>
                            <td>{{$item->category}}</td>
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
