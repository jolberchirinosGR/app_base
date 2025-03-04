import axios from 'axios';
import { defineStore } from 'pinia';
import { ref } from 'vue';
import { AuthUser } from '../types/interfaces';

export const useAuthUserStore = defineStore('AuthUserStore', () => {
  // Definimos el estado con su tipo
  const user = ref<AuthUser>({
    id: '',
    name: '',
    email: '',
    id_role: '',
    theme: '',
  });

  // Función para obtener el usuario autenticado
  const getAuthUser = async (): Promise<void> => {
    try {
      const response = await axios.get<AuthUser>('/web/profile');
      user.value = response.data;
    } catch (error) {
      console.error('Error al obtener el usuario autenticado:', error);
    }
  };

  return { user, getAuthUser };
});
