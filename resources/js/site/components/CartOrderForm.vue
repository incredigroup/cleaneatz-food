<template>

  <form method="POST" id="order-form" ref="form" v-bind:action="formAction">
    <input type="hidden" :value="csrfToken" name="_token" />
    <input type="hidden" :value="cardToken" name="card_token" />
    <input type="hidden" :value="clientToken" name="client_token" />
    
    <slot></slot>

    <hr class="hr-legacy" />

    <div class="row" style="padding-top: 10px">
      <div class="col-sm-5 d-none d-sm-block" style="padding-top: 11px">
        <a v-bind:href="editCartUrl"><i class="fa fa-caret-left"></i> Return to Menu</a>
      </div>
      <div class="col-sm-7">


       <span id="my-submit-buttonx"  class="btn btn-lg btn-orange w-100" style="padding: 5px; width: 100%; height: 40px; border-radius: 3px;">
            <span id="my-submit-button-text">Place Order (verify CVV)</span>
          </span>

                      <span id="my-submit-button"    class="btn btn-lg btn-orange w-100">
						                       <span id="my-submit-button-text">Place Order</span>
					</span>
        <button
          type="button"
          id="place-order"
          :disabled="submissionInProgress"
          class="btn btn-lg btn-orange w-100"
          @click="handleSubmit()"
          style="padding: 16px">
          <i class="saving-spinner fa fa-circle-o-notch fa-spin" v-if="submissionInProgress"></i>
          <span v-if="submissionInProgress">Placing Order...</span>
          <span v-else>Place Order</span>
        </button>
      </div>

      <div class="row d-block d-sm-none">
        <div class="col-sm-12" style="text-align: center; padding-top: 60px">
          <a v-bind:href="editCartUrl"><i class="fa fa-caret-left"></i> Return to Menu</a>
        </div>
      </div>
      
    </div>
  </form>

</template>

<script>
import { orderForm } from '../order-form';

export default {
  props: {
    formAction: String,
    editCartUrl: String,
    defaultEmail: String,
  },

  data() {
    return {
      csrfToken: null,
      clientToken: null,
      cardToken: null,
      tip: orderForm.tipAmount,
      orderForm: orderForm,
      submissionInProgress: false,
      email: null,
      firstName: null,
      lastName: null,
      paymentType: 'online',
    };
  },

  mounted() {
    this.csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

    this.email = this.defaultEmail;
    this.$root.$on('EMAIL_SET', (response) => {
      this.email = response.email;
    });

    this.$root.$on('FIRST_NAME_SET', (response) => {
      this.firstName = response.firstName;
    });

    this.$root.$on('LAST_NAME_SET', (response) => {
      this.lastName = response.lastName;
    });

    this.$root.$on('PAYMENT_TYPE_SET', (response) => {
      this.paymentType = response.paymentType;
    });
  },

  methods: {
    handleSubmit($event) {
      let validForm = true;

      if (!this.firstName) {
        validForm = false;
        this.$root.$emit('FIRST_NAME_INVALID');
      } else {
        this.$root.$emit('FIRST_NAME_VALID');
      }

      if (!this.lastName) {
        validForm = false;
        this.$root.$emit('LAST_NAME_INVALID');
      } else {
        this.$root.$emit('LAST_NAME_VALID');
      }

      if (!this.email) {
        validForm = false;
        this.$root.$emit('EMAIL_INVALID');
      } else if (!this.email.match(/.+\@.+\..+/)) {
        validForm = false;
        this.$root.$emit('EMAIL_INVALID');
      } else {
        this.$root.$emit('EMAIL_VALID');
      }

      if (validForm === false) {
        $('html, body').animate({ scrollTop: 0 }, 'fast');
        return;
      }

      this.submissionInProgress = true;

  
     if (this.orderForm.fields.paymentType === 'in-store') {
        this.$nextTick(() => {
          this.$refs.form.submit();
        });
        return;
      }

      // if we have a stored token, set it and submit
      if (
        this.orderForm.fields.storedCardToken &&
        this.orderForm.fields.storedCardToken !== 'new-card'
      ) {
        this.cardToken = this.orderForm.fields.storedCardToken;
        this.$nextTick(() => {
          this.$refs.form.submit();
        });
        return;
      }

      // if we have a new card, first tokenize then submit
      return this.orderForm
        .tokenizeCard()
        .then((token) => {
          this.clientToken = token;
          this.$nextTick(() => {
            this.$refs.form.submit();
          });
        })
        .catch((error) => {
          alert(error);
        })
        .finally(() => {
          this.submissionInProgress = false;
        });
    },
  },
};
</script>

 
