(function($) {

	$.fn.campaignMonitorLists = function(settings) {
		if(typeof settings != "object") {
			settings = {};
		}

		var defaultSettings = {
			target: false,
			clientIdField: false,
			clientId: '',
			listIds: []
		};

		settings = $.extend({}, defaultSettings, settings);

		return this.each(function() {
			var $t = $(this);
			var lastValue, lastClientId;

			var update = function(key) {
				if(settings.target) {
					settings.target.html('<p>Loading lists...</p>');
				}

				$.post(Craft.getCpUrl('postmaster/campaignmonitor/lists'), {
					key: key,
					clientId: settings.clientIdField ? settings.clientIdField.val() : settings.clientId,
					listIds: settings.listIds
				}, function(response) {
					if(settings.target) {
						settings.target.html(response);
					}
				});
				
				lastValue = key;
				lastClientId = settings.clientId;
			};

			$t.blur(function() {
				var key = $(this).val();

				if(key != '' && key != lastValue) {
					update(key);
				}
			})
			.blur();

			if(settings.clientIdField) {
				settings.clientIdField.blur(function() {
					var id = $(this).val();

					if(id != '' && id != lastClientId) {
						update($t.val());
					}
				});
			}
		});
	};

}(jQuery));