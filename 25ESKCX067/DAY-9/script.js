document.getElementById('registerForm').addEventListener('submit', function (e) {

  let isValid = true;


  document.querySelectorAll('.error').forEach(el => el.textContent = '');
  document.querySelectorAll('input').forEach(el => el.classList.remove('invalid'));

  const fullname = document.getElementById('fullname');
  const email = document.getElementById('email');
  const username = document.getElementById('username');
  const password = document.getElementById('password');
  const confirmPassword = document.getElementById('confirmPassword');

  
  if (fullname.value.trim() === '') {
    showError(fullname, 'fullnameError', 'Full name is required.');
    isValid = false;
  }

  
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email.value.trim())) {
    showError(email, 'emailError', 'Please enter a valid email address.');
    isValid = false;
  }


  if (username.value.trim().length < 4) {
    showError(username, 'usernameError', 'Username must be at least 4 characters.');
    isValid = false;
  }

  
  if (password.value.length < 6) {
    showError(password, 'passwordError', 'Password must be at least 6 characters.');
    isValid = false;
  }

  
  if (confirmPassword.value !== password.value || confirmPassword.value === '') {
    showError(confirmPassword, 'confirmPasswordError', 'Passwords do not match.');
    isValid = false;
  }

  if (!isValid) {
    e.preventDefault(); 
  }
});

function showError(inputEl, errorId, message) {
  inputEl.classList.add('invalid');
  document.getElementById(errorId).textContent = message;
}
