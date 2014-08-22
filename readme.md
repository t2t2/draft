## Movie Draft? Movie Draft.

[![Build Status](https://travis-ci.org/t2t2/draft.svg?branch=master)](https://travis-ci.org/t2t2/draft) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/t2t2/draft/badges/quality-score.png?s=4314a52de603f19a6c33135c5ebce00aff7d874b)](https://scrutinizer-ci.com/g/t2t2/draft/) [![Code Coverage](https://scrutinizer-ci.com/g/t2t2/draft/badges/coverage.png?s=1c4ae8b00f1e8483489a3b3af985eec1d1bb85c4)](https://scrutinizer-ci.com/g/t2t2/draft/)

Based on the Summer / Winter Movie Draft of NSFWshow, Draftr is site for creating your own fantasy movie league.

Note: Draftr is codename for the codebase, while the product is called Box Office Draft. Draftr also used to be the name of the site until August 2013.

Framework: Laravel 4

# Installation

0. Follow basic steps for installing a laravel project (most likely `git clone` + `composer install`)
1. Create a file `.env` in root folder and have it's contents be only the environment you wish to run it in (probably `local`)
2. Duplicate everything from `app/config/local.dist` into `app/config/local` and replace values according to your preferences
3. Migrate the database & optionally run the database seeder.