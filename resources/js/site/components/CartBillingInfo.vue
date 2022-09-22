<template>
  <div>
    <h6>Billing Information</h6>
    <div v-if="satellite">
      <div class="row mb-2">
        <div class="col-md-12">
          <strong>Satellite pickup:</strong><br />
          {{ satellite.name }} <br />
          {{ satellite.address }} <br />
          {{ satellite.city }},
          {{ satellite.state }}
          {{ satellite.zip }}
        </div>
      </div>
    </div>

    <!-- payment options -->
    <div v-if="onlinePaymentsOnly">
      <input
        type="hidden" 
        name="payment_type"
        v-model="orderFormFields.paymentType"
        value="online" />
    </div>
    <div v-else-if="inStorePaymentsOnly">
      <div class="row mb-2">
        <div class="col-md-6">
          <label class="radio-inline">
            <input
              type="radio"
              name="payment_type"
              id="paymentOptionInStore"
              v-model="orderFormFields.paymentType"
              value="in-store"
              checked="selected" />
            Pay in store
          </label>
        </div>
      </div>
    </div>
    <div v-else>
      <div class="row mb-3">
        <div class="col-md-12">
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="payment_type"
              id="paymentOptionOnline"
           @click="btnadd()"
              @change="paymentTypeChanged()"
              v-model="orderFormFields.paymentType"
              value="online"
              checked="selected" />
            <label class="radio-inline" for="paymentOptionOnline">Pay online</label>
          </div>

          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="payment_type"
               @click="btnremove()"
              @change="paymentTypeChanged()"
              id="paymentOptionInStore"
              v-model="orderFormFields.paymentType"
              value="in-store" />
            <label class="form-check-label" for="paymentOptionInStore">Pay in store</label>
          </div>
        </div>
      </div>
    </div>

    <!-- payment info -->
    <div id="credit-card-form" v-if="orderFormFields.paymentType === 'online'">
               <support-total-amount></support-total-amount>

<!-- 
      <main>
        <div id="myForm" style="padding:5px; height: auto; width: 520px">
          <iframe id="pay-form"></iframe>
        </div>
      </main> -->
      
    </div>
    <div v-if="orderFormFields.paymentType === 'online'">
      <div class="col-md-12 mb-2">
        <hr class="hr-legacy" />
      </div>
      <div class="col-md-6 mb-3">
        <label>Would you like to add a tip?</label>
      </div>
      <div class="col-md-6">
        <div class="input-group">
          <div class="input-group-text">$</div>
          <input
            type="number"
            step="0.01"
            name="tip_amount"
            class="form-control"
            @change="updateTip"
            v-model="orderFormFields.tipAmount" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { orderForm } from '../order-form';

export default {
  props: {
    formErrors: [Object, Array],
    defaultPaymentType: String,
    storeLocationId: Number,
    satellite: Object,
    onlinePaymentsOnly: Boolean,
    inStorePaymentsOnly: Boolean,
    isLoggedIn: Boolean,
    storedPaymentMethods: Array,
    defaultName: String,
  },

  data() {
    return {
      orderFormFields: orderForm.fields,
      selectedCard: null,
    };
  },

  mounted() {
    this.btnadd(),
    this.orderFormFields.paymentType = this.defaultPaymentType;
    this.orderFormFields.name = this.defaultName;

    if (this.inStorePaymentsOnly) {
      this.orderFormFields.paymentType = 'in-store';
    }

    if (this.hasStoredPaymentMethods()) {
      this.selectedCard = this.storedPaymentMethods[0].card_token;
      this.updateSelectedCard();
    }

    if (this.showCCForm()) {
      this.initCCForm();
    }

      
  },

  methods: {

    btnadd(){
      
        document.getElementById("my-submit-button").style.display = "block";
         document.getElementById("place-order").style.display = "none";
        console.log('this add')
    },


    btnremove(){
    
           document.getElementById("place-order").style.display = "block";
 document.getElementById("my-submit-button").style.display = "none";
 

      },

    hasError(field) {
      return this.formErrors[field] !== undefined;
    },

    
    showCCForm() {
      if (this.hasStoredPaymentMethods() && this.selectedCard !== 'new-card') {
        return false;
      }

      return true;
    },

    hasStoredPaymentMethods() {
      return this.storedPaymentMethods.length > 0;
    },

    initCCForm() {
      this.$nextTick(function () {
        orderForm.initCCForm(this.storeLocationId);
      });
    },

    paymentTypeChanged() {
      this.initCCForm();
   
      const paymentType = this.orderFormFields.paymentType;
      this.$root.$emit('PAYMENT_TYPE_SET', { paymentType });

      if (paymentType !== 'online') {
        const tipAmount = 0;
        this.orderFormFields.tipAmount = tipAmount.toFixed(2);
        this.$root.$emit('TIP_SET', { tipAmount });
   
      }

      
    },

    updateSelectedCard() {
      if (this.selectedCard === 'new-card') {
        this.orderFormFields.storedCardToken = null;
        this.initCCForm();
      }

      this.orderFormFields.storedCardToken = this.selectedCard;
    },

    getError(field) {
      return this.formErrors[field][0];
    },

    updateTip(event) {
      let tipAmount = parseFloat(event.target.value.replace(/[^\d.-]/g, ''));

      if (isNaN(tipAmount) || tipAmount < 0) {
        tipAmount = 0;
      }

      this.orderFormFields.tipAmount = tipAmount.toFixed(2);
      this.$root.$emit('TIP_SET', { tipAmount });
    },
  },
};
</script>
 <style scoped>
 iframe#pay-form {
    width: 100%;
    height: 317px;
}



</style>