<?php

use Steam\Steam as Steam;

class ProfileController extends BaseController {

  public function profileAction($steam3Id = null)
  {
    if($steam3Id) {

      $profile = Profile::whereSmallId(Steam::toSmallId($steam3Id))->first();

      if(!isset($profile->id)) {
        return View::make('profile/blankProfile')
        ->with('steam3Id', $steam3Id);
      }

      $old = Array(1, 0);
      if(Cache::has("checked_$steam3Id")) {
        $old = Array(Cache::get("checked_$steam3Id"), Cache::get("checked_time_$steam3Id"));
      }

      if(Steam::canUpdate(Steam::toSmallId($steam3Id))) {
        return View::make('profile/profile')
        ->with('steam3Id', $steam3Id)
        ->with('profile', $profile)
        ->with('old_check', $old)
        ->with('update', true);
      }

      if(Cache::has("checked_$steam3Id")) {
        Cache::forever("checked_$steam3Id", $old[0] + 1);
      } else {
        Cache::forever("checked_$steam3Id", 1);
      }

      Cache::forever("checked_time_$steam3Id", time());

      return View::make('profile/profile')
      ->with('steam3Id', $steam3Id)
      ->with('profile', $profile)
      ->with('old_check', $old);
    }

    return Redirect::home();
  }

  public function updateSingleProfileAction($steam3Id = null) {

    if($steam3Id) {
      $profile = Profile::updateSingleProfile($steam3Id);

      if($profile == 'error') {
        // not stable connection to steam
        return App::abort(500);
      }

      $old = Array(1, 0);
      if(Cache::has("checked_$steam3Id")) {
        $old = Array(Cache::get("checked_$steam3Id"), Cache::get("checked_time_$steam3Id"));
        Cache::forever("checked_$steam3Id", $old[0] + 1);
      } else {
        Cache::forever("checked_$steam3Id", 1);
      }

      return View::make('profile/profileSkeleton')
      ->with('profile', $profile)
      ->with('old_check', $old);
    }
    return App::abort(500);
  }

}
