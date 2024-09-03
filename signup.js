document.getElementById('signupForm').addEventListener('submit', function(event) {
    // Get form fields
    var name = document.getElementById('name').value.trim();
    var age = document.getElementById('age').value.trim();
    var dob = document.getElementById('dob').value.trim();
    var genderMale = document.getElementById('male').checked;
    var genderFemale = document.getElementById('female').checked;
    var mobile = document.getElementById('mno').value.trim();
    var email = document.getElementById('email').value.trim();
    var password = document.getElementById('pass').value.trim();
    var repeatPassword = document.getElementById('rpass').value.trim();

    // Email validation regex
    var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    // Password validation regex (1 uppercase, 3 lowercase, 1 special char, 4 digits)
    var passwordRegex = /^(?=.*[A-Z])(?=.*[a-z]{3})(?=.*[!@#$%^&*])(?=.*\d{4}).{9}$/;

    // Mobile number validation regex (10 digits)
    var mobileRegex = /^\d{10}$/;

    // Validate email
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        event.preventDefault();
        return;
    }

    // Validate password
    if (!passwordRegex.test(password)) {
        alert('Password must contain exactly 1 uppercase letter, 3 lowercase letters, 1 special character, and 4 digits, and be 9 characters long.');
        event.preventDefault();
        return;
    }

    // Validate repeat password
    if (password !== repeatPassword) {
        alert('Passwords do not match.');
        event.preventDefault();
        return;
    }

    
    // Validate date of birth
    if (!isValidDate(dob)) {
        alert('Please enter a valid date of birth in the format YYYY-MM-DD.');
        event.preventDefault();
        return;
    }

    // Validate mobile number
    if (!mobileRegex.test(mobile)) {
        alert('Please enter a valid 10-digit mobile number.');
        event.preventDefault();
        return;
    }

    // Disable the submit button to prevent multiple submissions
    document.getElementById('submitBtn').disabled = true;
});

// Function to validate date format (YYYY-MM-DD) and ensure it's a valid date
function isValidDate(dateString) {
    var dobRegex = /^\d{4}-\d{2}-\d{2}$/;

    if (!dobRegex.test(dateString)) {
        return false;
    }

    var parts = dateString.split("-");
    var year = parseInt(parts[0], 10);
    var month = parseInt(parts[1], 10);
    var day = parseInt(parts[2], 10);

    // Check if the month is valid
    if (month < 1 || month > 12) {
        return false;
    }

    // Check if the day is valid for the given month
    var daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    // Check for leap year
    if ((year % 4 === 0 && year % 100 !== 0) || year % 400 === 0) {
        daysInMonth[1] = 29;
    }

    return day > 0 && day <= daysInMonth[month - 1];
}
