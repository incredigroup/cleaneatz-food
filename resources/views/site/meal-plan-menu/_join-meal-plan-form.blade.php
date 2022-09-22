<div class="bg-color-light-gray py-4 text-center">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h3 class="text-champion mb-4">
          Join The Meal Plan Email
        </h3>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <form id="newsletter-subscribe"
              novalidate>
          <input type="hidden"
                 name="SOURCE"
                 value="meal-plan-order-form">

          <div class="form-floating mb-2">
            <select name="LOCATION_CODE"
                    class="form-select"
                    id="storeLocation">
              <option value="">Select Store Location*</option>
              <option value="other">Other/Not Listed</option>
              @foreach ($storeLocations as $storeLocation)
                <option value="{{ $storeLocation->code }}">
                  {{ $storeLocation->locale }}
                </option>
              @endforeach
            </select>
            <label for="storeLocation">Store Location</label>
          </div>

          <div class="form-floating mb-2">
            <input type="text"
                   class="form-control"
                   name="FNAME"
                   placeholder="First Name"
                   id="firstName" />
            <label for="firstName">First Name</label>
          </div>

          <div class="form-floating mb-2">
            <input type="text"
                   class="form-control"
                   name="LNAME"
                   placeholder="Last Name"
                   id="lastName">
            <label for="firstName">Last Name</label>
          </div>

          <div class="form-floating mb-3">
            <input type="email"
                   class="form-control"
                   name="EMAIL"
                   required
                   placeholder="Email*"
                   id="email">
            <label for="email">Email*</label>
          </div>

          <div class="form-group">
            <button class="g-recaptcha btn btn-lg btn-round-lg btn-orange"
                    name='subscribe'
                    data-sitekey="{{ env('RECAPTCHA_V3_KEY') }}"
                    data-callback='onSubmit'
                    data-action='submit'>
              Subscribe
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
