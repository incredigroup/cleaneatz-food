function isValidEmail(email) {
  var re = /\S+@\S+\.\S+/;
  return re.test(email);
}

function validateEmail() {
  const $input = $('input[name=EMAIL]');
  const $formGroup = $input.parent();

  $formGroup.removeClass('has-error');
  $formGroup.find('.help-block').remove();

  if ($input.val().length === 0) {
    $formGroup.addClass('has-error');
    const errorSpan = $('<span>').addClass('help-block').text('Email address is required');
    $formGroup.append(errorSpan);
    return false;
  }

  if (isValidEmail($input.val()) === false) {
    $formGroup.addClass('has-error');
    const errorSpan = $('<span>').addClass('help-block').text('Please enter a valid email address');
    $formGroup.append(errorSpan);
    return false;
  }

  return true;
}

function validateStoreSelection() {
  const $input = $('select[name="LOCATION_CODE"]');

  // no location select, everything is valid
  if ($input.length === 0) {
    return true;
  }

  const $formGroup = $input.parent();

  $formGroup.removeClass('has-error');
  $formGroup.find('.help-block').remove();

  if ($input.val().length === 0) {
    $formGroup.addClass('has-error');
    const errorSpan = $('<span>').addClass('help-block').text('Store location is required');
    $formGroup.append(errorSpan);
    return false;
  }

  return true;
}

$('input[name=EMAIL]').on('blur', validateEmail);
const form = '#newsletter-subscribe';

function onSubmit(token) {
  $(form).submit();
}

$(form).submit((e) => {
  e.preventDefault();

  const hasValidEmail = validateEmail();
  const hasValidStoreSelection = validateStoreSelection();

  if (hasValidEmail === false || hasValidStoreSelection === false) {
    return;
  }

  // in WP the subscribe button is an input
  let submitButton = $('input[name=subscribe]');
  if (submitButton.length === 0) {
    // in Laravel the subscribe button is an input
    submitButton = $('button[name=subscribe]');
  }

  submitButton.prop('disabled', true);
  submitButton.css('display', 'none');

  // post form data
  const data = $(form).serialize();
  let postUrl = '/api/newsletter/register';

  if ($('input[name=postDomain]').val()) {
    postUrl = $('input[name=postDomain]').val() + postUrl;
  }

  $.post(postUrl, data, function (response) {
    const $alert = $('<div class="alert" role="alert">');
    let messageText = 'Successfully Subscribed!';
    let messageClass = 'alert-success';

    if (response.status === 201) {
      messageText =
        'Because you previously unsubscribed from our meal plan emails, you are being sent an email confirming your intent to re-subscribe. Once confirmed, you will be re-added to the list.';
    }

    if (response.success !== true) {
      if (response.status === 400) {
        messageText =
          'This email address has either unsubscribed, bounced, or is in compliance review and cannot be subscribed. Please reach out to your local cafe to get added back to the list.';
      } else {
        messageText = 'Unable to subscribe. Please try again later';
      }

      messageClass = 'alert-danger';
    }

    $alert.text(messageText).addClass(messageClass);
    $(form).replaceWith($alert);
  });
});

$(function () {
  const storedLocation = JSON.parse(localStorage.getItem('kf92afdla'));
  if (storedLocation !== null) {
    $('select[name="LOCATION_CODE"]').val(storedLocation.code);
  }
});
