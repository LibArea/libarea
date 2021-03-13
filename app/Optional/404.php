<?php
/* Actual 404 error page */
/* Актуальная страница 404 ошибки */
header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
?><html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Ошибка 404</title>
        <link href="/css/404.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="/favicon.ico" type="image/png">
    </head>
    <body>
        <div class="container">
        
            <div class="header">
                <div class="logo">
                    <a title="На главную" href="/">DEV</a> 
                </div>
            </div>
        
            <div class="telo">
                <div class="telo-r">
                      
                    <div class="telo-txt">Страница не существует</div>
                       
                    <div class="telo-404">404</div>
                    
                    <div class="telo-txt-desc">
                        Вы можете найти (почти) что угодно на сайте - очевидно, даже страницу, которой не существует. Может быть, этот случай, приведет вас в новое место?
                    </div>
                
                    <br><a href="/">Перейти на главную</a> 
               
                </div>
                <div class="telo-l">
                    <svg xmlns="http://www.w3.org/2000/svg" class="svg-404" viewBox="0 0 24 24" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M6 17.6l-2 -1.1v-2.5"></path>
                      <path d="M4 10v-2.5l2 -1.1"></path>
                      <path d="M10 4.1l2 -1.1l2 1.1"></path>
                      <path d="M18 6.4l2 1.1v2.5"></path>
                      <path d="M20 14v2.5l-2 1.12"></path>
                      <path d="M14 19.9l-2 1.1l-2 -1.1"></path>
                      <line stroke="#ddd"  x1="12" y1="12" x2="14" y2="10.9"></line>
                      <line x1="18" y1="8.6" x2="20" y2="7.5"></line>
                      <line stroke="#ddd"  x1="12" y1="12" x2="12" y2="14.5"></line>
                      <line x1="12" y1="18.5" x2="12" y2="21"></line>
                      <path stroke="#ddd" d="M12 12l-2 -1.12"></path>
                      <line x1="6" y1="8.6" x2="4" y2="7.5"></line>
                    </svg>
                </div>
            </div>  

        </div>
    </body>
</html>