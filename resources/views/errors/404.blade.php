<html>
   <head></head>
      <title>404 page</title>
    <style>
        .full-height {
                min-height: 100%;
               position:relative;
        }
    .page-mid{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
        }
        .page4-text{
        color: #09409d;
    font-size: 65px;
    margin-bottom: 0px;
    font-family: poppins;
    }
   
.text-primary {
    font-family: 'BrandonGrotesque-Black';
    color: #e85325 !important;
    outline: none;
}

.font-weight-light {
    font: normal 14px/1.5em Arial, sans-serif;
    color: #999;
    font-family: poppins;
}

.lead{
    font-family: 'poppins';
    font-size:15px;
}
 .home-page{
    text-align: center;
    text-transform: uppercase;
    margin-top: 00px;
    margin-bottom: 30px;
 }
.home-page a{
text-decoration: none;
 }
.btn-primary {
    color: #e85325;
    border: 3px solid #e85325;
    padding: 0px 20px;
    font-size: 16px;
    line-height: 40px;
    width: 230px;
    text-decoration: none;
    text-align: center;
    text-transform: uppercase;
    background: #fff;
    display: inline-block;
    margin: 10px 10px;
    font-family: 'BrandonGrotesque-Regular', arial, sans-serif;
    border-radius: 0;
}

.notfoundimg-1,
.notfoundimg-2 {
    position: absolute;
    left: 0;
    top: 10px;
}

.notfoundimg-3 {
    position: relative;
    z-index: 1;
}

.notfoundimg-1 {
    animation-name: floating;
    animation-duration: 2s;
    animation-iteration-count: infinite;
    animation-timing-function: ease-in-out;
}
.notfoundimg-2 {
    animation-name: floating;
    animation-duration: 3s;
    animation-iteration-count: infinite;
    animation-timing-function: ease-in-out;
}
.imgFoundRealtive {
    position: relative;
    width: 100%;
    min-height: 330px;
}
img.img-fluid.notfoundimg-1 {
    max-height: 275px;
}
img.img-fluid.notfoundimg-2 {
    max-height: 230px;
}
img.img-fluid.notfoundimg-3 {
    position: absolute;
    max-height: 180px;
}

@keyframes floating {
    from { transform: translate(0,  0px); }
    65%  { transform: translate(0, 8px); }
    to   { transform: translate(0, -0px); }    
}
@media(max-width:767.98px){
    .page-mid{
        width:calc(100% - 30px);
        max-width:350px;
        margin:auto;
        text-align: center;
    }
    img.img-fluid.notfoundimg-1{
        max-width:300px;
    }
    img.img-fluid.notfoundimg-3{
        max-width:150px;
    }
    img.img-fluid.notfoundimg-2{
        max-width:180px;
    }
}



    
</style>
   <body>
     
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="p-v-30 page-mid text-center">
                     <h1 class="font-weight-semibold display-1 lh-1-2 page4-text">404</h1>
                     <h2 class="font-weight-light font-size-30">Whoops! Looks like you got lost</h2>
                     <p class="lead">We couldn't find what you were looking for.</p>
                     <div class="row my-3">
                        <div class="col-7 mx-auto">
                           <div class="imgFoundRealtive">
                              <img class="img-fluid notfoundimg-1 lazyload" src="{{WEBSITE_IMG_URL.'1.png'}}" alt="">
                              
                              <img class="img-fluid notfoundimg-2 lazyload" src="{{WEBSITE_IMG_URL.'2.png'}}" alt="">
                              <img class="img-fluid notfoundimg-3 lazyload" src="{{WEBSITE_IMG_URL.'3.png'}}" alt="">
                           </div>
                            <div class="home-page">
                                <a href="{{WEBSITE_URL}}"> Back to home page </a>
                            </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      
      
   </body>

</html>