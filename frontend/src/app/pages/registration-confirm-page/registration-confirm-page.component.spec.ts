import {ComponentFixture, TestBed} from '@angular/core/testing';

import {RegistrationConfirmPageComponent} from './registration-confirm-page.component';

describe('RegistrationConfirmPageComponent', () => {
    let component: RegistrationConfirmPageComponent;
    let fixture: ComponentFixture<RegistrationConfirmPageComponent>;

    beforeEach(async () => {
        await TestBed.configureTestingModule({
            declarations: [RegistrationConfirmPageComponent]
        })
            .compileComponents();
    });

    beforeEach(() => {
        fixture = TestBed.createComponent(RegistrationConfirmPageComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
