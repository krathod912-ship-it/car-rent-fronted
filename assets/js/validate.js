/**
 * ========================================
 * COMPREHENSIVE FORM VALIDATION LIBRARY
 * ========================================
 * All-in-one form validation using jQuery
 * Priority: 1. Core validations, 2. Security, 3. UX, 4. Advanced
 */

$(document).ready(function () {
  // ==========================================
  // PRIORITY 1: CORE VALIDATION FUNCTIONS
  // ==========================================
  
  /**
   * Core validation function - handles all field validations
   * @param {HTMLElement} input - The input element to validate
   * @returns {void}
   */
  function validateInput(input) {
    const field = $(input);
    const value = field.val().trim();
    const errorfield = $("#" + field.attr("name") + "_error");
    const validationType = field.data("validation");
    const minLength = field.data("min") || 0;
    const maxLength = field.data("max") || 9999;
    const fileSize = field.data("filesize") || 0;
    const fileType = field.data("filetype") || "";
    let errorMessage = "";

    if (validationType) {
      // ========== PRIORITY 1: REQUIRED FIELD (Most Important) ==========
      if (validationType.includes("required") && value === "") {
        errorMessage = "This field is required.";
        setFieldError(field, errorfield, errorMessage);
        return;
      }

      // If field is empty and not required, pass validation
      if (value === "" && !validationType.includes("required")) {
        clearFieldError(field, errorfield);
        return;
      }

      // ========== PRIORITY 1: LENGTH VALIDATIONS ==========
      if (validationType.includes("min") && value.length < minLength) {
        errorMessage = `This field must be at least ${minLength} characters long.`;
        setFieldError(field, errorfield, errorMessage);
        return;
      }

      if (validationType.includes("max") && value.length > maxLength) {
        errorMessage = `This field must be at most ${maxLength} characters long.`;
        setFieldError(field, errorfield, errorMessage);
        return;
      }

      // ========== PRIORITY 2: EMAIL VALIDATION (Security) ==========
      if (validationType.includes("email") && value !== "") {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
          errorMessage = "Please enter a valid email address (e.g., user@example.com).";
          setFieldError(field, errorfield, errorMessage);
          return;
        }
      }

      // ========== PRIORITY 2: PHONE NUMBER VALIDATION (Business Logic) ==========
      if (validationType.includes("phone") && value !== "") {
        const phoneRegex = /^[0-9]{10}$/;
        if (!phoneRegex.test(value)) {
          errorMessage = "Please enter a valid 10-digit phone number.";
          setFieldError(field, errorfield, errorMessage);
          return;
        }
      }

      // ========== PRIORITY 2: NUMERIC VALIDATION ==========
      if (validationType.includes("number") && value !== "") {
        const numberRegex = /^[0-9]+$/;
        if (!numberRegex.test(value)) {
          errorMessage = "Please enter a valid number (digits only).";
          setFieldError(field, errorfield, errorMessage);
          return;
        }
      }

      // ========== PRIORITY 2: ALPHANUMERIC VALIDATION ==========
      if (validationType.includes("alphanumeric") && value !== "") {
        const alphanumericRegex = /^[a-zA-Z0-9\s]+$/;
        if (!alphanumericRegex.test(value)) {
          errorMessage = "This field can only contain letters and numbers.";
          setFieldError(field, errorfield, errorMessage);
          return;
        }
      }

      // ========== PRIORITY 2: URL VALIDATION ==========
      if (validationType.includes("url") && value !== "") {
        const urlRegex = /^(https?:\/\/)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}(\/.*)?$/;
        if (!urlRegex.test(value)) {
          errorMessage = "Please enter a valid URL.";
          setFieldError(field, errorfield, errorMessage);
          return;
        }
      }

      // ========== PRIORITY 2: STRONG PASSWORD VALIDATION (Security Critical) ==========
      // Password: min 8 chars, uppercase, lowercase, number, special char
      if (validationType.includes("strongPassword") && value !== "") {
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordRegex.test(value)) {
          errorMessage = "Password must be 8+ characters with uppercase, lowercase, number, and special char (@$!%*?&).";
          setFieldError(field, errorfield, errorMessage);
          return;
        }
      }

      // ========== PRIORITY 2: MEDIUM PASSWORD VALIDATION (6+ chars, case + number) ==========
      if (validationType.includes("mediumPassword") && value !== "") {
        const mediumPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,}$/;
        if (!mediumPasswordRegex.test(value)) {
          errorMessage = "Password must be 6+ characters with uppercase, lowercase, and number.";
          setFieldError(field, errorfield, errorMessage);
          return;
        }
      }

      // ========== PRIORITY 2: SIMPLE PASSWORD VALIDATION (6+ chars) ==========
      if (validationType.includes("simplePassword") && value !== "") {
        if (value.length < 6) {
          errorMessage = "Password must be at least 6 characters long.";
          setFieldError(field, errorfield, errorMessage);
          return;
        }
      }

      // ========== PRIORITY 1: PASSWORD CONFIRMATION VALIDATION ==========
      if (validationType.includes("confirmPassword") && value !== "") {
        const confirmFieldName = field.attr("name").replace("confirm", "").toLowerCase();
        const originalPassword = $(`input[name*="${confirmFieldName}Password"]:not([name*="confirm"])`).val();
        if (value !== originalPassword) {
          errorMessage = "Passwords do not match.";
          setFieldError(field, errorfield, errorMessage);
          return;
        }
      }

      // ========== PRIORITY 1: SELECT/DROPDOWN VALIDATION ==========
      if (validationType.includes("select") && value === "") {
        errorMessage = "Please select an option.";
        setFieldError(field, errorfield, errorMessage);
        return;
      }

      // ========== PRIORITY 2: FILE SIZE VALIDATION ==========
      if (validationType.includes("fileSize")) {
        const file = field[0].files[0];
        if (file && file.size > fileSize * 1024) {
          errorMessage = `File size must be less than ${fileSize}KB. Current: ${(file.size / 1024).toFixed(2)}KB`;
          setFieldError(field, errorfield, errorMessage);
          return;
        }
      }

      // ========== PRIORITY 2: FILE TYPE VALIDATION ==========
      if (validationType.includes("fileType")) {
        const file = field[0].files[0];
        if (file) {
          const fileExtension = file.name.split(".").pop().toLowerCase();
          const allowedExtensions = fileType.split(",").map((ext) => ext.trim().toLowerCase());
          if (!allowedExtensions.includes(fileExtension)) {
            errorMessage = `File type must be ${fileType.toUpperCase()}. Uploaded: .${fileExtension}`;
            setFieldError(field, errorfield, errorMessage);
            return;
          }
        }
      }

      // ========== PRIORITY 3: CHECKBOX VALIDATION (UX) ==========
      if (validationType.includes("checkbox")) {
        if (!field.is(":checked")) {
          errorMessage = "Please check this box to continue.";
          setFieldError(field, errorfield, errorMessage);
          return;
        }
      }

      // ========== PRIORITY 3: DATE VALIDATION ==========
      if (validationType.includes("date") && value !== "") {
        const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
        if (!dateRegex.test(value)) {
          errorMessage = "Please enter a valid date (YYYY-MM-DD).";
          setFieldError(field, errorfield, errorMessage);
          return;
        }
        // Check if date is in future
        if (validationType.includes("futureDate")) {
          const selectedDate = new Date(value);
          const today = new Date();
          today.setHours(0, 0, 0, 0);
          if (selectedDate <= today) {
            errorMessage = "Please select a future date.";
            setFieldError(field, errorfield, errorMessage);
            return;
          }
        }
      }

      // ========== PRIORITY 3: TIME VALIDATION ==========
      if (validationType.includes("time") && value !== "") {
        const timeRegex = /^([0-1][0-9]|2[0-3]):[0-5][0-9]$/;
        if (!timeRegex.test(value)) {
          errorMessage = "Please enter a valid time (HH:MM).";
          setFieldError(field, errorfield, errorMessage);
          return;
        }
      }

      // ========== PRIORITY 3: CREDIT CARD VALIDATION ==========
      if (validationType.includes("creditCard") && value !== "") {
        const ccRegex = /^[0-9]{13,19}$/;
        if (!ccRegex.test(value.replace(/\s/g, ""))) {
          errorMessage = "Please enter a valid credit card number (13-19 digits).";
          setFieldError(field, errorfield, errorMessage);
          return;
        }
      }

      // ========== PRIORITY 3: CUSTOM REGEX VALIDATION ==========
      if (validationType.includes("custom")) {
        const customPattern = field.data("pattern");
        if (customPattern && !new RegExp(customPattern).test(value)) {
          const customMessage = field.data("custom-message") || "Invalid format.";
          errorMessage = customMessage;
          setFieldError(field, errorfield, errorMessage);
          return;
        }
      }

      // All validations passed
      clearFieldError(field, errorfield);
    }
  }

  // ==========================================
  // HELPER FUNCTIONS
  // ==========================================

  /**
   * Set field error state and display message
   * @param {jQuery} field - The input field
   * @param {jQuery} errorfield - The error message container
   * @param {string} message - Error message to display
   */
  function setFieldError(field, errorfield, message) {
    errorfield.text(message).show();
    field.addClass("is-invalid").removeClass("is-valid");
    errorfield.addClass("small text-danger d-block");
  }

  /**
   * Clear field error state
   * @param {jQuery} field - The input field
   * @param {jQuery} errorfield - The error message container
   */
  function clearFieldError(field, errorfield) {
    errorfield.text("").hide();
    field.removeClass("is-invalid").addClass("is-valid");
  }

  /**
   * Validate entire form
   * @param {jQuery} form - The form to validate
   * @returns {boolean} - True if all fields are valid
   */
  function validateForm(form) {
    let isValid = true;
    form.find("input, textarea, select").each(function () {
      validateInput(this);
      const errorfield = $("#" + $(this).attr("name") + "_error");
      if (errorfield.text().trim() !== "") {
        isValid = false;
      }
    });
    return isValid;
  }

  // ==========================================
  // PRIORITY 3: EVENT HANDLERS (UX Enhancements)
  // ==========================================

  // Real-time validation on input/change
  $(document).on("input change", "input, textarea, select", function () {
    validateInput(this);
  });

  // Form submission validation
  $(document).on("submit", "form", function (e) {
    const form = $(this);
    if (!validateForm(form)) {
      e.preventDefault();
      // Focus on first invalid field
      const firstInvalid = form.find(".is-invalid").first();
      if (firstInvalid.length) {
        $("html, body").animate({ scrollTop: firstInvalid.offset().top - 100 }, 500);
        firstInvalid.focus();
      }
    }
  });

  // Clear error on focus
  $(document).on("focus", "input, textarea, select", function () {
    const field = $(this);
    const errorfield = $("#" + field.attr("name") + "_error");
    if (field.val().trim() === "" && field.data("validation")?.includes("required")) {
      field.removeClass("is-valid");
    }
  });

  // ==========================================
  // UTILITY FUNCTIONS FOR EXTERNAL USE
  // ==========================================

  // Expose validation functions globally
  window.FormValidator = {
    validateInput: validateInput,
    validateForm: validateForm,
    clearFieldError: clearFieldError,
    setFieldError: setFieldError,

    /**
     * Add custom validation rule
     * @param {string} name - Rule name
     * @param {function} validationFn - Validation function
     */
    addCustomRule: function (name, validationFn) {
      window.customValidationRules = window.customValidationRules || {};
      window.customValidationRules[name] = validationFn;
    },

    /**
     * Get validation status of form
     * @param {string|jQuery} formSelector - Form selector or jQuery object
     * @returns {object} - Validation status with details
     */
    getFormStatus: function (formSelector) {
      const form = typeof formSelector === "string" ? $(formSelector) : formSelector;
      const status = {
        isValid: true,
        errors: {},
        totalFields: form.find("input, textarea, select").length,
        invalidFields: 0
      };

      form.find("input, textarea, select").each(function () {
        const field = $(this);
        const name = field.attr("name");
        const errorfield = $("#" + name + "_error");
        if (errorfield.text().trim() !== "") {
          status.isValid = false;
          status.errors[name] = errorfield.text();
          status.invalidFields++;
        }
      });

      return status;
    }
  };

  console.log("✓ Form Validator Initialized - Ready for use");
});
