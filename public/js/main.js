var DRAFTR;
DRAFTR = {
	config: {},
	personaEls: '.persona-button[data-persona="login"] span',
	common: {
		init: function () {
			// Load config
			DRAFTR.config = $.extend({},
				{unloading: false},
				$(document.body).data());

			// Pants check
			if (location.hostname.indexOf(".dev") > 0) {
				document.title = "[DEV] " + document.title
			}

			// Unload check
			$(window).on("beforeunload", function () {
				DRAFTR.config.unloading = true
			});

			// Mozilla Pesona stuff
			this.personaHooks();

			// Initialise foundaton
			$(document).foundation();
		},

		personaHooks: function () {
			navigator.id.watch({
				loggedInUser: (DRAFTR.config.user ? DRAFTR.config.user : null),
				onlogin: function (assertion) {
					$(DRAFTR.personaEls).html('Logging in... <i class="fa fa-spinner fa-spin"></i>');

					$.ajax({
						type: 'POST',
						url: DRAFTR.config.url + '/auth/login',
						data: {
							"_token": DRAFTR.config.csrf,
							"assertion": assertion
						},
						success: function (response) {
							if (response.refresh) {
								window.location.reload()
							}
							if (response.redirect) {
								window.location = response.redirect
							}
						},
						error: function () {
							navigator.id.logout();
						}
					});
				},
				onlogout: function () {
					setTimeout(function () { // http://stackoverflow.com/a/15623312/211088
						if (DRAFTR.config.unloading) {
							return false;
						}

						$.ajax({
							type: 'POST',
							url: DRAFTR.config.url + '/auth/logout',
							data: {
								"_token": DRAFTR.config.csrf
							},
							success: function (response) {
								if (response.refresh) {
									window.location.reload()
								}
								if (response.redirect) {
									window.location = response.redirect
								}
							},
							error: function () {
								window.location.reload()
							}
						});
					}, 100);
				}
			});

			// Login links
			$('[data-persona="login"]').click(function () {
				var $el = $(this).find('span');

				// Switch text
				$el.html('Waiting on user... <i class="fa fa-spinner fa-spin"></i>');

				navigator.id.request({
					siteName: 'Box Office Draft',
					oncancel: function () {
						$el.html("Cancelled by user");
						setTimeout(function () {
							$el.text('Sign in / Sign up')
						}, 2000);
					}
				});
			});

			// Logout links
			$('[data-persona="logout"]').click(function () {
				var $el = $(this).find('span');

				// Switch text
				$el.html('Logging out... <i class="fa fa-spinner fa-spin"></i>');

				navigator.id.logout();
				return false;
			});

		}
	},

	/* http://viget.com/inspire/extending-paul-irishs-comprehensive-dom-ready-execution */
	UTIL: {
		exec: function (controller, action) {
			var ns = DRAFTR;
			action = ( action === undefined ) ? "init" : action;

			if (controller !== "" && ns[controller] && typeof ns[controller][action] == "function") {
				ns[controller][action]();
			}
		},

		init: function () {
			var body = document.body,
				controller = body.getAttribute("data-controller"),
				action = body.getAttribute("data-action");

			DRAFTR.UTIL.exec("common");
			DRAFTR.UTIL.exec(controller);
			DRAFTR.UTIL.exec(controller, action);
		},

		debounce: function (func, wait, immediate) {
			var timeout;
			return function () {
				var context = this, args = arguments;
				var later = function () {
					timeout = null;
					if (!immediate) func.apply(context, args);
				};
				var callNow = immediate && !timeout;
				clearTimeout(timeout);
				timeout = setTimeout(later, wait);
				if (callNow) func.apply(context, args);
			};
		}

	}
};

$(document).ready(DRAFTR.UTIL.init);