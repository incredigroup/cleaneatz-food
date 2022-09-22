<template>
  <div>
    <hr style="margin-top: 20px" />
    <form ref="form" method="POST" action="/order/custom-cart">
      <input type="hidden" name="_token" v-bind:value="csrf()" />
      <input type="hidden" name="store_location" v-bind:value="myStoreLocation.code" />
      <input type="hidden" name="meals" v-bind:value="JSON.stringify(meals)" />
      <div class="form-group">
        <label>Protein</label>
        <select
          v-model="selectedProtein"
          v-on:change="calculatePrice()"
          class="form-control meal-option"
        >
          <option v-for="(protein, index) in proteins" v-bind:value="index">
            {{ protein.label }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>Protein Portion</label>
        <select
          v-model="selectedProteinPortion"
          v-on:change="calculatePrice()"
          class="form-control meal-option"
        >
          <option v-for="(proteinPortion, index) in proteinPortions" v-bind:value="index">
            {{ proteinPortion.label }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>Carbohydrate</label>
        <select
          v-model="selectedCarb"
          v-on:change="calculatePrice()"
          class="form-control meal-option"
        >
          <option v-for="(carb, index) in carbs" v-bind:value="index">
            {{ carb.label }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>Carbohydrate Portion</label>
        <select
          v-model="selectedCarbPortion"
          v-on:change="calculatePrice()"
          class="form-control meal-option"
        >
          <option v-for="(carbPortion, index) in carbPortions" v-bind:value="index">
            {{ carbPortion.label }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>Vegetables</label>
        <select
          v-model="selectedVegetable"
          v-on:change="calculatePrice()"
          class="form-control meal-option"
        >
          <option v-for="(vegetable, index) in vegetables" v-bind:value="index">
            {{ vegetable.label }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>Vegetables 2</label>
        <select
          v-model="selectedVegetable2"
          v-on:change="calculatePrice()"
          class="form-control meal-option"
        >
          <option v-for="(vegetable, index) in vegetables2" v-bind:value="index">
            {{ vegetable.label }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>Vegetables 3</label>
        <select
          v-model="selectedVegetable3"
          v-on:change="calculatePrice()"
          class="form-control meal-option"
        >
          <option v-for="(vegetable, index) in vegetables3" v-bind:value="index">
            {{ vegetable.label }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>Sauce</label>
        <select
          v-model="selectedSauce"
          v-on:change="calculatePrice()"
          class="form-control meal-option"
        >
          <option v-for="(sauce, index) in sauces" v-bind:value="index">
            {{ sauce.label }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>Comments</label>
        <textarea v-model="comments" class="form-control meal-option"></textarea>
      </div>

      <div class="form-group">
        <label>Quantity</label>
        <input
          v-model="qty"
          v-on:change="calculatePrice()"
          min="1"
          name="qty"
          type="number"
          value="1"
        />
      </div>

      <div class="form-group">
        <button
          type="button"
          class="btn btn-primary"
          v-on:click="addMeal()"
          id="add-to-cart"
          :disabled="Number(qty) < 1"
        >
          Add Meal
        </button>
      </div>

      <div class="form-group">
        <p>**<em>Requires Pre-Payment</em></p>
        <p>Pick Up Meals <strong>Tuesday or Wednesday</strong></p>
      </div>

      <div id="price-container">
        <h4>Price: ${{ price.toFixed(2) }}</h4>
      </div>

      <div>
        <p style="font-size: 20px; font-style: italic">
          Meals in Queue: <span id="meal-queue">{{ meals.length }}</span>
        </p>
      </div>

      <div>
        <button class="btn btn-success" type="submit" :disabled="this.meals.length === 0">
          Go to Cart
        </button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    proteins: { type: Array },
    proteinPortions: { type: Array },
    carbs: { type: Array },
    carbPortions: { type: Array },
    vegetables: { type: Array },
    vegetables2: { type: Array },
    vegetables3: { type: Array },
    sauces: { type: Array },
    basePrice: { type: Number },
  },
  data() {
    return {
      myStoreLocation: {},
      selectedProtein: 0,
      selectedProteinPortion: 0,
      selectedCarb: 0,
      selectedCarbPortion: 0,
      selectedVegetable: 0,
      selectedVegetable2: 0,
      selectedVegetable3: 0,
      selectedSauce: 0,
      price: 0,
      qty: 1,
      comments: '',
      meals: [],
    };
  },
  mounted() {
    this.$root.$on('LOCAL_STORE_LOCATION_SET', (response) => {
      this.myStoreLocation = response.storeLocation;
    });

    this.calculatePrice();
  },
  methods: {
    calculatePrice() {
      if (Number(this.qty) < 0) {
        this.qty = 1;
      }

      const ingredientsPrice = Object.values(this.getSelections()).reduce(
        (total, currentValue) => total + currentValue.cost,
        this.basePrice
      );

      this.price = ingredientsPrice * this.qty;
    },

    addMeal() {
      this.meals.push({
        qty: this.qty,
        ingredients: this.getSelections(),
        comments: this.comments,
      });

      this.selectedProtein = 0;
      this.selectedProteinPortion = 0;
      this.selectedCarb = 0;
      this.selectedCarbPortion = 0;
      this.selectedVegetable = 0;
      this.selectedVegetable2 = 0;
      this.selectedVegetable3 = 0;
      this.selectedSauce = 0;
      this.qty = 1;
      this.comments = '';

      setTimeout(() => {
        alert('Custom meal added!');
      }, 10);
    },

    csrf() {
      return document.head.querySelector('meta[name="csrf-token"]').content;
    },

    getSelections() {
      const protein = this.proteins[this.selectedProtein];
      const proteinPortion = this.proteinPortions[this.selectedProteinPortion];
      const carb = this.carbs[this.selectedCarb];
      const carbPortion = this.carbPortions[this.selectedCarbPortion];
      const vegetable = this.vegetables[this.selectedVegetable];
      const vegetable2 = this.vegetables2[this.selectedVegetable2];
      const vegetable3 = this.vegetables3[this.selectedVegetable3];
      const sauce = this.sauces[this.selectedSauce];

      return {
        protein,
        proteinPortion,
        carb,
        carbPortion,
        vegetable,
        vegetable2,
        vegetable3,
        sauce,
      };
    },
  },
};
</script>
