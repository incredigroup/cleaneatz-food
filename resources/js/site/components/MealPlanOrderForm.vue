<template>
  <div>
    <div v-if="this.myStoreLocation.code">
      <form ref="form" method="POST" action="/order/cart">
        <input type="hidden" name="_token" v-bind:value="csrf()" />
        <input type="hidden" name="store_location" v-bind:value="this.myStoreLocation.code" />
        <input type="hidden" name="meal_plan_id" v-bind:value="this.mealPlanId" />
        <input type="hidden" name="promo_code" v-bind:value="this.promoCode" />

        <div class="container">
          <div class="all-menu-items row row-cols-1 row-cols-md-3 g-5">
            <div
              class="single-menu-item col"
              v-for="(meal, index) in JSON.parse(this.mealPlanItems)">
              <div class="menu-item text-center d-flex flex-column h-100">
                <img v-bind:src="imageUrlFor(meal)" v-bind:alt="meal.name" />
                <h5 class="bold-orange mt-3">{{ meal.name }}</h5>
                <p>
                  {{ meal.description }}
                  <span v-if="meal.is_breakfast" style="font-weight: 600">
                    REMINDER: Extra protein is the only special request option for breakfast meals.
                  </span>
                </p>

                <div class="stats flex-grow-1 mb-1">
                  <span>Calories:</span> {{ meal.calories }} <span>Fat:</span> {{ meal.fat }}g
                  <br />
                  <span>Carbs:</span> {{ meal.carbs }}g <span>Protein:</span> {{ meal.protein }}g
                  <br />
                  <span v-if="meal.points !== null">Points:</span> {{ meal.points }}
                </div>

                <div class="form-group row">
                  <label class="col-xs-12 text-uppercase">Quantity</label>
                  <div class="col-xs-12">
                    <input
                      v-bind:name="'quantity[' + meal.id + ']'"
                      v-bind:id="'meal' + index"
                      v-model="quantity[meal.id]"
                      class="meal-quant"
                      type="number"
                      max="200"
                      min="0"
                      value="0" />
                  </div>
                </div>
              </div>
            </div>
            <div
              class="col single-menu-item"
              v-for="(addOnItem, index) in JSON.parse(this.addOnItems)">
              <div class="menu-item text-center d-flex flex-column h-100">
                <img
                  class="img-responsive"
                  v-bind:src="imageUrlFor(addOnItem[0])"
                  v-bind:alt="addOnItem[0].group_name" />
                <h5 class="bold-orange mt-3">
                  {{ addOnItem[0].name }}
                </h5>

                <p>${{ addOnItem[0].price_override.toFixed(2) }} each</p>

                <template v-if="addOnItem.length > 1">
                  <div class="flex-grow-1">
                    <p>{{ addOnItem[0].group_desc }}</p>
                  </div>

                  <div class="row">
                    <div class="col-6 text-uppercase">Flavor</div>
                    <div class="col-6 text-uppercase">Quantity</div>
                  </div>
                  <div
                    class="row mt-1"
                    v-for="(addOnItemOption, addOnItemOptionIndex) in addOnItem">
                    <div class="col-6">
                      {{ addOnItemOption.description }}
                    </div>
                    <div class="col-6">
                      <input
                        v-bind:name="'addOnQuantity[' + addOnItemOption.id + ']'"
                        v-bind:id="'addOnItem' + addOnItemOptionIndex"
                        v-model="quantity[addOnItemOption.id]"
                        class="meal-quant"
                        type="number"
                        max="200"
                        min="0"
                        value="0" />
                    </div>
                  </div>
                </template>
                <!-- Add on item with no variants -->
                <template v-if="addOnItem.length === 1">
                  <div class="col-sm-12 flex-grow-1">
                    <p>{{ addOnItem[0].description }}</p>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-12 text-uppercase">Quantity</label>
                    <div class="col-sm-12">
                      <input
                        v-bind:name="'addOnQuantity[' + addOnItem[0].id + ']'"
                        v-bind:id="'addOnItem' + addOnItem[0].id"
                        v-model="quantity[addOnItem[0].id]"
                        class="meal-quant"
                        type="number"
                        max="200"
                        min="0"
                        value="0" />
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-color-light-gray mt-5 py-4">
          <div class="container">
            <div class="row px-3 px-md-0">
              <div class="col-sm-12 col-md-6">
                <h5 class="bold-orange">Special Requests</h5>
                <h6>Meal Customizations - Select All That Apply</h6>

                <div class="form-group">
                  <div>
                    <label>
                      <input
                        class="meal-add-on"
                        v-model="lowCarb"
                        name="LowCarb"
                        :disabled="disableCustomizations()"
                        type="checkbox" />
                      Low Carb (extra veg), Tuesday Pickup
                    </label>
                  </div>

                  <div>
                    <label>
                      <input
                        class="meal-add-on"
                        v-model="extraProtein"
                        name="ExtraProtein"
                        :disabled="disableCustomizations()"
                        type="checkbox" />
                      Extra Protein (+ $1.50 per meal), Tuesday Pickup
                    </label>
                  </div>
                </div>
                <p class="mt-2 fst-italic mb-2">
                  ** These changes will apply to all meals. The exception is with breakfast meals,
                  which can only be regular or extra protein.
                </p>
                <p class="mt-2 fst-italic mb-0">** Only available with in store pick up</p>
              </div>

              <div class="col-sm-12 col-md-6 mt-3 mt-md-0">
                <h5 v-if="deliveryAvailable" class="bold-orange">Pick up or Delivery</h5>
                <h5 v-if="!deliveryAvailable" class="bold-orange">Pick up Locations</h5>

                <div class="form-group">
                  <div id="how-to-get">
                    <label class="d-flex">
                      <input
                        v-model="pickUpOption"
                        v-on:change="pickUpChanged()"
                        checked="checked"
                        name="pick_up_options"
                        type="radio"
                        value="pick-up" />
                      <h6 class="ms-2">Pick up in store</h6>
                    </label>
                    <label v-if="satellitePickupAvailable" class="d-flex">
                      <input
                        v-model="pickUpOption"
                        v-on:change="pickUpChanged()"
                        name="pick_up_options"
                        type="radio"
                        value="satellite" />
                      <h6 class="ms-2">Satellite pick up</h6>
                    </label>
                    <label v-if="deliveryAvailable" class="d-flex">
                      <input
                        v-model="pickUpOption"
                        v-on:change="pickUpChanged()"
                        name="pick_up_options"
                        type="radio"
                        value="delivery" />
                      <h6 class="ms-2">Local Delivery</h6>
                    </label>
                  </div>
                </div>

                <div v-if="pickUpOption === 'satellite'">
                  <div id="satellite-results">
                    <select
                      class="form-select"
                      v-model="satellite"
                      name="satellite"
                      title="Choose from the following...">
                      <option v-for="item in myStoreLocation.available_satellites" :value="item.id">
                        {{ item.name }}
                      </option>
                    </select>
                  </div>
                  <p class="fst-italic mb-2">** Must pay online. Convenience fee will be added.</p>
                  <p class="fst-italic mb-0">** Unavailable with meal customizations</p>
                </div>

                <div v-if="pickUpOption === 'delivery'">
                  <div class="form-horizontal">
                    <div class="form-group">
                      <div v-bind:class="{ 'has-error': fieldErrors['phone'] }">
                        <label for="address" class="col-sm-2 control-label">Phone</label>
                        <div class="col-sm-7">
                          <input
                            type="text"
                            name="phone"
                            v-model="delivery['phone']"
                            class="form-control"
                            id="phone"
                            placeholder="Phone" />
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div v-bind:class="{ 'has-error': fieldErrors['address'] }">
                        <label for="address" class="col-sm-2 control-label">Address</label>
                        <div class="col-sm-7">
                          <input
                            type="text"
                            name="address"
                            v-model="delivery['address']"
                            class="form-control"
                            id="address"
                            placeholder="Street Address" />
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <input
                          type="text"
                          name="apt"
                          v-model="delivery['apt']"
                          class="form-control"
                          placeholder="Apt" />
                      </div>
                    </div>
                    <div class="form-group" v-bind:class="{ 'has-error': fieldErrors['city'] }">
                      <label for="city" class="col-sm-2 control-label">City</label>
                      <div class="col-sm-10">
                        <input
                          type="text"
                          name="city"
                          v-model="delivery['city']"
                          class="form-control"
                          id="city"
                          placeholder="City" />
                      </div>
                    </div>
                    <div class="form-group">
                      <div v-bind:class="{ 'has-error': fieldErrors['state'] }">
                        <label for="state" class="col-sm-2 control-label">State/Zip</label>
                        <div class="col-sm-7">
                          <input
                            type="text"
                            name="state"
                            v-model="delivery['state']"
                            class="form-control"
                            id="state"
                            placeholder="State" />
                        </div>
                      </div>
                      <div class="col-sm-3" v-bind:class="{ 'has-error': fieldErrors['zip'] }">
                        <input
                          type="text"
                          name="zip"
                          v-model="delivery['zip']"
                          class="form-control"
                          id="zip"
                          placeholder="Zip" />
                      </div>
                    </div>
                  </div>
                  <p>
                    **<em>Must pay online. $6.99 delivery fee will be added.</em><br />
                    **<em>Meals delivered on Monday starting at 3pm.</em><br />
                    **<em>Unavailable with meal customizations</em>
                  </p>
                </div>

                <div class="text-center text-md-start">
                  <a
                    class="btn btn-gray btn-round-lg btn-continue mt-4"
                    href="javascript:;"
                    v-on:click="onSubmit()"
                    id="place-order"
                    title="Select Payment Mode">
                    <span v-if="!isVerifyingDelivery">Continue</span>
                    <span v-if="isVerifyingDelivery">Verifying Delivery Address...</span>
                  </a>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12 continue-to-cart"></div>
            </div>

            <div class="row" v-if="!closeEnoughForDelivery">
              <div class="col-sm-12">
                <p class="text-danger text-center">
                  <strong
                    >Unfortunately, the address entered out of range for delivery, please choose
                    different address or different pick up method.</strong
                  >
                </p>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div v-if="showShipOption" class="col-sm-12 py-4 bg-color-light-gray text-white">
      <div class="container bg-color-black text-center py-4">
        <h3 class="text-champion mb-4">Meals Shipped</h3>
        <div>
          <img v-bind:src="`${assetPath}img/icons/shipping-truck.png`" style="max-width: 150px" />
        </div>

        <div>If there's no Clean Eatz near you, get meals shipped.</div>

        <a
          href="https://cleaneatzkitchen.com/collections/meal-plans"
          class="btn btn-round btn-white mt-4"
          >Get Meals Shipped</a
        >
      </div>
    </div>
  </div>
</template>

<script>
import Cleaneatz from '../cleaneatz';
import geolocator from 'geolocator';

export default {
  props: {
    assetPath: '',
    mealPlanId: '',
    mealPlanItems: '',
    addOnItems: '',
    formData: { type: String },
  },
  data() {
    return {
      cleaneatz: new Cleaneatz(),
      myStoreLocation: {},
      quantity: {},
      lowCarb: false,
      extraProtein: false,
      pickUpOption: 'pick-up',
      deliveryAvailable: false,
      showShipOption: false,
      satellitePickupAvailable: false,
      satellite: {},
      isVerifyingDelivery: false,
      closeEnoughForDelivery: true,
      fieldErrors: {},
      delivery: {},
      promoCode: null,
    };
  },
  mounted() {
    this.$root.$on('LOCAL_STORE_LOCATION_SET', (response) => {
      var storeLocation = response.storeLocation;
      if (storeLocation === false) {
        this.myStoreLocation = {};
        this.deliveryAvailable = false;
        this.satellitePickupAvailable = false;
        this.showShipOption = response.message !== 'UNABLE_TO_LOCATE';
        return;
      }

      this.showShipOption = false;
      this.myStoreLocation = storeLocation;
      this.deliveryAvailable = Boolean(storeLocation.delivers);
      this.satellitePickupAvailable = storeLocation.available_satellites.length > 0;
      // default the first satellite
      if (this.satellitePickupAvailable) {
        this.satellite = storeLocation.available_satellites[0].id;
      }
    });
  },
  created() {
    var formData = JSON.parse(this.formData);

    // we are not editing the form
    if (Object.keys(formData).length === 0) {
      return;
    }

    this.quantity = formData.quantity;
    this.promoCode = formData.promoCode;

    this.lowCarb = formData.specialRequests.lowCarb;
    this.extraProtein = formData.specialRequests.extraProtein;

    if (formData.delivery) {
      this.pickUpOption = 'delivery';
      this.delivery = formData.deliveryAddress;
    } else if (formData.satellite) {
      this.pickUpOption = 'satellite';
      this.satellite = formData.satellite;
    }
  },
  computed: {},
  methods: {
    asset(uri) {
      return `${this.assetPath}img/meals/${uri}`;
    },

    onSubmit() {
      if (this.pickUpOption === 'delivery') {
        if (this.validateAddressFields() === false) {
          return;
        }

        this.isVerifyingDelivery = true;

        this.hasDeliverableAddress()
          .then((isCloseEnough) => {
            this.closeEnoughForDelivery = isCloseEnough;

            if (isCloseEnough) {
              this.submitForm();
            }
          })
          .finally(() => (this.isVerifyingDelivery = false));
      } else {
        this.submitForm();
      }
    },

    submitForm() {
      if (Object.values(this.quantity).find((quantity) => Number(quantity) > 0) === undefined) {
        alert('Please enter a quantity of at least one item to order');
        return;
      }

      this.$refs.form.submit();
    },

    async hasDeliverableAddress() {
      const address =
        this.delivery['address'] +
        ' ' +
        this.delivery['apt'] +
        ' ' +
        this.delivery['city'] +
        ' ' +
        this.delivery['state'] +
        ' ' +
        this.delivery['zip'];

      try {
        const isCloseEnough = await this.cleaneatz.closeEnoughForDelivery(
          address,
          this.myStoreLocation
        );
        return isCloseEnough;
      } catch (error) {
        console.error(error);
        return false;
      }
    },

    imageUrlFor(meal) {
      return meal.image_url ? this.asset(meal.image_url) : '//via.placeholder.com/1000x667';
    },

    pickUpChanged() {
      if (this.pickUpOption !== 'pick-up') {
        this.lowCarb = false;
        this.extraProtein = false;
      }
    },

    disableCustomizations() {
      return this.pickUpOption !== 'pick-up';
    },

    csrf() {
      return document.head.querySelector('meta[name="csrf-token"]').content;
    },

    validateAddressFields() {
      this.fieldErrors = {};

      ['address', 'city', 'state', 'zip', 'phone'].forEach((field) => {
        this.fieldErrors[field] =
          this.delivery[field] === undefined || this.delivery[field].length < 1;
      });

      return Object.values(this.fieldErrors).every((error) => error === false);
    },
  },
};
</script>
