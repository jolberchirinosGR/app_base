import axios from 'axios';
import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useAuthUserStore = defineStore('AuthUserStore', () => {
    const user = ref({
        name: '',
        email: '',
        permissions: '',
    });

    const getAuthUser = async () => {
        await axios.get('/web/users')
            .then((response) => {
                user.value = response.data;
            });
    };

    return { user, getAuthUser };
});
