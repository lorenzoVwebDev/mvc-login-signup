export async function signIn(form, url) {

  const response = await fetch(`${url}authentication/signin`, {
    method: 'POST',
    body: form
  });

  if (response.status >= 200 && response.status < 400) {
    const result = await response.json();
    if (result['requested-url']&&result['access_token']) {
      const webPageResponse = await fetch(`${url}${result['requested-url']}`, {
        headers: {
         Authorization: `Bearer ${result['access_token']}`
       }
       });

       if (response.status >= 200 && response.status < 400) {
        const htmlResult = await webPageResponse.text();

        return {
          htmlResult,
          webPageResponse
        }
       }
    }
  } else if (response.status >= 400 && response.status < 500) {
    const result = await response.json()
    return {
      result,
      response
    }
  } else if (response.status > 500 ) {
    const result = await response.json()
    return {
      result,
      response
    }
  }
}