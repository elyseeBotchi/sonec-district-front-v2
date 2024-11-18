@extends('layout.ControlApp')

@section('content')

<style>
    body {
        padding: 0px;
        margin: 0px;
        background: white;
        font-family: roboto;
        overflow-x: hidden;
    }

    .empty {
        display: block;
        width: 100%;
        height: 20px;
    }

    #new-scanned-result {
        width: 80%;
        background: white;
        text-align: center;
        overflow-x: hidden;
        overflow-y: scroll;
        padding: 0px;
    }


    #logo {
        text-align: center;
        padding: 10px;
    }

    #logo img {
        height: 40px;
        background: #ffffff66;
        border-radius: 5px;
        padding: 0px 5px 0px 5px;
    }

    #scanapp-top-container {
       /*max-height:90vh;*/
        display:flex;
        flex-direction:column;
        justify-content:space-between;
    }

    @media(max-width: 500px) {
        /* Mobile phone display. */
        #scanapp-container {
            display: block;
        }

        #scanner, #workspace {
            display: 100px;
        }

        #reader {
            width: 300px;
            margin: 0px;
            padding: 0px;
        }

        #new-scanned-result {
            display: none;
            position: fixed;
            top: calc(25%);
            border-radius: 10px 10px 0px 0px;
            height: calc(75%);
            /* border: 1px solid black; */
        }

        div.workspace-header {
            display: none;
        }

        div#no-result-container {
            display: none;
        }
    }

    @media(min-width: 600px) {
        #scanapp-container {
            display: flex;
            align-items: flex-start;
        }

        #scanner {
            flex: 2
        }
        #workspace {
            flex: 1;
            /* border: 1px solid silver; */
            /* border-bottom: 1px solid silver; */
            margin: 20px;
            margin-top: 60px;
        }

        #workspace #result {
            border: 1px solid silver;
        }

        #reader {
            width: 300px;
            margin: auto;
            padding: 0px;
        }

        #new-scanned-result {
            display: none;
            /* height: 100%; */
        }

        div.workspace-header {
            text-align: center;
            font-size: 20pt;
            font-weight: bold;
            background: #dadada;
        }

        div#no-result-container {
            text-align: center;
            font-size: 14pt;
            padding: 50px 0px 50px 0px;
        }
    }

    #history {
        display: none;
        border: 1px solid silver;
        margin-top: 10px;
    }


    div#no-result-container.hidden {
        display: none;
    }

    #new-scanned-result {
        width: 50%;
        background: white;
        text-align: center;
        overflow-x: hidden;
        overflow-y: scroll;
    }

    div#new-scanned-result .header {
        margin: 10px;
        font-size: 18pt;
        font-weight: bold;
    }

    div#new-scanned-result .image {
        display: block;
        width: 200px;
        height: 100px;
        border: 1px solid #00000040;
        margin: auto;
        background: silver;
        margin-bottom: 15px;
    }

    #footer {
        margin-top: 100px;
        border-top: 1px solid silver;
        font-size: 9pt;
        font-weight: 400;
        background: #8080804a;
        padding: 50px;
        text-align: center;
    }

    div#scan-result-parsed {
        background: #dadada54;
        color: black;
        border: 1px solid #b3b3b333;
        padding: 5px;
        font-family: consolas;
        word-break: break-word;
    }

    table#result_table {
        /* border: 1px solid #c0c0c04a; */
        width: 80%;
        margin: auto;
        text-align: left;
        padding: 5px;
    }

    .action_image {
        width: 20px;
        padding: 10px;
        border: 1px solid #c0c0c066;
        border-radius: 10px;
    }

    .action_image:hover {
        background: #c0c0c06e;
    }

    #body-footer {
        margin-top: 20px;
        border-top: silver solid 1px;
        padding: 10px;
    }

    /** Badge **/
    :root{
        --color-primary: #214fe0;
        --color-dark: #1d1f20;
        --color-light: #f4f4f4;
        --color-shade: #bbb;

        --badge-size: 150px;

        --lock-color: #fff;
        --lock-width: 20px;
        --lock-stroke: 2.5px;

    }

    .badge-icon,
    .badge-text{
        padding: 5px 5px;
        float:left;
        -webkit-box-flex:1;
        flex:1;
        text-align: center;
        font: normal small-caps normal 10px/1.5 Arial, Helvetica,   sans-serif;
        text-transform: uppercase;
        text-align: left;
    }

    .badge{
        display: inline-block;
        color: var(--color-light);
        /* min-width: var(--badge-size); */
        border-radius: 5px;
        overflow: hidden;
        border: 1px solid silver;
    }

    .badge-icon{
        background: silver;
        max-width: calc( var(--badge-size) / 4);
        color: black;
    }

    .badge-text{
        color: var(--color-dark);
        background-color: var(--color-light);
    }

    /** Banner */
    .banners-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 5;
    }

    .banner {
        color: white;
        font-weight: 700;
        padding: 2rem;
        display: flex;
        flex-direction: row;
        align-items: center;
    }
    .banner .banner-message {
        flex: 1;
        padding: 0 2rem;
    }
    .banner .banner-close {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .banner .banner-close:hover {
        background: rgba(0, 0, 0, 0.12);
    }

    .banner.success {
        background: #10c15c;
    }
    .banner.success::after {
        background: #10c15c;
    }
    .banner.error {
        background: #ed1c24;
    }
    .banner.error::after {
        background: #ed1c24;
    }
    .banner.info {
        background: #0b22e2;
    }
    .banner.info::after {
        background: #0b22e2;
    }
    .banner::after {
        content: "";
        position: absolute;
        height: 10%;
        width: 100%;
        bottom: 100%;
        left: 0;
    }

    .banner:not(.visible) {
        display: none;
        transform: translateY(-100%);
    }

    .banner.visible {
        box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.12);
        animation-name: banner-in;
        animation-direction: forwards;
        animation-duration: 0.6s;
        animation-timing-function: ease-in-out;
        animation-fill-mode: forwards;
        animation-iteration-count: 1;
    }

    @keyframes banner-in {
        0% {
            transform: translateY(-100%);
        }
        50% {
            transform: translateY(10%);
        }
        100% {
            transform: translateY(0);
        }
    }
    .show-banner {
        appearance: none;
        background: #ededed;
        border: 0;
        padding: 1rem 2rem;
        border-radius: 4px;
        cursor: pointer;
        text-transform: uppercase;
        margin: 0.25rem;
    }

    /** iframe guard */
    #iframe-alert {
        position: absolute;
        top: 20px;
        left: 20px;
        width: calc(90% - 40px);
        height: calc(90% - 40px);
        background: rgb(242 242 242 / 98%);
        border: 1px solid #727272;
        box-shadow: -4px -4px 10px 1px #00000061;
        z-index: 10;
    }

    div#iframe-alert-image {
        text-align: center;
        margin: 20px;
    }

    div#iframe-alert-subimage {
        text-align: center;
    }

    div#iframe-alert-subimage img {
        max-width: 60%;
    }

    div#iframe-alert-section {
        font-weight: bold;
        margin: 50px 10px 10px 10px;
        text-align: center;
        font-size: 16pt;
    }

    div#iframe-alert-actions {
        text-align: center;
    }

    div#iframe-ad {
        text-align: center;
        margin: 20px;
        position: absolute;
        bottom: 0px;
    }

    a, a:visited {
        color: black;
        text-decoration: underline;
    }
    button{
        display: inline-block;
        font-weight: 400;
        line-height: 1.5;
        color: #fff;
        background-color: #556ee6;
        border-color: #556ee6;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        padding: .47rem .75rem;
        padding-left: 0.75rem;
        font-size: .8125rem;
        border-radius: .25rem;
        -webkit-transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    }

</style>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-2KZEP7DPYH"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-2KZEP7DPYH');
</script>


<center class="bottom">
    <a href="{{ route('user.scan') }}"  class="btn btn-primary waves-effect btn-label waves-light"><i class="fa fa-qrcode label-icon"></i> Scanner le QR Code</a>
</center>



    <div id="scanapp-top-container">
        <div class="col-md-12">
     
        </div>
        @if(Session::has('message'))
            <div class="card col-md-10">
                <div class="card-body">
                    {!! Session::get('message') !!}
                </div>
            </div>
        @endif
            <div id="scanner" style="display: none">
                <div id="logo">
                    <img src="{{ asset('template/assets/images/logo.png') }}" alt="logo" />
                    <br>
                </div>


                <div id="reader"></div>
                <div class="empty"></div>
            </div>
            <div id="workspace" style="display: none">
                <div id="result">
                    <div class="workspace-header">
                    </div>
                    <div id="no-result-container">Scan to get results</div>
                    <div id="new-scanned-result">
                      <div class="header">Scan <span id="scan-result-code-type">{code}</span> terminé !</div>
                        <div class="section">
                            <div class="image" id="scan-result-image"></div>
                            <div class="data">
                                <table id="result_table">
                                    <tr>
                                        <!-- <td>Parsed</td> -->
                                        <td colspan="2">
                                            <div>
                                                <div class="badge">
                                                    <div class="badge-icon">
                                                        <span><b>Type</b></span>
                                                    </div>
                                                    <div class="badge-text">
                                                        <span id="scan-result-badge-body">{type}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="badge row" id="loading_status">
                                                    <div class="badge-icon">
                                                        <span><b>Statut</b></span>
                                                    </div>
                                                    <div class="badge-text ">
                                                        <span id="statut_qrcode"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="scan-result-parsed">{parsed result here}</div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Actions</td>
                                        <td>
                                            <img class="action_image" id="action-share"
                                                 src="{{ asset('assets/svg/share-svgrepo-com.svg') }}">
                                            <img class="action_image" id="action-copy"
                                                 src="{{ asset('assets/svg/copy-svgrepo-com.svg') }}">
                                            <img class="action_image" id="action-payment"
                                                 src="{{ asset('assets/svg/coin-svgrepo-com.svg') }}" style="display: none">
                                        </td>
                                    </tr>
                                    <tr style="display: none">
                                        <td>Text</td>
                                        <td style="word-break: break-word">
                                            <div id="scan-result-text">{text result here}</div>
                                        </td>
                                    </tr>

                                </table>
                                <div id="body-footer">
                                    <button id="scan-result-close" class="btn btn-primary waves-effect btn-label waves-light">
                                        <i class="fa fa-qrcode label-icon"></i> Scanner le QR Code
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="history">
                    <div class="workspace-header">Scan History</div>
                    <div id="no-result-container">No scans in history</div>
                    <div id="history-list"></div>
                </div>
            </div>
        </div>


    <div class="banners-container">
        <div class="banners">
            <div class="banner error">
                <div class="banner-icon"><i data-eva="alert-circle-outline" data-eva-fill="#ffffff" data-eva-height="48" data-eva-width="48"></i></div>
                <div class="banner-message" id="banner-error-message">{}</div>
                <div class="banner-close" onclick="hideBanners()"><i data-eva="close-outline" data-eva-fill="#ffffff"></i></div>
            </div>
            <div class="banner success">
                <div class="banner-icon"><i data-eva="checkmark-circle-outline" data-eva-fill="#ffffff" data-eva-height="48" data-eva-width="48"></i></div>
                <div class="banner-message" id="banner-success-message">{}</div>
                <div class="banner-close" onclick="hideBanners()"><i data-eva="close-outline" data-eva-fill="#ffffff"></i></div>
            </div>
        </div>
    </div>

    <div id="iframe-alert" style="display: none;">
        <div id="iframe-alert-image"><img src="{{ asset('assets/svg/alert-svgrepo-com.svg') }}"></div>
        <div id="iframe-alert-subimage"><img src="{{ asset('assets/svg/scanapp.svg') }}" /></div>
    </div>
        <!-- ScanAppAntiEmbedBottomAd -->


@endsection
@push('footer-script')
{{--  <script src="{{ asset('backoffice/js/agents.controle.js') }}"></script>
 --}}
    <!-- <script src="/assets/js/app.js"></script> -->
<script src="https://unpkg.com/eva-icons" onload="eva.replace()"></script>

<script src="{{ asset('backoffice/scripts.js') }}"></script>
<script src="{{ asset('backoffice/html5-qrcode.min.js') }}"></script>

 <script>
    var userUuid = @Json($user->uuid ?? '' );
  </script>
@endpush

