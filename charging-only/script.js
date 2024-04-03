document.addEventListener('DOMContentLoaded', function() {
    var messageElement = document.getElementById('message');

    function checkChargingStatus() {
        if (navigator.getBattery) {
            navigator.getBattery().then(function(battery) {
                if (battery.charging) {
                    allowAccess();
                } else {
                    denyAccess();
                }
            });
        } else {
            denyAccess();
        }
    }

    function allowAccess() {
        messageElement.textContent = 'You are currently charging. You have access to the website.';
    }

    function denyAccess() {
        messageElement.textContent = 'You are not charging. The only way to access this website is while charging your device.';
    }

    checkChargingStatus();
});
