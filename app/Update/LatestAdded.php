<?php namespace VacStatus\Update;

use VacStatus\Update\BaseUpdate;

use Cache;
use Carbon;

/*

	STEPS TO GET LATEST TRACKED USERS

*************************************************************************************************

	->	Grab 20 rows from the 'user_list_profile' table
		->	Order By DESC
		->	The '20' is some nice number I came up with. It could be always changed around
*/

/*

	RETURN FORMAT

*************************************************************************************************

	return [
		[ (There should be many of these) (SORTED BY DESC) (NO SPECIFIC INDEX VALUE)
			profile.id
			profile.display_name
			profile.avatar_thumb

			profile_ban.vac
				-> this is the number of vac bans
			profile_ban.vac_banned_on
				-> see to convert date
					https://github.com/jung3o/VacStatus/tree/c6e626d8f8ab5f8c99db80f904275c185698c645/app/models/Profile.php#L131
			profile_ban.community
			profile_ban.trade

			users.site_admin
				-> color name (class: .admin-name)
			users.donation
				-> color name (class: .donator-name)
			users.beta
				-> color name (class: .beta-name)
		]
	]

*/

class LatestAdded extends BaseUpdate
{
	function __constructor()
	{
		$this->cacheName = "latestAdded";
	}

	public function getLatestAdded()
	{
		if(!$this->canUpdate()) return Cache::get($this->cacheName);

		// do update stuff thingy
	}
}