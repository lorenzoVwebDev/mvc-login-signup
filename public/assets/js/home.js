import { signIn } from './services/home/authentication.service.js'
import { url } from './utils/globalVariables.js'

document.getElementById('sign-in-form').addEventListener('submit', async (event) => {
  event.preventDefault();

  const form = new FormData(event.target);
  const responseObject = await signIn(form, url);
})