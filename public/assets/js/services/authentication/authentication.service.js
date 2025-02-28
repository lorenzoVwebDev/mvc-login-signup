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