

document.addEventListener('DOMContentLoaded', () => {
  const contactForm = document.getElementById('contactForm');
  if (!contactForm) return;

  contactForm.addEventListener('submit', async e => {
    e.preventDefault();

    const formData = new FormData(contactForm);
    try {
      const res = await fetch('https://script.google.com/macros/s/AKfycbxZYNV7aDAAuq4rZF7imSwkfcJz27KEEGF3DKLEbyMAAnUqoYxUnybmpFuPIWjyNdF85Q/exec', { method: 'POST', body: formData });
      const data = await res.json();
      if (data.result === 'success') {
        document.getElementById('formSuccess').textContent = 'Thank you! Your message has been sent.';
        contactForm.reset();
      } else {
        alert('Submission error.');
      }
    } catch (err) {
      console.error(err);
      alert('Network error.');
    }
  });
});


