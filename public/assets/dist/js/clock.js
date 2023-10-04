// function startTime() {
//     var today = new Date();
//     var hr = today.getHours();
//     var min = today.getMinutes();
//     var sec = today.getSeconds();

//     session = hr < 12 ? "<span>AM</span>" : "<span>PM</span>";
//     hr = hr == 0 ? 12 : hr;
//     hr = hr > 12 ? hr - 12 : hr;

//     //Add a zero in front of numbers<10
//     hr = checkTime(hr);
//     min = checkTime(min);
//     sec = checkTime(sec);

//     document.getElementById(
//         "currentTime"
//     ).innerHTML = `${hr}:${min}:${sec} ${session}`;

//     var months = [
//         "Jan",
//         "Febr",
//         "March",
//         "April",
//         "May",
//         "June",
//         "July",
//         "Aug",
//         "Sept",
//         "Oct",
//         "Nov",
//         "Dec",
//     ];
//     var days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

//     var currentWeekDay = days[today.getDay()];
//     var currentDay = today.getDate();
//     var currentMonth = months[today.getMonth()];
//     var currentYear = today.getFullYear();
//     var date = `${currentWeekDay}, ${currentDay} ${currentMonth} ${currentYear}`;

//     document.getElementById("currentDate").innerHTML = date;

//     // var time = setTimeout(function () {
//     //     startTime();
//     // }, 1000);
// }
// setInterval(startTime, 1000);

// function checkTime(i) {
//     if (i < 10) {
//         i = "0" + i;
//     }
//     return i;
// }
function startTime() {
    var today = new Date();

    // Format time
    var timeOptions = {
        hour: "numeric",
        minute: "2-digit",
        second: "2-digit",
        hour12: true,
    };
    var timeString = today.toLocaleString(undefined, timeOptions);

    // Format date
    var dateOptions = {
        weekday: "short",
        year: "numeric",
        month: "short",
        day: "numeric",
    };
    var dateString = today.toLocaleString(undefined, dateOptions);

    document.getElementById("currentTime").innerHTML = timeString;
    document.getElementById("currentDate").innerHTML = dateString;
}

setInterval(startTime, 1000);
