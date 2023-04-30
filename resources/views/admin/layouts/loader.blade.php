<!---- Loader start here--------->
<div class="box-loader" id="loader" style="display:none"> 
    <img src="{{WEBSITE_IMG_URL}}ikrunch-load.png" alt="loader" class="loader-logo">
    <div class="loader-05"> </div>
</div>
<style>
    /* ========== Preloader ========== */
    .box-loader {
        display: inline-block;
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background-color: #fff;
        z-index: 99999;
    }

  /*  .loader-05 {
        border: 3px dashed #09409d;
        border-radius: 50%;
        -webkit-animation: 5s lr linear infinite;
        animation: 5s lr linear infinite;
        position: absolute;
        z-index: 9999;
        left: 50%;
        top: 50%;
        margin: -50px 0 0 -50px;
        display: inline-block;
        width: 100px;
        height: 100px; 
        color: inherit;
        vertical-align: middle;
        pointer-events: none;
    }*/

    .loader-05:before {
        content: '';
        display: block;
        width: inherit;
        height: inherit;
        position: absolute;
        top: -.2em;
        left: -.2em;
        /* border: .2em solid var(--theme_gold); */
        border-radius: 50%;
        opacity: 0.1;
    }

    @-webkit-keyframes lr {
        0% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }

        100% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        }

        @keyframes lr {
        0% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }

        100% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
    }

    img.loader-logo {
        /* height: 13px;
        width: auto; */
        position: absolute;
        right: 0;
        left: 0;
        margin: 0 auto;
        top: 50%;
        transform: translateY(-50%);
    }
</style>
<!--loader end here-->