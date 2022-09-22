<template>
  <div>
    <div class="row" v-if="accountExists">
      <div class="col-sm-12">
        <div class="alert alert-warning" role="alert">
          An account exists with this email address. Use the button above to Login before placing
          your order to speed up the check out process!
        </div>
      </div>
    </div>
    <h6>Order Information</h6>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group mb-2" v-bind:class="{ 'has-error': invalidFirstName }">
          <input
            type="text"
            required
            name="first_name"
            v-model="firstName"
            @input="firstNameSet()"
            placeholder="First Name"
            class="form-control" />
          <span v-if="invalidFirstName" class="help-block">First name is required.</span>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group mb-2" v-bind:class="{ 'has-error': invalidLastName }">
          <input
            type="text"
            required
            name="last_name"
            @input="lastNameSet()"
            v-model="lastName"
            placeholder="Last Name"
            class="form-control" />
          <span v-if="invalidLastName" class="help-block">Last name is required.</span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="form-group mb-2" v-bind:class="{ 'has-error': invalidEmail }">
          <input
            type="email"
            required
            name="email"
            v-model="email"
            @input="emailSet()"
            @blur="emailCheck()"
            placeholder="Email"
            class="form-control" />
          <span v-if="invalidEmail" class="help-block">Valid email is required.</span>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="chkNews" checked="checked" />
          <label class="form-check-label" for="chkNews"> Receive email updates about orders </label>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    defaultEmail: String,
    defaultFirstName: String,
    defaultLastName: String,
    isLoggedIn: Boolean,
    formErrors: [Object, Array],
  },

  data() {
    return {
      email: null,
      firstName: null,
      lastName: null,
      invalidFirstName: false,
      invalidLastName: false,
      invalidEmail: false,
      accountExists: false,
    };
  },

  mounted() {
    
    this.email = this.defaultEmail;
    this.firstName = this.defaultFirstName;
    this.lastName = this.defaultLastName;

    // make sure the parent container is listening
    // for events
    setTimeout(() => {
      this.emailSet();
      this.firstNameSet();
      this.lastNameSet();
    }, 500);

    this.$root.$on('FIRST_NAME_INVALID', () => {
      this.invalidFirstName = true;
    });

    this.$root.$on('LAST_NAME_INVALID', () => {
      this.invalidLastName = true;
    });

    this.$root.$on('EMAIL_INVALID', () => {
      this.invalidEmail = true;
    });

    this.$root.$on('FIRST_NAME_VALID', () => {
      this.invalidFirstName = false;
    });

    this.$root.$on('LAST_NAME_VALID', () => {
      this.invalidLastName = false;
    });

    this.$root.$on('EMAIL_VALID', () => {
      this.invalidEmail = false;
    });
  },

  methods: {
    emailSet() {
      this.$root.$emit('EMAIL_SET', { email: this.email });
      if (this.email) {
        this.invalidEmail = false;
      }
    },
    emailCheck() {
      axios.get('/api/user/exists', { params: { email: this.email } }).then((res) => {
        this.accountExists = Boolean(res.data);
      });
    },
    firstNameSet() {
      this.$root.$emit('FIRST_NAME_SET', { firstName: this.firstName });
      if (this.firstName) {
        this.invalidFirstName = false;
      }
    },
    lastNameSet() {
      this.$root.$emit('LAST_NAME_SET', { lastName: this.lastName });
      if (this.lastName) {
        this.invalidLastName = false;
      }
    },
  },
};
</script>
