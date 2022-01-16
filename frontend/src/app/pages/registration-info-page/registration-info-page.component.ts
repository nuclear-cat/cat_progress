import { Component, OnInit } from '@angular/core';
import {NavService} from "../../services/nav-service";

@Component({
  selector: 'app-registration-info-page',
  templateUrl: './registration-info-page.component.html',
  styleUrls: ['./registration-info-page.component.scss']
})
export class RegistrationInfoPageComponent implements OnInit {

  constructor(public navService: NavService,) { }

  ngOnInit(): void {
    this.navService.title = 'Confirm registration';
  }
}
