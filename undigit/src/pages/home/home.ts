import { Component, group } from '@angular/core';
import { NavController } from 'ionic-angular';
import { FormBuilder, FormGroup, Validators, Form, ValidatorFn, AbstractControl } from '@angular/forms';
import { Http, Headers, Jsonp } from '@angular/http';
import 'rxjs/add/operator/map';
import { ProfilePage } from '../profile/profile';
@Component({
  selector: 'page-home',
  templateUrl: 'home.html'
})
export class HomePage {

  user:FormGroup;
  constructor(public navCtrl: NavController, public http:Http, public formBuilder:FormBuilder) {

      this.user = formBuilder.group({
        name:['', Validators.required],
        username: ['', Validators.required],
        password: ['', Validators.compose([Validators.minLength(8), Validators.required])],
        email: ['', Validators.compose([Validators.email, Validators.required])],
        phone: ['', Validators.compose([Validators.minLength(10), Validators.maxLength(10), Validators.required])],
        dob: ['', Validators.required],
        address: ['', Validators.required],
        
      });

  }

  signUp()
  {
    var data = {
      name:this.user.get('name').value,
      username:this.user.get('username').value,
      password:this.user.get('password').value,
      email:this.user.get('email').value,
      phone:this.user.get('phone').value,
      dob:this.user.get('dob').value,
      address:this.user.get('address').value
    };

    let headers = new Headers();
    headers.append('Content-type', 'application/json');
    this.http.post('http://localhost/undigit/undigit.php?rquest=signUp', JSON.stringify(data), {headers:headers}).map(res => res.json()).subscribe(res => {
      if(res.status = "Success")
      {
        this.navCtrl.push(ProfilePage, {
          id: res.id
        } )
      }
    });
    
  }

  
}




