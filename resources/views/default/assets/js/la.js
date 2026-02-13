/**
 * @link https://libarea.ru/
 * @copyright Copyright (c) 2024
 * @author Evg <budo@narod.ru>
 */

const COOKIE_MAX_AGE_DAYS = 365;
const MS_PER_DAY = 24 * 60 * 60 * 1000;

function getById(id) {
  return document.getElementById(id);
}

function queryAll(selector) {
  return Array.from(document.querySelectorAll(selector));
}

/**
 * Returns the element if it exists, otherwise false (for conditional use in onclick etc).
 */
function isIdEmpty(elementId) {
  const el = getById(elementId);
  return el != null ? el : false;
}

/**
 * Builds application/x-www-form-urlencoded body string with optional CSRF token.
 * Skips params whose value is undefined, null, or empty string.
 * @param {Record<string, string | undefined | null>} params - key-value pairs
 * @param {string} [csrfToken] - optional _token
 */
function buildFormBody(params, csrfToken) {
  const pairs = Object.entries(params)
    .filter(([, v]) => v != null && v !== "")
    .map(([k, v]) => `${encodeURIComponent(k)}=${encodeURIComponent(String(v))}`);
  if (csrfToken != null && csrfToken !== "") {
    pairs.push(`_token=${encodeURIComponent(csrfToken)}`);
  }
  return pairs.join("&");
}

/**
 * POST request with form body. By default reloads on success.
 * @param {string} url
 * @param {{ body?: string; method?: string }} [options]
 * @param {{ reload?: boolean }} [behavior] - reload: default true
 */
function makeRequest(url, options = {}, behavior = { reload: true }) {
  return fetch(url, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    ...options,
  }).then((response) => {
    if (behavior.reload !== false) {
      location.reload();
    }
    return response;
  });
}

function getDefaultTime() {
  const expirationDate = new Date();
  expirationDate.setTime(expirationDate.getTime() + COOKIE_MAX_AGE_DAYS * MS_PER_DAY);
  return `expires=${expirationDate.toUTCString()}`;
}

function getCookie(cookieName) {
  const name = `${cookieName}=`;
  const cookies = document.cookie.split(";");
  for (const raw of cookies) {
    const cookie = raw.trim();
    if (cookie.startsWith(name)) {
      return cookie.slice(name.length);
    }
  }
  return "";
}

/**
 * Returns CSRF token from meta tag or empty string if missing.
 */
function getCsrfToken() {
  const meta = document.querySelector("meta[name='csrf-token']");
  return meta ? (meta.getAttribute("content") || "") : "";
}
