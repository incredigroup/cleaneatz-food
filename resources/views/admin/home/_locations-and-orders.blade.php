@component('admin.home.home-card-title', ['title' => 'Locations and Orders'])
@endcomponent

@component('admin.home.home-card', ['title' => 'Store Options', 'route' => 'admin.store-options.index'])
  This will be a list of all stores by state. It will contain links that store level admins can access. They can only access their individual store. There is a link to view all the orders, to create a new satellite for the location, and to view all satellites for the location.
@endcomponent

@component('admin.home.home-card', ['title' => 'Global Tip Report', 'route' => 'admin.reports.tips.tips'])
  Overview report of tips.
@endcomponent
