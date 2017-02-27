'use strict';

var app = {
    init: function() {
        this.notif = $('#notif');
        this.switches = $('#switch span');
        this.listeners();
    },

    listeners: function() {
        $('#menu .icon').on('click',function() {
            var action = $(this).data('action');
            console.log(action);
            app.actions[action].call();
        });
        app.switches.on('click',function(){
            app.switches.toggleClass('selected');
            var tab = $(this).data('tab');
            if (tab == 'dl') {
                $('.feeds-tab').hide();
                $('.dl-tab').slideDown();
            }
            else {
                $('.dl-tab').hide();
                $('.feeds-tab').slideDown();
            }
        });
    },

    actions: {
        download: function() {
            app.notif.html('<p>Checking RSS feeds...</p>').slideToggle();
            $.get('download.php')
            .done(function(data) {
                if (data) {
                    var data = JSON.parse(data);
                    var msg = '';
                    if (data.length > 0) {
                        msg += '<h2>New episodes:</h2>';
                        $.each(data, function(key, dl) {
                            msg += '<p>Added episode ' + dl.show + ': ' + dl.episode + '</p>';
                        });
                    }
                }
                else {
                    msg = '<p>No new episodes this time.';
                }
                app.notif.html(msg).delay(4000).slideToggle();
            })
            .fail(function(){
                app.notif.html('<p class="red">Request failed. Try again</p>').delay(2000).slideToggle();
            });
        },
        cleanup: function() {
            app.notif.html('<p>Removing finished torrents...</p>').slideToggle();
            $.get('cleanup.php')
            .done(function(){
                app.notif.html('<p>OK</p>').delay(2000).slideToggle();
            })
            .fail(function(){
                app.notif.html('<p class="red">Request failed. Try again</p>').delay(2000).slideToggle();
            });
        },
        webgui: function() {
            window.open('http://iben12.noip.us:9091/transmission/web/');
        }
    }

};

app.init();