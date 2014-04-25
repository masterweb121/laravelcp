<?php


Route::model('user', 'User');
Route::model('profile', 'UserProfile');
Route::model('comment', 'Comment');
Route::model('id', 'id');
Route::model('post', 'Post');
Route::model('role', 'Role');

/** ------------------------------------------
 *  Route constraint patterns
 *  ------------------------------------------
 */
Route::pattern('comment', '[0-9]+');
Route::pattern('post', '[0-9]+');
Route::pattern('user', '[0-9]+');
Route::pattern('profile', '[0-9]+');
Route::pattern('role', '[0-9]+');
Route::pattern('id', '[0-9]+');
Route::pattern('token', '[0-9a-z]+');



/** ------------------------------------------
 *  Admin Routes
 *  ------------------------------------------
 */

# json api
Route::group(array('prefix' => 'json/admin', 'before' => 'json|auth.basic|checkuser'), function()
{

	Route::controller('users/{user}', 'AdminUsersController');
	Route::controller('users', 'AdminUsersController');

});

# xml api
Route::group(array('prefix' => 'xml/admin', 'before' => 'xml|auth.basic|checkuser'), function()
{

		Route::controller('users/{user}', 'AdminUsersController');
		Route::controller('users', 'AdminUsersController');
});


# web 
Route::group(array('prefix' => 'admin', 'before' => 'auth|checkuser'), function()
{


    # Settings Management
    Route::controller('settings', 'AdminSettingsController');

    # Comment Management
    Route::controller('comments/{comment}', 'AdminCommentsController');
    Route::controller('comments', 'AdminCommentsController');

    # Slug Management
    Route::controller('slugs/{post}', 'AdminBlogsController');
    Route::controller('slugs', 'AdminBlogsController');

    # User Mass Management
    Route::get('user/mass/email', 'AdminUsersController@getEmailMass');
    Route::post('user/mass/email', 'AdminUsersController@postEmail');
    Route::post('user/mass/merge', 'AdminUsersController@postMerge');
    Route::delete('user/mass', 'AdminUsersController@postDeleteMass');


    # User Profile Management
	Route::controller('users/{user}/profile/{profile}', 'AdminProfileController');

    # User Management
	Route::controller('users/{user}', 'AdminUsersController');
	Route::controller('users', 'AdminUsersController');
    

    # User Role Management
    Route::controller('roles/{role}', 'AdminRolesController');
    Route::controller('roles', 'AdminRolesController');

    # Admin Dashboard
    Route::controller('/', 'AdminDashboardController');
});


/** ------------------------------------------
 *  Frontend Routes
 *  ------------------------------------------
 */

Route::get('invalidtoken', 'UserController@invalidtoken');
Route::get('nopermission', 'UserController@noPermission');
Route::get('suspended', 'UserController@suspended');


Route::controller('user/reset/{token}', 'UserController');
Route::controller('user/{user}', 'UserController');
Route::controller('user', 'UserController');

//:: Application Routes ::

# Filter for detect language
Route::when('contact-us','detectLang');

# Contact Us Static Page
Route::post('contact-us', 'UserController@postContactUs');
Route::get('contact-us', 'UserController@getContactUs');


# Posts - Second to last set, match slug
Route::get('{postSlug}', 'BlogController@getView');
Route::post('{postSlug}', 'BlogController@postView');

# Index Page - Last route, no matches
Route::get('/', array('before' => 'detectLang','uses' => 'BlogController@getIndex'));
