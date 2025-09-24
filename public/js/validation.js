document.addEventListener('DOMContentLoaded', () => {
  const signup = document.querySelector('#signupForm');
  if (signup) {
    signup.addEventListener('submit', (e) => {
      clearErrors();
      let ok = true;
      const email = document.querySelector('#email')?.value || '';
      const pwd = document.querySelector('#password')?.value || '';
      const cpwd = document.querySelector('#confirm_password')?.value || '';

      if (!/^\S+@\S+\.\S+$/.test(email)) { showError('#email', 'Invalid email'); ok = false; }
      if (pwd.length < 8) { showError('#password', 'Password must be at least 8 characters'); ok = false; }
      if (pwd !== cpwd) { showError('#confirm_password', 'Passwords do not match'); ok = false; }

      if (!ok) e.preventDefault();
    });
  }

  function showError(sel, msg) {
    const el = document.querySelector(sel);
    if (!el) return;
    const err = document.createElement('div');
    err.className = 'text-danger small mt-1';
    err.innerText = msg;
    el.parentNode.appendChild(err);
  }
  function clearErrors() {
    document.querySelectorAll('.text-danger.small').forEach(n => n.remove());
  }
});
