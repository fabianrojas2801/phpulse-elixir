import { Component } from '@angular/core';
import { DataService } from './data.service';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'servicios';
  usuarios: any[] = [];
  productos: any[] = [];
  error: string | null = null;
  data: any;  // Variable para almacenar los datos
  loading: boolean = true;  // Indicador de carga

  constructor(private dataService: DataService) { }

  ngOnInit() {
    this.getProductos();  // Cargar los datos cuando el componente se inicializa
  }


  getProductos(): void {
    this.dataService.getData('usuarios').subscribe({
      next: (data) => {
        this.usuarios = data;  // Asignar los datos de productos
        this.data = data;  // Asignar la respuesta a la variable 'data'
        this.loading = false;   // Detener el indicador de carga
      },
      error: (err) => {
        this.error = 'Error al cargar productos';  // Manejar errores
        console.error(err);
      }
    });
  }
/*

   // Crear un nuevo usuario (ejemplo con POST)
   createUsuario(): void {
    const nuevoUsuario = { nombre: 'Nuevo Usuario', email: 'nuevo@usuario.com' };
    this.dataService.postData('usuarios', nuevoUsuario).subscribe({
      next: (response) => {
        console.log('Usuario creado:', response);
        this.getUsuarios();  // Recargar la lista de usuarios después de crear
      },
      error: (err) => {
        this.error = 'Error al crear usuario';
        console.error(err);
      }
    });
  }

  // Actualizar un usuario (ejemplo con PUT)
  updateUsuario(id: number): void {
    const usuarioActualizado = { id, nombre: 'Usuario Actualizado', email: 'actualizado@usuario.com' };
    this.dataService.putData('usuarios', usuarioActualizado).subscribe({
      next: (response) => {
        console.log('Usuario actualizado:', response);
        this.getUsuarios();  // Recargar la lista de usuarios después de actualizar
      },
      error: (err) => {
        this.error = 'Error al actualizar usuario';
        console.error(err);
      }
    });
  }

// Eliminar un usuario
  deleteUsuario(id: number): void {
    this.dataService.deleteData('usuarios', id).subscribe({
      next: (response) => {
        console.log('Usuario eliminado:', response);
        this.getUsuarios();  // Recargar la lista de usuarios después de eliminar
      },
      error: (err) => {
        this.error = 'Error al eliminar usuario';
        console.error(err);
      }
    });
  }
  */
}