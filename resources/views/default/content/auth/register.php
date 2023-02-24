<main>
  <h1 class="title-max"><?= __('app.' . $data['sheet']); ?></h1>

  <form class="max-w300" action="<?= url('register.add'); ?>" id="registration" method="post">
    <?php csrf_field(); ?>
    <?= insert('/_block/form/registration'); ?>
  </form>

  <p><?= __('app.agree_rules'); ?>.</p>
  <p><?= __('help.security_info'); ?></p>
</main>

<aside>
  <div class="p15 bg-violet">
    <?= __('auth.mail_confirm'); ?>
  </div>
</aside>

<script nonce="<?= $_SERVER['nonce']; ?>">
  const nicknameEl = document.querySelector('#login');
  const emailEl = document.querySelector('#email');
  const passwordEl = document.querySelector('#password');
  const confirmPasswordEl = document.querySelector('#password_confirm');
  const form = document.querySelector('#registration');

  const checkNickname = () => {

    let valid = false;

    const min = 3,
      max = 25;

    const nickname = nicknameEl.value.trim();

    if (!isRequired(nickname)) {
      showError(nicknameEl, '<?= __('auth.nickname_empty'); ?>');
    } else if (!isBetween(nickname.length, min, max)) {
      showError(nicknameEl, `<?= __('auth.nickname_least', ['info' => 3]); ?>`)
    } else {
      showSuccess(nicknameEl);
      valid = true;
    }

    return valid;
  };

  const checkEmail = () => {
    let valid = false;
    const email = emailEl.value.trim();
    if (!isRequired(email)) {
      showError(emailEl, '<?= __('auth.email_empty'); ?>');
    } else if (!isEmailValid(email)) {
      showError(emailEl, '<?= __('auth.email_valid'); ?>')
    } else {
      showSuccess(emailEl);
      valid = true;
    }

    return valid;
  };

  const checkPassword = () => {

    let valid = false;

    const min = 8,
      max = 32;

    const password = passwordEl.value.trim();

    if (!isRequired(password)) {
      showError(passwordEl, '<?= __('auth.password_empty'); ?>');
    } else if (!isBetween(password.length, min, max)) {
      showError(passwordEl, '<?= __('auth.password_least', ['info' => 8]); ?>');
    } else {
      showSuccess(passwordEl);
      valid = true;
    }

    return valid;
  };

  const checkConfirmPassword = () => {
    let valid = false;

    const confirmPassword = confirmPasswordEl.value.trim();
    const password = passwordEl.value.trim();

    if (!isRequired(confirmPassword)) {
      showError(confirmPasswordEl, '<?= __('auth.password_again'); ?>');
    } else if (password !== confirmPassword) {
      showError(confirmPasswordEl, '<?= __('auth.password_mismatch'); ?>');
    } else {
      showSuccess(confirmPasswordEl);
      valid = true;
    }

    return valid;
  };

  const isEmailValid = (email) => {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  };

  const isRequired = value => value === '' ? false : true;
  const isBetween = (length, min, max) => length < min || length > max ? false : true;

  const showError = (input, message) => {

    const formField = input.parentElement;

    formField.classList.remove('success');
    formField.classList.add('error');

    const error = formField.querySelector('small');
    error.textContent = message;
  };

  const showSuccess = (input) => {

    const formField = input.parentElement;

    formField.classList.remove('error');
    formField.classList.add('success');

    const error = formField.querySelector('small');
    error.textContent = '';
  }

  form.addEventListener('submit', function(e) {

    e.preventDefault();

    let isUsernameValid = checkNickname(),
      isEmailValid = checkEmail(),
      isPasswordValid = checkPassword(),
      isConfirmPasswordValid = checkConfirmPassword();

    let isFormValid = isUsernameValid &&
      isEmailValid &&
      isPasswordValid &&
      isConfirmPasswordValid;

    // если надо отправить, если форма действительна
    if (isFormValid) {}
  });

  const debounce = (fn, delay = 500) => {
    let timeoutId;
    return (...args) => {
      if (timeoutId) {
        clearTimeout(timeoutId);
      }

      timeoutId = setTimeout(() => {
        fn.apply(null, args)
      }, delay);
    };
  };

  form.addEventListener('input', debounce(function(e) {
    switch (e.target.id) {
      case 'login':
        checkNickname();
        break;
      case 'email':
        checkEmail();
        break;
      case 'password':
        checkPassword();
        break;
      case 'password_confirm':
        checkConfirmPassword();
        break;
    }
  }));
</script>