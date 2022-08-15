// VALIDATOIN FOR NEW LOGIN 
function checkMail(mailName) {
    let regExp = /^([\w\.\+]{1,})([^\W])(@)([\w]{1,})(\.[\w]{1,})+$/;
    return regExp.test(mailName);
}

function checkMinimumPhone(phoneNumber) {
    return phoneNumber.length >= 10;
}

function passwordMinValue(passwordValue) {
    return passwordValue.length < 6;
}

// contdown left
var countdownLeft, minutes, second;

function countdown(element, minutes, seconds) {
    // set time for the particular countdown
    var time = minutes*60 + seconds;
    var interval = setInterval(function() {
        var el = document.getElementById(element);
        // if the time is 0 then end the counter
        if (time <= 0) {
            var text = "";
            el.innerHTML = text;
            setTimeout(function() {
                countdown('clock', 0, 5);
            }, 2000);
            clearInterval(interval);
            return;
        }
        var minutes = Math.floor( time / 60 );
        if (minutes < 10) minutes = "0" + minutes;
        var seconds = time % 60;
        if (seconds < 10) seconds = "0" + seconds; 
        var text = minutes + ':' + seconds;
        if (!!text) el.innerHTML = text;
        time--;
    }, 1000);
}

function checkLastSendOtp(element) {
    element.attr('disabled', true);
    setInterval(() => {
        let last_send = window.localStorage.getItem('last_send_otp');
        if (!!last_send) {
            now = new Date();
            time = new Date(parseInt(window.localStorage.getItem('last_send_otp')))
            let threeMinLater = time.getTime() + (3 * 60 * 1000);
            if (now - time <  3*60*1000) {
                element.attr('disabled', true);
                let leftTime = (threeMinLater - now.getTime())/1000,
                    minutes = Math.floor(leftTime / 60),
                    seconds = Math.floor(leftTime % 60);
                console.log('minutes ' + minutes.toString().padStart(2, '0') + ' second ' + seconds.toString().padStart(2, '0'));
                document.getElementById('timer').innerHTML =  minutes.toString().padStart(2, '0') + ' : ' + seconds.toString().padStart(2, '0');
                
            } else {
                element.removeAttr('disabled');
                window.localStorage.removeItem('last_send_otp');
                document.getElementById('timer').innerHTML =  '';
            }
        } else {
            element.removeAttr('disabled');
        }
    }, 1000);
}