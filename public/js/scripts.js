const cfg = {

    // Fecha final RENDIDA TESIS
    finalDate : 'September 23, 2022 00:00:00',
};

/* Countdown Timer
    * ------------------------------------------------------ */
const Countdown = function () {

    const finalDate = new Date(cfg.finalDate).getTime();
    const daysSpan = document.querySelector('.timer .days');
    const hoursSpan = document.querySelector('.timer .hours');
    const minutesSpan = document.querySelector('.timer .minutes');
    const secondsSpan = document.querySelector('.timer .seconds');
    let timeInterval;

    if (!(daysSpan && hoursSpan && minutesSpan && secondsSpan)) return;

    function timer() {

        const now = new Date().getTime();
        let diff = finalDate - now;

        if (diff <= 0) {
            if (timeInterval) { 
                clearInterval(timeInterval);
            }
            return;
        }

        let days = Math.floor( diff/(1000*60*60*24) );
        let hours = Math.floor( (diff/(1000*60*60)) % 24 );
        let minutes = Math.floor( (diff/1000/60) % 60 );
        let seconds = Math.floor( (diff/1000) % 60 );

        if (days <= 99) {
            if (days <= 9) {
                days = '00' + days;
            } else { 
                days = '0' + days;
            }
        }

        hours <= 9 ? hours = '0' + hours : hours;
        minutes <= 9 ? minutes = '0' + minutes : minutes;
        seconds <= 9 ? seconds = '0' + seconds : seconds;

        daysSpan.textContent = days;
        hoursSpan.textContent = hours;
        minutesSpan.textContent = minutes;
        secondsSpan.textContent = seconds;

    }

    timer();
    timeInterval = setInterval(timer, 1000);
};

jQuery(document).ready(function() {
    Countdown();
});