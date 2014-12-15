(function($) {

	$.fn.mailchimpLists = function(settings) {
		if(typeof settings != "object") {
			settings = {};
		}

		var defaultSettings = {
			target: false,
			listIds: []
		};

		settings = $.extend({}, defaultSettings, settings);

		return this.each(function() {
			var $t = $(this);
			var lastValue;

			$t.blur(function() {
				var key = $(this).val();

				if(key != '' && key != lastValue) {
					if(settings.target) {
						settings.target.html('<p>Loading lists...</p>');
					}

					$.post(Craft.getCpUrl('postmaster/mailchimp/lists'), {
						key: key,
						listIds: settings.listIds
					}, function(response) {
						if(settings.target) {
							settings.target.html(response);
						}
					});
					
					lastValue = key;
				}
			})
			.blur();
		});
	};

}(jQuery));