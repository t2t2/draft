<?php

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputArgument;

class MigrateOldDatabase extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'upgrade:old-database';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Upgrade database from alpha.';

	/**
	 * Create a new command instance.
	 *
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {

		if (App::environment() == 'production' || ! $this->confirm('Are you sure?')) {
			$this->error('Disabled for safety');

			return;
		}

		$disableForeignKeyChecks = in_array(DB::connection()->getDriverName(), ['mysql']);

		Eloquent::unguard();
		if ($disableForeignKeyChecks) {
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		}

		DB::beginTransaction();

		// Users
		$users = $this->migrateUsers();
		$this->migrateMovies();
		$this->migrateMovieEarnings();
		$this->migrateLeagues();
		$league_movie_by_league_and_movie_id = $this->migrateLeagueMovies();
		$team_by_league_and_user_id = $this->migrateLeagueUsers($users);
		$this->migrateLeagueMovieUsers($team_by_league_and_user_id, $league_movie_by_league_and_movie_id);


		DB::commit();
		if ($disableForeignKeyChecks) {
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		}
	}

	/**
	 * Get stuff from old database
	 *
	 * @return \Illuminate\Database\Connection
	 */
	public function fromOldDB() {
		return DB::connection($this->argument('old-connection'));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return [
			['old-connection', InputArgument::REQUIRED, 'The old connection to use'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		return [
		];
	}

	/**
	 * @return Collection Users
	 */
	protected function migrateUsers() {
		$this->info('Migrating users...');
		$users = (new Collection($this->fromOldDB()->table('users')->get()))->keyBy('id');
		$count = 0;
		foreach ($users as $user) {
			if (++$count % 1000 == 0) $this->info($count);
			DB::table('users')->insert([
				'id'          => $user->id,
				'username'    => $user->username,
				'displayname' => $user->displayname,
				'email'       => $user->email ?: null,
				'admin'       => $user->admin,
				'created_at'  => $user->created_at,
				'updated_at'  => $user->updated_at,
			]);
		}

		return $users;
	}

	/**
	 */
	protected function migrateMovies() {
		$this->info('Migrating movies...');
		$movies = (new Collection($this->fromOldDB()->table('movies')->get()))->keyBy('id');
		$count = 0;
		foreach ($movies as $movie) {
			if (++$count % 1000 == 0) $this->info($count);
			DB::table('movies')->insert([
				'id'                 => $movie->id,
				'name'               => $movie->name,
				'boxmojo_id'         => $movie->boxmojo_id,
				'release'            => $movie->release,
				'latest_earnings_id' => $movie->latest_earnings_id,
				'created_at'         => $movie->created_at,
			]);
		}

	}

	/**
	 */
	protected function migrateMovieEarnings() {
		$this->info('Migrating movie_earnings...');
		$movie_earnings = (new Collection($this->fromOldDB()->table('movie_earnings')->get()))->keyBy('id');
		$count = 0;
		foreach ($movie_earnings as $movie_earning) {
			if (++$count % 1000 == 0) $this->info($count);
			DB::table('movie_earnings')->insert([
				'id'         => $movie_earning->id,
				'movie_id'   => $movie_earning->movie_id,
				'date'       => $movie_earning->date,
				'domestic'   => $movie_earning->domestic,
				'created_at' => $movie_earning->created_at,
				'updated_at' => $movie_earning->updated_at,
			]);
		}
	}

	/**
	 */
	protected function migrateLeagues() {
		$this->info('Migrating leagues...');
		$leagues = (new Collection($this->fromOldDB()->table('leagues')->get()))->keyBy('id');
		$count = 0;
		foreach ($leagues as $league) {
			if (++$count % 1000 == 0) $this->info($count);
			DB::table('leagues')->insert([
				'id'          => $league->id,
				'name'        => $league->name,
				'slug'        => $league->slug,
				'description' => $league->description,
				'url'         => $league->url,
				'mode'        => $league->mode,
				'money'       => $league->money,
				'units'       => $league->units,
				'extra_weeks' => $league->extra_weeks,
				'start_date'  => $league->start_date,
				'end_date'    => $league->end_date,
				'active'      => true,
				'private'     => $league->private,
				'featured'    => $league->featured,
				'created_at'  => $league->created_at,
				'updated_at'  => $league->updated_at,
			]);
		}
	}

	/**
	 * @return array
	 */
	protected function migrateLeagueMovies() {
		$this->info('Migrating league_movies...');
		$league_movies = (new Collection($this->fromOldDB()->table('league_movie')->get()))->keyBy('id');
		$league_movie_by_league_and_movie_id = [];

		$count = 0;
		foreach ($league_movies as $league_movie) {
			if (++$count % 1000 == 0) $this->info($count);
			$league_movie_id = DB::table('league_movies')->insertGetId([
				// ID's are skippable here, so getting fresh ones
				'league_id'          => $league_movie->league_id,
				'movie_id'           => $league_movie->movie_id,
				'price'              => $league_movie->price,
				'latest_earnings_id' => $league_movie->latest_earnings_id,
				'created_at'         => $league_movie->created_at,
				'updated_at'         => $league_movie->updated_at,
			]);

			$league_movie_by_league_and_movie_id[$league_movie->league_id][$league_movie->movie_id] = $league_movie_id;
		}

		return $league_movie_by_league_and_movie_id;
	}

	/**
	 * @param $users
	 *
	 * @return array
	 */
	protected function migrateLeagueUsers(Collection $users) {
		$this->info('Migrating league_users...');
		$league_users = (new Collection($this->fromOldDB()->table('league_user')->get()))->keyBy('id');
		$team_by_league_and_user_id = [];

		$count = 0;
		foreach ($league_users as $league_user) {
			if (++$count % 500 == 0) $this->info($count);
			// For league admins
			if ($league_user->admin) {
				DB::table('league_admins')->insert([
					// Skip id's
					'league_id'  => $league_user->league_id,
					'user_id'    => $league_user->user_id,
					'created_at' => $league_user->created_at,
					'updated_at' => $league_user->updated_at,
				]);
			}
			// For league players create a team named after them
			if ($league_user->player) {
				$team_id = DB::table('league_teams')->insertGetId([
					// Get a new ID
					'league_id'  => $league_user->league_id,
					'name'       => $users->get($league_user->user_id)->displayname ?: $users->get($league_user->user_id)->username,
					'created_at' => $league_user->created_at,
					'updated_at' => $league_user->updated_at,
				]);
				$team_by_league_and_user_id[$league_user->league_id][$league_user->user_id] = $team_id;

				DB::table('league_team_user')->insert([
					// Skip id's
					'league_team_id' => $team_id,
					'user_id'        => $league_user->user_id,
					'created_at'     => $league_user->created_at,
					'updated_at'     => $league_user->updated_at,
				]);
			}
		}

		return $team_by_league_and_user_id;
	}

	/**
	 * @param $team_by_league_and_user_id
	 * @param $league_movie_by_league_and_movie_id
	 */
	protected function migrateLeagueMovieUsers($team_by_league_and_user_id, $league_movie_by_league_and_movie_id) {
		$this->info('Migrating league_movie_users...');
		$league_movie_users = (new Collection($this->fromOldDB()->table('league_movie_user')->get()))->keyBy('id');
		$count = 0;
		foreach ($league_movie_users as $league_movie_user) {
			if (++$count % 1000 == 0) $this->info($count);

			$league_id = $league_movie_user->league_id;
			$user_id = $league_movie_user->user_id;
			$movie_id = $league_movie_user->movie_id;
			if (! isset($team_by_league_and_user_id[$league_id][$user_id])
				|| ! isset($league_movie_by_league_and_movie_id[$league_id][$movie_id])
			) {
				$this->error("Movie bugged: - League: {$league_id} - Movie: {$movie_id}");
				continue;
			}
			DB::table('league_team_movies')->insert([
				'league_team_id'  => $team_by_league_and_user_id[$league_id][$user_id],
				'league_movie_id' => $league_movie_by_league_and_movie_id[$league_id][$movie_id],
				'created_at'      => $league_movie_user->created_at,
				'updated_at'      => $league_movie_user->updated_at,
			]);
		}
	}

}
