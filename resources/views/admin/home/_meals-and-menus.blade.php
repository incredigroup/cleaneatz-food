@component('admin.home.home-card-title', ['title' => 'Meals and Menus'])
@endcomponent

@component('admin.home.home-card', ['title' => 'Create a Meal', 'route' => 'admin.meals.create'])
  Fill out a form to create a new meal in the system. Add the name, description, calories, fat, carbs, protein, and upload a corresponding photo. Only one photo is needed and does not need to be of a very large size. Making all the photos for all the meals have the same width and height (i.e. 500x375) will create a better user experience.
@endcomponent

@component('admin.home.home-card', ['title' => 'View All Meals', 'route' => 'admin.meals.index'])
  View all meals inside a table. Here you will be able to either edit the meal OR delete from the database. If you delete, it will be gone for good and will not show in any menus it may be associated with.
@endcomponent

@component('admin.home.home-card', ['title' => 'Create a Menu', 'route' => 'admin.meal-plans.create'])
  <p>Fill out a form to create a new menu. The page with the path '/current-menu' shows a menu dependent on the "Start Date" and "End Date" fields (Important). The menu will appear on '/meal-plan-menu' when it is between the "Start Date" and the "End Date". You can select specific times for these as well, otherwise it will default to 12:00AM(midnight) of the specified date.</p>

  <p>You have the ability to add up to 10 meals per menu. You don't have to fill in all fields. Each Meal is a dropdown with a list of all meals in the database ordered alphabetically. You can delete meals in '/admin-all-meals'. When your menu is set, select 'Create Menu'.</p>
@endcomponent

@component('admin.home.home-card', ['title' => 'View All Menus', 'route' => 'admin.meal-plans.index'])
  View all menus inside a table. Here you will be able to either edit the menu OR delete from the database. If you delete, it will be gone for good and will not appear on '/current-menu' if it's with in the correct date range.
@endcomponent

@component('admin.home.home-card', ['title' => 'Manage Add-On Items', 'route' => 'admin.add-ons.index'])
  Manage the current Add-On Items that will show on each weeks meal plans
@endcomponent

@component('admin.home.home-card', ['title' => 'Create Satellite', 'route' => 'admin.satellite-locations.create'])
  Global Admins can create a new satellite location for any store location.
@endcomponent

@component('admin.home.home-card', ['title' => 'Approve Satellite Locations', 'route' => 'admin.satellite-locations.unapproved'])
  Global Admins can approve new satellite location submitted by any store location.
@endcomponent

@component('admin.home.home-card', ['title' => 'View All Satellites', 'route' => 'admin.satellite-locations.index'])
  Global Admins can view all of the satellites that have been created. They can also delete satellites here. They can not however edit a satellite. To Edit, find the individual store all satellites page, and you can find the edit link from there.
@endcomponent

@component('admin.home.home-card', ['title' => 'Promo Code Manager', 'route' => 'admin.promo-codes.index'])
  Global Admins can view, edit, create, and delete Promo codes for all stores or an individual store.
@endcomponent
