import {ComponentFixture, TestBed} from '@angular/core/testing';

import {CropImageDialogComponent} from './crop-image-dialog.component';

describe('CropImageDialogComponent', () => {
    let component: CropImageDialogComponent;
    let fixture: ComponentFixture<CropImageDialogComponent>;

    beforeEach(async () => {
        await TestBed.configureTestingModule({
            declarations: [CropImageDialogComponent]
        })
            .compileComponents();
    });

    beforeEach(() => {
        fixture = TestBed.createComponent(CropImageDialogComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
