export async function signIn(form, url) {

  const response = await fetch(`${url}authentication/signin`, {
    method: 'POST',
    body: form
  });

  if (response.status >= 200 && response.status < 400) {
    const result = await response.json();
    if (result['requested-url']) {
      await fetch('http://mvc-login-signup/public/dashboard/view', {
        headers: {
         Authorization: `Bearer ${result['authorization_token']}`
       }
       });
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