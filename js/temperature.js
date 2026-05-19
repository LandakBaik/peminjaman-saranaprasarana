document.addEventListener("DOMContentLoaded", function () {

    // DOM cuaca
    const tempDisplay =
        document.getElementById('temperatureDisplay');

    const weatherDesc =
        document.getElementById('weatherDescription');

    const weatherIcon =
        document.getElementById('weatherIcon');

    const locationName =
        document.getElementById('locationName');


    // Lokasi default
    const defaultCoords = {
        lat: -8.1724,
        lon: 113.7008,
        name: "Jember"
    };


    // Ambil data cuaca
    function updateWeather(lat, lon, name = null) {

        const url =
            `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,weather_code&timezone=auto`;

        fetch(url)

            .then(response => response.json())

            .then(data => {

                const temp =
                    Math.round(data.current.temperature_2m);

                const code =
                    data.current.weather_code;

                tempDisplay.innerText =
                    `${temp}°C`;

                // Mapping icon dan deskripsi
                const weatherInfo =
                    getWeatherInfo(code);

                weatherDesc.innerText =
                    weatherInfo.desc;

                weatherIcon.innerHTML =
                    `<i class="fas ${weatherInfo.icon}"></i>`;

                // Tampilkan nama lokasi
                if (name) {

                    locationName.innerHTML =
                        `<i class="fas fa-location-dot me-2"></i>${name}`;
                }
            })

            .catch(err => {

                console.error(
                    "Weather fetch error:",
                    err
                );

                weatherDesc.innerText =
                    "Gagal memuat cuaca";
            });
    }


    // Mapping weather code
    function getWeatherInfo(code) {

        const codes = {

            0: {
                desc: "Cerah",
                icon: "fa-sun"
            },

            1: {
                desc: "Cerah Berawan",
                icon: "fa-cloud-sun"
            },

            2: {
                desc: "Berawan",
                icon: "fa-cloud"
            },

            3: {
                desc: "Mendung",
                icon: "fa-cloud"
            },

            45: {
                desc: "Berkabut",
                icon: "fa-smog"
            },

            48: {
                desc: "Kabut Rime",
                icon: "fa-smog"
            },

            51: {
                desc: "Gerimis Ringan",
                icon: "fa-cloud-rain"
            },

            53: {
                desc: "Gerimis",
                icon: "fa-cloud-rain"
            },

            55: {
                desc: "Gerimis Lebat",
                icon: "fa-cloud-rain"
            },

            61: {
                desc: "Hujan Ringan",
                icon: "fa-cloud-showers-water"
            },

            63: {
                desc: "Hujan",
                icon: "fa-cloud-showers-heavy"
            },

            65: {
                desc: "Hujan Lebat",
                icon: "fa-cloud-showers-heavy"
            },

            80: {
                desc: "Hujan Guyur",
                icon: "fa-cloud-sun-rain"
            },

            95: {
                desc: "Badai Petir",
                icon: "fa-bolt"
            }
        };

        return codes[code] || {
            desc: "Berawan",
            icon: "fa-cloud"
        };
    }


    // Ambil nama lokasi
    function getLocationName(lat, lon) {

        fetch(
            `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`
        )

            .then(res => res.json())

            .then(data => {

                const city =
                    data.address.city ||
                    data.address.town ||
                    data.address.village ||
                    data.address.county ||
                    "Lokasi Anda";

                locationName.innerHTML =
                    `<i class="fas fa-location-dot me-2"></i>${city}`;
            })

            .catch(() => {

                locationName.innerHTML =
                    `<i class="fas fa-location-dot me-2"></i>Lokasi Anda`;
            });
    }


    // Gunakan geolocation
    if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(

            (position) => {

                const lat =
                    position.coords.latitude;

                const lon =
                    position.coords.longitude;

                updateWeather(lat, lon);

                getLocationName(lat, lon);
            },

            () => {

                // Fallback lokasi default
                updateWeather(
                    defaultCoords.lat,
                    defaultCoords.lon,
                    defaultCoords.name
                );
            }
        );

    } else {

        updateWeather(
            defaultCoords.lat,
            defaultCoords.lon,
            defaultCoords.name
        );
    }

});