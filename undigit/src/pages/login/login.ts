import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Http, Headers, Jsonp } from '@angular/http';
import 'rxjs/add/operator/map';
import { ProfilePage } from '../profile/profile';
import {HomePage} from '../home/home';
/**
 * Generated class for the LoginPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-login',
  templateUrl: 'login.html',
})
export class LoginPage {

  login:FormGroup;
  constructor(public navCtrl: NavController, public navParams: NavParams, public http:Http, public formBuilder:FormBuilder) {
    this.login = formBuilder.group({
      username: ['', Validators.required],
      password: ['', Validators.required]
    });
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad LoginPage');
  }
  
  loggedin()
  {
    var data = {
      username: this.login.get('username').value,
      password: this.login.get('password').value
    }

    let headers = new Headers();
    headers.append('Content-type', 'application/json');

    this.http.post('http://localhost/undigit/undigit.php?rquest=login', JSON.stringify(data), {headers:headers}).map(res => res.json()).subscribe(res => {
      console.log(res);
      if(res.status == "Success")
      {
        this.navCtrl.push(ProfilePage, {
          id:res.id
        })
      }
    });
    
  }

  test()
  {
    this.navCtrl.push(HomePage);
  }

}
