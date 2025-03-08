//service
import { signUp } from '../../services/authentication/authentication.service.js'
//view
import { signUpView } from '../../view/authentication/authentication.view.js';

import {url} from '../../utils/globalVariables.js'

document.getElementById('sign-up-form').addEventListener('submit', async (event) => {
  try {
    event.preventDefault();
    const form = new FormData(event.target);
    const responseObject = await signUp(form, url);
    const {result, response} = responseObject;
    signUpView(result, response, url, event);
  } catch (err) {
    console.error(err)
  }
})