// Form Validation using jQuery
$(document).ready(function() {
    
    // ========== LOGIN FORM VALIDATION ==========
    $('#loginForm').on('submit', function(e) {
        let isValid = true;
        
        // Get form inputs
        const email = $('#loginEmail').val().trim();
        const password = $('#loginPassword').val().trim();
        
        // Clear previous error messages
        $('.error-message').remove();
        
        // Email validation
        if (!email) {
            showError('#loginEmail', 'Email is required');
            isValid = false;
        } else if (!isValidEmail(email)) {
            showError('#loginEmail', 'Please enter a valid email address');
            isValid = false;
        }
        
        // Password validation
        if (!password) {
            showError('#loginPassword', 'Password is required');
            isValid = false;
        } else if (password.length < 6) {
            showError('#loginPassword', 'Password must be at least 6 characters');
            isValid = false;
        }
        
        // Prevent form submission if validation fails
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
    
    // ========== REGISTRATION FORM VALIDATION ==========
    $('#registerForm').on('submit', function(e) {
        let isValid = true;
        
        // Get form inputs
        const firstName = $('#firstName').val().trim();
        const lastName = $('#lastName').val().trim();
        const email = $('#regEmail').val().trim();
        const phone = $('#phone').val().trim();
        const password = $('#regPassword').val().trim();
        const confirmPassword = $('#confirmPassword').val().trim();
        const terms = $('#termsRegister').is(':checked');
        
        // Clear previous error messages
        $('.error-message').remove();
        
        // First Name validation
        if (!firstName) {
            showError('#firstName', 'First name is required');
            isValid = false;
        } else if (firstName.length < 2) {
            showError('#firstName', 'First name must be at least 2 characters');
            isValid = false;
        }
        
        // Last Name validation
        if (!lastName) {
            showError('#lastName', 'Last name is required');
            isValid = false;
        } else if (lastName.length < 2) {
            showError('#lastName', 'Last name must be at least 2 characters');
            isValid = false;
        }
        
        // Email validation
        if (!email) {
            showError('#regEmail', 'Email is required');
            isValid = false;
        } else if (!isValidEmail(email)) {
            showError('#regEmail', 'Please enter a valid email address');
            isValid = false;
        }
        
        // Phone validation
        if (!phone) {
            showError('#phone', 'Mobile number is required');
            isValid = false;
        } else if (!isValidPhone(phone)) {
            showError('#phone', 'Please enter a valid 10-digit mobile number');
            isValid = false;
        }
        
        // Password validation
        if (!password) {
            showError('#regPassword', 'Password is required');
            isValid = false;
        } else if (password.length < 6) {
            showError('#regPassword', 'Password must be at least 6 characters');
            isValid = false;
        } else if (!isStrongPassword(password)) {
            showError('#regPassword', 'Password must contain uppercase, lowercase, and number');
            isValid = false;
        }
        
        // Confirm Password validation
        if (!confirmPassword) {
            showError('#confirmPassword', 'Please confirm your password');
            isValid = false;
        } else if (password !== confirmPassword) {
            showError('#confirmPassword', 'Passwords do not match');
            isValid = false;
        }
        
        // Terms checkbox validation
        if (!terms) {
            showError('#termsRegister', 'Please accept the terms and conditions');
            isValid = false;
        }
        
        // Prevent form submission if validation fails
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
    
    // ========== BOOKING FORM VALIDATION ==========
    $('#bookingForm').on('submit', function(e) {
        let isValid = true;
        
        // Clear previous error messages
        $('.error-message').remove();
        
        // Get all inputs from booking form
        const bookingInputs = $(this).find('input, select');
        
        // Pickup Location validation
        const pickupLocationInput = bookingInputs.filter('[placeholder="e.g., New York"]').eq(0);
        const pickupLocation = pickupLocationInput.val().trim();
        if (!pickupLocation) {
            showError(pickupLocationInput, 'Pickup location is required');
            isValid = false;
        }
        
        // Drop Location validation
        const dropLocationInput = bookingInputs.filter('[placeholder="e.g., New York"]').eq(1);
        const dropLocation = dropLocationInput.val().trim();
        if (!dropLocation) {
            showError(dropLocationInput, 'Drop location is required');
            isValid = false;
        }
        
        // Pickup DateTime validation
        const pickupDateTimeInput = bookingInputs.filter('[type="datetime-local"]').eq(0);
        const pickupDateTime = pickupDateTimeInput.val();
        if (!pickupDateTime) {
            showError(pickupDateTimeInput, 'Pickup date & time is required');
            isValid = false;
        }
        
        // Drop DateTime validation
        const dropDateTimeInput = bookingInputs.filter('[type="datetime-local"]').eq(1);
        const dropDateTime = dropDateTimeInput.val();
        if (!dropDateTime) {
            showError(dropDateTimeInput, 'Drop date & time is required');
            isValid = false;
        } else if (pickupDateTime && new Date(dropDateTime) <= new Date(pickupDateTime)) {
            showError(dropDateTimeInput, 'Drop date must be after pickup date');
            isValid = false;
        }
        
        // Car selection validation
        const carSelectInput = bookingInputs.filter('select').eq(0);
        const carSelect = carSelectInput.val();
        if (!carSelect) {
            showError(carSelectInput, 'Please select a car');
            isValid = false;
        }
        
        // Pickup Type validation
        const pickupTypeInput = bookingInputs.filter('select').eq(1);
        const pickupType = pickupTypeInput.val();
        if (!pickupType) {
            showError(pickupTypeInput, 'Please select a pickup type');
            isValid = false;
        }
        
        // Full Name validation
        const fullNameInput = bookingInputs.filter('[placeholder="e.g., John Doe"]');
        const fullName = fullNameInput.val().trim();
        if (!fullName) {
            showError(fullNameInput, 'Full name is required');
            isValid = false;
        } else if (fullName.length < 3) {
            showError(fullNameInput, 'Name must be at least 3 characters');
            isValid = false;
        }
        
        // Mobile Number validation
        const mobileInput = bookingInputs.filter('[type="tel"]');
        const mobileNumber = mobileInput.val().trim();
        if (!mobileNumber) {
            showError(mobileInput, 'Mobile number is required');
            isValid = false;
        } else if (!isValidPhone(mobileNumber)) {
            showError(mobileInput, 'Please enter a valid 10-digit mobile number');
            isValid = false;
        }
        
        // Email validation
        const emailInput = bookingInputs.filter('[type="email"]');
        const bookingEmail = emailInput.val().trim();
        if (!bookingEmail) {
            showError(emailInput, 'Email is required');
            isValid = false;
        } else if (!isValidEmail(bookingEmail)) {
            showError(emailInput, 'Please enter a valid email address');
            isValid = false;
        }
        
        // License Number validation
        const licenseInput = bookingInputs.filter('[placeholder="DL-0000XX..."]');
        const licenseNumber = licenseInput.val().trim();
        if (!licenseNumber) {
            showError(licenseInput, 'License number is required');
            isValid = false;
        } else if (licenseNumber.length < 8) {
            showError(licenseInput, 'Please enter a valid license number');
            isValid = false;
        }
        
        // Terms checkbox validation
        const termsBooking = $('#termsBooking').is(':checked');
        if (!termsBooking) {
            showError($('#termsBooking'), 'Please confirm the details');
            isValid = false;
        }
        
        // Prevent form submission if validation fails
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
    
    // ========== SEARCH & FILTER FORM VALIDATION ==========
    $('#searchForm').on('submit', function(e) {
        let isValid = true;
        
        // Clear previous error messages
        $('.error-message').remove();
        
        const searchInput = $('#searchInput').val().trim();
        
        // At least one search field should be filled
        if (!searchInput && !$('#priceFilter').val() && !$('#transmissionFilter').val() && !$('#fuelFilter').val()) {
            showError($('#searchInput'), 'Please enter at least one search criteria');
            isValid = false;
        }
        
        if (searchInput && searchInput.length < 2) {
            showError($('#searchInput'), 'Search term must be at least 2 characters');
            isValid = false;
        }
        
        // Prevent form submission if validation fails
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
    
    // ========== REAL-TIME VALIDATION ==========
    
    // Email field - validate on blur
    $(document).on('blur', 'input[type="email"]', function() {
        const email = $(this).val().trim();
        $(this).next('.error-message').remove();
        
        if (email && !isValidEmail(email)) {
            showError($(this), 'Please enter a valid email address');
        }
    });
    
    // Phone field - validate on blur
    $(document).on('blur', 'input[type="tel"]', function() {
        const phone = $(this).val().trim();
        $(this).next('.error-message').remove();
        
        if (phone && !isValidPhone(phone)) {
            showError($(this), 'Please enter a valid 10-digit number');
        }
    });
    
    // Password confirmation - validate on blur
    $(document).on('blur', '#confirmPassword', function() {
        const password = $('#regPassword').val().trim();
        const confirmPassword = $(this).val().trim();
        $(this).next('.error-message').remove();
        
        if (confirmPassword && password !== confirmPassword) {
            showError($(this), 'Passwords do not match');
        }
    });
    
    // Text fields - remove error on focus
    $(document).on('focus', 'input[type="text"], input[type="email"], input[type="password"], input[type="tel"]', function() {
        $(this).next('.error-message').remove();
    });
    
});

// ========== UTILITY FUNCTIONS ==========

// Validate email format
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Validate phone (10-digit)
function isValidPhone(phone) {
    const phoneRegex = /^[0-9]{10}$/;
    return phoneRegex.test(phone);
}

// Check if password is strong (contains uppercase, lowercase, and number)
function isStrongPassword(password) {
    const uppercaseRegex = /[A-Z]/;
    const lowercaseRegex = /[a-z]/;
    const numberRegex = /[0-9]/;
    
    return uppercaseRegex.test(password) && lowercaseRegex.test(password) && numberRegex.test(password);
}

// Display error message below input
function showError(element, message) {
    $(element).after('<div class="error-message text-danger small mt-1">' + message + '</div>');
    $(element).addClass('is-invalid');
}
