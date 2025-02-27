import {} from '../../services/authentication/authentication.service.js';




window.addEventListener('load', () => {
  if (document.cookie
  .split("; ")
  .find((row) => row.startsWith("jwtRefresh="))) {
    window.location.href = url
  }

  document.getElementById('signup-submit-button').addEventListener('click', (event) => {
    event.preventDefault();

    const form = new FormData(event.target);


  })
})