import {ErrorHandler as AngularErrorHandler, Injectable, NgZone} from "@angular/core";
import {MatSnackBar} from "@angular/material/snack-bar";

@Injectable({
    providedIn: 'root'
})
export class ErrorHandler implements AngularErrorHandler {
    private snackBarRef: any;

    public constructor(
        private snackBar: MatSnackBar,
        private zone: NgZone,
    ) {
    }

    public handleError(error: Error): void {
        this.zone.run(() => {
            this.snackBarRef = this.snackBar.open(error.message, 'Close', {
                panelClass: ['error'],
            });
        });
    }
}
