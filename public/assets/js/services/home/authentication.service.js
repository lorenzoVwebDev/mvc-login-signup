export async function signIn(form, url) {

  const response = fetch(`${url}authentication/signin`, {
    method: 'POST',
    body: form
  });

  if (response.status >= 200 && response.status < 400) {
    const result = await response.json();
    return {
      result,
      response
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