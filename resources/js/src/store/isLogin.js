import { reactive } from "vue";
const userProfile = reactive({
    isLogin: false,
});

function checkUserLogin(data) {
    userProfile.isLogin = data;
}

export { userProfile, checkUserLogin };
