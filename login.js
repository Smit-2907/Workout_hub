document.getElementById('loginForm').addEventListener('submit', function(event) {
    // Get form fields
    var name = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();
    var password = document.getElementById('pass').value.trim();

    // Email validation regex
    var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    // Password validation regex (1 uppercase, 3 lowercase, 1 special char, 4 digits)
    var passwordRegex = /^(?=.*[A-Z])(?=.*[a-z]{3})(?=.*[!@#$%^&*])(?=.*\d{4,}).{9}$/;

    // Required field validation
    if (name === '' || email === '' || password === '') {
        alert('All fields are required.');
        event.preventDefault();
        return;
    }

    // Validate email
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        event.preventDefault();
        return;
    }

    // Validate password
    if (!passwordRegex.test(password)) {
        alert('Password must contain exactly 1 uppercase letter, 3 lowercase letters, 1 special character, and 4 digits.');
        event.preventDefault();
        return;
    }
});
