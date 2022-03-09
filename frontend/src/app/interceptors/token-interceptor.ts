import {Injectable} from "@angular/core";
import {HttpErrorResponse, HttpEvent, HttpHandler, HttpInterceptor, HttpRequest} from "@angular/common/http";
import {BehaviorSubject, catchError, filter, Observable, switchMap, take, throwError} from "rxjs";
import {AuthService} from "../services/auth.service";
import {Router} from "@angular/router";

@Injectable()
export class TokenInterceptor implements HttpInterceptor {

    private isRefreshing: boolean = false;
    private refreshTokenSubject: BehaviorSubject<any> = new BehaviorSubject<any>(null);

    constructor(
        private authService: AuthService,
        private router: Router,
    ) {
    }

    intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        if (this.authService.getAccessToken()) {
            request = TokenInterceptor.addToken(request, this.authService.getAccessToken()!);
        }

        return next.handle(request).pipe(catchError(error => {
            if (error instanceof HttpErrorResponse && error.status === 401 && error.error.error === 'unknown') {
                this.router.navigate(['/login']);

                return throwError(() => error)
            } else if (
                error instanceof HttpErrorResponse
                && error.status === 401
                && !error.url?.match(/\/api\/v1\/login?/gm)
            ) {
                return this.handle401Error(request, next);
            } else {
                return throwError(error);
            }
        }));
    }

    private static addToken(request: HttpRequest<any>, token: string) {
        return request.clone({
            setHeaders: {
                'Authorization': `Bearer ${token}`
            }
        });
    }

    private handle401Error(request: HttpRequest<any>, next: HttpHandler) {
        if (!this.isRefreshing) {
            this.isRefreshing = true;
            this.refreshTokenSubject.next(null);

            return this.authService.refresh().pipe(
                switchMap((token: any) => {
                    this.isRefreshing = false;
                    this.refreshTokenSubject.next(token.access_token);

                    return next.handle(TokenInterceptor.addToken(request, token.access_token));
                }),
            );
        } else {
            return this.refreshTokenSubject.pipe(
                filter(token => token != null),
                take(1),
                switchMap(jwt => {
                    return next.handle(TokenInterceptor.addToken(request, jwt));
                }),
            );
        }
    }
}
