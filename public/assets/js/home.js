//services
import { requestDashboard } from './services/home/requestdashboard.service.js'
//views
import { renderProject } from './view/pagerendering/dashboard.view.js';
//global variables
import { url, accessToken } from './utils/globalVariables.js'

window.addEventListener('load', () => {
  addPopState()
  history.replaceState(undefined, '', url);
  document.getElementById('dashboard-link').addEventListener('click', async () => {
    const responseObject = await requestDashboard(accessToken, url);
    const {htmlResult, webPageResponse} = responseObject;
    const state = {
      htmlResult
    }
    history.pushState(state.htmlResult, '', url+'dashboard/');
    renderProject();
  })
})

function addPopState() {
  window.addEventListener("popstate", (event) => {
    if (event.currentTarget.location.pathname === '/public/dashboard/') {
      renderProject();
    } else {
      const popstateurl = event.currentTarget.location.href;
      open(popstateurl, '_self');
    }
  });
}