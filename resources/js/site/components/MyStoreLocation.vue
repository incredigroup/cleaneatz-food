<style scoped>
h5 {
  margin-bottom: 0;
  text-transform: uppercase;
}

.choose-location select {
  border: none;
  padding-left: 0;
  text-transform: uppercase;
  font-size: 1rem;
}
</style>

<template>
  <div>
    <div class="row pt-4">
      <div class="col-xs-12 col-lg-8 offset-lg-2">
        <orange-oval-decorator>
          <h5 v-if="!isTooFar">
            Your Nearest Cafe Is:
            <span class="color-ce-orange">
              <div v-if="myStoreLocation">
                {{ myStoreLocation.address
                }}<span class="d-none d-md-inline"
                  >, {{ myStoreLocation.city }} {{ myStoreLocation.state }}</span
                >
              </div>
              <div v-if="locating && !manualLocationChange">
                searching for closest cafe...
                <img v-bind:src="this.asset('/img/locating.svg')" />
              </div>
              <div v-if="unableToFind">Unable to find a nearby cafe</div>
            </span>
          </h5>
          <h5 v-else>
            Unable to find a cafe close enough to
            <span class="color-ce-orange">
              {{ this.userCity }}
            </span>
          </h5>
        </orange-oval-decorator>
      </div>
    </div>
    <div class="text-center pt-3 pb-2">
      <h5>Choose a <span v-if="myStoreLocation || isTooFar">different</span> location</h5>
    </div>
    <div class="row pb-4 choose-location">
      <div class="col-xs-12 col-lg-4 offset-lg-2 pb-2 pb-lg-0">
        <orange-oval-decorator>
          <select
            class="form-select form-select-lg no-border"
            @change="stateChanged()"
            v-model="storeLocationState"
            id="location-states"
            name="location"
            title="Choose from the following...">
            <option selected="selected" value="">Select Your State</option>
            <option v-for="(item, state) in storeLocationsByState" :value="state">
              {{ getStateName(state) }}
            </option>
            <option value="-1">(My state isn't listed)</option>
          </select>
        </orange-oval-decorator>
      </div>
      <div class="col-xs-12 col-lg-4">
        <orange-oval-decorator>
          <select
            v-model="selectedStoreLocation"
            @change="storeLocationChanged()"
            name="cafe"
            class="form-select form-select-lg state-locs">
            <option selected="selected" value="">Select Your Cafe</option>
            <option
              v-for="storeLocation in storeLocationsByState[storeLocationState]"
              :value="storeLocation.code">
              {{ renderStoreLocationName(storeLocation) }}
            </option>
            <option value="-1">(My city isn't listed)</option>
          </select>
        </orange-oval-decorator>
      </div>
    </div>
  </div>
</template>

<script>
import Cleaneatz from '../cleaneatz';
import states from '../states';
import OrangeOvalDecorator from './OrangeOvalDecorator';

export default {
  components: { OrangeOvalDecorator },
  props: {
    assetPath: String,
    mealPlanStoresOnly: {
      type: Boolean,
      default: false,
    },
    defaultStoreCode: {
      default: false,
    },
  },
  data() {
    return {
      myStoreLocation: false,
      locating: false,
      unableToFind: false,
      isTooFar: false,
      manualLocationChange: false,
      cleaneatz: new Cleaneatz(),
      storeLocationsByState: [],
      storeLocationState: '',
      storeLocationsForState: [],
      selectedStoreLocation: '',
      userCity: '',
    };
  },

  mounted() {
    const mealPlanStoresOnly = this.mealPlanStoresOnly;
    this.cleaneatz.getStoreLocationsByState({ mealPlanStoresOnly }).then((storeLocations) => {
      this.storeLocationsByState = storeLocations;
      if (this.defaultStoreCode !== false) {
        const allStores = Object.values(this.storeLocationsByState).flat();
        const defaultStore = allStores.find(
          (store) => store.code === Number(this.defaultStoreCode)
        );

        if (defaultStore) {
          this.setLocalStoreLocation(defaultStore);
          return;
        }
      }

      this.findClosestStoreLocation();
    });
  },

  methods: {
    setLocalStoreLocation(storeLocation) {
      if (!storeLocation) {
        return;
      }

      this.isTooFar = false;
      this.unableToFind = false;
      this.myStoreLocation = storeLocation;
      this.storeLocationState = storeLocation.state;
      this.selectedStoreLocation = storeLocation.code;
      this.userCity = storeLocation.city;
      this.cleaneatz.storeMyStoreLocation(storeLocation);
      this.$root.$emit('LOCAL_STORE_LOCATION_SET', {
        storeLocation: this.myStoreLocation,
        message: 'STORE_FOUND',
      });
    },

    setNoLocalStoreLocation(error) {
      this.unableToFind = true;
      this.myStoreLocation = false;
      this.userCity = '';
      this.cleaneatz.removeMyStoreLocation();

      if (error.storeLocation === 'TOO_FAR_AWAY' && error.userAddress) {
        this.userCity = `${error.userAddress.city}, ${error.userAddress.stateCode}`;
        this.isTooFar = true;
      }

      this.$root.$emit('LOCAL_STORE_LOCATION_SET', {
        storeLocation: false,
        message: error.name === 'GeoError' ? 'UNABLE_TO_LOCATE' : 'TOO_FAR_AWAY',
      });
    },

    findClosestStoreLocation() {
      if (this.cleaneatz.hasStoredStoreLocation()) {
        const myStoreLocation = this.cleaneatz.getStoredStoreLocation();

        // refresh the store location in local storage in case a property has changed since
        // it was last stored
        const refreshedStoreLocation = this.findStoreLocationByCode(
          myStoreLocation.state,
          myStoreLocation.code
        );
        this.setLocalStoreLocation(refreshedStoreLocation);
        return;
      }

      this.locating = true;

      this.cleaneatz
        .getClosestStoreLocation()
        .then((response) => {
          if (!this.manualLocationChange) {
            this.setLocalStoreLocation(response);
          }
        })
        .catch((error) => {
          console.error(error);
          if (!this.manualLocationChange) {
            this.setNoLocalStoreLocation(error);
          }
        })
        .finally(() => (this.locating = false));
    },

    getStateName(abbr) {
      return states[abbr];
    },

    stateChanged() {
      this.manualLocationChange = true;

      this.selectedStoreLocation = '';
      if (this.storeLocationState === '-1') {
        this.setNoLocalStoreLocation({});
      }
    },

    storeLocationChanged() {
      this.manualLocationChange = true;

      if (this.selectedStoreLocation === '-1') {
        this.setNoLocalStoreLocation({});
        return;
      }

      const storeLocation = this.findStoreLocationByCode(
        this.storeLocationState,
        this.selectedStoreLocation
      );
      this.setLocalStoreLocation(storeLocation);
    },

    findStoreLocationByCode(state, code) {
      const forState = this.storeLocationsByState[state];
      return forState.filter((f) => f.code === code)[0];
    },

    asset(uri) {
      return `${this.assetPath}${uri}`;
    },

    renderStoreLocationName(storeLocation) {
      if (!storeLocation) {
        return '';
      }

      let name = storeLocation.city;

      if (storeLocation.location) {
        name += ` - ${storeLocation.location}`;
      }

      return name;
    },
  },
};
</script>
