//service
import {changePwr} from '../../services/authentication/authentication.service.js'
//view

import {url} from '../../utils/globalVariables.js'
 
document.getElementById('changepwr-form').addEventListener('submit', async (event) => {
  event.preventDefault();

  const form = new FormData(event.target)
  const responseObject = await changePwr(form, url);
  console.log(responseObject)
})