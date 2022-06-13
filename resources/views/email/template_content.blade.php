<div style="display: flex; align-content: center; justify-content: center">
    <div>
        <div class="header">
            <img class="logo" src="{{asset('images/paidicon.png')}}">
        </div>
        <header>
            {!! $header!!}
        </header>
        <div class="email-body">
            {!! $body!!}
        </div>
        <footer class="footer">
            <div class="button"><a href="{{env('FRONTEND_URL')}}" style="text-decoration: none"><span class="button-text">Visit Now</span></a></div>
            {!!$footer!!}
        </footer>
    </div>
</div>
<style>
    .email-body {
        width: 536px;
        /* UI Properties */

        background: #FFFFFF 0% 0% no-repeat padding-box;
        opacity: 1;
    }

    .logo {
        width: 200px;
        height: 40px;
        padding-top: 40px;

        /* UI Properties */

        background: transparent url({{asset('images/paidicon.png')}}) 0% 0% no-repeat padding-box;
        opacity: 1;
    }

    .header {
        text-align: center;
        border-radius: 20px 20px 0px 0px;
        width: 536px;
        min-height: 120px;

        /* UI Properties */

        background: transparent url({{asset('images/email-header-background.png')}}) 0% 0% no-repeat padding-box;
        opacity: 1;
    }

    .footer {
        width: 536px;
        min-height: 94px;

        padding-top: 30px;

        /* UI Properties */

        background: transparent linear-gradient(90deg, #008BD0 0%, #00C4F0 100%) 0% 0% no-repeat padding-box;
        border-radius: 0px 0px 20px 20px;
        opacity: 1;
    }

    .button {
        text-align: center;
        width: 110px;
        height: 34px;
        padding-top: 10px;

        /* UI Properties */
        margin: auto;
        background: #FFFFFF 0% 0% no-repeat padding-box;
        border-radius: 40px;
        opacity: 1;
    }
    .button-text {
        width: 62px;
        height: 11px;

        /* UI Properties */

        background: transparent linear-gradient(90deg, #008BD0 0%, #00C4F0 100%) 0% 0% no-repeat padding-box;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        opacity: 1;
    }

</style>
