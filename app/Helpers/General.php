<?php

use Illuminate\Support\Facades\Config;
 
 
if(!function_exists('get_default_lang'))
{
	function get_default_lang(){
		return   Config::get('app.locale');
	}
	
}

if(!function_exists('uploadImage'))
{
	function uploadImage($folder, $image)
	{
		$image->store('/', $folder);
		$filename = $image->hashName();
		$path = 'images/' . $folder . '/' . $filename;
		return $path;
	}
	
}

if(!function_exists('get_languages'))
{
	function get_languages()
	{
		return \App\Models\Language::active()->Selection()->get();
	}
}
if (!function_exists('lang')) {
	function lang() {
			return  session('lang')?:app()->getLocale();
		}
}

if (!function_exists('direction')) {
	function direction() {
		if (session()->has('lang')) {
			if (session('lang') == 'ar') {
				return 'rtl';
			} else {
				return 'ltr';
			}
		} else {
			return 'ltr';
		}
	}
}
 
if (!function_exists('admin')) {
	function admin() {
		return auth()->guard('admin');
	}
}
