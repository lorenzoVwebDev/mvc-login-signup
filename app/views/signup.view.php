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
  <link rel="stylesheet" href="<?=ROOT?>public/assets/css/common.css"/>
</head>
<body>
  <section class="git-header-section"></section>
  <section class="main-header">
  </section>

  <section class="main-section">
    <div class="modal modal-sheet position-static d-block bg-body-secondary p-4 py-md-5" tabindex="-1" role="dialog" id="modalSignin">
    <div class="d-flex flex-column" role="document">
      <div class="modal-content rounded-4 shadow">
        <div class="modal-header p-5 pb-4 border-bottom-0">
          <h1 class="fw-bold mb-0 fs-2">Sign up for free</h1>
        </div>

        <div class="modal-body p-5 pt-0">
          <form class="" id="sign-up-form">
            <div class="form-floating mb-3">
              <input type="text" class="form-control rounded-3" placeholder="name@example.com" pattern="[a-zA-Z0-9]{8,20}" required name="username">
              <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating mb-3">
              <input type="email" class="form-control rounded-3" placeholder="name@example.com" name="email" required>
              <label for="floatingInput">email</label>
            </div>
            <div class="form-floating mb-3">
              <input type="password" class="form-control rounded-3" id="floatingPassword" placeholder="Password" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$" name="password" required>
              <label for="floatingPassword">Password</label>
            </div>
            <input type="hidden" name="authentication" value="sign-up"/>
            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" id="signup-submit-button" type="submit">Sign up</button>
            <small class="text-body-secondary">By clicking Sign up, you agree to the terms of use.</small>
          </form>
        </div>
      </div>
    </div>
  </div>
  </section>

  <section class="footer-section"></section>
  <div class="modal-container">
  </div>
  <div class="edit-modal-container">
  </div>

  <script src="<?= ROOT?>public/assets/bootstrap.assets/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= ROOT?>public/assets/js/common-components/index.js" type="module"></script>
  <script src="<?= ROOT?>public/assets/js/main/main.authentication/signup.js" type="module"></script>
  <script>
  </script>
</body>
</html>