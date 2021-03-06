<?php

get('/', [
	'as' => 'home',
	'uses' => 'PagesController@indexPage'
]);

Route::group(['prefix' => 'auth'], function()
{
	get('/login', [
		'as' => 'auth.login',
		'uses' => 'LoginController@login'
	]);

	get('/logout', [
		'as' => 'auth.logout',
		'uses' => 'LoginController@logout'
	]);
});

get('/list', [
	'as' => 'list.list',
	'uses' => 'PagesController@listListPage'
]);

get('/list/most', [
	'as' => 'tracked.most',
	'uses' => 'PagesController@mostTrackedPage'
]);

get('/list/latest', [
	'as' => 'tracked.latest',
	'uses' => 'PagesController@latestTrackedPage'
]);

get('/list/{listId}', [
	'as' => 'tracked.custom',
	'uses' => 'PagesController@customListPage'
]);


get('/list/{useless}/{listId}', function($soUSLESS, $listId) {
	return Redirect::route('tracked.custom', $listId, 301); 
});

get('/l/{listId}', function($listId) {
	return Redirect::route('tracked.custom', $listId, 301); 
});

get('/l/{useless}/{listId}', function($soUSLESS, $listId) {
	return Redirect::route('tracked.custom', $listId, 301); 
});

get('/u/{steam65BitId}', [
	'as' => 'profile',
	'uses' => 'PagesController@profilePage'
]);

get('/news/{page?}', [
	'as' => 'news',
	'uses' => 'PagesController@newsPage'
]);

get('/privacy', [
	'as' => 'privacy',
	'uses' => 'PagesController@privacyPage'
]);

get('/contact', [
	'as' => 'contact',
	'uses' => 'PagesController@contactPage'
]);

get('/donate', [
	'as' => 'donate',
	'uses' => 'PagesController@donatePage'
]);

post('/search', [
	'as' => 'search',
	'uses' => 'PagesController@searchPage'
]);

Route::group(['prefix' => 'settings'], function()
{
	get('/', [
		'as' => 'settings',
		'uses' => 'SettingsController@subscriptionPage'
	]);

	get('/subscribe/{email}/{verify}', [
		'as' => 'settings.subscription.verify',
		'uses' => 'SettingsController@subscriptionVerify'
	]);
});

Route::group(['prefix' => 'api'], function()
{
	Route::group(['prefix' => 'v1', 'namespace' => 'APIv1'], function()
	{
		get('/profile/{steam65BitId}', [
			'as' => 'api.v1.profile',
			'uses' => 'ProfileController@index'
		]);

		get('/search/{searchKey}', [
			'as' => 'api.v1.search',
			'uses' => 'SearchController@search'
		]);

		Route::group(['prefix' => 'list'], function()
		{
			get('/simple', [
				'as' => 'api.v1.list.simple',
				'uses' => 'ListController@mySimpleList'
			]);

			get('/most', [
				'as' => 'api.v1.tracked.most',
				'uses' => 'ListController@mostTracked'
			]);

			get('/latest', [
				'as' => 'api.v1.tracked.latest',
				'uses' => 'ListController@latestTracked'
			]);

			get('/{userList}', [
				'as' => 'api.v1.tracked.latest',
				'uses' => 'ListController@customList'
			]);

			get('/', [
				'as' => 'api.v1.list.list',
				'uses' => 'ListController@listList'
			]);

			//
			//	---------------------------------
			//

			post('/add', [
				'as' => 'api.v1.list.user.add',
				'uses' => 'ListUserController@addToList'
			]);

			post('/{listId?}', [
				'as' => 'api.v1.list.create',
				'uses' => 'ListController@modifyCustomList'
			]);

			post('/subscribe/{userList}', [
				'as' => 'api.v1.list.subscribe',
				'uses' => 'ListController@listSubscribe'
	     	]);

			//
			//	---------------------------------
			//

			delete('/subscribe/{userList}', [
				'as' => 'api.v1.list.unsubscribe',
				'uses' => 'ListController@listUnsubscribe'
	     	]);

			delete('/delete', [
				'as' => 'api.v1.list.user.delete',
				'uses' => 'ListUserController@deleteFromList'
			]);

			delete('/{userList}', [
				'as' => 'api.v1.tracked.latest',
				'uses' => 'ListController@deleteCustomList'
			]);
		});

		Route::group(['prefix' => 'news'], function()
		{

			get('/', [
				'as' => 'api.v1.news',
				'uses' => 'NewsController@index'
			]);

			get('/{news}', [
				'as' => 'api.v1.news.item',
				'uses' => 'NewsController@showArticle'
			]);
		});

		Route::group(['prefix' => 'donate'], function()
		{

			get('/', [
				'as' => 'api.v1.donate',
				'uses' => 'DonationController@index'
			]);

			Route::any('/ipn', array('uses' => 'DonationController@IPN'));
		});

		Route::group(['prefix' => 'settings'], function()
		{
			get('/', [
				'as' => 'api.v1.settings',
				'uses' => 'SettingsController@subscribeIndex'
			]);

			post('/subscribe', [
				'as' => 'api.v1.settings.subscribe',
				'uses' => 'SettingsController@makeSubscription'
			]);

			delete('/subscribe/email', [
				'as' => 'api.v1.settings.subscribe.email.delete',
				'uses' => 'SettingsController@deleteEmail'
			]);

			delete('/subscribe/pushbullet', [
				'as' => 'api.v1.settings.subscribe.pushbullet.delete',
				'uses' => 'SettingsController@deletePushBullet'
			]);
		});
	});
});

Route::group([
	'prefix' => 'admin',
	'middleware' => 'admin',
	'namespace' => 'Admin'
], function() {
	get('/', [
		'as' => 'admin.home',
		'uses' => 'MainController@index'
	]);

	post('/announcement', [
     	'as' => 'admin.announcement.save',
     	'uses' => 'MainController@announcementSave'
 	]);

	Route::group(['prefix' => 'db'], function()
	{
		get('/', [
			'as' => 'admin.db',
			'uses' => 'DatabaseController@index'
		]);
		
		get('/users', [
			'as' => 'admin.db.users',
			'uses' => 'DatabaseController@user'
		]);
		
		get('/profiles', [
			'as' => 'admin.db.profiles',
			'uses' => 'DatabaseController@profile'
		]);
	});

	Route::group(['prefix' => 'news'], function()
	{
		get('/', [
			'as' => 'admin.news',
			'uses' => 'NewsController@index'
		]);

		get('/{news}', [
			'as' => 'admin.news.edit',
			'uses' => 'NewsController@editForm'
		]);

		post('/{newsId?}', [
			'as' => 'admin.news.save',
			'uses' => 'NewsController@saveNews'
		]);

		delete('/{news}', [
			'as' => 'admin.news.delete',
			'uses' => 'NewsController@delete'
		]);
	});
});

Route::model('userList', 'VacStatus\Models\UserList', function()
{
	return ['error' => '404'];
});

Route::model('news', 'VacStatus\Models\News', function()
{
	return ['error' => '404'];
});