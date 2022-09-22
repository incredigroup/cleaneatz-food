@component('admin.home.home-card-title', ['title' => 'Admin Users'])
@endcomponent

@component('admin.home.home-card', ['title' => 'Create an Admin User', 'route' => 'admin.users.create'])
  <p>Create an Admin User. Admins are seperated by two types, Global and Store. A global admin has full access to everything. They can see all orders from every store, add/edit meals and menus, etc. A Store admin will only be able to view the orders for their location.</p>

  <p>You can fill out all of the new Admins information, pick a username and give them a password. Make sure you have properly selected "Global" or "Store" and if "Store" select a location!</p>
@endcomponent

@component('admin.home.home-card', ['title' => 'View All Admin Accounts', 'route' => 'admin.users.index'])
  <p>View all admin accounts. Admins are seperated by two types, Global and Store. A global admin has full access to everything. They can see all orders from every store, add/edit meals and menus, etc. A Store admin will only be able to view the orders for their location.</p>

  <p>You can update a users basic information and status (only 'Global' admins will be able to do so). Make sure you have properly selected "Global" or "Store" and if "Store" select a location! You will be able to "Delete" admins as well.</p>
@endcomponent

@component('admin.home.home-card', ['title' => 'Edit Your Own Account', 'url' => route('admin.users.edit', ['user' => Auth::user()->id])])
  This page is Accessible To Everyone! This is where a person can go and edit their own basic account information(Name, email, phone, username, password). They can not change admin level or store location. This is mainly for store admins to go and edit their password or basic info. A customer could also update their information here as well.
@endcomponent
