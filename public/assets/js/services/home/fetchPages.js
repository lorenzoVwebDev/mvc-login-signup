export async function fetchDashboard(url) {
  if (sessionStorage.getItem('access_token')) {
    const accessToken = sessionStorage.getItem('access_token');
    const response = await fetch(`${url}admin/view/dashboard`, {
      headers: {
        'Authorization': `Bearer ${accessToken}`
      }
    });

    const result = await response.text();

    return {
      result, 
      response
    }
  } else {
    const response = await fetch(`${url}admin/view/dashboard`);
    const accessToken = document.cookie.split("; ").find(element => {
      return element.includes("jwtAccess")
    }).replace('jwtAccess=', '');

    sessionStorage.setItem('access_token', accessToken);

    const result = await response.text();

    return {
      result, 
      response
    }
  }
}