import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RegistrationInfoPageComponent } from './registration-info-page.component';

describe('RegistrationInfoPageComponent', () => {
  let component: RegistrationInfoPageComponent;
  let fixture: ComponentFixture<RegistrationInfoPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RegistrationInfoPageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(RegistrationInfoPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
