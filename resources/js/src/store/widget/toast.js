import { reactive } from "vue";
import { v4 as uuidv4 } from "uuid";
let toastList = reactive([]);

function addToast(data) {
  data.uniqId = uuidv4();
  toastList.push(data);
}

function deleteToast(number) {
  let index = toastList.findIndex((xx) => xx.uniqId == number);
  if (index !== -1) {
    toastList.splice(index, 1);
  }
}

export { toastList, addToast, deleteToast };