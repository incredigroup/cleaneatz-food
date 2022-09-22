import axios from 'axios';
import geolocator from 'geolocator';

export default class CleanEatz {
  constructor() {
    this.storeLocations = [];
    this.storeLocationsByState = {};
    this.storeLocationStorageKey = 'kf92afdla';
    this.maxStoreLocationDistance = 12; // miles
    this.maxDeliveryDistance = 10; // miles

    this.geoOptions = {
      timeout: 20000, // 20 second timeout
      enableHighAccuracy: false, // turn off for speed
      addressLookup: true,
    };

    geolocator.config({
      language: 'en',
      google: {
        version: '3',
        key: 'AIzaSyBnzTfJWhCBdnCD7pbAeMgTC5drLzHvsLo',
      },
    });
  }

  hasStoredStoreLocation() {
    return this.getStoredStoreLocation() !== null;
  }

  getStoredStoreLocation() {
    return JSON.parse(localStorage.getItem(this.storeLocationStorageKey));
  }

  storeMyStoreLocation(storeLocation) {
    localStorage.setItem(this.storeLocationStorageKey, JSON.stringify(storeLocation));
  }

  removeMyStoreLocation(storeLocation) {
    localStorage.removeItem(this.storeLocationStorageKey);
  }

  isCloseEnough(storeLocation) {
    if (isNaN(storeLocation.distanceFrom)) {
      return false;
    }

    return storeLocation.distanceFrom < this.maxStoreLocationDistance;
  }

  async closeEnoughForDelivery(address, storeLocation) {
    return new Promise((resolve, reject) => {
      geolocator.geocode(address, (error, location) => {
        if (error) return reject(error);

        try {
          const from = {
            latitude: storeLocation.lat,
            longitude: storeLocation.lng,
          };

          const distanceInMiles = geolocator.calcDistance({
            from,
            to: location.coords,
            unitSystem: geolocator.UnitSystem.IMPERIAL,
            formula: geolocator.DistanceFormula.PYTHAGOREAN,
          });

          return resolve(distanceInMiles <= this.maxDeliveryDistance);
        } catch (error) {
          return reject(error);
        }
      });
    });
  }

  async closestLocationTo(to) {
    let storeLocations = await this.getStoreLocations();
    storeLocations = storeLocations.map((storeLocation) => {
      const from = {
        latitude: storeLocation.lat,
        longitude: storeLocation.lng,
      };

      const unitSystem = geolocator.UnitSystem.IMPERIAL;
      const formula = geolocator.DistanceFormula.PYTHAGOREAN;
      let distanceFrom = geolocator.calcDistance({ from, to, formula, unitSystem });

      if (isNaN(distanceFrom)) {
        distanceFrom = 10000;
      }

      return {
        ...storeLocation,
        distanceFrom,
      };
    });

    return storeLocations.sort((a, b) => a.distanceFrom - b.distanceFrom).shift();
  }

  async getClosestStoreLocation() {
    return new Promise((resolve, reject) => {
      geolocator.locate(this.geoOptions, (error, location) => {
        if (error) return reject(error);

        const userAddress = location.address;

        this.closestLocationTo(location.coords).then((storeLocation) => {
          if (this.isCloseEnough(storeLocation)) {
            this.storeMyStoreLocation(storeLocation);
            return resolve(storeLocation);
          } else {
            return reject({ storeLocation: 'TOO_FAR_AWAY', userAddress });
          }
        });
      });
    });
  }

  async getStoreLocations(options) {
    if (this.storeLocations.length !== 0) {
      return Promise.resolve(this.storeLocations);
    }

    const requestUri = `/api/store-locations?meal-plan-stores-only=${options.mealPlanStoresOnly}`;

    return await axios.get(requestUri).then((storeLocations) => {
      this.storeLocations = storeLocations.data;
      return this.storeLocations;
    });
  }

  async getStoreLocation(id) {
    if (this.storeLocationsByState[id] !== undefined) {
      return Promise.resolve(this.storeLocationsByState[id]);
    }

    return await axios.get(`/api/store-locations/${id}`).then((storeLocation) => {
      this.storeLocationsByState[id] = storeLocation.data;
      return this.storeLocationsByState[id];
    });
  }

  async getStoreLocationsByState(options) {
    const groupBy = (items, key) =>
      items.reduce(
        (result, item) => ({
          ...result,
          [item[key]]: [...(result[item[key]] || []), item],
        }),
        {}
      );

    return this.getStoreLocations(options).then((storeLocations) => {
      return groupBy(storeLocations, 'state');
    });
  }
}
