export async function signIn(form, url) {

  const response = await fetch(`${url}authentication/signin`, {
    method: 'POST',
    body: form
  });

    const result = await response.json();

    return {
      result,
      response
    }
}

export async function signUp(form, url) {

  const response = await fetch(`${url}authentication/signup`, {
    method: 'POST',
    body: form
  });

    const result = await response.json();

    return {
      result,
      response
    }
}

export async function changePwr(form, url) {

  const response = await fetch(`${url}authentication/changepwr`, {
    method: 'POST',
    body: form
  });

  return {
    result,
    response
  }
}
