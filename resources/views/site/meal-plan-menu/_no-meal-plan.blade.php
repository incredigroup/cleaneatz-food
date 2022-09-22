<section id="main">
  <div class="PrintFriendly">
    <section class="page-banner banner">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            <div class="banner-content">
              <h3 class="top">New Meals. Every Week.</h3>
              <h1 class="bottom">Meal Plans</h1>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="green-banner banner" id="location-finder">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            @error('meal_plan_expired')
              <div class="alert alert-danger" role="alert">
                <h3>The meal plan you ordered is no longer available.<br>Your card was not charged.</h3>
              </div>
            @enderror

            <h3 class="page-title heading">New Meals. Every Week.</h3>
            <p>
              Our goal is to give you the tools needed by preparing properly portioned sized meals with a balance of protein, carbs, and fats. Our meals eliminate the guessing, temptation, and lack of being prepared due to a busy lifestyle. Clean Eatz Meal Plans offers you the opportunity to have a personal chef prepare every meal for you at a cost you can afford.<strong>&nbsp;</strong><br>
              <br>
              &nbsp;
            </p>
          </div>
        </div>
      </div>
    </section>
    <section class="white-banner banner" style="padding-bottom:20px">
      <div class="container">
        <div class="all-menu-items" style='text-align: center'>
          <div style='width:100%;padding:6rem'>
            <h3>
              @empty(option('meal_plan_pause_msg'))
                {{ option('upcoming_meals_msg') }}
              @else
                {{ option('meal_plan_pause_msg') }}
              @endempty
            </h3>
            <hr>
          </div>
        </div>
      </div>
    </section>
  </div>
</section>
