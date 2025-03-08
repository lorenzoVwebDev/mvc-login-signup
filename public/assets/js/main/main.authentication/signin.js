//service
import { signIn } from '../../services/authentication/authentication.service.js'
//view
import { signInView } from '../../view/authentication/authentication.view.js';

import {url} from '../../utils/globalVariables.js'

document.getElementById('sign-in-form').addEventListener('submit', async (event) => {
  try {
    event.preventDefault()
    const form = new FormData(event.target);
    const responseObject = await signIn(form, url);
    const {result, response} = responseObject;
    signInView(result, response, url, event);
  } catch (err) {
    console.error(err)
  }
})