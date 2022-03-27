var sidenav = true; //* To detect if the left navigation is open/closed 
var profile = false; //* To detect if the profile is displayed 
var notifications = false; //* To detect if the notifications are
var squery; //* To capture the search query
var help = false; //* To detect if the help container is open/closed
var alerts = false; //* To detect if the alerts are displayed
/***** BACKDROP FUNCTIONS *****/
function showBackdrop(index) {
    jQuery('#backdrop').css('z-index', index);
    jQuery('body').addClass('modal-open');
    jQuery('#backdrop').addClass('modal-backdrop fade show');

}
function hideBackdrop() {
    if (!profile && !notifications && !help && !alerts) {
        jQuery('body').removeClass('modal-open');
        jQuery('#backdrop').removeClass('modal-backdrop fade show');
    } else if (alerts || help)
        jQuery('#backdrop').css('z-index', 1043);
    else if (profile || notifications)
        jQuery('#backdrop').css('z-index', 1040);
}
(function ($, Drupal, drupalSettings) {

    /***** HELP FUNCTIONS *****/
    var squery; //* To capture the search query
    //* Initialize the search
    //* src will equal a string or an object
    function init_search(src) {
        set_search_query(src);
        submit_help_query();
        display_search_query();
        if (!help || src != 'input_search_query')
            openHelp();
    }

    //* Captures when a predefined contextual help link is clicked and initializes the search
    //jQuery('.predefined-contextual-help').on('click', function () { init_search(event) });

    //* Captures when a dynamic contextual help link is clicked and initializes the search
    //var db = document.getElementById('dynamic_contextual_help_button');
    //if (db) {
    //    db.addEventListener('click', function () { init_search('dynamic_contextual_help_button') });
    //}
    //document.getElementById('dynamic_contextual_help_button').addEventListener('click',function(){init_search('dynamic_contextual_help_button')});

    //* Captures when the search button is clicked and initializes the search
    var mq = document.getElementById('main_input_search_query');
    if (mq) {
        mq.addEventListener('click', function () { init_search('main_input_search_query') });
    }
    // document.getElementById('main_input_search_query').addEventListener('click',function(){init_search('main_input_search_query')});

    //* Captures when the search button is clicked and initializes the search
    var iq = document.getElementById('input_search_query');
    if (iq) {
        iq.addEventListener('click', function () { init_search('input_search_query') });
    }
    // document.getElementById('input_search_query').addEventListener('click',function(){init_search('input_search_query')});

    //* Captures when clicks within the main frame and initializes the tooltip if needed
    var main = document.getElementById('main');
    if (main) {
        main.addEventListener('click', dynamic_contextual_help_tooltip);
    }
    // document.getElementById('main').addEventListener('click',dynamic_contextual_help_tooltip);    

    //* Displays tooltip popup if text is selected within the main frame
    function dynamic_contextual_help_tooltip() {
        if (window.getSelection().toString()) {
            var popup = document.getElementById('popup_tool_tip');
            popup.classList.toggle('show');
        }
    }

    //* Display search query label 
    function display_search_query() {
        event.preventDefault();
        jQuery('.sr_query').text(jQuery('#help input:text').val());
        jQuery('#help .d-none').removeClass('d-none');
    }

    //* Captures the query from the correct method being used
    function set_search_query(src) {
        if (src.target && src.target.className == 'predefined-contextual-help') {
            squery = get_predefined_contextual_help_query(src);
            set_input_box_text();
        }
        else if (src == 'dynamic_contextual_help_button') {
            squery = get_dynamic_contextual_help_query();
            set_input_box_text();
        }
        else if (src == 'main_input_search_query') {
            squery = get_main_input_help_query();
            set_input_box_text();
        }
        else if (src == 'input_search_query')
            squery = get_input_query();
        else
            squery = "UHRP";
    }

    //* Updates input box with the current query
    function set_input_box_text() {
        jQuery('#help input').val(squery);
    }

    function get_main_input_help_query() {
        return jQuery('#main input:text').val();
    }
    //* Get predefined query
    function get_predefined_contextual_help_query(src) {
        return src.target.innerHTML;
    }

    //* Get from selected text
    function get_dynamic_contextual_help_query() {
        return window.getSelection().toString();
    }

    //* Get query from input box
    function get_input_query() {
        return jQuery('#help input:text').val();
    }
    //* Submit/Return results to/from server
    function submit_help_query() {
        event.preventDefault();
        if (squery != "") {
            jQuery.get(drupalSettings.path.baseUrl + 'askhr/' + squery, function (data) {
                jQuery('#query_results').html(data);
            });
        } else jQuery('#query_results').html("<div id='srq_container' class='mb-3'>Please Enter A Search Query</div>");
        squery = "";
    }
})(jQuery, Drupal, drupalSettings);

/***** SIDEBAR FUNCTIONS *****/
/* Set the width of the side navigation to 250px and the left margin of the page content to 250px */
function openNav(e) {
    e.innerHTML = '<h3><i class="fa fa-caret-left"></i></h3>'
    e.setAttribute('onclick', 'closeNav(this)');
    document.getElementById("sideNav").style.width = "320px";
    document.getElementById("sidenav-content").style.marginLeft = "0px";
    document.getElementById("version").style.marginLeft = "0px";
    document.getElementById("main").style.marginLeft = "0px";
    document.getElementById("content_container").style.marginLeft = "320px";
    sidenav = true;
    if (profile)
        document.getElementById("profile").style.left = "320px";
    if (notifications)
        document.getElementById("notifications").style.left = "320px";
    if (alerts)
        document.getElementById("alerts").style.left = "320px";
}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0 */
function closeNav(e) {
    e.innerHTML = '<h3><i class="fa fa-caret-right"></i></h3>'
    e.setAttribute('onclick', 'openNav(this)');
    document.getElementById("sideNav").style.width = "50px";
    document.getElementById("sidenav-content").style.marginLeft = "-320px";
    document.getElementById("version").style.marginLeft = "-200px";
    document.getElementById("main").style.marginLeft = "00px";
    document.getElementById("content_container").style.marginLeft = "50px";
    sidenav = false;
    if (profile)
        document.getElementById("profile").style.left = "50px";
    if (notifications)
        document.getElementById("notifications").style.left = "50px";
    if (alerts)
        document.getElementById("alerts").style.left = "50px";
}

/***** ALERT FUNCTIONS *****/
function openAlerts(e) {
    document.getElementById("alerts").style.top = "0px";
    alerts = true;
    showBackdrop(1043);
}
function closeAlerts(e) {
    document.getElementById("alerts").style.top = "-200px";
    alerts = false;
    hideBackdrop();
}
/***** NOTIFICATION FUNCTIONS *****/
function notificationsTrigger() {
    if (notifications)
        closeNotifications(null);
    else
        openNotifications(null);
}
function openNotifications(e) {
    if (profile)
        closeProfile(null);
    if (sidenav)
        document.getElementById("notifications").style.left = "320px";
    else
        document.getElementById("notifications").style.left = "50px";
    jQuery('.profile-container img').attr('onclick', 'closeNotifications(this)');
    showBackdrop(1040);
    notifications = true;
    $(".dot").css("display", "none");
}
function closeNotifications(e) {
    document.getElementById("notifications").style.left = "-650px";
    jQuery('.profile-container img').attr('onclick', 'openNotifications(this)');
    notifications = false;
    hideBackdrop();
}
/***** PROFILE FUNCTIONS *****/
function openProfile(e) {
    if (alerts)
        closeAlerts(null);
    if (notifications)
        closeNotifications(null);
    if (sidenav)
        document.getElementById("profile").style.left = "320px";
    else
        document.getElementById("profile").style.left = "50px";
    jQuery('#profile button').attr('onclick', 'closeProfile(this)');
    jQuery('.profile-container a').attr('onclick', 'closeProfile(this)');
    profile = true;
    showBackdrop(1040);
}
function closeProfile(e) {
    document.getElementById("profile").style.left = "-775px";
    jQuery('#profile button').attr('onclick', 'openProfile(this)');
    jQuery('.profile-container a').attr('onclick', 'openProfile(this)');
    profile = false;
    hideBackdrop();
}

//* Slides out the help sidebar
function openHelp(id) {
    document.getElementById('help_caret').setAttribute('onclick', 'closeHelp(this.id)');
    document.getElementById("help").style.right = "0%";
    document.getElementById('help_caret').innerHTML = '<h3><i class="fa fa-caret-right"></i></h3>'
    showBackdrop(1043);
    help = true;
}
//* Hides the help sidebar
function closeHelp(id) {
    //document.getElementById(id).setAttribute('onclick','openHelp(this.id)');
    document.getElementById("help").style.right = "-27.48%";
    document.getElementById('help_caret').setAttribute('onclick', 'openHelp(this.id)');
    document.getElementById('help_caret').innerHTML = '<h3><i class="fa fa-caret-left"></i></h3>'
    help = false;
    hideBackdrop();
}

//* Open Chat Modal
function open_chat_modal() {
    jQuery('#help').css('z-index', '1040');
    jQuery('#sideNav').css('z-index', '1040');
    jQuery('#chatModal').modal();
}

jQuery('#chatModal').on('hide.bs.modal', function () {
    jQuery('#help').css('z-index', '1041')
});