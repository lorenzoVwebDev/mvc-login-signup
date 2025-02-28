import {url} from '../utils/globalVariables.js'
//services
import { fetchDashboard } from '../services/home/fetchPages.js';
//view
import { renderDashboard } from '../view/dashboard/dashboard.view.js'



window.addEventListener('load', () => {

  window.addEventListener('popstate', () => {
    console.log("URL changed to:", window.location.pathname);
  });
  document.getElementById('dashboard-link').addEventListener('click', async () => {
    const responseObject = await fetchDashboard(url);
    const {result, response } = responseObject;


  })
})