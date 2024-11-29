import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';


@Injectable({
  providedIn: 'root'
})
export class DataService {

  private apiUrl = 'http://localhost/dashboard/PHPulse/index.php';  // URL base de la API

  constructor(private http: HttpClient) { }

  // Método genérico para obtener datos (GET)
  getData(action: string): Observable<any> {
    const url = `${this.apiUrl}?action=${action}`;  // URL dinámica basada en la acción
    return this.http.get<any>(url);  // Hacer la solicitud GET
  }

  // Método genérico para enviar datos (POST)
  postData(action: string, data: any): Observable<any> {
    const url = `${this.apiUrl}?action=${action}`;  // URL dinámica basada en la acción
    return this.http.post<any>(url, data);  // Hacer la solicitud POST
  }

  // Método genérico para actualizar datos (PUT)
  putData(action: string, data: any): Observable<any> {
    const url = `${this.apiUrl}?action=${action}`;  // URL dinámica basada en la acción
    return this.http.put<any>(url, data);  // Hacer la solicitud PUT
  }

  // Método genérico para eliminar datos (DELETE)
  deleteData(action: string, id: number): Observable<any> {
    const url = `${this.apiUrl}?action=${action}&id=${id}`;  // URL dinámica con el id
    return this.http.delete<any>(url);  // Hacer la solicitud DELETE
  }
}