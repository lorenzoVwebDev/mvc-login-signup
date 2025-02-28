import {url} from '../utils/globalVariables.js'
//services
import { fetchDashboard } from '../services/home/fetchPages.js';
//view
import { renderDashboard } from '../view/dashboard/'

window.addEventListener('popstate', () => {
  console.log("URL changed to:", window.location.pathname);
});

window.addEventListener('load', () => {


  document.getElementById('dashboard-link').addEventListener('click', async () => {
    const responseObject = await fetchDashboard(url);
    const {result, response } = responseObject;

    
  })
})