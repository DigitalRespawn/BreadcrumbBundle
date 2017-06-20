DigitalRespawnBreadcrumbBundle
==============================

A simple bundle to handle and generate a breadcrumb

Installation
------------

Install the bundle:

    composer require "digitalrespawn/breadcrumb-bundle"

Register the bundle in `app/AppKernel.php`:

``` php
<?php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
       	new DigitalRespawn\BreadcrumbBundle\DigitalRespawnBreadcrumbBundle(),
    );
}
```

Register the routing in `app/config/routing.yml`:

``` yaml
# app/config/routing.yml
_digitalrespawn_breadcrumb:
    resource: "@DigitalRespawnBreadcrumbBundle/Resources/config/routing.yml"
```

Usage
-----

#### Configuring routes

First, you'll need to configure your routes.
Add a breadcrumb option in your route configuration to be able to set breadcrumb params.
You need at least a label for the route to appear in the breadcrumb

``` yml
# routing.yml
acme_app_homepage:
    path:     /index
    defaults: { _controller: AcmeAppBundle:Default:index }
    options:
        breadcrumb:
            label: Home                   # label for the route in breadcrumb
```

A route can have a parent. To configure it, just add the option 'parent' with parent route's name

``` yml
# routing.yml
acme_app_user_list:
    path:     /users
    defaults: { _controller: AcmeAppBundle:User:userList }
    options:
        breadcrumb:
            label: Users
            parent: acme_app_homepage     # name of the parent route
```

#### Render the breadcrumb

You can render the breadcrumb in your twig template with the following code snippet

``` html
{{ render(controller('DigitalRespawnBreadcrumbBundle:Breadcrumb:breadcrumb')) }}
```

#### Parent route with parameters

As your routes are not always static, you may want to add some logic to browse from a route to the parent.
You can do that by adding the option "parent_params"

``` yml
# routing.yml
acme_app_user_profile:
    path:     /user/{id}
    defaults: { _controller: AcmeAppBundle:User:userProfile }
    requirements:
        id: \d+
    options:
        breadcrumb:
            label: Profile
            parent: acme_app_user_list

acme_app_user_posts:
    path:     /user/{id}/posts
    defaults: { _controller: AcmeAppBundle:User:userPosts }
    requirements:
        id: \d+
    options:
        breadcrumb:
            label: Posts
            parent: acme_app_user_profile
            parent_params:
                id: user.id                  # "user" is the name of the argument in AcmeAppBundle:User:userPosts Action (ex: public function userPostsAction(User $user){...})
```

#### Route label with parameters

If you want you route labels to be dynamic, you can add parameters with the option "label_params"

``` yml
# routing.yml
acme_app_user_profile:
    path:     /user/{id}
    defaults: { _controller: AcmeAppBundle:User:userProfile }
    requirements:
        id: \d+
    options:
        breadcrumb:
            label: Profile of %username%
            label_params:
                username: user.username       # "user" is the name of the argument in AcmeAppBundle:User:userProfile Action (ex: public function userProfileAction(User $user){...})
            parent: acme_app_user_list        # name of the parent route
```

Get Breadcrumb from Controller
------------------------------

You can get the breadcrumb as an array directly from a controller
You'll need to get the "digitalrespawn.breadcrumb.breadcrumb" service and call getBreadcrumb()

``` php
$this->get('digitalrespawn.breadcrumb.breadcrumb')->getBreadcrumb()
```

This method returns an associative array looking like this:

``` php
array(
	array(
		'uri' => '/home',
		'label' => 'Home'
	),
	array(
		'uri' => '/users',
		'label' => 'Users'
	),
	...
)
```

Override Default Template
-------------------------

Set your breadcrumb template path in "app/config.yml"

``` yml
# app/config.yml
digitalrespawn_breadcrumb:
    template: "AcmeAppBundle:Breadcrumb:breadcrumb.html.twig"
```

Or define a new template in "app/Resources"

	app/Resources/DigitalRespawnBreadcrumbBundle/views/Breadcrumb/breadcrumb.html.twig

Or create a child bundle and override the template

Internationalization
--------------------

This bundle supports Internationalization for breadcrumb labels.
Of course, it needs to be enabled in your project configuration.

#### Label params delimiter

If you need to redefine a default delimiter for your label parameters:

``` yml
# app/config.yml
digitalrespawn_breadcrumb:
    trans_delimiter: %                  # delimiter used to translate vars (ex: 'Hello %username%')
```
If you need to redefine a specific delimiter for your route parameters:

``` yml
# routing.yml
acme_route_example:
    # ...
    options:
        breadcrumb:
            trans_delimiter: %		# delimiter used to translate vars (ex: 'Hello %username%')
            # ...
```

#### Translation domain

If you need to redefine a default translation domain for your label parameters :

``` yml
# app/config.yml
digitalrespawn_breadcrumb:
    trans_domain: admin                 # default domain used to translate labels
```
If you need to redefine a specific delimiter for your route parameters:

``` yml
# routing.yml
acme_route_example:
    # ...
    options:
        breadcrumb:
            trans_domain: admin		# domain used for this route's label translation
            # ...
```
