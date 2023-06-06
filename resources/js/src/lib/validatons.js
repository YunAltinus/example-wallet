const errorManager = () => {
    //  Required
    const required = (obj, key) => {
        if (!obj[key] && obj[key] !== 0) {
            const msg = "This is a required";

            if (obj.error) obj.error[key] = msg;
            else obj.erorr = { [key]: msg };

            return true;
        }
        delete obj.error[key];
    };
    return { required };
};

export default errorManager;
