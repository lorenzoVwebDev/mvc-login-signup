//services
import { signIn } from './services/authentication/authentication.service.js'
//views
import { renderProject } from './view/pagerendering/dashboard.view.js';
//global variables
import { url } from './utils/globalVariables.js'

window.addEventListener('load', () => {
  if (document.cookie
  .split("; ")
  .find((row) => row.startsWith("jwtRefresh="))) {
    window.location.href = url
  }

  document.getElementById('sign-in-form').addEventListener('submit', async (event) => {
    event.preventDefault();

    const form = new FormData(event.target);
    const responseObject = await signIn(form, url);
    const {htmlResult, webPageResponse} = responseObject;
    const state = {
      htmlResult
    }
    history.pushState(state.htmlResult, '', url+'dashboard/');
    renderProject();
  })
})

