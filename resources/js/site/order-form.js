export const config = {
  fields: {
    card: {
      selector: '[data-cc-card]',
    },
    cvv: {
      selector: '[data-cc-cvv]',
    },
    exp: {
      selector: '[data-cc-exp]',
    },
    name: {
      selector: '[data-cc-name]',
      placeholder: 'Full Name',
    },
  },

  styles: {
    input: {
      'font-family': '"Open Sans", sans-serif',
      'font-size': '14px',
      color: '#00a9e0',
      background: 'black',
    },
    '.card': {
      'font-family': 'monospace',
    },
    ':focus': {
      color: '#00a9e0',
    },
    '.valid': {
      color: '#43B02A',
    },
    '.invalid': {
      color: '#C01324',
    },
    '@media screen and (max-width: 700px)': {
      input: {
        'font-size': '14px',
      },
    },
    'input:-webkit-autofill': {
      '-webkit-box-shadow': '0 0 0 50px white inset',
    },
    'input:focus:-webkit-autofill': {
      '-webkit-text-fill-color': '#00a9e0',
    },
    'input.valid:-webkit-autofill': {
      '-webkit-text-fill-color': '#43B02A',
    },
    'input.invalid:-webkit-autofill': {
      '-webkit-text-fill-color': '#C01324',
    },
    'input::placeholder': {
      color: '#bbb',
    },
  },

  classes: {
    empty: 'empty',
    focus: 'focus',
    invalid: 'invalid',
    valid: 'valid',
  },
};

export const DomUtils = {
  getEl: (selector) => window.document.querySelector(selector),

  hasClass: (el, cssClass) => {
    if (el.classList) {
      return el.classList.contains(cssClass);
    }
  },

  removeClass: (el, cssClass) => {
    if (el.classList) {
      el.classList.remove(cssClass);
    } else if (DomUtils.hasClass(el, cssClass)) {
      const reg = new RegExp(`(\\s|^)${cssClass}(\\s|$)`);
      el.className = el.className.replace(reg, ' ');
    }
  },
};

export const orderForm = {
  fields: {
    email: null,
    paymentType: 'online',
    clientToken: null,
    tipAmount: 0,
    storedCardToken: null,
  },

  paymentForm: false,

  initCCForm(storeLocationId) {
    if (this.fields.paymentType !== 'online') {
      return;
    }

    const preFlowHook = (callback) => {
      this.authorizeSession(callback, storeLocationId);
    };

    const onFormInit = (paymentForm) => {
      const ccFields = window.document.getElementsByClassName('payment-fields');

      for (let i = 0; i < ccFields.length; i++) {
        DomUtils.removeClass(ccFields[i], 'disabled');
      }

      this.paymentForm = paymentForm;
    };

    window.firstdata.createPaymentForm(config, { preFlowHook }, onFormInit);
  },

  authorizeSession(callback, storeLocationId) {
    let request = new XMLHttpRequest();
    request.onload = () => {
      if (request.status >= 200 && request.status < 300) {
        // values come from authorize-session endpoint
        callback(JSON.parse(request.responseText));
      } else {
        throw new Error('error response: ' + request.responseText);
      }
      request = null;
    };

    request.open('POST', '/paymentjs/authorize-session/' + storeLocationId, true);
    request.send();
  },

  tokenizeCard() {
    if (this.paymentForm === false) {
      console.error('Payment form not initizlied.  Call initCCForm first');
    }

    return new Promise((resolve, reject) => {
      this.paymentForm.onSubmit(resolve, reject);
    });
  },
};
