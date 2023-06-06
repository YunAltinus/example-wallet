// require('./bootstrap');

import { createApp } from "vue";

import App from "./App.vue";
import router from "./router/index.js";
import VueCookies from "vue-cookies";
import "@/lib/fontAwesome";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

// Components
import InputTag from "./components/Forms/InputTag.vue";
import InputButton from "./components/Forms/InputButton.vue";
import InputSelect from "./components/Forms/InputSelect.vue";
import Loader from "./components/shared/Loader.vue";
import CommonTable from "./components/shared/CommonTable.vue";
import Wallet from "./components/shared/Wallet.vue";
import Price from "./components/shared/Price.vue";
import Popup from "./components/shared/Popup.vue";
import Skeleton from "./components/shared/Skeleton.vue";
import SkeletonTable from "./components/shared/SkeletonTable.vue";

const app = createApp(App);

app.use(router)
    .use(VueCookies)
    .component("fai", FontAwesomeIcon)
    .component("InputTag", InputTag)
    .component("InputButton", InputButton)
    .component("InputSelect", InputSelect)
    .component("Loader", Loader)
    .component("CommonTable", CommonTable)
    .component("Skeleton", Skeleton)
    .component("SkeletonTable", SkeletonTable)
    .component("Wallet", Wallet)
    .component("Price", Price)
    .component("Popup", Popup)
    .mount("#app");
