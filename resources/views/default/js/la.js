/**
 * @link https://libarea.ru/
 * @copyright Copyright (c) 2023 
 * @author Evg <budo@narod.ru>
 */

function getById(id) {
	return document.getElementById(id);
};

function queryAll(id) {
	return document.querySelectorAll(id);
};

function isIdEmpty(elmId) {
  let elem = getById(elmId);
  if (typeof elem !== 'undefined' && elem !== null) return elem;
  return false;
}

function makeRequest(url, options = {}) {
  return fetch(url, {
    ...options,
    method: "POST",
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
  })
    .then((response) => {
      location.reload();
    })
}

function defTime() {
  let d = new Date();
  d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000)); //365 days
  return "expires=" + d.toGMTString();
}

function getCookie(cname) {
  let name = cname + "=";
  let ca = document.cookie.split(';');
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) === 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
