//Funciones para trabajar con los estados de las tareas
// 0 Por comenzar
// 1 En curso
// 2 Cancelada
// 3 Finalizada

  export function getIcon(status: number): string[] {
    switch (status) {
      case 1:
        return ['fas', 'sync-alt'];
      case 2:
        return ['fas', 'ban'];
      case 3:
        return ['fas', 'check-double'];
      default:
        return ['fas', 'hourglass'];
    }
  }
  
  export function getStatusName(status: number): string {
    switch (status) {
      case 1:
        return 'En curso';
      case 2:
        return 'Cancelada';
      case 3:
        return 'Finalizada';
      default:
        return 'Por comenzar';
    }
  }
  
  export function getIconColor(status: number): string {
    switch (status) {
      case 1:
        return 'text-blue-700 dark:text-blue-400';
      case 2:
        return 'text-red-700 dark:text-red-400';
      case 3:
        return 'text-green-700 dark:text-green-400';
      default:
        return 'text-gray-700 dark:text-gray-400';
    }
  }

  export function getStatusColor(status: number): string {
    switch (status) {
      case 1:
        return 'bg-blue-700 dark:bg-blue-400';
      case 2:
        return 'bg-red-700 dark:bg-red-400';
      case 3:
        return 'bg-green-700 dark:bg-green-400';
      default:
        return 'bg-gray-700 dark:bg-gray-400';
    }
  }
  
  //Formato fecha de BD a dd-mm-Y
  export function getDate(date: string): string {
    const d = new Date(date);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    return `${day}-${month}-${year}`;
  }