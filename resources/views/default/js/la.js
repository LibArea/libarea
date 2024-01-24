/**
 * @link https://libarea.ru/
 * @copyright Copyright (c) 2024
 * @author Evg <budo@narod.ru>
 */

function getById(id) {
  return document.getElementById(id);
}

function queryAll(selector) {
  return document.querySelectorAll(selector);
}

function isIdEmpty(elementId) {
  let element = getById(elementId);
  return element !== null && element !== undefined ? element : false;
}

function makeRequest(url, options = {}) {
  return fetch(url, {
    ...options,
    method: "POST",
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
  })
    .then((response) => {
      location.reload();
    });
}

function getDefaultTime() {
  let expirationDate = new Date();
  expirationDate.setTime(expirationDate.getTime() + (365 * 24 * 60 * 60 * 1000)); // 365 days
  return "expires=" + expirationDate.toGMTString();
}

function getCookie(cookieName) {
  let name = cookieName + "=";
  let cookieArray = document.cookie.split(';');
  for (let i = 0; i < cookieArray.length; i++) {
    let cookie = cookieArray[i].trim();
    if (cookie.indexOf(name) === 0) {
      return cookie.substring(name.length);
    }
  }
  return "";
}