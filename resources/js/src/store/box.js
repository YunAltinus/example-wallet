import { addToast } from "@/store/widget/toast.js";
import { ref } from "vue";

const box = {
    isLogin: ref(false),
    // ERROR POPUP
    addError(title = "Hata", text = "İşlem başarısız", time = 3000) {
        addToast({
            cls: "response-message response-error",
            title: title,
            icon: "warning",
            iconCls: "error",
            text: text,
            time: time,
        });
    },

    // SUCCESS POPUP
    addSuccess(title = "Başarılı", text = "İşlem başarılı", time = 3000) {
        addToast({
            cls: "response-message response-success",
            title: title,
            icon: "check",
            iconCls: "success",
            text: text,
            time: time,
        });
    },

    // RESPONSE CONTROL POPUP
    controlResponse(res) {
        if (!res.success) {
            box.addError();
        }
    },
};
export default box;
