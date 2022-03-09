import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {ImageCroppedEvent} from "ngx-image-cropper";
import {LoadedImage} from "ngx-image-cropper/lib/interfaces";

@Component({
    selector: 'app-crop-image-dialog',
    templateUrl: './crop-image-dialog.component.html',
    styleUrls: ['./crop-image-dialog.component.scss']
})
export class CropImageDialogComponent implements OnInit {
    public imageSrc: string | null = null;
    public croppedEvent: ImageCroppedEvent | null = null;
    public isLoading: boolean = true;

    public constructor(
        private dialogRef: MatDialogRef<CropImageDialogComponent>,
        @Inject(MAT_DIALOG_DATA) public data: { file: File },
    ) {
    }

    public ngOnInit(): void {
    }

    public imageCropped(event: ImageCroppedEvent): void {
        this.croppedEvent = event;
    }

    public imageLoaded(event: LoadedImage): void {
        this.isLoading = false;
    }

    public submit(): void {
        this.dialogRef.close(this.croppedEvent);
    }
}
