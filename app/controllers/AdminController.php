<?php

//namespace Admin;

use Illuminate\Support\Facades\Input;
use Krucas\Notification\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
class AdminController extends \AdminBaseController {

	/**
	 * Admin Homepage
	 */
	public function index() {
		$stats = [
			'users' => User::count(),
			'leagues' => League::count(),
			'movies' => Movie::count(),
		];


		$this->layout->title = 'Admin';
		$this->layout->content = View::make('admin.index', compact('stats'));
	}
	
	public function movies() {
		$movies_query = Movie::query();
		$movies_query->orderBy('release', 'desc');
		$movies_query->select('movies.*');
		$movies = $movies_query->paginate(50);
		$this->layout->title = 'Movies';
		$this->layout->content = View::make('admin.movies',compact('movies'));
	}	
	public function addMovie() {
		$mojo_id = str_replace(".htm",'',Input::get('movie'));
		$info = new stdClass();
		$info->found = false;
		$info->mojo_id = $mojo_id;
		$info->old = null;
		$info->grosses = null;
		if ($mojo_id != '')
		{
			$client = new Client();
			$crawler = $client->request('GET', 'http://www.boxofficemojo.com/movies/?page=daily&view=chart&id='.$mojo_id.'.htm', [], [], [
				'HTTP_USER_AGENT' => "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0"
			]);
			$mojo_query = Movie::query();
			$mojo_query->where('boxmojo_id', $mojo_id);
			$query_result = $mojo_query->first();
			if ($query_result)
			{
				$info->old = $query_result;
			}
			
			$info->title = $crawler->filterXPath("//div[@id='body']//font[@face='Verdana']/b")->text();
			$info->release_date = new Carbon($crawler->filterXPath("//div[@id='body']//td[starts-with(.,'Release Date')]//b")->text());
			if (!$info->old)
			{
				$info->grosses = $crawler->filter('#body table[border="0"][class="chart-wide"] tr[bgcolor]:not([bgcolor="#dcdcdc"])')->each(function(Crawler $node) {
					$cols = $node->children();

					$myinfo = [
						'release_date'   => new Carbon($cols->eq(1)->text()),
						'domestic_total' => intval(str_replace(['$', ','], '', $cols->eq(8)->text())),
					];
					
					return $myinfo;
				});
			}
			
			$info->found = true;
			
			
			//Notification::success($title." ".$release_date." ".$boxmojo_id);

			//return Redirect::route('admin.movies');			
		}

		$this->layout->content = View::make('admin.addmovie',[
			'info' => $info
		]);
	}
	
	public function confirmMovie() {
		// Create the league
		$old_id = Input::get('old_id');
		if ($old_id) {
			$movie_query = Movie::query();
			$movie_query->where('id',$old_id);
			$movie = $movie_query->first();
		}
		else
		{
			$movie = new Movie(Input::only([
				'title', 'release_date', 'boxmojo_id'
			]));			
		}
		$movie->name = Input::get('title');
		$movie->release = Input::get('release_date');
		$movie->boxmojo_id = Input::get('mojo_id');
		
		$movie->save();
		
		$earnings = unserialize(Input::get('grosses'));
		$count = 0;
		if ($earnings)
		{
			$latest_earnings_id = 0;
			
			foreach($earnings as $gross) { 
				$earnings = new MovieEarning([
					'movie_id' => $movie->id,
					'date'     => $gross['release_date'],
					'domestic' => $gross['domestic_total']
				]);
				$earnings->save();
				$latest_earnings_id = $earnings->id;
				$count += 1;
			}
			if($latest_earnings_id != 0) {
				$movie->latest_earnings_id = $latest_earnings_id;
				$movie->save();
			}			
		}
		
		
		Notification::success("Successfully ".($old_id ? "updated " : "added ").$movie->name." with ".$count." day(s) of earnings");

		return Redirect::route('admin.addMovie');

	}
	
	
} 