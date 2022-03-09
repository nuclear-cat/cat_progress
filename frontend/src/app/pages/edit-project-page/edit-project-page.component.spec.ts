import {ComponentFixture, TestBed} from '@angular/core/testing';

import {EditProjectPageComponent} from './edit-project-page.component';

describe('EditProjectPageComponent', () => {
    let component: EditProjectPageComponent;
    let fixture: ComponentFixture<EditProjectPageComponent>;

    beforeEach(async () => {
        await TestBed.configureTestingModule({
            declarations: [EditProjectPageComponent]
        })
            .compileComponents();
    });

    beforeEach(() => {
        fixture = TestBed.createComponent(EditProjectPageComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
