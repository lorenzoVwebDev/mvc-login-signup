<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MVC-login-signup</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/headers/">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
  <link href="<?=ROOT?>public/assets/bootstrap.assets/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?=ROOT?>public/assets/css/home.css"/>
  <link rel="stylesheet" href="<?=ROOT?>public/assets/css/common.css"/>
</head>
<body>
  <section class="git-header-section"></section>
  <section class="main-header">
  </section>

  <section class="main-section">
    <main class="form-signin w-100 m-auto">
      <form id="sign-in-form">
        <div>
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

        <div class="form-floating">
          <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" pattern="^(?=.{8,}$)([a-zA-Z0-9._-]+|[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$
  " form="sign-in-form" name="username" required>
          <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control" id="floatingPassword" placeholder="Password" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$" name="password" form="sign-in-form" required>
          <label for="floatingPassword">Password</label>
        </div>

        <div class="form-check text-start my-3">
          <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault" form="sign-in-form">
          <label class="form-check-label" for="flexCheckDefault">
            Remember me
          </label>
        </div>
        <input type='hidden' name='authentication' value="sign-in">
        <button class="btn btn-primary w-100 py-2" type="submit" form="sign-in-form" id="signinsubmit-button">Sign in</button>
        <p class="mt-5 mb-3 text-body-secondary">&copy; 2017–2024</p>
        </div>
      </form>
      <div>
        <a href="<?=ROOT?>public/authentication/authentication/signup">Sign Up</a>
      </div>
      <div>
      <a href="<?=ROOT?>public/authentication/authentication/changepwr">Change Password</a>
      </div>
    </main>

  </section>

  <section class="footer-section"></section>
  <div class="modal-container">
  </div>
  <div class="edit-modal-container">
  </div>

  <script src="<?= ROOT?>public/assets/bootstrap.assets/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= ROOT?>public/assets/js/common-components/index.js" type="module"></script>
  <script src="<?= ROOT?>public/assets/js/main/main.authentication/signin.js" type="module"></script>
  <script>
  </script>
</body>
</html>