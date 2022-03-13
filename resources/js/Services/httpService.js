import axios from "axios";
import { useToast } from "vue-toastification";

const toast = useToast()

axios.interceptors.response.use(response => response, (error) => {
    const expectedError = error.response && error.response.status >= 400 && error.response.status < 500

    if (!expectedError)
        toast.error(`${ex.response.status} ${ex.response.data}`)

    return Promise.reject(error)
})

export default {
    get: axios.get,
    post: axios.post,
    put: axios.put,
    delete: axios.delete
}