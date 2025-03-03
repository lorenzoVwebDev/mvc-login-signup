export function signInView(result, response, url, event) {
  if (response.status >= 200 && response.status < 400) {
    window.location.href = url;
  } else if (response.status >= 400 && response.status < 500) {
    event.target.children[0].children[0].remove()
    const h3 = document.createElement('h3')
    h3.innerText = result['result'];
    event.target.children[0].prepend(h3)
  } else if (response.status >= 500 ) {
    event.target.children[0].children[0].remove()
    const logInForm = event.target.children[0]
    logInForm.innerHTML = '';
    const h1 = document.createElement('h1')
    h1.innerText = result['result'] + ' Please, try to log in again';
    logInForm.append(h1);
  }
}

export function signUpView(result, response, url, event) {
  if (response.status >= 200 && response.status < 400) {
    const parent = event.target.parentElement
    parent.innerHTML = "";
    const h1 = document.createElement('h1');
    h1.innerText = "Thank you so much for signing up! Check your email!";
    parent.append(h1);
    setTimeout(() => {
      window.location.href = `${url}authentication/authentication/signin`
    }, 4000)
  } else if (response.status >= 400 && response.status < 500) {
    event.target.reset()
    const h2 = document.createElement('h2');
    h2.innerText = `Username exists yet`;
    event.target.prepend(h2)
  } else if (response.status >= 500 ) {
    console.dir(event.target)
  }
}