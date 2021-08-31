function get(url, success, fail) {
    let httpRequest = new XMLHttpRequest();
    httpRequest.open('GET', url);
    httpRequest.onload = function() {
        if (httpRequest.status === 200) {
            success(httpRequest.responseText);
        } else {
            fail(httpRequest.status);
        }
    }
    httpRequest.send();
};

function successHandler(data) {
    const dataObj = JSON.parse(data);
    const weatherDiv = document.querySelector('#weather');
    const weatherFragment = `
        <h1>Weather</h1>
        <h2 class="top">
        <img
            src="http://openweathermap.org/img/w/${dataObj.weather[0].icon}.png"
            alt="${dataObj.weather[0].description}"
            width="50"
            height="50"
        />${dataObj.name}
        </h2>
        <p>
        <span class="tempF">${tempToF(dataObj.main.temp)}&deg;</span> | ${dataObj.weather[0].description}
        </p>
    `
    weatherDiv.innerHTML = weatherFragment;
    weatherDiv.classList.remove('hidden');
}

function failHandler(status) {
    console.log(status);
    const weatherDiv = document.querySelector('#weather');
    weatherDiv.classList.remove('hidden');
}

function tempToF(kelvin) {
    return ((kelvin - 273.15) * 1.8 + 32).toFixed(0);
}

document.addEventListener('DOMContentLoaded', function() {
	const udAjaxThings = parentNode.querySelectorAll(".ud-active");
	
	udAjaxThings.forEach(function(udThing) {
	    // activate ajax things
		udThing.addEventListener("click", function() {
			/*
<article
  id="electric-cars"
  data-columns="3"
  data-index-number="12314"
  data-parent="cars">
...
</article>
			
			
const article = document.querySelector('#electric-cars');
// The following would also work:
// const article = document.getElementById("electric-cars")

article.dataset.columns // "3"
article.dataset.indexNumber // "12314"
article.dataset.parent // "cars"
				
			*/
		});
	});
	
    const apiKey = 'd126cacbbfebf7c84ad878e9deffc0e1';
//    const apiKey = '';
    const url = 'https://api.openweathermap.org/data/2.5/weather?q=los+angeles&APPID=' + apiKey;
    get(url, successHandler, failHandler);
});
