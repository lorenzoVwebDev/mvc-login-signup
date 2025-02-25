//services
import { requestDashboard } from './services/home/requestdashboard.service.js'
//views
import { renderProject } from './view/pagerendering/dashboard.view.js';
//global variables
import { url, accessToken } from './utils/globalVariables.js'

window.addEventListener('load', () => {
  document.getElementById('dashboard-link').addEventListener('click', async () => {
    history.pushState(undefined, '', url + 'dashboard')
/*     history.replaceState(undefined, '', url); */
    const responseObject = await requestDashboard(accessToken, url);
    const {htmlResult, webPageResponse} = responseObject;
    renderProject(htmlResult);
  })
})
