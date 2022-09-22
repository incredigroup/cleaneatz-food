<template>
  <div>
    <div class="row">
      <div class="col-8 col-sm-10 bold text-right">Subtotal</div>
      <div class="col-4 col-sm-2">
        <div class="pull-right bold">
          {{ subtotal | toCurrency }}
          
        </div>
      </div>
 
    </div>

    <div v-if="deliveryFee > 0" class="row">
      <div class="col-8 col-sm-10 bold text-right">Delivery Fee</div>
      <div class="col-4 col-sm-2">
        <div class="pull-right bold">
          {{ deliveryFee | toCurrency }}
        </div>
      </div>
    </div>

    <div v-if="satelliteFee > 0" class="row">
      <div class="col-8 col-sm-10 bold text-right">Satellite Pick Up Fee</div>
      <div class="col-4 col-sm-2">
        <div class="pull-right bold">
          {{ satelliteFee | toCurrency }}
        </div>
      </div>
    </div>

    <div v-if="discount > 0" class="row">
      <div class="col-8 col-sm-10 bold text-right">Coupon</div>
      <div class="col-4 col-sm-2">
        <div class="pull-right bold">-{{ discount | toCurrency }}</div>
      </div>
    </div>

    <div v-if="tip > 0" class="row">
      <div class="col-8 col-sm-10 bold text-right">Tip</div>
      <div class="col-4 col-sm-2">
        <div class="pull-right bold">
          {{ tip | toCurrency }}
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-8 col-sm-10 bold text-right">Tax</div>
      <div class="col-4 col-sm-2">
        <div class="pull-right bold">
          {{ tax | toCurrency }}
        </div>
      </div>
    </div>
    <div class="row">
      <hr />
      <div class="col-8 col-sm-10 bold text-right">Grand Total</div>
      <div class="col-4 col-sm-2">
        <div class="pull-right bold">
          {{ (total + tip) | toCurrency }}
        </div>   
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    subtotal: Number,
    deliveryFee: Number,
    satelliteFee: Number,
    discount: Number,
    tax: Number,
    total: Number,
  },

  data() {
    return {
      tip: 0,
    };
  },

  mounted() {
    this.$root.$on('TIP_SET', (response) => {
      this.tip = response.tipAmount;
    });
  },

  methods: {
    
  },
  
  


};


</script>
