import dayjs from "dayjs";
import tz from "dayjs/plugin/timezone";
import utc from "dayjs/plugin/utc";

dayjs.extend(tz);
dayjs.extend(utc);

const timeZone = dayjs.tz.guess();

export default function (value) {
    return dayjs(value).tz(timeZone).format("HH:mm:ss DD/MM/YYYY");
}
