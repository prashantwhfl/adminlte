@extends('admin.layouts.login_layout')
@section('content')
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Login-->
    <div class="login login-4 wizard d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Content-->
        <div class="login-container order-2 order-lg-1 d-flex flex-center flex-row-fluid px-7 pt-lg-0 pb-lg-0 pt-4 pb-6 bg-white">
            <!--begin::Wrapper-->
            <div class="login-content d-flex flex-column pt-lg-0 pt-12">
                <!--begin::Logo-->
                <a href="{{WEBSITE_URL}}" class="login-logo pb-xl-20 pb-15">
                    <img src="{{WEBSITE_IMG_URL}}logo.png" class="max-h-70px" alt="" />
                </a>
                <!--end::Logo-->

                <!--begin::Signin-->
                <div class="login-form">
                    <!--begin::Form-->
                    {{ Form::open(['role' => 'form','url' => 'adminpnlx/send_password',"class"=>"form","id"=>"kt_login_singin_form"]) }}   
                        <!--begin::Title-->
                        <div class="pb-5 pb-lg-15">
                            <h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Forgot Password</h3>
                            <!-- <div class="text-muted font-weight-bold font-size-h4">
                                New Here?
                                    <a href="custom/pages/login/login-4/signup.html"
                                    class="text-primary font-weight-bolder">Create Account</a>
                            </div> -->
                        </div>
                        <!--begin::Title-->
                        <!--begin::Form group-->
                        <div class="form-group">
                            <label class="font-size-h6 font-weight-bolder text-dark">Your Email</label>
                            <!-- toggle class "is-invalid" on input for error -->
                            {{ Form::text('email', null, ['placeholder' => 'Email', 'class' => "form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0 ".($errors->has('email') ? 'is-invalid':'')]) }}	
                            <div class="invalid-feedback"><?php echo $errors->first('email'); ?></div>
                        </div>
                        <!--end::Form group-->
                        
                        <!--begin::Action-->
                        <div class="pb-lg-0 pb-5">
                            <a href="javascript:void(0);" id="kt_login_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Submit</a>
                            <a href="{{URL('/adminpnlx/login')}}" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Back To Login</a>
                        </div>
                        <!--end::Action-->
                    {{ Form::close() }}
                    <!--end::Form-->
                </div>
                <!--end::Signin-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--begin::Content-->

        <!--begin::Aside-->
        <div class="login-aside order-1 order-lg-2 bgi-no-repeat bgi-position-x-right">
            <div class="login-conteiner bgi-no-repeat bgi-position-x-right bgi-position-y-bottom" style="background-image: url({{WEBSITE_IMG_URL}}login-visual-4.svg);">
                <!--begin::Aside title-->
                <h3 class="pt-lg-40 pl-lg-20 pb-lg-0 pl-10 py-20 m-0 d-flex justify-content-lg-start font-weight-boldest display5 display1-lg text-white">
                    We Got<br />
                    A Surprise<br />
                    For You
                </h3>
                <!--end::Aside title-->
            </div>
        </div>
        <!--end::Aside-->
    </div>
    <!--end::Login-->
</div>
<!--end::Main-->
<script>
    jQuery(document).ready(function () {
        $('input').keypress(function (e) {
            if (e.which == 13) {
                $("#kt_login_singin_form").submit();
            }
        });
        
        $("#kt_login_submit").click(function(e) {
            $("#kt_login_singin_form").submit();
        });
    });
</script>
@stop
