import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { environment } from '@environments/environment';
import { User } from '@app/_models';

@Injectable({ providedIn: 'root' })
export class UserService {

    constructor(private http: HttpClient) { }

    getSentInvitations() {
        return this.http.get<any>(`${environment.apiUrl}/api/invitation/sent`);
    }

    getReceivedInvitations() {
        return this.http.get<any>(`${environment.apiUrl}/api/invitation/received`);
    }

    cancelInvitation(idInvitation : any) {
        return this.http.get<any>(`${environment.apiUrl}/api/invitation/${idInvitation}/cancel`);
    }

    removeInvitation(idInvitation : any) {
        return this.http.get<any>(`${environment.apiUrl}/api/invitation/${idInvitation}/remove`);
    }

    refuseInvitation(idInvitation : any) {
        return this.http.get<any>(`${environment.apiUrl}/api/invitation/${idInvitation}/refuse`);
    }

    acceptInvitation(idInvitation : any) {
        return this.http.get<any>(`${environment.apiUrl}/api/invitation/${idInvitation}/accept`);
    }

    sendInvitation(idUser : any) {
        return this.http.post<any>(`${environment.apiUrl}/api/invitation/${idUser}/send`, {});
    }


  
    searchBackendUsers(item : any) {
        return this.http.get<any>(`${environment.apiUrl}/api/invitation/${item}/search/user`);
    }

}