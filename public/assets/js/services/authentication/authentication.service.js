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
        console.log(result['requested-url'])
        const result2 = await webPageResponse.json();


        const htmlResult = result2.html

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

export async function signUp(form, url) {
  const response = await fetch(`${url}authentication/signup`, {
    method: "POST",
    body: form,
  })

  if (response.status >= 200 && response.status < 400) {
    const result = await response.json()

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
  } else if (response.status >= 500) {
    const result = await response.json()

    return {
      result,
      response
    }
  }
}