import { Injectable } from '@angular/core';
import { HttpRequest, HttpHandler, HttpEvent, HttpInterceptor } from '@angular/common/http';
import { Observable } from 'rxjs';

import { environment } from '@environments/environment';
import { AuthenticationService } from '@app/_services';

@Injectable()
export class JwtInterceptor implements HttpInterceptor {
    constructor(private authenticationService: AuthenticationService) { }

    intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        // add auth header with jwt if user is logged in and request is to the api url
        const user = this.authenticationService.userValue;
        const token : any = this.authenticationService.tokenValue;
        console.log(token?.token)
        const isLoggedIn = token?.token;
        const isApiUrl = request.url.startsWith(environment.apiUrl);
        const isAnonymous = request.url.endsWith("/login_check") || request.url.endsWith("/register");
        if (isLoggedIn && isApiUrl && !isAnonymous) {
            request = request.clone({
                setHeaders: {
                    Authorization: `Bearer ${token.token}`
                }
            });
        }

        return next.handle(request);
    }
}