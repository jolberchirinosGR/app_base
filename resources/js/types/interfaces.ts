// Definimos la interfaz para un usuario.
  export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    created_at: string;
    updated_at: string;
    id_role: number;
    pivot: {
      task_id: number;
      user_id: number;
    };
    theme: string;
  }
  
  // Definimos la interfaz para la tarea.
  export interface Task {
    id: number;
    name: string;
    description: string;
    start_date: string;
    end_date: string | null;
    hour: string;
    period: string;
    repeat: boolean;
    status: number;
    created_at: string;
    updated_at: string;
    days: string; // Esta propiedad envia un array con dias con estrcutura muy rara, la manejaremos como string.
    users: User[]; // Un arreglo de usuarios asociados a la tarea
  }
  
  export interface Role {
    id: string;
    name: string;
  }

  export interface AuthUser {
    id: string;
    name: string;
    email: string;
    id_role: string;
    theme: string;
  }