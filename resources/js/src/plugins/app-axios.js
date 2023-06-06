import axios from "axios";
import $cookies from "vue-cookies";

const appAxios = axios.create({
    withCredentials: true,
});

appAxios.interceptors.request.use(
    function (config) {

        if ($cookies.get("accessToken")) {
            config.headers["Authorization"] = `Bearer ${$cookies.get(
                "accessToken"
            )}`;
        }

        return config;
    },
    function (error) {
        // Do something with request error
        return Promise.reject(error);
    }
);

export default appAxios;
