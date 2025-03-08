//service
import {changePwr} from '../../services/authentication/authentication.service.js'
//view
import {changepwrView} from '../../view/authentication/authentication.view.js'

import {url} from '../../utils/globalVariables.js'
 
document.getElementById('changepwr-form').addEventListener('submit', async (event) => {
  event.preventDefault();
  try {
    const form = new FormData(event.target)
    const responseObject = await changePwr(form, url);
    const {result, response} = responseObject;
    changepwrView(result, response, url, event);
  } catch (err) {
    console.error(err);
  }
})