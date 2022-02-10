@extends('template.master')
@section('content')
    <div class="container">
        <div id="content" class="space-top-none">
            
            <div class="space50">&nbsp;</div>
            <div class="row">
                @if(Session::has('signup'))
                <div class="alert alert-success">{{Session::get('message')}}</div>
                @endif
                <div class="col-sm-8">
                    <h2>Form liên hệ</h2>
                    <div class="space20">&nbsp;</div>
                    <p>Nếu có bất kì thắc mắc hay phản hồi gì, hãy để lại lời nhắn cho chúng tôi biết.</p>
                    <div class="space20">&nbsp;</div>
                    <form action="{{route('postMail.postMail')}}" method="post" class="contact-form">	
                        <!-- <div class="form-block">
                            <input name="your-name" type="text" placeholder="Họ và tên của bạn (bắt buộc)" style ="color:black"> 
                        </div>
                        <div class="form-block">
                            <input name="your-email" type="email" placeholder="Địa chỉ email (bắt buộc)" style ="color:black">
                        </div> -->
                        <div class="form-block">
                            <input name="subject" type="text" placeholder="Tiêu đề" style ="color:black">
                        </div>
                        <div class="form-block">
                            <textarea name="content" placeholder="Nhập nội dung" style ="color:black"></textarea>
                        </div>
                        <div class="form-block">
                            <button onclick="return confirm('Gửi phản hồi tới quản trị viên ?')" type="submit" class="beta-btn primary" style ="color: black">Gửi tin nhắn <i class="fa fa-chevron-right"></i></button>
                        </div>
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                    </form>
                </div>
                <div class="col-sm-4">
                    <h2>Thông tin liên hệ</h2>
                    <div class="space20">&nbsp;</div>

                    <h6 class="contact-title">Địa chỉ</h6>
                    <p>
                        48 Cao Thắng, Hải Châu, Đà Nẵng.<br>
                        Email: docuute@gmail.com. <br>
                        Số điện thoại: (0236) 3822571
                    </p>
                    <div class="space20">&nbsp;</div>
                    <h6 class="contact-title">Liên lạc</h6>
                    <p>
                        Liên lạc với chúng tôi qua địa chỉ email dưới đây<br>
        
                        <a href="mailto:biz@betadesign.com">docuute.com</a>
                    </p>
                    <div class="space20">&nbsp;</div>
                    <h6 class="contact-title">Tuyển dụng</h6>
                    <p>
                        Chúng tôi đang tìm kiếm các nhân sự tài tăng <br>
                        Nộp CV tại đây. <br>
                        <a href="tuyendung@gmail.com.com">tuyendung@gmail.com</a>
                    </p>
                </div>
            </div>
        </div> <!-- #content -->
    </div> <!-- .container -->
@endsection