export async function requestDashboard(accessToken, url) {
  if (accessToken) {
    const webPageResponse = await fetch(`${url}admin/dashboard/dashboard']}`, {
      headers: {
       Authorization: `Bearer ${accessToken}`
     }
     });

     if (webPageResponse.status >= 200 && webPageResponse.status < 400) {
      const htmlResult = await webPageResponse.text();
      console.log(htmlResult)
      return {
        htmlResult,
        webPageResponse
      }
     } else if (webPageResponse.status >= 400 && webPageResponse.status < 500) {
      window.location.href = `${url}authentication/authentication/signin`
     }
  } else {
    const webPageResponse = await fetch(`${url}admin/dashboard/dashboard`);

    if (webPageResponse.status >= 200 && webPageResponse.status < 400) {
      const result = await webPageResponse.json();

      accessToken = result['access_token'];
      const htmlResult = result.html
      return {
        htmlResult,
        webPageResponse
      }
     } else if (webPageResponse.status >= 400 && webPageResponse.status < 500) {
      window.location.href = `${url}authentication/authentication/signin`
     }
  }
}