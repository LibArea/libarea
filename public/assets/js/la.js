function getById(id){return document.getElementById(id)}
function queryAll(selector){return document.querySelectorAll(selector)}
function isIdEmpty(elementId){let element=getById(elementId);return element!==null&&element!==undefined?element:!1}
function makeRequest(url,options={}){return fetch(url,{...options,method:"POST",headers:{'Content-Type':'application/x-www-form-urlencoded'}}).then((response)=>{location.reload()})}
function getDefaultTime(){let expirationDate=new Date();expirationDate.setTime(expirationDate.getTime()+(365*24*60*60*1000));return"expires="+expirationDate.toGMTString()}
function getCookie(cookieName){let name=cookieName+"=";let cookieArray=document.cookie.split(';');for(let i=0;i<cookieArray.length;i++){let cookie=cookieArray[i].trim();if(cookie.indexOf(name)===0){return cookie.substring(name.length)}}
return""}
function toggleMode(cookieName,firstMode,secondMode){let mode=getCookie(cookieName);let expires=getDefaultTime();if(mode==firstMode){document.cookie=cookieName+"="+secondMode+"; "+expires+";path=/";document.getElementsByTagName('body')[0].classList.remove(firstMode)}else{document.cookie=cookieName+"="+firstMode+"; "+expires+";path=/";document.getElementsByTagName('body')[0].classList.add(firstMode)}}