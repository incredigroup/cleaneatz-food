<template>
  <div class="order-options-row row text-white">
    <div
      v-if="hasLocalStore && cafeOrderingEnabled"
      id="cafe-order"
      v-bind:class="cardCSSClass"
      class="order-card">
      <h5 class="text-champion">Cafe Order</h5>
      <div class="order-icon">
        <img v-bind:src="this.asset('/img/icons/cafe-icon.png')" />
      </div>
      <div v-if="this.isExpressStore">Order Grab & Go for pick up</div>
      <div v-else>Order lunch or dinner online for pick up</div>
      <a
        v-if="this.cafeOrderUrl"
        target="_blank"
        rel="noopener noreferrer"
        v-bind:href="this.cafeOrderUrl"
        class="order-card-button"
        >Place a Cafe Order</a
      >
      <a v-if="!this.cafeOrderUrl" class="order-card-button">Online Cafe Ordering Not Available</a>
    </div>

    <div
      v-if="hasLocalStore && mealPlanOrderingEnabled"
      id="mealplan-order"
      v-bind:class="cardCSSClass"
      class="order-card">
      <h5 class="text-champion">Meal Plan</h5>
      <div class="order-icon">
        <img v-bind:src="this.asset('img/icons/calendar-icon.png')" />
      </div>
      <div>Order meal plans from your local cafe</div>
      <a href="/mealplanmenu" class="order-card-button">Place a Meal Plan Order</a>
    </div>

    <div v-if="hasLocalStore" id="catering-order" v-bind:class="cardCSSClass" class="order-card">
      <h5 class="text-champion">Catering</h5>
      <div class="order-icon">
        <img v-bind:src="this.asset('img/icons/plate-and-utensils-icon.png')" />
      </div>
      <div>We cater for groups from 10 people to 500</div>
      <a href="https://order.catering/clean25" class="order-card-button">Place a Catering Order</a>
    </div>

    <div v-if="showShipOption" id="shipped-order" class="col-sm-12 order-card">
      <h5>Meals Shipped</h5>
      <div class="order-icon">
        <img v-bind:src="this.asset('img/icons/shipping-truck.png')" style="max-width: 150px" />
      </div>
      <div>If there's no Clean Eatz near you, get meals shipped.</div>
      <a href="https://cleaneatzkitchen.com/collections/meal-plans" class="order-card-button"
        >Get Meals Shipped</a
      >
    </div>
  </div>
</template>

<script>
import Cleaneatz from '../cleaneatz';

export default {
  props: {
    assetPath: '',
  },

  data() {
    return {
      hasLocalStore: true,
      showShipOption: false,
      mealPlanOrderingEnabled: true,
      cafeOrderingEnabled: true,
      isCafeStore: false,
      orderingOptions: 3,
      cardCSSClass: 'col-sm-4',
      cafeOrderUrl: '',
      isExpressStore: false,
    };
  },

  mounted() {
    this.$root.$on('LOCAL_STORE_LOCATION_SET', (response) => {
      var storeLocation = response.storeLocation;
      this.orderingOptions = 3;

      if (storeLocation) {
        this.showShipOption = false;
        this.hasLocalStore = true;
        this.cafeOrderUrl = storeLocation.cafe_order_url;
        this.mealPlanOrderingEnabled = Boolean(storeLocation.is_meal_plan_ordering_enabled);
        this.cafeOrderingEnabled = Boolean(storeLocation.is_cafe_ordering_enabled);
        this.isExpressStore = storeLocation.store_type === 'Express';

        if (this.mealPlanOrderingEnabled === false) {
          this.orderingOptions = this.orderingOptions - 1;
        }

        if (this.cafeOrderingEnabled === false) {
          this.orderingOptions = this.orderingOptions - 1;
        }

        this.cardCSSClass = `col-sm-${12 / this.orderingOptions}`;
      } else {
        this.hasLocalStore = false;
        this.showShipOption = response.message !== 'UNABLE_TO_LOCATE';
      }
    });
  },

  methods: {
    asset(uri) {
      return `${this.assetPath}${uri}`;
    },
  },
};
</script>
