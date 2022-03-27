/**
 * @file
 * Behaviors for the hrcms_vartheme theme.
 */

(function ($, _, Drupal, drupalSettings) {
  'use strict';

  Drupal.behaviors.hrcms_vartheme = {
    attach: function (context) {

	  	$(".country-switcher").change(function(){
		var countrynid = $(".country-switcher").val();
		var country_id = countrynid.split(',');
		var cntryid = country_id[0];
		var nodeid = country_id[1];
		$.ajax({
			url: drupalSettings.path.baseUrl+'country_switcher_redirect/' + cntryid + '/' + nodeid,
			success: function (data) {
				$(location).attr('href', data);
			}
		})
	
	});	
	
		// When the user clicks on the button, open the modal
		$(".views-field-user-picture" ).click(function(e) {
			e.preventDefault();
			$(".dot").css("display", "none");
			$.ajax({
				url: drupalSettings.path.baseUrl +'user_update_notification',
				success: function (data) {
					$(".dot").css("display", "none");
				}
			})
		});


		$("#myBtns" ).click(function() {
			$(".field--name-field-user-profile-reference").css("display", "block");
		});


		// When the user clicks on <span> (x), close the modal

		$(".close" ).click(function() {
			$("#block-views-block-notifications-notify-user").css("display", "none");
		});		
		$(".closes" ).click(function() {
			$(".field--name-field-user-profile-reference").css("display", "none");
		});
		$(".closeit" ).click(function() {
			$("#block-views-block-push-notification-block-1").css("display", "none");
		});
		//Notification Views more view less
		$(".arrow-right" ).click(function() {
			$("#block-views-block-push-notification-block-1").css("display", "none");
			$("#myBtn").css("display", "none");
			$("#block-views-block-notifications-notify-user").css("display", "block");
			$.ajax({
				url: drupalSettings.path.baseUrl +'/user_update_notification',
				success: function (data) {
					$("#myBtn").css("display", "none");
				}
			})
		});
    }
  };

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
