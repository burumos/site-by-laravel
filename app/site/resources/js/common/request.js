
const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
let csrfToken = '';

if (csrfTokenElement) {
  csrfToken = csrfTokenElement.content;
}else {
  console.error('Not found csrf token!!!');
}

export function jsonAjax(url, jsonContent, method='POST', responseJson=true) {
  return window.fetch(url, {
    method: method,
    body: jsonContent,
    headers: {
      "Content-Type": "application/json; charset=utf-8",
      'X-CSRF-TOKEN': csrfToken,
    },
  }).then(response => {
    if (response.ok) {
      return responseJson ? response.json() : response;
    }
    throw new Error('ajax error');
  });
}
